<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {

    public function forbidden()
    {
        // set status code 403
        $this->output->set_status_header(403);
        $this->load->view('errors/html/custom_403');
    }
}
