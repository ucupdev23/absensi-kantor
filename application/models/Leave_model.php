<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leave_model extends CI_Model
{

    private $table = 'leave_requests';

    public function get_by_employee($employee_id)
    {
        return $this->db
            ->where('employee_id', $employee_id)
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_all_with_employee()
    {
        $this->db->select('lr.*, u.nama_lengkap, e.kode_pegawai');
        $this->db->from($this->table . ' lr');
        $this->db->join('employees e', 'e.id = lr.employee_id');
        $this->db->join('users u', 'u.id = e.user_id');
        $this->db->order_by('lr.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
    }

    /**
     * Hitung pengajuan yang statusnya pending (menunggu)
     */
    public function count_pending()
    {
        return $this->db
            ->where('status', 'menunggu')
            ->count_all_results($this->table);
    }

    public function get_used_leave_days($employee_id, $year)
    {
        $leaves = $this->db->select('tanggal_mulai, tanggal_selesai, jumlah_hari')
            ->from($this->table)
            ->where('employee_id', $employee_id)
            ->where('jenis', 'cuti')
            ->where('status', 'disetujui')
            ->group_start()
                ->where('YEAR(tanggal_mulai)', $year)
                ->or_where('YEAR(tanggal_selesai)', $year)
            ->group_end()
            ->get()
            ->result();

        $total_days = 0;
        foreach ($leaves as $l) {
            $start = new DateTime($l->tanggal_mulai);
            $end = new DateTime($l->tanggal_selesai);
            for ($d = clone $start; $d <= $end; $d->modify('+1 day')) {
                if ($d->format('Y') == $year && $d->format('N') != 7) {
                    $total_days++;
                }
            }
        }
        return $total_days;
    }

    public function get_remaining_leave_quota($employee_id, $year)
    {
        $employee = $this->db->select('jatah_cuti')->get_where('employees', ['id' => $employee_id])->row();
        $jatah_cuti = $employee ? (int)$employee->jatah_cuti : 12;
        $used = $this->get_used_leave_days($employee_id, $year);
        return $jatah_cuti - $used;
    }
}
