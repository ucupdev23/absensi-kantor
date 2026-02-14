<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Otp_model extends CI_Model
{
    private $table = 'user_otp';

    public function create_otp($user_id, $kode_otp)
    {
        $now = date('Y-m-d H:i:s');

        $expired_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        $resend_at  = date('Y-m-d H:i:s', strtotime('+1 minute'));

        // nonaktifkan OTP lama
        $this->db->where('user_id', $user_id)
                 ->where('is_used', 0)
                 ->update($this->table, ['is_used' => 1]);

        $this->db->insert($this->table, [
            'user_id'    => $user_id,
            'kode_otp'   => $kode_otp,
            'expired_at'=> $expired_at,
            'resend_at' => $resend_at,
            'is_used'   => 0,
            'created_at'=> $now
        ]);

        return [
            'expired_at' => $expired_at,
            'resend_at'  => $resend_at
        ];
    }

    public function get_latest_active($user_id)
    {
        return $this->db->where('user_id', $user_id)
            ->where('is_used', 0)
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->row();
    }

    public function can_resend($user_id)
    {
        $otp = $this->get_latest_active($user_id);
        if (!$otp) return true;

        return strtotime($otp->resend_at) <= time();
    }

    public function get_valid_otp($user_id, $kode_otp)
    {
        return $this->db->where([
                'user_id' => $user_id,
                'kode_otp'=> $kode_otp,
                'is_used' => 0
            ])
            ->where('expired_at >=', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->row();
    }

    public function mark_used($id)
    {
        $this->db->where('id', $id)
                 ->update($this->table, ['is_used' => 1]);
    }
}
