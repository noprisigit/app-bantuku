<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller 
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('Customer_m', 'customer');

      if (!$this->session->userdata('AdminName'))
         redirect('auth');
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

   public function customer_post()
   {
      $customer = $this->db->get_where('customers', ['CustomerEmail' => $this->input->post('email')]);
      if ($customer->num_rows() > 0) {
         $res['status'] = false;
         $res['message'] = "Email ini sudah terdaftar";
      } else {
         date_default_timezone_set('Asia/Jakarta');
   
         $uniqueID = random_strings(5) . date('YmdHis');
         $generateCode = generate_code(6);
         $token = generateTokenLogin();
   
         $input = [
            'CustomerUniqueID'         => $uniqueID,
            'CustomerName'             => $this->input->post('name'),
            'CustomerEmail'            => $this->input->post('email'),
            'CustomerPassword'         => password_hash($this->input->post('pass'), PASSWORD_DEFAULT),
            'CustomerAddress1'         => $this->input->post('address'),
            'CustomerPhone'            => $this->input->post('phone'),
            'CustomerVerifiedEmail'    => 0,
            'CustomerRegistrationDate' => date('Y-m-d H:i:s'),
            'CustomerVerificationCode' => $generateCode,
            'CustomerLoginToken'       => $token
         ];
   
         $this->db->insert('customers', $input);
         $res['status'] = true;
         $res['message'] = "Customer baru berhasil ditambahkan";
      }
      echo json_encode($res);
   }

   public function show_list_customers()
   {
      $categories = $this->customer->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($categories as $field) {
         // $button1 = '<a class="btn btn-sm btn-danger btn-disable-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i> Disable</a>'.'&nbsp;<button type="button" data-id="'.$field->CategoryID.'" data-name="'.$field->CategoryName.'" data-description="'.$field->CategoryDescription.'" class="btn btn-sm btn-info btn-edit-category"><i class="fas fa-pencil-alt"></i> Edit</button>'.'&nbsp;<a class="btn btn-sm btn-danger btn-delete-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';

         // $button2 = '<a class="btn btn-sm btn-primary btn-enable-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i> Enable</a>'.'&nbsp;<button type="button" data-id="'.$field->CategoryID.'" data-name="'.$field->CategoryName.'" data-description="'.$field->CategoryDescription.'" class="btn btn-sm btn-info btn-edit-category"><i class="fas fa-pencil-alt"></i> Edit</button>'.'&nbsp;<a class="btn btn-sm btn-danger btn-delete-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';
         
         $no++;
         $row = array();
         $row[] = $no;
         $row[] = $field->CustomerUniqueID;
         $row[] = $field->CustomerName;
         $row[] = $field->CustomerEmail;
         $row[] = $field->CustomerPhone;
         if ($field->CustomerVerifiedEmail == 1) {
               $row[] = '<span class="badge badge-success">Email Telah Diverifikasi</span>';
         } else {
               $row[] = '<span class="badge badge-danger">Email Belum Diverifikasi</span>';
         }
         $row[] = '<button></button>';
         // if ($field->CategoryStatus == 1) {
         //       $row[] = ;
         // } else {
         //       $row[] = $button2;
         // }

         $data[] = $row;
      }

      $output = array(
         "draw" => $_POST['draw'],
         "recordsTotal" => $this->customer->count_all(),
         "recordsFiltered" => $this->customer->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }
}