<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['pegawai']);

        $this->load->model('Pegawai_model');
        $this->load->model('Leave_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $pegawai = $this->Pegawai_model->get_by_user_id($user_id);
        if (!$pegawai) show_error('Data pegawai tidak ditemukan.', 500);

        $pengajuan = $this->Leave_model->get_by_employee($pegawai->id);

        $tahun_ini = date('Y');
        $cuti_terpakai = $this->Leave_model->get_used_leave_days($pegawai->id, $tahun_ini);
        $sisa_cuti = (int)$pegawai->jatah_cuti - $cuti_terpakai;

        $data['title']         = 'Pengajuan Cuti / Izin / Sakit';
        $data['pegawai']       = $pegawai;
        $data['pengajuan']     = $pengajuan;
        $data['cuti_terpakai'] = $cuti_terpakai;
        $data['sisa_cuti']     = $sisa_cuti;

        $this->load->view('templates/header',$data);
        $this->load->view('pengajuan/index',$data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $user_id = $this->session->userdata('user_id');
        $pegawai = $this->Pegawai_model->get_by_user_id($user_id);
        if (!$pegawai) show_error('Data pegawai tidak ditemukan.', 500);

        $tahun_ini = date('Y');
        $cuti_terpakai = $this->Leave_model->get_used_leave_days($pegawai->id, $tahun_ini);
        $sisa_cuti = (int)$pegawai->jatah_cuti - $cuti_terpakai;

        $data['title']     = 'Buat Pengajuan';
        $data['pegawai']   = $pegawai;
        $data['sisa_cuti'] = $sisa_cuti;

        $this->load->view('templates/header',$data);
        $this->load->view('pengajuan/form',$data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $user_id = $this->session->userdata('user_id');
        $pegawai = $this->Pegawai_model->get_by_user_id($user_id);
        if (!$pegawai) show_error('Data pegawai tidak ditemukan.', 500);

        $jenis          = $this->input->post('jenis', TRUE);
        $tanggal_mulai  = $this->input->post('tanggal_mulai', TRUE);
        $tanggal_selesai= $this->input->post('tanggal_selesai', TRUE);
        $alasan         = $this->input->post('alasan', TRUE);

        if ($tanggal_selesai < $tanggal_mulai) {
            $this->session->set_flashdata('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            return redirect('pengajuan/create');
        }

        // Hitung jumlah hari cuti/izin (kecuali hari Minggu)
        $start = new DateTime($tanggal_mulai);
        $end = new DateTime($tanggal_selesai);
        $jumlah_hari = 0;
        $requested_by_year = [];

        for ($d = clone $start; $d <= $end; $d->modify('+1 day')) {
            if ($d->format('N') != 7) {
                $jumlah_hari++;
                $yr = $d->format('Y');
                if (!isset($requested_by_year[$yr])) {
                    $requested_by_year[$yr] = 0;
                }
                $requested_by_year[$yr]++;
            }
        }

        if ($jumlah_hari == 0) {
            $this->session->set_flashdata('error', 'Pengajuan tidak valid karena seluruh tanggal yang dipilih adalah hari Minggu (libur).');
            return redirect('pengajuan/create');
        }

        // Validasi jatah cuti jika jenis pengajuan adalah 'cuti'
        if ($jenis == 'cuti') {
            foreach ($requested_by_year as $yr => $days) {
                $used = $this->Leave_model->get_used_leave_days($pegawai->id, $yr);
                $jatah_cuti = (int)$pegawai->jatah_cuti;
                if ($used + $days > $jatah_cuti) {
                    $remaining = $jatah_cuti - $used;
                    $this->session->set_flashdata('error', "Jatah cuti tahunan Anda pada tahun {$yr} tidak mencukupi. Sisa jatah cuti: {$remaining} hari, sedangkan Anda mengajukan: {$days} hari.");
                    return redirect('pengajuan/create');
                }
            }
        }

        // (opsional) upload lampiran
        $lampiran_file = null;
        if (!empty($_FILES['lampiran']['name'])) {
            $upload_path = FCPATH.'uploads/lampiran/';
            if (!is_dir($upload_path)) mkdir($upload_path,0777,true);

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size']      = 4096;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('lampiran')) {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                return redirect('pengajuan/create');
            }
            $data_upload  = $this->upload->data();
            // $lampiran_file = 'uploads/lampiran/'.$data_upload['file_name'];
            $lampiran_file = $data_upload['file_name'];
        }

        $data_insert = [
            'employee_id'    => $pegawai->id,
            'jenis'          => $jenis,
            'tanggal_mulai'  => $tanggal_mulai,
            'tanggal_selesai'=> $tanggal_selesai,
            'jumlah_hari'    => $jumlah_hari,
            'alasan'         => $alasan,
            'lampiran_file'  => $lampiran_file,
            'status'         => 'menunggu'
        ];

        $this->Leave_model->insert($data_insert);
        $this->session->set_flashdata('success', 'Pengajuan berhasil dikirim, menunggu persetujuan.');
        redirect('pengajuan');
    }
}
