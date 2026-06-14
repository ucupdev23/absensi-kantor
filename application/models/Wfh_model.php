<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wfh_model extends CI_Model
{
    private $table = 'wfh_assignments';

    public function get_all()
    {
        return $this->db->select('w.*, u.nama_lengkap, e.kode_pegawai')
            ->from($this->table . ' w')
            ->join('employees e', 'e.id = w.employee_id')
            ->join('users u', 'u.id = e.user_id')
            ->order_by('w.created_at', 'DESC')
            ->get()->result();
    }

    public function get_by_id($id)
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

    public function delete($id)
    {
        $this->db->delete($this->table, ['id' => $id]);
    }

    public function get_active_wfh_for_employee($employee_id, $date)
    {
        return $this->db->from($this->table)
            ->where('employee_id', $employee_id)
            ->where('tanggal_mulai <=', $date)
            ->where('tanggal_selesai >=', $date)
            ->get()->row();
    }
}
