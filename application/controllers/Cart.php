<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {
   public function __construct()
   {
      parent::__construct();
      $this->load->model('Cart_m', 'cart');

      if (!$this->session->userdata('AdminName'))
         redirect('auth');
   }

   public function index()
   {
      $data['main_title'] = "Home";
      $data['title'] = "Keranjang";
      $data['js'] = [
         'assets/dist/js/cart.js'
      ];

      $this->load->view('template/header', $data);
      $this->load->view('cart/index');
      $this->load->view('template/footer', $data);
   }

   public function show_list_cart()
   {
      $carts = $this->cart->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($carts as $field) {
         // $button1 = '<a class="btn btn-sm btn-danger btn-disable-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i> Disable</a>'.'&nbsp;<button type="button" data-id="'.$field->CategoryID.'" data-name="'.$field->CategoryName.'" data-description="'.$field->CategoryDescription.'" class="btn btn-sm btn-info btn-edit-category"><i class="fas fa-pencil-alt"></i> Edit</button>'.'&nbsp;<a class="btn btn-sm btn-danger btn-delete-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';

         // $button2 = '<a class="btn btn-sm btn-primary btn-enable-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i> Enable</a>'.'&nbsp;<button type="button" data-id="'.$field->CategoryID.'" data-name="'.$field->CategoryName.'" data-description="'.$field->CategoryDescription.'" class="btn btn-sm btn-info btn-edit-category"><i class="fas fa-pencil-alt"></i> Edit</button>'.'&nbsp;<a class="btn btn-sm btn-danger btn-delete-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';
         $date = date_create($field->date_created);

         $no++;
         $row = array();
         $row[] = $no;
         $row[] = $field->CartNumber;
         $row[] = $field->ProductName;
         $row[] = $field->CompanyName;
         $row[] = $field->CustomerName;
         $row[] = $field->CartProductQuantity;
         $row[] = "Rp " . number_format($field->CartPrice,2,',','.');
         $row[] = date_format($date, 'd-m-Y H:i:s');
         // if ($field->CategoryStatus == 1) {
         //       $row[] = '<span class="badge badge-success">Active</span>';
         // } else {
         //       $row[] = '<span class="badge badge-danger">Not Active</span>';
         // }
         // if ($field->CategoryStatus == 1) {
         //       $row[] = $button1;
         // } else {
         //       $row[] = $button2;
         // }

         $data[] = $row;
      }

      $output = array(
         "draw" => $_POST['draw'],
         "recordsTotal" => $this->cart->count_all(),
         "recordsFiltered" => $this->cart->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }
}