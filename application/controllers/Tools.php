<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {

    public function make_admin()
    {
        $this->load->model('User_model');

        $password_plain = 'admin123'; // bisa kamu ganti
        $data = [
            'username'      => 'admin',
            'password'      => password_hash($password_plain, PASSWORD_BCRYPT),
            'nama_lengkap'  => 'Administrator',
            'role'          => 'admin',
            'status'        => 'aktif'
        ];

        $id = $this->User_model->create($data);

        echo "Admin dibuat. ID: {$id}, username: admin, password: {$password_plain}";
    }
}
