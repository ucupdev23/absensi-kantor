<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['admin']);
        $this->load->model('Shift_model');
        $this->load->model('Pegawai_model');
    }

    public function index()
    {
        $data['title'] = 'Master Shift';
        $data['shift'] = $this->Shift_model->get_all();

        $this->load->view('templates/header',$data);
        $this->load->view('shift/index',$data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Shift';
        $data['row']   = null;
        $this->load->view('templates/header',$data);
        $this->load->view('shift/form',$data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $data = [
            'nama_shift'                  => $this->input->post('nama_shift',TRUE),
            'jam_masuk'                   => $this->input->post('jam_masuk',TRUE),
            'jam_pulang'                  => $this->input->post('jam_pulang',TRUE),
            'toleransi_telat_menit'       => $this->input->post('toleransi_telat_menit',TRUE),
            'toleransi_pulang_cepat_menit'=> $this->input->post('toleransi_pulang_cepat_menit',TRUE),
        ];
        $this->Shift_model->insert($data);
        $this->session->set_flashdata('success','Shift berhasil ditambahkan.');
        redirect('shift');
    }

    public function edit($id)
    {
        $row = $this->Shift_model->get($id);
        if(!$row) show_404();

        $data['title'] = 'Edit Shift';
        $data['row']   = $row;
        $this->load->view('templates/header',$data);
        $this->load->view('shift/form',$data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $row = $this->Shift_model->get($id);
        if(!$row) show_404();

        $data = [
            'nama_shift'                  => $this->input->post('nama_shift',TRUE),
            'jam_masuk'                   => $this->input->post('jam_masuk',TRUE),
            'jam_pulang'                  => $this->input->post('jam_pulang',TRUE),
            'toleransi_telat_menit'       => $this->input->post('toleransi_telat_menit',TRUE),
            'toleransi_pulang_cepat_menit'=> $this->input->post('toleransi_pulang_cepat_menit',TRUE),
        ];
        $this->Shift_model->update($id,$data);
        $this->session->set_flashdata('success','Shift berhasil diperbarui.');
        redirect('shift');
    }

    // Shift.php
public function delete($id)
{

    $count = $this->Pegawai_model->count_by_shift($id);

    if ($count > 0) {
        $this->session->set_flashdata(
            'error',
            'Shift tidak dapat dihapus karena masih digunakan oleh '.$count.' pegawai. Hapus / pindahkan pegawai terlebih dahulu.'
        );
        return redirect('shift');
    }

    $this->Shift_model->delete($id);
    $this->session->set_flashdata('success', 'Shift berhasil dihapus.');
    redirect('shift');
}

}
