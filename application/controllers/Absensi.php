<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_role(['pegawai']);

        $this->load->model('Pegawai_model');
        $this->load->model('Attendance_model');
        $this->load->model('Field_assignment_model');
        date_default_timezone_set('Asia/Jakarta'); // sesuaikan kalau perlu
    }

    // dashboard kecil absensi pegawai
    public function index()
    {
        $user_id  = $this->session->userdata('user_id');
        $pegawai  = $this->Pegawai_model->get_by_user_id($user_id);
        if (!$pegawai) {
            show_error('Data pegawai tidak ditemukan.', 500, 'Error');
        }

        $today        = date('Y-m-d');
        $absen_hari   = $this->Attendance_model->get_today($pegawai->id, $today);
        $penugasan = $this->Field_assignment_model->get_active_for_employee($pegawai->id, $today, date('H:i:s'));
        $data['penugasan'] = $penugasan;

        $this->load->model('Wfh_model');
        $wfh = $this->Wfh_model->get_active_wfh_for_employee($pegawai->id, $today);
        $data['wfh'] = $wfh;

        $data['title']      = 'Absensi Hari Ini';
        $data['pegawai']    = $pegawai;
        $data['absen_hari'] = $absen_hari;
        $data['today']      = $today;

        $this->load->view('templates/header', $data);
        $this->load->view('absensi/index', $data);
        $this->load->view('templates/footer');
    }

    // proses absen masuk
    public function masuk()
    {
        $user_id = $this->session->userdata('user_id');
        $pegawai = $this->Pegawai_model->get_by_user_id($user_id);
        if (!$pegawai) show_error('Data pegawai tidak ditemukan.', 500);

        $today  = date('Y-m-d');
        $now    = date('Y-m-d H:i:s');

        $lat = $this->input->post('latitude');
        $lng = $this->input->post('longitude');

        if (empty($lat) || empty($lng)) {
            $this->session->set_flashdata('error', 'Lokasi tidak terbaca. Pastikan izin lokasi diaktifkan.');
            return redirect('absensi');
        }

        // cek status harian (cuti/izin/sakit)
        $absen = $this->Attendance_model->get_today($pegawai->id, $today);
        if ($absen && in_array($absen->status_harian, ['Cuti', 'Izin', 'Sakit', 'Ganti_hari', 'Potong_gaji'])) {
            $this->session->set_flashdata('error', 'Hari ini Anda tercatat sedang ' . $absen->status_harian . '. Absensi ditolak.');
            return redirect('absensi');
        }

        // cek jika hari Minggu (libur) dan tidak ada penugasan lapangan/WFH aktif
        $is_sunday = (date('N', strtotime($today)) == 7);
        $penugasan = $this->Field_assignment_model->get_active_for_employee($pegawai->id, $today, date('H:i:s'));
        $this->load->model('Wfh_model');
        $wfh = $this->Wfh_model->get_active_wfh_for_employee($pegawai->id, $today);
        if ($is_sunday && !$penugasan && !$wfh) {
            $this->session->set_flashdata('error', 'Hari ini (Minggu) adalah hari libur mingguan. Absensi ditolak.');
            return redirect('absensi');
        }

        // cek sudah pernah absen masuk
        if ($absen && $absen->jam_masuk) {
            $this->session->set_flashdata('error', 'Anda sudah absen masuk hari ini.');
            return redirect('absensi');
        }

        // cek lokasi (geofence)
        // $distance = $this->distance_in_meters(
        //     $pegawai->latitude, $pegawai->longitude,
        //     $lat, $lng
        // );

        // if ($distance > $pegawai->radius_meter) {
        //     $this->session->set_flashdata('error',
        //         'Lokasi Anda di luar radius kantor ('.round($distance).' m). Absensi ditolak.');
        //     return redirect('absensi');
        // }

        $check = $this->resolve_mode_absen($pegawai, $lat, $lng);
if (!$check['allowed']) {
    $this->session->set_flashdata('error', $check['message']);
    return redirect('absensi');
}
$mode_absen = $check['mode'];
$assignment_id = $check['assignment_id'];


        // hitung status_masuk (On time / Telat)
        $status_masuk = $this->hitung_status_masuk($pegawai);

        // upload foto
        // $foto_masuk = $this->upload_foto('foto', 'masuk', $pegawai->id);
        $foto_masuk = $this->save_base64_photo('photo_base64', 'masuk', $pegawai->id);
        if (!$foto_masuk) {
            // pesan error sudah di-set di upload_foto
            return redirect('absensi');
        }

        $data_insert = [
            'employee_id'      => $pegawai->id,
            'tanggal'          => $today,
            'jam_masuk'        => $now,
            'foto_masuk'       => $foto_masuk,
            'lokasi_masuk_lat' => $lat,
            'lokasi_masuk_lng' => $lng,
            'status_masuk'     => $status_masuk,
            'status_harian'    => 'Hadir', // sementara: hadir; nanti bisa disesuaikan dg cuti/izin
            'mode_absen'    => $mode_absen,
'assignment_id' => $assignment_id,
        ];

        if ($absen) {
            // kalau record sudah ada (misal ke depan dipakai utk input cuti), kita update
            $this->Attendance_model->update_pulang($absen->id, $data_insert);
        } else {
            $this->Attendance_model->insert_masuk($data_insert);
        }

        // $this->session->set_flashdata('success', 'Absen masuk berhasil. Status: '.$status_masuk);
        $this->session->set_flashdata('success', 'Absen masuk berhasil. Mode: '.ucfirst($mode_absen).'. Status: '.$status_masuk);
        redirect('absensi');
    }

    // proses absen pulang
    public function pulang()
    {
        $user_id = $this->session->userdata('user_id');
        $pegawai = $this->Pegawai_model->get_by_user_id($user_id);
        if (!$pegawai) show_error('Data pegawai tidak ditemukan.', 500);

        $today = date('Y-m-d');
        $now   = date('Y-m-d H:i:s');

        $lat = $this->input->post('latitude');
        $lng = $this->input->post('longitude');

        if (empty($lat) || empty($lng)) {
            $this->session->set_flashdata('error', 'Lokasi tidak terbaca. Pastikan izin lokasi diaktifkan.');
            return redirect('absensi');
        }

        $absen = $this->Attendance_model->get_today($pegawai->id, $today);
        // cek status harian (cuti/izin/sakit)
        if ($absen && in_array($absen->status_harian, ['Cuti', 'Izin', 'Sakit', 'Ganti_hari', 'Potong_gaji'])) {
            $this->session->set_flashdata('error', 'Hari ini Anda tercatat sedang ' . $absen->status_harian . '. Absensi ditolak.');
            return redirect('absensi');
        }

        // cek jika hari Minggu (libur) dan tidak ada penugasan lapangan/WFH aktif
        $is_sunday = (date('N', strtotime($today)) == 7);
        $penugasan = $this->Field_assignment_model->get_active_for_employee($pegawai->id, $today, date('H:i:s'));
        $this->load->model('Wfh_model');
        $wfh = $this->Wfh_model->get_active_wfh_for_employee($pegawai->id, $today);
        if ($is_sunday && !$penugasan && !$wfh) {
            $this->session->set_flashdata('error', 'Hari ini (Minggu) adalah hari libur mingguan. Absensi ditolak.');
            return redirect('absensi');
        }

        if (!$absen || !$absen->jam_masuk) {
            $this->session->set_flashdata('error', 'Anda belum absen masuk hari ini.');
            return redirect('absensi');
        }

        if ($absen->jam_pulang) {
            $this->session->set_flashdata('error', 'Anda sudah absen pulang hari ini.');
            return redirect('absensi');
        }

        // cek lokasi
        // $distance = $this->distance_in_meters(
        //     $pegawai->latitude, $pegawai->longitude,
        //     $lat, $lng
        // );

        // if ($distance > $pegawai->radius_meter) {
        //     $this->session->set_flashdata('error',
        //         'Lokasi Anda di luar radius kantor ('.round($distance).' m). Absensi pulang ditolak.');
        //     return redirect('absensi');
        // }

        $check = $this->resolve_mode_absen($pegawai, $lat, $lng);
if (!$check['allowed']) {
    $this->session->set_flashdata('error', $check['message']);
    return redirect('absensi');
}
$mode_absen = $check['mode'];
$assignment_id = $check['assignment_id'];


        // hitung status_pulang & total jam kerja
        $status_pulang    = $this->hitung_status_pulang($pegawai);
        $total_jam_kerja  = $this->hitung_total_jam_kerja($absen->jam_masuk, $now);

        // upload foto
        // $foto_pulang = $this->upload_foto('foto', 'pulang', $pegawai->id);
        $foto_pulang = $this->save_base64_photo('photo_base64', 'pulang', $pegawai->id);
        if (!$foto_pulang) {
            return redirect('absensi');
        }

        $data_update = [
            'jam_pulang'        => $now,
            'foto_pulang'       => $foto_pulang,
            'lokasi_pulang_lat' => $lat,
            'lokasi_pulang_lng' => $lng,
            'status_pulang'     => $status_pulang,
            'total_jam_kerja'   => $total_jam_kerja,
            'mode_absen'    => $mode_absen,
'assignment_id' => $assignment_id,
        ];

        $this->Attendance_model->update_pulang($absen->id, $data_update);

        // $this->session->set_flashdata('success', 'Absen pulang berhasil. Status: '.$status_pulang);
        $this->session->set_flashdata('success', 'Absen pulang berhasil. Mode: '.ucfirst($mode_absen).'. Status: '.$status_pulang);
        redirect('absensi');
    }

    // ==== helper privat: upload foto, hitung jarak & status =====

    private function upload_foto($field, $jenis, $employee_id)
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] == 4) {
            $this->session->set_flashdata('error', 'Foto tidak ditemukan. Silakan ambil foto terlebih dahulu.');
            return false;
        }

        $upload_path = FCPATH.'uploads/absensi/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 8192; // 8MB dalam KB
        $config['file_name']     = $jenis.'_'.$employee_id.'_'.time();

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field)) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            return false;
        }

        $data = $this->upload->data();
        // kompres / resize gambar biar ga kegedean
        $this->compress_image($data['full_path']);
        // return 'uploads/absensi/'.$data['file_name']; // path yang disimpan di DB
        return $data['file_name']; // path yang disimpan di DB
    }

    private function save_base64_photo($field, $jenis, $employee_id)
{
    $dataUrl = $this->input->post($field, false); // jangan XSS clean, base64 bisa kepotong
    if (empty($dataUrl)) {
        $this->session->set_flashdata('error', 'Foto tidak ditemukan. Pastikan kamera berhasil mengambil gambar.');
        return false;
    }

    // Terima SEMUA image mime: jpeg/png/webp/gif/bmp/heic/heif, dll (asal base64)
    if (!preg_match('#^data:(image/[^;]+);base64,(.+)$#', $dataUrl, $m)) {
        $this->session->set_flashdata('error', 'Format data foto tidak valid (bukan data URL base64).');
        return false;
    }

    $mime   = strtolower(trim($m[1]));
    $base64 = $m[2];

    // normalisasi base64
    $base64 = str_replace([' ', "\n", "\r", "\t"], ['+', '', '', ''], $base64);

    $binary = base64_decode($base64, true); // strict
    if ($binary === false) {
        $this->session->set_flashdata('error', 'Gagal decode foto.');
        return false;
    }

    // Validasi benar2 gambar
    $info = @getimagesizefromstring($binary);
    if ($info === false || empty($info['mime']) || strpos($info['mime'], 'image/') !== 0) {
        $this->session->set_flashdata('error', 'File yang dikirim bukan gambar yang valid.');
        return false;
    }

    // Batas ukuran lebih longgar (mis. 10MB) karena nanti kita kompres ulang
    if (strlen($binary) > 10 * 1024 * 1024) {
        $this->session->set_flashdata('error', 'Ukuran foto terlalu besar. Coba ulangi.');
        return false;
    }

    $upload_path = FCPATH . 'uploads/absensi/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    $filename_base = $jenis . '_' . $employee_id . '_' . time();

    /**
     * Prefer: re-encode jadi JPG biar seragam & selalu bisa dikompres.
     * imagecreatefromstring() mendukung jpeg/png/gif/webp tergantung GD build.
     */
    $img = @imagecreatefromstring($binary);

    if ($img !== false) {
        $fullpath = $upload_path . $filename_base . '.jpg';

        // OPTIONAL: resize dulu biar ringan (mis. max 900px)
        $img = $this->resize_gd_image($img, 900, 900);

        // Simpan JPG kualitas 75 (bisa kamu atur)
        imagejpeg($img, $fullpath, 75);
        imagedestroy($img);

        // kompres/resize tambahan kalau kamu mau (boleh skip karena sudah jpg kecil)
        // $this->compress_image($fullpath);

        return $filename_base . '.jpg';
    }

    /**
     * Fallback: kalau GD tidak bisa decode format tertentu (mis HEIC),
     * kita simpan original sesuai mime (kalau dari canvas biasanya tidak HEIC).
     */
    $ext = $this->mime_to_ext($info['mime']); // pakai mime hasil getimagesizefromstring
    if (!$ext) $ext = 'jpg';

    $fullpath = $upload_path . $filename_base . '.' . $ext;

    if (file_put_contents($fullpath, $binary) === false) {
        $this->session->set_flashdata('error', 'Gagal menyimpan foto.');
        return false;
    }

    // coba kompres kalau didukung
    $this->compress_image($fullpath);

    return $filename_base . '.' . $ext;
}

// helper mapping mime -> ext
private function mime_to_ext($mime)
{
    $map = [
        'image/jpeg' => 'jpg',
        'image/jpg'  => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
        'image/bmp'  => 'bmp',
    ];
    return isset($map[$mime]) ? $map[$mime] : null;
}

// helper resize gambar GD (biar file kecil)
private function resize_gd_image($src, $max_w = 900, $max_h = 900)
{
    $w = imagesx($src);
    $h = imagesy($src);

    $ratio = min($max_w / $w, $max_h / $h, 1);
    $nw = (int)($w * $ratio);
    $nh = (int)($h * $ratio);

    if ($nw === $w && $nh === $h) return $src;

    $dst = imagecreatetruecolor($nw, $nh);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
    imagedestroy($src);
    return $dst;
}



    // Haversine formula: jarak dalam meter
    private function distance_in_meters($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    private function hitung_status_masuk($pegawai)
    {
        $now        = new DateTime(date('H:i:s'));
        $jam_masuk  = new DateTime($pegawai->jam_masuk);
        $batas_telat= (clone $jam_masuk)->modify('+'.$pegawai->toleransi_telat_menit.' minutes');

        if ($now <= $batas_telat) {
            return 'On time';
        }
        return 'Telat';
    }

    private function hitung_status_pulang($pegawai)
    {
        $now              = new DateTime(date('H:i:s'));
        $jam_pulang       = new DateTime($pegawai->jam_pulang);
        $batas_cepat      = (clone $jam_pulang)->modify('-'.$pegawai->toleransi_pulang_cepat_menit.' minutes');

        if ($now < $batas_cepat) {
            return 'Pulang cepat';
        }
        return 'Normal';
    }

    private function hitung_total_jam_kerja($jam_masuk, $jam_pulang)
    {
        $start = new DateTime($jam_masuk);
        $end   = new DateTime($jam_pulang);
        $diff  = $start->diff($end);

        // konversi ke jam desimal
        $jam   = $diff->h + ($diff->days * 24);
        $menit = $diff->i;
        $total = $jam + ($menit / 60);

        return round($total, 2);
    }

    private function compress_image($file_path, $max_width = 800, $max_height = 800, $quality = 75)
{
    if (!file_exists($file_path)) return;

    $info = getimagesize($file_path);
    if ($info === false) return;

    $width  = $info[0];
    $height = $info[1];
    $mime   = $info['mime'];

    // hitung ukuran baru proporsional
    $ratio = min($max_width / $width, $max_height / $height, 1); // jangan dibesarin
    $new_width  = (int)($width * $ratio);
    $new_height = (int)($height * $ratio);

    switch ($mime) {
        case 'image/jpeg':
            $src_image = imagecreatefromjpeg($file_path);
            break;
        case 'image/png':
            $src_image = imagecreatefrompng($file_path);
            break;
        default:
            // tipe lain ga usah diapa-apain
            return;
    }

    $dst_image = imagecreatetruecolor($new_width, $new_height);

    // untuk PNG, jaga transparansi
    if ($mime == 'image/png') {
        imagealphablending($dst_image, false);
        imagesavealpha($dst_image, true);
    }

    imagecopyresampled(
        $dst_image, $src_image,
        0, 0, 0, 0,
        $new_width, $new_height,
        $width, $height
    );

    // overwrite file lama (boleh juga disimpan sebagai jpg/webp baru kalau mau)
    if ($mime == 'image/jpeg') {
        imagejpeg($dst_image, $file_path, $quality);
    } else {
        imagepng($dst_image, $file_path); // PNG biasa sudah kompres
    }

    imagedestroy($src_image);
    imagedestroy($dst_image);
}

private function resolve_mode_absen($pegawai, $lat, $lng)
{
    // 0) cek penugasan WFH
    $today = date('Y-m-d');
    $this->load->model('Wfh_model');
    $wfh = $this->Wfh_model->get_active_wfh_for_employee($pegawai->id, $today);
    if ($wfh) {
        return [
            'allowed' => true,
            'mode' => 'wfh',
            'assignment_id' => null,
            'message' => null
        ];
    }

    // 1) cek kantor
    $dist_kantor = $this->distance_in_meters(
        $pegawai->latitude, $pegawai->longitude,
        $lat, $lng
    );

    if ($dist_kantor <= $pegawai->radius_meter) {
        return [
            'allowed' => true,
            'mode' => 'kantor',
            'assignment_id' => null,
            'message' => null
        ];
    }

    // 2) cek tugas lapangan aktif
    $today = date('Y-m-d');
    $nowTime = date('H:i:s');

    $assign = $this->Field_assignment_model->get_active_for_employee($pegawai->id, $today, $nowTime);

    if (!$assign) {
        return [
            'allowed' => false,
            'mode' => null,
            'assignment_id' => null,
            'message' => 'Lokasi di luar radius kantor ('.round($dist_kantor).' m) dan tidak ada penugasan lapangan aktif.'
        ];
    }

    // 3) cek radius tugas lapangan
    $dist_task = $this->distance_in_meters($assign->lat, $assign->lng, $lat, $lng);
    if ($dist_task > $assign->radius_meter) {
        return [
            'allowed' => false,
            'mode' => null,
            'assignment_id' => null,
            'message' => 'Lokasi di luar radius tugas lapangan ('.round($dist_task).' m). Absensi ditolak.'
        ];
    }

    return [
        'allowed' => true,
        'mode' => 'lapangan',
        'assignment_id' => $assign->id,
        'message' => null
    ];
}


}
