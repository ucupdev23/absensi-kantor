<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attendance_model extends CI_Model
{

    private $table = 'attendances';

    public function get_today($employee_id, $tanggal)
    {
        return $this->db
            ->get_where($this->table, [
            'employee_id' => $employee_id,
            'tanggal' => $tanggal
        ])->row();
    }

    public function insert_masuk($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_pulang($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function set_status_range($employee_id, $jenis, $tanggal_mulai, $tanggal_selesai)    {
        $start = new DateTime($tanggal_mulai);
        $end = new DateTime($tanggal_selesai);

        for ($d = $start; $d <= $end; $d->modify('+1 day')) {
            $tgl = $d->format('Y-m-d');

            $row = $this->get_today($employee_id, $tgl);

            $status_harian = ucfirst($jenis); // cuti -> Cuti, izin -> Izin, sakit -> Sakit

            if ($row) {
                $this->db->where('id', $row->id)
                    ->update($this->table, ['status_harian' => $status_harian]);
            }
            else {
                $this->db->insert($this->table, [
                    'employee_id' => $employee_id,
                    'tanggal' => $tgl,
                    'status_harian' => $status_harian
                ]);
            }
        }    }
    // public function get_report($start_date, $end_date, $employee_id = null, $lokasi_id = null)
// {
//     $this->db->select('
//         a.*,
//         e.kode_pegawai,
//         u.nama_lengkap,
//         j.nama AS nama_jabatan,
//         l.nama AS nama_lokasi
//     ');
//     $this->db->from($this->table.' a');
//     $this->db->join('employees e', 'e.id = a.employee_id');
//     $this->db->join('users u', 'u.id = e.user_id');
//     $this->db->join('job_positions j', 'j.id = e.jabatan_id');
//     $this->db->join('locations l', 'l.id = e.lokasi_id');
//     $this->db->where('a.tanggal >=', $start_date);
//     $this->db->where('a.tanggal <=', $end_date);
    //     if (!empty($employee_id)) {
//         $this->db->where('a.employee_id', $employee_id);
//     }
//     if (!empty($lokasi_id)) {
//         $this->db->where('e.lokasi_id', $lokasi_id);
//     }
    //     $this->db->order_by('u.nama_lengkap', 'ASC');
//     $this->db->order_by('a.tanggal', 'ASC');
    //     return $this->db->get()->result();
// }
    public function get_report($start_date, $end_date, $employee_id = null, $lokasi_id = null)    {
        $this->db->select('
        a.*,
        e.kode_pegawai,
        u.nama_lengkap,
        j.nama AS nama_jabatan,
        l.nama AS nama_lokasi,

        s.jam_masuk AS shift_jam_masuk,
    s.toleransi_telat_menit,

        fa.lokasi_nama AS tugas_lokasi_nama,
        fa.tanggal AS tugas_tanggal,
        fa.start_time AS tugas_start_time,
        fa.end_time AS tugas_end_time
    ');
        $this->db->from($this->table . ' a');
        $this->db->join('employees e', 'e.id = a.employee_id');
        $this->db->join('users u', 'u.id = e.user_id');
        $this->db->join('shifts s', 's.id = e.shift_id');
        $this->db->join('job_positions j', 'j.id = e.jabatan_id');
        $this->db->join('locations l', 'l.id = e.lokasi_id');

        // join penugasan (left join karena tidak semua punya)
        $this->db->join('field_assignments fa', 'fa.id = a.assignment_id', 'left');

        $this->db->where('a.tanggal >=', $start_date);
        $this->db->where('a.tanggal <=', $end_date);

        if (!empty($employee_id)) {
            $this->db->where('a.employee_id', $employee_id);
        }
        if (!empty($lokasi_id)) {
            $this->db->where('e.lokasi_id', $lokasi_id);
        }

        $this->db->order_by('u.nama_lengkap', 'ASC');
        $this->db->order_by('a.tanggal', 'ASC');

        return $this->db->get()->result();    }

    public function get_history($employee_id, $limit = 5)    {
        return $this->db
            ->where('employee_id', $employee_id)
            ->order_by('tanggal', 'DESC')
            ->limit($limit)
            ->get($this->table)
            ->result();    }
    // TOP KARYAWAN PALING RAJIN (HADIR TERBANYAK)
    public function get_top_rajin_bulan_ini($limit = 5)
    {
        $this->db->select('
            users.nama_lengkap,
            COUNT(attendances.id) as total_hadir
        ');
        $this->db->from('attendances');
        $this->db->join('employees', 'employees.id = attendances.employee_id');
        $this->db->join('users', 'users.id = employees.user_id');
        $this->db->where('attendances.status_harian', 'Hadir');
        $this->db->where('MONTH(attendances.tanggal)', date('m'));
        $this->db->where('YEAR(attendances.tanggal)', date('Y'));
        $this->db->group_by('employees.id');
        $this->db->order_by('total_hadir', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    // TOP KARYAWAN PALING SERING TELAT
    public function get_top_telat_bulan_ini($limit = 5)
    {
        $this->db->select('
            users.nama_lengkap,
            COUNT(attendances.id) as total_telat
        ');
        $this->db->from('attendances');
        $this->db->join('employees', 'employees.id = attendances.employee_id');
        $this->db->join('users', 'users.id = employees.user_id');
        $this->db->join('shifts', 'shifts.id = employees.shift_id');
        $this->db->where('attendances.status_masuk', 'Telat');
        $this->db->where('MONTH(attendances.tanggal)', date('m'));
        $this->db->where('YEAR(attendances.tanggal)', date('Y'));
        $this->db->group_by('employees.id');
        $this->db->order_by('total_telat', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    // ===== DASHBOARD METHODS =====

    /**
     * Hitung kehadiran hari ini per status dalam 1 query
     */
    public function count_today_by_status($date)
    {
        $sql = "SELECT
                    SUM(CASE WHEN status_harian = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
                    SUM(CASE WHEN status_masuk  = 'Telat'  THEN 1 ELSE 0 END) AS telat,
                    SUM(CASE WHEN status_harian = 'Izin'   THEN 1 ELSE 0 END) AS izin,
                    SUM(CASE WHEN status_harian = 'Cuti'   THEN 1 ELSE 0 END) AS cuti,
                    SUM(CASE WHEN status_harian = 'Sakit'  THEN 1 ELSE 0 END) AS sakit,
                    SUM(CASE WHEN status_harian = 'Ganti_hari' THEN 1 ELSE 0 END) AS ganti_hari,
                    SUM(CASE WHEN status_harian = 'Potong_gaji' THEN 1 ELSE 0 END) AS potong_gaji
                FROM {$this->table}
                WHERE tanggal = ?";
        $row = $this->db->query($sql, array($date))->row();

        return array(
            'hadir' => (int)(isset($row->hadir) ? $row->hadir : 0),
            'telat' => (int)(isset($row->telat) ? $row->telat : 0),
            'izin' => (int)(isset($row->izin) ? $row->izin : 0),
            'cuti' => (int)(isset($row->cuti) ? $row->cuti : 0),
            'sakit' => (int)(isset($row->sakit) ? $row->sakit : 0),
            'ganti_hari' => (int)(isset($row->ganti_hari) ? $row->ganti_hari : 0),
            'potong_gaji' => (int)(isset($row->potong_gaji) ? $row->potong_gaji : 0)
        );
    }

    /**
     * Data kehadiran harian 7 hari terakhir (untuk chart)
     */
    public function get_weekly_trend($days = 7)
    {
        $results = array();
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));

            $sql = "SELECT
                        SUM(CASE WHEN status_harian = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
                        SUM(CASE WHEN status_masuk  = 'Telat'  THEN 1 ELSE 0 END) AS telat
                    FROM {$this->table}
                    WHERE tanggal = ?";
            $row = $this->db->query($sql, array($date))->row();

            $results[] = array(
                'tanggal' => $date,
                'label' => date('d/m', strtotime($date)),
                'hadir' => (int)(isset($row->hadir) ? $row->hadir : 0),
                'telat' => (int)(isset($row->telat) ? $row->telat : 0)
            );
        }
        return $results;
    }

    /**
     * Daftar absensi hari ini terbaru (untuk live table)
     */
    public function get_today_list($date, $limit = 10)
    {
        $this->db->select('a.*, u.nama_lengkap, e.kode_pegawai');
        $this->db->from($this->table . ' a');
        $this->db->join('employees e', 'e.id = a.employee_id');
        $this->db->join('users u', 'u.id = e.user_id');
        $this->db->where('a.tanggal', $date);
        $this->db->where('a.jam_masuk IS NOT NULL', null, false);
        $this->db->order_by('a.jam_masuk', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

}
