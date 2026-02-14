<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login();
        $this->load->model('Pegawai_model');
        $this->load->model('Attendance_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $role = $this->session->userdata('role');
        if ($role == 'admin') {
            $this->admin_dashboard();
        } else {
            $this->pegawai_dashboard();
        }
    }

    private function admin_dashboard()
    {
        $today     = date('Y-m-d');
        $pegawai   = $this->Pegawai_model->get_all();

        $total_pegawai = count($pegawai);
        $hadir = $izin = $cuti = $sakit = $belum_absen = 0;

        foreach ($pegawai as $p) {
            $absen = $this->Attendance_model->get_today($p->id, $today);

            if ($absen) {
                $status = $absen->status_harian ?: 'Hadir';

                if ($status == 'Hadir') $hadir++;
                elseif ($status == 'Izin') $izin++;
                elseif ($status == 'Cuti') $cuti++;
                elseif ($status == 'Sakit') $sakit++;
            } else {
                $belum_absen++;
            }
        }

        // DATA BARU (DASHBOARD ANALYTICS)
    $top_rajin = $this->Attendance_model->get_top_rajin_bulan_ini();
    $top_telat = $this->Attendance_model->get_top_telat_bulan_ini();

        $data = [
            'title'        => 'Dashboard Admin',
            'today'        => $today,
            'total_pegawai'=> $total_pegawai,
            'hadir'        => $hadir,
            'izin'         => $izin,
            'cuti'         => $cuti,
            'sakit'        => $sakit,
            'belum_absen'  => $belum_absen,
            'top_rajin'    => $top_rajin,
            'top_telat'    => $top_telat
        ];

        $this->load->view('templates/header',$data);
        $this->load->view('dashboard/admin',$data);
        $this->load->view('templates/footer');
    }

    private function pegawai_dashboard()
    {
        $user_id = $this->session->userdata('user_id');
        $pegawai = $this->Pegawai_model->get_by_user_id($user_id);
        if (!$pegawai) show_error('Data pegawai tidak ditemukan.',500);

        $today  = date('Y-m-d');
        $absen_hari = $this->Attendance_model->get_today($pegawai->id, $today);
        $riwayat    = $this->Attendance_model->get_history($pegawai->id, 7);

        $data = [
            'title'      => 'Dashboard Pegawai',
            'pegawai'    => $pegawai,
            'today'      => $today,
            'absen_hari' => $absen_hari,
            'riwayat'    => $riwayat
        ];

        $this->load->view('templates/header',$data);
        $this->load->view('dashboard/pegawai',$data);
        $this->load->view('templates/footer');
    }
}
