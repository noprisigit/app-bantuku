<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    public function index()
    {
        $data['main_title'] = "Category";
        $data['title'] = "Category";

        $this->load->view('template/header', $data);
        $this->load->view('category/index');
        $this->load->view('template/footer');
    }
}