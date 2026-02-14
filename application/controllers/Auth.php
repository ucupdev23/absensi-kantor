<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        date_default_timezone_set('Asia/Jakarta'); // sesuaikan kalau perlu
    }

    private function normalize_wa($no_wa)
    {
        $p = preg_replace('/[^0-9]/', '', $no_wa);

        // ubah 08xx -> 628xx
        if (substr($p, 0, 1) === '0') {
            $p = '62' . substr($p, 1);
        }
        return $p;
    }

    public function login()
    {
        // kalau sudah login, langsung lempar ke dashboard
        if ($this->session->userdata('logged_in')) {
            return redirect('dashboard');
        }

        $data['title'] = 'Login';

        $this->load->view('templates/header', $data);
        $this->load->view('auth/login', $data);
        $this->load->view('templates/footer');
    }

    public function do_login()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->User_model->get_by_username($username);

        if ($user && password_verify($password, $user->password)) {
            // set session
            $session_data = [
                'user_id'       => $user->id,
                'username'      => $user->username,
                'nama_lengkap'  => $user->nama_lengkap,
                'role'          => $user->role,
                'logged_in'     => TRUE
            ];
            $this->session->set_userdata($session_data);

            // redirect sesuai role
            if ($user->role == 'admin') {
                redirect('dashboard'); // nanti dashboard admin
            } else {
                redirect('dashboard'); // nanti dashboard pegawai
            }
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }
    }

    public function forgot_password()
    {
        $data['title'] = 'Lupa Password';
        
        $this->load->view('templates/header', $data);
        $this->load->view('auth/forgot_password', $data);
        $this->load->view('templates/footer');
    }

public function forgot_password_process()
{
    date_default_timezone_set('Asia/Jakarta');
    $this->load->model('Otp_model');
    $this->load->library('Fonnte_lib');

    $identifier = trim($this->input->post('identifier', TRUE));
    if ($identifier === '') {
        $this->session->set_flashdata('error', 'Silakan isi username atau nomor WhatsApp.');
        return redirect('auth/forgot_password');
    }

    // cari user by username ATAU no_wa
    $user = $this->db->get_where('users', ['username' => $identifier])->row();

    if (!$user) {
        $wa = $this->normalize_wa($identifier);
        $user = $this->db->get_where('users', ['no_wa' => $wa])->row();
    }

    if (!$user) {
        $this->session->set_flashdata('error', 'Akun tidak ditemukan. Cek username atau nomor WhatsApp.');
        return redirect('auth/forgot_password');
    }

    if ($user->status !== 'aktif') {
        $this->session->set_flashdata('error', 'Akun tidak aktif. Hubungi admin.');
        return redirect('auth/forgot_password');
    }

    if (empty($user->no_wa)) {
        $this->session->set_flashdata('error', 'Nomor WA untuk akun ini belum diisi. Silakan hubungi admin.');
        return redirect('auth/forgot_password');
    }

    $no_wa = $this->normalize_wa($user->no_wa);

    // THROTTLE: jika OTP terakhir masih aktif (belum expired), jangan kirim lagi
    $latest = $this->Otp_model->get_latest_active($user->id);
    if ($latest && time() < strtotime($latest->expired_at)) {
        // simpan ke session supaya halaman OTP tidak hilang saat refresh
        $this->session->set_userdata([
            'fp_user_id' => $user->id,
            'fp_username' => $user->username,
            'fp_no_wa' => $no_wa,
            'fp_expired_at' => $latest->expired_at,
        ]);
        $this->session->set_flashdata('success', 'OTP sudah dikirim. Silakan cek WhatsApp Anda.');
        return redirect('auth/forgot_password/otp');
    }

    // generate OTP 6 digit
    $kode_otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // OTP berlaku 1 menit
    // $expired  = date('Y-m-d H:i:s', time() + 60);

    // $this->Otp_model->create_otp($user->id, $kode_otp, $expired);

    $result = $this->Otp_model->create_otp($user->id, $kode_otp);

$this->session->set_userdata([
    'fp_expired_at' => $result['expired_at'],
    'fp_resend_at'  => $result['resend_at']
]);


    // kirim WA
    $sent = $this->fonnte_lib->kirim_otp($no_wa, $kode_otp);
    if (!$sent) {
        $this->session->set_flashdata('error', 'Gagal mengirim OTP. Coba lagi.');
        return redirect('auth/forgot_password');
    }

    // simpan ke session (anti hilang saat refresh)
    $this->session->set_userdata([
        'fp_user_id' => $user->id,
        'fp_username' => $user->username,
        'fp_no_wa' => $no_wa,
        'fp_expired_at' => $expired,
    ]);

    $this->session->set_flashdata('success', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    redirect('auth/forgot_password/otp');
}

public function forgot_password_otp()
{
    if (!$this->session->userdata('fp_user_id')) {
        return redirect('auth/forgot_password');
    }

    $data['title'] = 'Verifikasi OTP';
    $data['username'] = $this->session->userdata('fp_username');
    $data['no_wa'] = $this->session->userdata('fp_no_wa');
    $data['expired_at'] = $this->session->userdata('fp_expired_at');

    $this->load->view('templates/header', $data);
    $this->load->view('auth/forgot_password_otp', $data);
    $this->load->view('templates/footer');
}

public function forgot_password_verify()
{
    $this->load->model('Otp_model');

    $user_id = $this->session->userdata('fp_user_id');
    if (!$user_id) return redirect('auth/forgot_password');

    $kode_otp = trim($this->input->post('kode_otp', TRUE));
    if ($kode_otp === '') {
        $this->session->set_flashdata('error', 'Silakan isi OTP.');
        return redirect('auth/forgot_password/otp');
    }

    $otp = $this->Otp_model->get_valid_otp($user_id, $kode_otp);
    if (!$otp) {
        $this->session->set_flashdata('error', 'Kode OTP tidak valid atau sudah kadaluarsa.');
        return redirect('auth/forgot_password/otp');
    }

    // tandai OTP dipakai
    $this->Otp_model->mark_used($otp->id);

    // set flag verified
    $this->session->set_userdata('fp_verified', 1);

    redirect('auth/forgot_password/new_password');
}

public function forgot_password_resend()
{
    $this->load->model('Otp_model');
    $this->load->library('Fonnte_lib');

    $user_id = $this->session->userdata('fp_user_id');
    if (!$user_id) return redirect('auth/forgot_password');

    $latest = $this->Otp_model->get_latest_active($user_id);

    // kalau belum expired, jangan resend
    // if ($latest && time() < strtotime($latest->expired_at)) {
    //     $this->session->set_flashdata('error', 'Tunggu hingga timer habis untuk kirim ulang OTP.');
    //     return redirect('auth/forgot_password/otp');
    // }

    if (!$this->Otp_model->can_resend($user_id)) {
    $this->session->set_flashdata('error', 'Tunggu 1 menit sebelum kirim ulang OTP.');
    return redirect('auth/forgot_password/otp');
}


    // ambil dari session
    $username = $this->session->userdata('fp_username');
    $no_wa = $this->session->userdata('fp_no_wa');

    $kode_otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expired  = date('Y-m-d H:i:s', time() + 60);

    $this->Otp_model->create_otp($user_id, $kode_otp, $expired);

    $sent = $this->fonnte_lib->kirim_otp($no_wa, $kode_otp);
    if (!$sent) {
        $this->session->set_flashdata('error', 'Gagal mengirim OTP. Coba lagi.');
        return redirect('auth/forgot_password/otp');
    }

    $this->session->set_userdata('fp_expired_at', $expired);

    $this->session->set_flashdata('success', 'OTP baru telah dikirim.');
    redirect('auth/forgot_password/otp');
}

public function forgot_password_new_password()
{
    if (!$this->session->userdata('fp_verified')) {
        return redirect('auth/forgot_password');
    }

    $data['title'] = 'Buat Password Baru';

    $this->load->view('templates/header', $data);
    $this->load->view('auth/new_password', $data);
    $this->load->view('templates/footer');
}

public function forgot_password_new_password_process()
{
    if (!$this->session->userdata('fp_verified')) {
        return redirect('auth/forgot_password');
    }

    $user_id   = $this->session->userdata('fp_user_id');
    $password   = $this->input->post('password_baru', TRUE);
    $konfirmasi = $this->input->post('password_konfirmasi', TRUE);

    if ($password !== $konfirmasi) {
        $this->session->set_flashdata('error', 'Konfirmasi password tidak sama.');
        return redirect('auth/forgot_password/new_password');
    }

    if (strlen($password) < 6) {
        $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
        return redirect('auth/forgot_password/new_password');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $this->User_model->update_password($user_id, $hash);

    // bersihkan session lupa password
    $this->session->unset_userdata([
        'fp_user_id','fp_username','fp_no_wa','fp_expired_at','fp_verified'
    ]);

    $this->session->set_flashdata('success', 'Password berhasil direset. Silakan login.');
    redirect('auth/login');
}



    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
