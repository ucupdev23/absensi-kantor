<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penugasan_wfh extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        require_role(['admin']);

        $this->load->model('Wfh_model');
        $this->load->model('Pegawai_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = 'Penugasan WFH';
        $data['rows'] = $this->Wfh_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('penugasan_wfh/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Penugasan WFH';
        $data['row'] = null;
        $data['employees'] = $this->Pegawai_model->get_active_list();

        $this->load->view('templates/header', $data);
        $this->load->view('penugasan_wfh/form', $data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $employee_id = $this->input->post('employee_id', true);
        $tanggal_mulai = $this->input->post('tanggal_mulai', true);
        $tanggal_selesai = $this->input->post('tanggal_selesai', true);
        $keterangan = $this->input->post('keterangan', true);

        if (empty($employee_id) || empty($tanggal_mulai) || empty($tanggal_selesai)) {
            $this->session->set_flashdata('error', 'Semua kolom wajib diisi.');
            return redirect('penugasan_wfh/create');
        }

        if ($tanggal_selesai < $tanggal_mulai) {
            $this->session->set_flashdata('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            return redirect('penugasan_wfh/create');
        }

        $data_insert = [
            'employee_id' => $employee_id,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'keterangan' => $keterangan
        ];

        $this->Wfh_model->insert($data_insert);
        $this->session->set_flashdata('success', 'Penugasan WFH berhasil ditambahkan.');
        redirect('penugasan_wfh');
    }

    public function edit($id)
    {
        $row = $this->Wfh_model->get_by_id($id);
        if (!$row) show_404();

        $data['title'] = 'Edit Penugasan WFH';
        $data['row'] = $row;
        $data['employees'] = $this->Pegawai_model->get_active_list();

        $this->load->view('templates/header', $data);
        $this->load->view('penugasan_wfh/form', $data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $row = $this->Wfh_model->get_by_id($id);
        if (!$row) show_404();

        $employee_id = $this->input->post('employee_id', true);
        $tanggal_mulai = $this->input->post('tanggal_mulai', true);
        $tanggal_selesai = $this->input->post('tanggal_selesai', true);
        $keterangan = $this->input->post('keterangan', true);

        if (empty($employee_id) || empty($tanggal_mulai) || empty($tanggal_selesai)) {
            $this->session->set_flashdata('error', 'Semua kolom wajib diisi.');
            return redirect('penugasan_wfh/edit/' . $id);
        }

        if ($tanggal_selesai < $tanggal_mulai) {
            $this->session->set_flashdata('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            return redirect('penugasan_wfh/edit/' . $id);
        }

        $data_update = [
            'employee_id' => $employee_id,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'keterangan' => $keterangan
        ];

        $this->Wfh_model->update($id, $data_update);
        $this->session->set_flashdata('success', 'Penugasan WFH berhasil diperbarui.');
        redirect('penugasan_wfh');
    }

    public function delete($id)
    {
        $row = $this->Wfh_model->get_by_id($id);
        if (!$row) show_404();

        $this->Wfh_model->delete($id);
        $this->session->set_flashdata('success', 'Penugasan WFH berhasil dihapus.');
        redirect('penugasan_wfh');
    }
}
