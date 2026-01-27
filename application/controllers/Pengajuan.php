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

        $data['title']     = 'Pengajuan Cuti / Izin / Sakit';
        $data['pegawai']   = $pegawai;
        $data['pengajuan'] = $pengajuan;

        $this->load->view('templates/header',$data);
        $this->load->view('pengajuan/index',$data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Buat Pengajuan';
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
            'alasan'         => $alasan,
            'lampiran_file'  => $lampiran_file,
            'status'         => 'menunggu'
        ];

        $this->Leave_model->insert($data_insert);
        $this->session->set_flashdata('success', 'Pengajuan berhasil dikirim, menunggu persetujuan.');
        redirect('pengajuan');
    }
}
