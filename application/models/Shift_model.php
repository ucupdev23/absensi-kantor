<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_model extends CI_Model {

    private $table = 'shifts';

    public function get_all()
    {
        return $this->db->order_by('nama_shift','ASC')->get($this->table)->result();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id'=>$id])->row();
    }

    public function insert($data) { $this->db->insert($this->table,$data); }

    public function update($id,$data)
    {
        $this->db->where('id',$id)->update($this->table,$data);
    }

    public function delete($id)
    {
        $this->db->where('id',$id)->delete($this->table);
    }
}
