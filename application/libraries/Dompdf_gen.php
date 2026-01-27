<?php
use Dompdf\Dompdf;

defined('BASEPATH') OR exit('No direct script access allowed');

class Dompdf_gen {

    public $dompdf;

    public function __construct()
    {
        // path ke autoload dompdf
        require_once APPPATH.'third_party/dompdf/autoload.inc.php';

        $this->dompdf = new Dompdf();
    }
}
