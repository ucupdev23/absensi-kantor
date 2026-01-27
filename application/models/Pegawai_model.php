<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_model extends CI_Model {

    private $table = 'employees';

    public function get_all()
    {
        $this->db->select('e.*, u.username, u.nama_lengkap, u.no_wa,
                           j.nama AS nama_jabatan,
                           l.nama AS nama_lokasi,
                           s.nama_shift');
        $this->db->from($this->table.' e');
        $this->db->join('users u','u.id = e.user_id');
        $this->db->join('job_positions j','j.id = e.jabatan_id');
        $this->db->join('locations l','l.id = e.lokasi_id');
        $this->db->join('shifts s','s.id = e.shift_id');
        $this->db->order_by('u.nama_lengkap','ASC');
        return $this->db->get()->result();
    }

    public function get($id)
    {
        $this->db->select('e.*, u.username, u.nama_lengkap, u.no_wa, u.status AS status_user');
        $this->db->from($this->table.' e');
        $this->db->join('users u','u.id = e.user_id');
        $this->db->where('e.id',$id);
        return $this->db->get()->row();
    }

    public function insert_employee($user_data, $employee_data)
    {
        // insert user dulu
        $this->db->insert('users',$user_data);
        $user_id = $this->db->insert_id();

        $employee_data['user_id'] = $user_id;
        $this->db->insert($this->table,$employee_data);
    }

    public function update_employee($id, $user_data, $employee_data)
    {
        // update employee
        $this->db->where('id',$id)->update($this->table,$employee_data);

        if (!empty($user_data)) {
            // ambil user_id
            $emp = $this->db->get_where($this->table,['id'=>$id])->row();
            if ($emp) {
                $this->db->where('id',$emp->user_id)->update('users',$user_data);
            }
        }
    }

    public function delete($id)
    {
        $emp = $this->db->get_where($this->table,['id'=>$id])->row();
        if ($emp) {
            $this->db->where('id',$id)->delete($this->table);
            $this->db->where('id',$emp->user_id)->delete('users');
        }
    }

    public function get_by_user_id($user_id)
{
    $this->db->select('e.*, u.username, u.nama_lengkap, u.no_wa,
                       j.nama AS nama_jabatan,
                       l.nama AS nama_lokasi, l.latitude, l.longitude, l.radius_meter,
                       s.nama_shift, s.jam_masuk, s.jam_pulang,
                       s.toleransi_telat_menit, s.toleransi_pulang_cepat_menit');
    $this->db->from($this->table.' e');
    $this->db->join('users u','u.id = e.user_id');
    $this->db->join('job_positions j','j.id = e.jabatan_id');
    $this->db->join('locations l','l.id = e.lokasi_id');
    $this->db->join('shifts s','s.id = e.shift_id');
    $this->db->where('u.id',$user_id);
    return $this->db->get()->row();
}

// application/models/Pegawai_model.php
public function count_by_jabatan($jabatan_id)
{
    return $this->db
        ->where('jabatan_id', $jabatan_id)
        ->count_all_results($this->table);
}

public function count_by_lokasi($lokasi_id)
{
    return $this->db
        ->where('lokasi_id', $lokasi_id)
        ->count_all_results($this->table);
}

public function count_by_shift($shift_id)
{
    return $this->db
        ->where('shift_id', $shift_id)
        ->count_all_results($this->table);
}


}
