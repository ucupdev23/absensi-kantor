<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_model extends CI_Model {

    private $table = 'leave_requests';

    public function get_by_employee($employee_id)
    {
        return $this->db
            ->where('employee_id', $employee_id)
            ->order_by('created_at','DESC')
            ->get($this->table)
            ->result();
    }

    public function get_all_with_employee()
    {
        $this->db->select('lr.*, u.nama_lengkap, e.kode_pegawai');
        $this->db->from($this->table.' lr');
        $this->db->join('employees e','e.id = lr.employee_id');
        $this->db->join('users u','u.id = e.user_id');
        $this->db->order_by('lr.created_at','DESC');
        return $this->db->get()->result();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id'=>$id])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id',$id)->update($this->table, $data);
    }
}
