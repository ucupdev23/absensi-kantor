<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['admin']);

        $this->load->model('Leave_model');
        $this->load->model('Attendance_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title']     = 'Persetujuan Pengajuan';
        $data['pengajuan'] = $this->Leave_model->get_all_with_employee();

        $this->load->view('templates/header',$data);
        $this->load->view('pengajuan/admin_index',$data);
        $this->load->view('templates/footer');
    }

    public function approve($id)
    {
        $row = $this->Leave_model->get($id);
        if (!$row) show_404();

        if ($row->status != 'menunggu') {
            $this->session->set_flashdata('error', 'Pengajuan sudah diproses sebelumnya.');
            return redirect('pengajuan-admin');
        }

        $admin_id = $this->session->userdata('user_id');

        // update status pengajuan
        $this->Leave_model->update($id, [
            'status'              => 'disetujui',
            'approver_id'         => $admin_id,
            'tanggal_persetujuan' => date('Y-m-d H:i:s'),
            'catatan_admin'       => $this->input->post('catatan_admin', TRUE)
        ]);

        // update status_harian di attendances
        $this->Attendance_model->set_status_range(
            $row->employee_id,
            $row->jenis,
            $row->tanggal_mulai,
            $row->tanggal_selesai
        );

        $this->session->set_flashdata('success', 'Pengajuan disetujui dan absensi diperbarui.');
        redirect('pengajuan-admin');
    }

    public function reject($id)
    {
        $row = $this->Leave_model->get($id);
        if (!$row) show_404();

        if ($row->status != 'menunggu') {
            $this->session->set_flashdata('error', 'Pengajuan sudah diproses sebelumnya.');
            return redirect('pengajuan-admin');
        }

        $admin_id = $this->session->userdata('user_id');

        $this->Leave_model->update($id, [
            'status'              => 'ditolak',
            'approver_id'         => $admin_id,
            'tanggal_persetujuan' => date('Y-m-d H:i:s'),
            'catatan_admin'       => $this->input->post('catatan_admin', TRUE)
        ]);

        $this->session->set_flashdata('success', 'Pengajuan ditolak.');
        redirect('pengajuan-admin');
    }
}
