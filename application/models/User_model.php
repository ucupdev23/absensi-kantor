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

    public function update_password($id, $password_hash)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, ['password' => $password_hash]);
    }
}
