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

        // Validasi jatah cuti jika jenis pengajuan adalah 'cuti'
        if ($row->jenis == 'cuti') {
            $this->load->model('Pegawai_model');
            $pegawai = $this->Pegawai_model->get($row->employee_id);
            
            // Hitung hari yang diajukan per tahun
            $start = new DateTime($row->tanggal_mulai);
            $end = new DateTime($row->tanggal_selesai);
            $requested_by_year = [];

            for ($d = clone $start; $d <= $end; $d->modify('+1 day')) {
                if ($d->format('N') != 7) {
                    $yr = $d->format('Y');
                    if (!isset($requested_by_year[$yr])) {
                        $requested_by_year[$yr] = 0;
                    }
                    $requested_by_year[$yr]++;
                }
            }

            foreach ($requested_by_year as $yr => $days) {
                $used = $this->Leave_model->get_used_leave_days($row->employee_id, $yr);
                $jatah_cuti = (int)$pegawai->jatah_cuti;
                if ($used + $days > $jatah_cuti) {
                    $remaining = $jatah_cuti - $used;
                    $this->session->set_flashdata('error', "Gagal menyetujui. Jatah cuti pegawai pada tahun {$yr} tidak mencukupi (Sisa: {$remaining} hari, Diajukan: {$days} hari).");
                    return redirect('pengajuan-admin');
                }
            }
        }

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
