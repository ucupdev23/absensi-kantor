<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Field_assignment_model extends CI_Model
{
    private $table = 'field_assignments';

    public function get_all($q = [])
    {
        $this->db->select('fa.*, u.nama_lengkap AS creator_name');
        $this->db->from($this->table.' fa');
        $this->db->join('users u', 'u.id = fa.created_by', 'left');

        if (!empty($q['tanggal'])) $this->db->where('fa.tanggal', $q['tanggal']);
        if (!empty($q['status']))  $this->db->where('fa.status', $q['status']);

        $this->db->order_by('fa.tanggal', 'DESC');
        $this->db->order_by('fa.id', 'DESC');

        return $this->db->get()->result();
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
        return $this->db->affected_rows() >= 0;
    }

    public function delete($id)
    {
        // hapus member dulu
        $this->db->where('assignment_id', $id)->delete('field_assignment_members');
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

    // ===== MEMBERS =====

    public function get_member_ids($assignment_id)
    {
        $rows = $this->db->select('employee_id')
            ->from('field_assignment_members')
            ->where('assignment_id', $assignment_id)
            ->get()->result();

        return array_map(fn($r) => (int)$r->employee_id, $rows);
    }

    public function set_members($assignment_id, $employee_ids)
    {
        // bersihin dulu
        $this->db->where('assignment_id', $assignment_id)->delete('field_assignment_members');

        $now = date('Y-m-d H:i:s');
        $batch = [];
        foreach ((array)$employee_ids as $eid) {
            $eid = (int)$eid;
            if ($eid <= 0) continue;
            $batch[] = [
                'assignment_id' => $assignment_id,
                'employee_id'   => $eid,
                'created_at'    => $now
            ];
        }

        if (!empty($batch)) {
            $this->db->insert_batch('field_assignment_members', $batch);
        }
    }

    public function get_members_detail($assignment_id)
    {
        // ambil nama dari users lewat employees.user_id
        return $this->db->select('e.id AS employee_id, e.kode_pegawai, u.nama_lengkap, e.status')
            ->from('field_assignment_members fam')
            ->join('employees e', 'e.id = fam.employee_id')
            ->join('users u', 'u.id = e.user_id')
            ->where('fam.assignment_id', $assignment_id)
            ->order_by('u.nama_lengkap', 'ASC')
            ->get()->result();
    }

    public function get_active_for_employee($employee_id, $date, $time = null)
{
    $this->db->select('fa.*');
    $this->db->from('field_assignments fa');
    $this->db->join('field_assignment_members fam', 'fam.assignment_id = fa.id');
    $this->db->where('fam.employee_id', $employee_id);
    $this->db->where('fa.tanggal', $date);
    $this->db->where('fa.status', 'aktif');

    if ($time) {
        $this->db->group_start();
        $this->db->where('fa.start_time IS NULL', null, false);
        $this->db->or_where('fa.start_time <=', $time);
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where('fa.end_time IS NULL', null, false);
        $this->db->or_where('fa.end_time >=', $time);
        $this->db->group_end();
    }

    $this->db->order_by('fa.id', 'DESC');
    return $this->db->get()->row();
}

public function get_history($start_date, $end_date, $employee_id = null, $status = null)
{
    $this->db->select('fa.*, GROUP_CONCAT(CONCAT(u.nama_lengkap, " (", e.kode_pegawai, ")") SEPARATOR ", ") AS anggota');
    $this->db->from('field_assignments fa');
    $this->db->join('field_assignment_members fam', 'fam.assignment_id = fa.id', 'left');
    $this->db->join('employees e', 'e.id = fam.employee_id', 'left');
    $this->db->join('users u', 'u.id = e.user_id', 'left');

    $this->db->where('fa.tanggal >=', $start_date);
    $this->db->where('fa.tanggal <=', $end_date);

    if (!empty($status)) {
        $this->db->where('fa.status', $status);
    }
    if (!empty($employee_id)) {
        $this->db->where('fam.employee_id', (int)$employee_id);
    }

    $this->db->group_by('fa.id');
    $this->db->order_by('fa.tanggal', 'DESC');
    $this->db->order_by('fa.id', 'DESC');

    return $this->db->get()->result();
}


}
