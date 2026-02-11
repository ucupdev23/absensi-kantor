<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penugasan_lapangan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['admin']);

        $this->load->model('Field_assignment_model');
        $this->load->model('Employee_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $q = [
            'tanggal' => $this->input->get('tanggal', true),
            'status'  => $this->input->get('status', true),
        ];

        $data['title'] = 'Penugasan Lapangan';
        $data['rows']  = $this->Field_assignment_model->get_all($q);
        $data['q']     = $q;

        $this->load->view('templates/header', $data);
        $this->load->view('penugasan_lapangan/index', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Penugasan Lapangan';
        $data['employees'] = $this->Employee_model->get_active_list();
        $data['selected_members'] = [];

        $this->load->view('templates/header', $data);
        $this->load->view('penugasan_lapangan/form', $data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $user_id = (int)$this->session->userdata('user_id');

        $payload = $this->_payload_from_post();
        $members = $this->input->post('members');

        // validasi minimal
        if (empty($payload['tanggal']) || empty($payload['lokasi_nama']) || empty($payload['lat']) || empty($payload['lng'])) {
            $this->session->set_flashdata('error', 'Tanggal, nama lokasi, lat dan lng wajib diisi.');
            return redirect('penugasan_lapangan/create');
        }
        if (empty($members)) {
            $this->session->set_flashdata('error', 'Pilih minimal 1 pegawai.');
            return redirect('penugasan_lapangan/create');
        }

        $payload['created_by'] = $user_id;
        $payload['created_at'] = date('Y-m-d H:i:s');

        $id = $this->Field_assignment_model->insert($payload);
        $this->Field_assignment_model->set_members($id, $members);

        $this->session->set_flashdata('success', 'Penugasan berhasil dibuat.');
        redirect('penugasan_lapangan');
    }

    public function edit($id)
    {
        $row = $this->Field_assignment_model->get_by_id($id);
        if (!$row) show_404();

        $data['title'] = 'Edit Penugasan Lapangan';
        $data['row']   = $row;
        $data['employees'] = $this->Employee_model->get_active_list();
        $data['selected_members'] = $this->Field_assignment_model->get_member_ids($id);

        $this->load->view('templates/header', $data);
        $this->load->view('penugasan_lapangan/form', $data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $row = $this->Field_assignment_model->get_by_id($id);
        if (!$row) show_404();

        $payload = $this->_payload_from_post();
        $members = $this->input->post('members');

        if (empty($payload['tanggal']) || empty($payload['lokasi_nama']) || empty($payload['lat']) || empty($payload['lng'])) {
            $this->session->set_flashdata('error', 'Tanggal, nama lokasi, lat dan lng wajib diisi.');
            return redirect('penugasan_lapangan/edit/'.$id);
        }
        if (empty($members)) {
            $this->session->set_flashdata('error', 'Pilih minimal 1 pegawai.');
            return redirect('penugasan_lapangan/edit/'.$id);
        }

        $payload['updated_at'] = date('Y-m-d H:i:s');

        $this->Field_assignment_model->update($id, $payload);
        $this->Field_assignment_model->set_members($id, $members);

        $this->session->set_flashdata('success', 'Penugasan berhasil diupdate.');
        redirect('penugasan_lapangan');
    }

    public function detail($id)
    {
        $row = $this->Field_assignment_model->get_by_id($id);
        if (!$row) show_404();

        $data['title'] = 'Detail Penugasan';
        $data['row'] = $row;
        $data['members'] = $this->Field_assignment_model->get_members_detail($id);

        $this->load->view('templates/header', $data);
        $this->load->view('penugasan_lapangan/detail', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id)
    {
        $row = $this->Field_assignment_model->get_by_id($id);
        if (!$row) show_404();

        $this->Field_assignment_model->delete($id);
        $this->session->set_flashdata('success', 'Penugasan berhasil dihapus.');
        redirect('penugasan_lapangan');
    }

    private function _payload_from_post()
    {
        return [
            'tanggal'      => $this->input->post('tanggal', true),
            'start_time'   => $this->input->post('start_time', true) ?: null,
            'end_time'     => $this->input->post('end_time', true) ?: null,
            'lokasi_nama'  => $this->input->post('lokasi_nama', true),
            'alamat'       => $this->input->post('alamat', true),
            'lat'          => $this->input->post('lat', true),
            'lng'          => $this->input->post('lng', true),
            'radius_meter' => (int)($this->input->post('radius_meter', true) ?: 200),
            'jenis'        => $this->input->post('jenis', true) ?: 'lainnya',
            'catatan'      => $this->input->post('catatan', true),
            'status'       => $this->input->post('status', true) ?: 'draft',
        ];
    }

    public function history()
{
    $data['title'] = 'Riwayat Penugasan Lapangan';

    $start = $this->input->get('start', true) ?: date('Y-m-01');
    $end   = $this->input->get('end', true) ?: date('Y-m-t');
    $status = $this->input->get('status', true);
    $employee_id = $this->input->get('employee_id', true);

    $data['q'] = [
        'start' => $start,
        'end' => $end,
        'status' => $status,
        'employee_id' => $employee_id
    ];

    $data['employees'] = $this->Employee_model->get_active_list();
    $data['rows'] = $this->Field_assignment_model->get_history($start, $end, $employee_id, $status);

    $this->load->view('templates/header', $data);
    $this->load->view('penugasan_lapangan/history', $data);
    $this->load->view('templates/footer');
}

}
