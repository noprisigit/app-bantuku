<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function index()
    {
        $data['main_title'] = "Home";
        $data['title'] = "Dashboard";

        $this->load->view('template/header', $data);
        $this->load->view('dashboard/index');
        $this->load->view('template/footer');
    }
}