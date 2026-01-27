<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['admin']);
        $this->load->model('Jabatan_model');
        $this->load->model('Pegawai_model');
    }

    public function index()
    {
        $data['title']   = 'Master Jabatan';
        $data['jabatan'] = $this->Jabatan_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('jabatan/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Jabatan';
        $data['row']   = null;

        $this->load->view('templates/header', $data);
        $this->load->view('jabatan/form', $data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $data = [
            'nama'       => $this->input->post('nama', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE)
        ];
        $this->Jabatan_model->insert($data);
        $this->session->set_flashdata('success', 'Jabatan berhasil ditambahkan.');
        redirect('jabatan');
    }

    public function edit($id)
    {
        $row = $this->Jabatan_model->get($id);
        if (!$row) show_404();

        $data['title'] = 'Edit Jabatan';
        $data['row']   = $row;

        $this->load->view('templates/header', $data);
        $this->load->view('jabatan/form', $data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $row = $this->Jabatan_model->get($id);
        if (!$row) show_404();

        $data = [
            'nama'       => $this->input->post('nama', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE)
        ];
        $this->Jabatan_model->update($id, $data);
        $this->session->set_flashdata('success', 'Jabatan berhasil diperbarui.');
        redirect('jabatan');
    }

    public function delete($id)
{

    $count = $this->Pegawai_model->count_by_jabatan($id);

    if ($count > 0) {
        $this->session->set_flashdata(
            'error',
            'Jabatan tidak dapat dihapus karena masih digunakan oleh '.$count.' pegawai. Hapus / pindahkan pegawai terlebih dahulu.'
        );
        return redirect('jabatan');
    }

    $this->Jabatan_model->delete($id); // atau $this->db->delete('job_positions', ['id'=>$id]);
    $this->session->set_flashdata('success', 'Jabatan berhasil dihapus.');
    redirect('jabatan');
}

}
