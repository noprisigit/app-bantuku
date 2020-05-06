<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {
    public function index()
    {
        $data['main_title'] = "Slider";
        $data['title'] = "Slider";

        $this->load->view('template/header', $data);
        $this->load->view('slider/index');
        $this->load->view('template/footer');
    }
}