<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        require_login();
        $this->load->model('Pegawai_model');
        $this->load->model('Attendance_model');
        $this->load->model('Leave_model');
        $this->load->model('Field_assignment_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $role = $this->session->userdata('role');
        if ($role == 'admin') {
            $this->admin_dashboard();
        }
        else {
            $this->pegawai_dashboard();
        }
    }

    private function admin_dashboard()
    {
        $today = date('Y-m-d');

        // Stat cards
        $total_pegawai = count($this->Pegawai_model->get_all());
        $stats = $this->Attendance_model->count_today_by_status($today);
        $sudah_absen = $stats['hadir'] + $stats['izin'] + $stats['cuti'] + $stats['sakit'];
        $belum_absen = $total_pegawai - $sudah_absen;
        if ($belum_absen < 0)
            $belum_absen = 0;

        // Leaderboard
        $top_rajin = $this->Attendance_model->get_top_rajin_bulan_ini();
        $top_telat = $this->Attendance_model->get_top_telat_bulan_ini();

        // New dashboard data
        $pending_count = $this->Leave_model->count_pending();
        $tugas_hari_ini = $this->Field_assignment_model->get_today_active($today);
        $weekly_trend = $this->Attendance_model->get_weekly_trend(7);
        $absensi_realtime = $this->Attendance_model->get_today_list($today, 10);

        $data = array(
            'title' => 'Dashboard Admin',
            'today' => $today,
            'total_pegawai' => $total_pegawai,
            'hadir' => $stats['hadir'],
            'telat' => $stats['telat'],
            'izin' => $stats['izin'],
            'cuti' => $stats['cuti'],
            'sakit' => $stats['sakit'],
            'belum_absen' => $belum_absen,
            'top_rajin' => $top_rajin,
            'top_telat' => $top_telat,
            'pending_count' => $pending_count,
            'tugas_hari_ini' => $tugas_hari_ini,
            'weekly_trend' => $weekly_trend,
            'absensi_realtime' => $absensi_realtime
        );

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/admin', $data);
        $this->load->view('templates/footer');
    }

    private function pegawai_dashboard()
    {
        $user_id = $this->session->userdata('user_id');
        $pegawai = $this->Pegawai_model->get_by_user_id($user_id);
        if (!$pegawai)
            show_error('Data pegawai tidak ditemukan.', 500);

        $today = date('Y-m-d');
        $absen_hari = $this->Attendance_model->get_today($pegawai->id, $today);
        $riwayat = $this->Attendance_model->get_history($pegawai->id, 7);

        $data = [
            'title' => 'Dashboard Pegawai',
            'pegawai' => $pegawai,
            'today' => $today,
            'absen_hari' => $absen_hari,
            'riwayat' => $riwayat
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/pegawai', $data);
        $this->load->view('templates/footer');
    }
}
