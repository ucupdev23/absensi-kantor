<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Otp_model extends CI_Model {

    private $table = 'user_otp';

    public function create_otp($user_id, $kode_otp, $expired_at)
    {
        $this->db->insert($this->table, [
            'user_id'   => $user_id,
            'kode_otp'  => $kode_otp,
            'expired_at'=> $expired_at,
        ]);
    }

    public function get_valid_otp($user_id, $kode_otp)
    {
        $now = date('Y-m-d H:i:s');

        return $this->db
            ->where('user_id', $user_id)
            ->where('kode_otp', $kode_otp)
            ->where('is_used', 0)
            ->where('expired_at >=', $now)
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->row();
    }

    public function mark_used($id)
    {
        $this->db->where('id', $id)->update($this->table, ['is_used' => 1]);
    }
}
