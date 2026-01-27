<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        date_default_timezone_set('Asia/Jakarta'); // sesuaikan kalau perlu
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
    $this->load->model('User_model');
    $this->load->model('Otp_model');
    $this->load->library('Fonnte_lib');

    $username = $this->input->post('username', TRUE);

    $user = $this->db->get_where('users', ['username' => $username])->row();
    if (!$user) {
        $this->session->set_flashdata('error', 'Username tidak ditemukan.');
        return redirect('auth/forgot_password');
    }

    if (empty($user->no_wa)) {
        $this->session->set_flashdata('error', 'Nomor WA untuk akun ini belum diisi. Silakan hubungi admin.');
        return redirect('auth/forgot_password');
    }

    // generate OTP 6 digit
    $kode_otp = mt_rand(100000, 999999);
    $expired  = date('Y-m-d H:i:s', time() + 10 * 60); // 10 menit

    $this->Otp_model->create_otp($user->id, $kode_otp, $expired);

    // kirim ke Fonnte
    $this->fonnte_lib->kirim_otp($user->no_wa, $kode_otp);

    // simpan username di flash untuk langkah berikutnya
    $this->session->set_flashdata('success', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    $this->session->set_flashdata('username_fp', $username);

    redirect('auth/reset_password');
}

public function reset_password()
{
    $data['title'] = 'Reset Password';

    // ambil username dari flashdata kalau ada
    $data['username'] = $this->session->flashdata('username_fp') ?: '';

    $this->load->view('templates/header', $data);
    $this->load->view('auth/reset_password', $data);
    $this->load->view('templates/footer');
}

public function reset_password_process()
{
    $this->load->model('User_model');
    $this->load->model('Otp_model');

    $username   = $this->input->post('username', TRUE);
    $kode_otp   = $this->input->post('kode_otp', TRUE);
    $password   = $this->input->post('password_baru', TRUE);
    $konfirmasi = $this->input->post('password_konfirmasi', TRUE);

    if ($password !== $konfirmasi) {
        $this->session->set_flashdata('error', 'Konfirmasi password tidak sama.');
        return redirect('auth/reset_password');
    }

    if (strlen($password) < 6) {
        $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
        return redirect('auth/reset_password');
    }

    $user = $this->db->get_where('users', ['username' => $username])->row();
    if (!$user) {
        $this->session->set_flashdata('error', 'Username tidak ditemukan.');
        return redirect('auth/reset_password');
    }

    $otp = $this->Otp_model->get_valid_otp($user->id, $kode_otp);
    if (!$otp) {
        $this->session->set_flashdata('error', 'Kode OTP tidak valid atau sudah kadaluarsa.');
        return redirect('auth/reset_password');
    }

    // update password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $this->User_model->update_password($user->id, $hash);

    // tandai OTP dipakai
    $this->Otp_model->mark_used($otp->id);

    $this->session->set_flashdata('success', 'Password berhasil direset. Silakan login dengan password baru.');
    redirect('auth/login');
}


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
