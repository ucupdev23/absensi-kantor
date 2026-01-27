<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['admin']);
        $this->load->model('Lokasi_model');
        $this->load->model('Pegawai_model');
    }

    public function index()
    {
        $data['title']  = 'Master Lokasi Kantor';
        $data['lokasi'] = $this->Lokasi_model->get_all();

        $this->load->view('templates/header',$data);
        $this->load->view('lokasi/index',$data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Lokasi Kantor';
        $data['row']   = null;
        $this->load->view('templates/header',$data);
        $this->load->view('lokasi/form',$data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $data = [
            'nama'        => $this->input->post('nama',TRUE),
            'alamat'      => $this->input->post('alamat',TRUE),
            'latitude'    => $this->input->post('latitude',TRUE),
            'longitude'   => $this->input->post('longitude',TRUE),
            'radius_meter'=> $this->input->post('radius_meter',TRUE),
        ];
        $this->Lokasi_model->insert($data);
        $this->session->set_flashdata('success','Lokasi berhasil ditambahkan.');
        redirect('lokasi');
    }

    public function edit($id)
    {
        $row = $this->Lokasi_model->get($id);
        if(!$row) show_404();

        $data['title'] = 'Edit Lokasi Kantor';
        $data['row']   = $row;
        $this->load->view('templates/header',$data);
        $this->load->view('lokasi/form',$data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $row = $this->Lokasi_model->get($id);
        if(!$row) show_404();

        $data = [
            'nama'        => $this->input->post('nama',TRUE),
            'alamat'      => $this->input->post('alamat',TRUE),
            'latitude'    => $this->input->post('latitude',TRUE),
            'longitude'   => $this->input->post('longitude',TRUE),
            'radius_meter'=> $this->input->post('radius_meter',TRUE),
        ];
        $this->Lokasi_model->update($id,$data);
        $this->session->set_flashdata('success','Lokasi berhasil diperbarui.');
        redirect('lokasi');
    }

    // Lokasi.php
public function delete($id)
{

    $count = $this->Pegawai_model->count_by_lokasi($id);

    if ($count > 0) {
        $this->session->set_flashdata(
            'error',
            'Lokasi tidak dapat dihapus karena masih digunakan oleh '.$count.' pegawai. Hapus / pindahkan pegawai terlebih dahulu.'
        );
        return redirect('lokasi');
    }

    $this->Lokasi_model->delete($id);
    $this->session->set_flashdata('success', 'Lokasi berhasil dihapus.');
    redirect('lokasi');
}

}
