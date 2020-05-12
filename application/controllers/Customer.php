<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller 
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('Customer_m', 'customer');
   }

   public function index()
   {
      $data['main_title'] = "Home";
      $data['title'] = "Customer";

      $data['js'] = [
         'assets/dist/js/customer.js'
      ];

      $this->load->view('template/header', $data);
      $this->load->view('customer/index');
      $this->load->view('template/footer', $data);
   }
}