<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users';

    public function get_by_username($username)
    {
        return $this->db->get_where($this->table, [
            'username' => $username,
            'status'   => 'aktif'
        ])->row();
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // public function update_password($id, $password_hash)
    // {
    //     return $this->db
    //         ->where('id', $id)
    //         ->update($this->table, ['password' => $password_hash]);
    // }

    public function find_by_username($username)
    {
        return $this->db->where('username', $username)->get($this->table)->row();
    }

    public function find_by_no_wa($no_wa)
    {
        return $this->db->where('no_wa', $no_wa)->get($this->table)->row();
    }

    public function find_by_identifier($identifier)
    {
        // coba username dulu
        $u = $this->find_by_username($identifier);
        if ($u) return $u;

        // kalau tidak ketemu, coba no_wa
        return $this->find_by_no_wa($identifier);
    }

    public function update_password($user_id, $hash)
    {
        $this->db->where('id', $user_id)->update($this->table, [
            'password' => $hash,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }
}
