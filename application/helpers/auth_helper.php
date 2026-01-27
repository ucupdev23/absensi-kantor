<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function is_logged_in()
{
    $CI =& get_instance();
    return $CI->session->userdata('logged_in') === TRUE;
}

function require_login()
{
    $CI =& get_instance();
    if (!is_logged_in()) {
        redirect('auth/login');
        exit;
    }
}

function require_role($roles = [])
{
    $CI =& get_instance();

    if (!is_logged_in()) {
        redirect('auth/login');
        exit;
    }

    $user_role = $CI->session->userdata('role');

    if (!in_array($user_role, (array)$roles)) {
        // nanti bisa diarahin ke halaman 403 custom
        // show_error('Anda tidak memiliki akses ke halaman ini.', 403, 'Akses Ditolak');
        redirect('errors/forbidden');
    }
}
