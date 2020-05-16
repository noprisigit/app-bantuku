<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {
   public function __construct()
   {
      parent::__construct();
      $this->load->model('Transaction_m', 'transaction');
   }

   public function index()
   {
      $data['main_title'] = 'Home';
      $data['title'] = 'Transaksi';

      $data['js'] = [
         'assets/dist/js/transaction.js'
      ];

      $this->load->view('template/header', $data);
      $this->load->view('transaction/index');
      $this->load->view('template/footer', $data);
   }

   public function show_list_transactions()
   {
      $list = $this->transaction->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $field) {
         // $btn_detail = '<button type="button" class="btn btn-sm btn-primary btn-detail-slider" data-name="'.$field->SliderName.'" data-description="'.$field->SliderDescription.'" data-start="'.$field->start_date.'" data-end="'.$field->end_date.'" data-picture="'.$field->SliderPicture.'"><i class="fas fa-folder"></i> Detail</button>';
         
         // $btn_edit = '<button type="button" class="btn btn-sm btn-info btn-edit-slider" data-id="'.$field->SliderID.'" data-name="'.$field->SliderName.'" data-description="'.$field->SliderDescription.'" data-start="'.$field->start_date.'" data-end="'.$field->end_date.'" data-picture="'.$field->SliderPicture.'"><i class="fas fa-pencil-alt"></i> Edit</button>';
         
         // $btn_delete = '<a class="btn btn-sm btn-danger btn-delete-slider" data-id="'.$field->SliderID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';

         // $date_start = date_create($field->start_date);
         // $date_end = date_create($field->end_date);
         $date = date_create($field->OrderDate);

         $no++;
         $row = array();
         $row[] = $no;
         $row[] = $field->OrderNumber;
         $row[] = $field->ProductName;
         $row[] = $field->CompanyName;
         $row[] = $field->OrderProductQuantity;
         $row[] = "Rp " . number_format($field->OrderTotalPrice,2,',','.');
         if ($field->OrderStatus == "Pending") {
            $row[] = '<span class="badge badge-danger">Pending</span>';
         } elseif ($field->OrderStatus == "Proses") {
            $row[] = '<span class="badge badge-warning">Proses</span>';
         } elseif ($field->OrderStatus == "Kirim") {
            $row[] = '<span class="badge badge-info">Kirim</span>';
         } else {
            $row[] = '<span class="badge badge-success">Selesai</span>';
         }
         $row[] = date_format($date, 'd-m-Y H:i:s');
         // $row[] = date_format($date_start, 'd-m-Y');
         // $row[] = date_format($date_end, 'd-m-Y');
         // $row[] = $btn_detail . "&nbsp" . $btn_edit . "&nbsp" . $btn_delete;

         $data[] = $row;
      }

      $output = array(
         "draw" => $_POST['draw'],
         "recordsTotal" => $this->transaction->count_all(),
         "recordsFiltered" => $this->transaction->count_filtered(),
         "data" => $data,
      );
      //output dalam format JSON
      echo json_encode($output);
   }
}