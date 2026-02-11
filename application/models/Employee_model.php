<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model
{
    private $table = 'employees';

    public function get_active_list()
    {
        return $this->db->select('e.id, e.kode_pegawai, u.nama_lengkap')
            ->from($this->table.' e')
            ->join('users u', 'u.id = e.user_id')
            ->where('e.status', 'aktif')
            ->where('u.status', 'aktif')
            ->order_by('u.nama_lengkap', 'ASC')
            ->get()->result();
    }
}
