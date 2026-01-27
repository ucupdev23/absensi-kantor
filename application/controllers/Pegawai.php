<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['admin']);
        $this->load->model('Pegawai_model');
        $this->load->model('Jabatan_model');
        $this->load->model('Lokasi_model');
        $this->load->model('Shift_model');
    }

    public function index()
    {
        $data['title']   = 'Master Pegawai';
        $data['pegawai'] = $this->Pegawai_model->get_all();

        $this->load->view('templates/header',$data);
        $this->load->view('pegawai/index',$data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title']   = 'Tambah Pegawai';
        $data['row']     = null;
        $data['jabatan'] = $this->Jabatan_model->get_all();
        $data['lokasi']  = $this->Lokasi_model->get_all();
        $data['shift']   = $this->Shift_model->get_all();

        $this->load->view('templates/header',$data);
        $this->load->view('pegawai/form',$data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $username = $this->input->post('username',TRUE);
        $password = $this->input->post('password',TRUE);

        $user_data = [
            'username'      => $username,
            'no_wa'         => $this->input->post('no_wa',TRUE),
            'password'      => password_hash($password, PASSWORD_BCRYPT),
            'nama_lengkap'  => $this->input->post('nama_lengkap',TRUE),
            'role'          => 'pegawai',
            'status'        => $this->input->post('status_user',TRUE)
        ];

        $employee_data = [
            'kode_pegawai'  => $this->input->post('kode_pegawai',TRUE),
            'jabatan_id'    => $this->input->post('jabatan_id',TRUE),
            'lokasi_id'     => $this->input->post('lokasi_id',TRUE),
            'shift_id'      => $this->input->post('shift_id',TRUE),
            'alamat'        => $this->input->post('alamat',TRUE),
            'status'        => $this->input->post('status_pegawai',TRUE),
        ];

        $this->Pegawai_model->insert_employee($user_data,$employee_data);
        $this->session->set_flashdata('success','Pegawai berhasil ditambahkan.');
        redirect('pegawai');
    }

    public function edit($id)
    {
        $row = $this->Pegawai_model->get($id);
        if(!$row) show_404();

        $data['title']   = 'Edit Pegawai';
        $data['row']     = $row;
        $data['jabatan'] = $this->Jabatan_model->get_all();
        $data['lokasi']  = $this->Lokasi_model->get_all();
        $data['shift']   = $this->Shift_model->get_all();

        $this->load->view('templates/header',$data);
        $this->load->view('pegawai/form',$data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $row = $this->Pegawai_model->get($id);
        if(!$row) show_404();

        $user_data = [
            'nama_lengkap' => $this->input->post('nama_lengkap',TRUE),
            'status'       => $this->input->post('status_user',TRUE),
            'no_wa'        => $this->input->post('no_wa',TRUE),
        ];
        // password baru opsional
        $password = $this->input->post('password',TRUE);
        if (!empty($password)) {
            $user_data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $employee_data = [
            'kode_pegawai'  => $this->input->post('kode_pegawai',TRUE),
            'jabatan_id'    => $this->input->post('jabatan_id',TRUE),
            'lokasi_id'     => $this->input->post('lokasi_id',TRUE),
            'shift_id'      => $this->input->post('shift_id',TRUE),
            'alamat'        => $this->input->post('alamat',TRUE),
            'status'        => $this->input->post('status_pegawai',TRUE),
        ];

        $this->Pegawai_model->update_employee($id,$user_data,$employee_data);
        $this->session->set_flashdata('success','Pegawai berhasil diperbarui.');
        redirect('pegawai');
    }

    public function delete($id)
    {
        $this->Pegawai_model->delete($id);
        $this->session->set_flashdata('success','Pegawai berhasil dihapus.');
        redirect('pegawai');
    }
}
