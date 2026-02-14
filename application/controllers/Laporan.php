<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['admin']);

        $this->load->model('Attendance_model');
        $this->load->model('Pegawai_model');
        $this->load->model('Lokasi_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        // default: bulan ini
        $bulan  = $this->input->get('bulan') ?: date('m');
        $tahun  = $this->input->get('tahun') ?: date('Y');
        $pegawai_id = $this->input->get('pegawai_id');
        $lokasi_id  = $this->input->get('lokasi_id');

        $start_date = date('Y-m-01', strtotime("$tahun-$bulan-01"));
        $end_date   = date('Y-m-t', strtotime($start_date));

        $report = $this->Attendance_model->get_report($start_date, $end_date, $pegawai_id, $lokasi_id);

        // rekap per pegawai (hitung di PHP)
        $rekap = [];
        // foreach ($report as $row) {
        //     $key = $row->employee_id;
        //     if (!isset($rekap[$key])) {
        //         $rekap[$key] = [
        //             'employee_id'   => $row->employee_id,
        //             'nama'          => $row->nama_lengkap,
        //             'kode_pegawai'  => $row->kode_pegawai,
        //             'nama_lokasi'   => $row->nama_lokasi,
        //             'hadir'         => 0,
        //             'telat'         => 0,
        //             'izin'          => 0,
        //             'cuti'          => 0,
        //             'sakit'         => 0,
        //             'total_jam'     => 0,
        //             'jumlah_hari'   => 0,
        //         ];
        //     }

        //     // status_harian
        //     $status_harian = $row->status_harian ?: 'Hadir'; // fallback

        //     if ($status_harian == 'Hadir')      $rekap[$key]['hadir']++;
        //     elseif ($status_harian == 'Izin')  $rekap[$key]['izin']++;
        //     elseif ($status_harian == 'Cuti')  $rekap[$key]['cuti']++;
        //     elseif ($status_harian == 'Sakit') $rekap[$key]['sakit']++;

        //     // telat
        //     if ($row->status_masuk == 'Telat') {
        //         $rekap[$key]['telat']++;
        //     }

        //     if (!empty($row->total_jam_kerja)) {
        //         $rekap[$key]['total_jam'] += (float)$row->total_jam_kerja;
        //     }

        //     $rekap[$key]['jumlah_hari']++;
        // }

        foreach ($report as $row) {
    $key = $row->employee_id;

    if (!isset($rekap[$key])) {
        $rekap[$key] = [
            'employee_id'       => $row->employee_id,
            'nama'              => $row->nama_lengkap,
            'kode_pegawai'      => $row->kode_pegawai,
            'nama_lokasi'       => $row->nama_lokasi,
            'hadir'             => 0,
            'telat'             => 0,
            'izin'              => 0,
            'cuti'              => 0,
            'sakit'             => 0,
            'total_jam'         => 0,
            'jumlah_hari'       => 0,
            'total_menit_telat' => 0,
            'hari_masuk'        => 0,
        ];
    }

    $status_harian = $row->status_harian ?: 'Hadir';

    if ($status_harian == 'Hadir') {
        $rekap[$key]['hadir']++;
        $rekap[$key]['hari_masuk']++;
    }
    elseif ($status_harian == 'Izin')  $rekap[$key]['izin']++;
    elseif ($status_harian == 'Cuti')  $rekap[$key]['cuti']++;
    elseif ($status_harian == 'Sakit') $rekap[$key]['sakit']++;

    // HITUNG TELAT MENIT
    $menit_telat = 0;
    if ($row->jam_masuk && $row->shift_jam_masuk) {
        $jam_shift = strtotime($row->tanggal.' '.$row->shift_jam_masuk);
        $jam_masuk = strtotime($row->jam_masuk);
        $batas_telat = $jam_shift + ($row->toleransi_telat_menit * 60);

        if ($jam_masuk > $batas_telat) {
            $menit_telat = floor(($jam_masuk - $batas_telat) / 60);
        }
    }

    $rekap[$key]['total_menit_telat'] += $menit_telat;

    if (!empty($row->total_jam_kerja)) {
        $rekap[$key]['total_jam'] += (float)$row->total_jam_kerja;
    }

    $rekap[$key]['jumlah_hari']++;
}


        $data['title']      = 'Laporan Absensi Bulanan';
        $data['bulan']      = $bulan;
        $data['tahun']      = $tahun;
        $data['pegawai_id'] = $pegawai_id;
        $data['lokasi_id']  = $lokasi_id;

        $data['report']     = $report;
        $data['rekap']      = $rekap;

        $data['list_pegawai'] = $this->Pegawai_model->get_all();
        $data['list_lokasi']  = $this->Lokasi_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('laporan/index', $data);
        $this->load->view('templates/footer');
    }

    public function excel()
{
    require_role(['admin']);

    $bulan      = $this->input->get('bulan') ?: date('m');
    $tahun      = $this->input->get('tahun') ?: date('Y');
    $pegawai_id = $this->input->get('pegawai_id');
    $lokasi_id  = $this->input->get('lokasi_id');

    $start_date = date('Y-m-01', strtotime("$tahun-$bulan-01"));
    $end_date   = date('Y-m-t', strtotime($start_date));

    $report = $this->Attendance_model->get_report($start_date, $end_date, $pegawai_id, $lokasi_id);

    $this->load->library('Excel_lib');
    $this->excel_lib->export_laporan_absensi($report, $bulan, $tahun);
}



public function pdf()
{
    require_role(['admin']);

    $bulan      = $this->input->get('bulan') ?: date('m');
    $tahun      = $this->input->get('tahun') ?: date('Y');
    $pegawai_id = $this->input->get('pegawai_id');
    $lokasi_id  = $this->input->get('lokasi_id');

    $start_date = date('Y-m-01', strtotime("$tahun-$bulan-01"));
    $end_date   = date('Y-m-t', strtotime($start_date));

    $report = $this->Attendance_model->get_report($start_date, $end_date, $pegawai_id, $lokasi_id);

    $data['report'] = $report;
    $data['bulan']  = $bulan;
    $data['tahun']  = $tahun;

    // render view jadi HTML string
    $html = $this->load->view('laporan/pdf', $data, true);

    // bersihin output buffer kalau ada
    if (ob_get_length()) {
        ob_end_clean();
    }

    // load dompdf via library
    $this->load->library('Dompdf_gen');
    $dompdf = $this->dompdf_gen->dompdf;

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $filename = "laporan_absensi_{$tahun}_{$bulan}.pdf";
    $dompdf->stream($filename, ["Attachment" => true]);
}



}
