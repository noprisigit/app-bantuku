<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use GuzzleHttp\Client;

class Transaction extends CI_Controller {
   public function __construct()
   {
      parent::__construct();
      $this->load->model('Transaction_m', 'transaction');
      $this->load->model('api/Product_m', 'product');

      if (!$this->session->userdata('AdminName'))
         redirect('auth');
   }

   private function _sendEmail($email, $type, $invoice, $dataOrder) {
      $config= [
         'protocol'      => 'smtp',
         'smtp_host'     => 'ssl://smtp.googlemail.com',
         'smtp_user'     => 'bantuku2020@gmail.com',
         'smtp_pass'     => '.BantukuBabelProv20',
         'smtp_port'     => 465,
         'smtp_timeout'  => '3',
         'mailtype'      => 'html',
         'charset'       => 'utf-8',
         'newline'       => "\r\n"
      ];

      $this->load->library('email', $config);

      $this->email->from('bantuku2020@gmail.com', 'Bantuku Support');
      $this->email->to($email);


      if ($type == "Proses") {
         $subject = "Pembayaran Anda Diterima dan Pesanan Akan Diproses";
      } else if ($type == "Kirim") {
         $subject = "Pesanan Anda Telah Dikirim";
      } 

      $data['subject'] = $subject;
      $data['invoice'] = $invoice;
      $data['orders'] = $dataOrder;

      $this->email->subject($subject);
      $this->email->message($this->load->view('template_payment', $data, true));

      if ($this->email->send()) {
         return true;
     } else {
         echo $this->email->print_debugger();
         die;
     }
   }

   public function index()
   {
      $data['main_title'] = 'Home';
      $data['title'] = 'Pesanan';

      $data['js'] = [
         'assets/dist/js/transaction.js'
      ];

      $this->load->view('template/header', $data);
      $this->load->view('transaction/index');
      $this->load->view('template/footer', $data);
   }

   public function processOrder()
   {
      date_default_timezone_set('Asia/Jakarta');
      $InvoiceNumber = $this->input->post('invoice');
      $orders = $this->transaction->getOrders($InvoiceNumber);

      $customer = $this->db->select('CustomerName, CustomerEmail')->get_where('customers', ['CustomerUniqueID' => $orders[0]['CustomerUniqueID']])->row_array();
      for ($i = 0; $i < count($orders); $i++) {
         $product[] = $this->product->getProductPrice($orders[$i]['ProductUniqueID']);
         $detailOrder[] = [
            'OrderNumber'     => $orders[$i]['OrderNumber'],
            'ProductUniqueID' => $orders[$i]['ProductUniqueID'],
            'ProductName'     => $product[$i]['ProductName'],
            'Harga'           => $product[$i]['ProductPrice'],  
            'Jumlah'          => $orders[$i]['OrderProductQuantity'],
            'Bayar'           => $orders[$i]['OrderTotalPrice']
         ];
      }
      $this->db->set('OrderStatus', 2);
      $this->db->set('OrderProcessDate', date('Y-m-d H:i:s'));
      $this->db->where('InvoiceNumber',$InvoiceNumber);
      $this->db->update('orders'); 
      
      $this->_sendEmail($customer['CustomerEmail'], "Proses", $orders[0]['Invoice'], $detailOrder);
   }

   public function sendOrder()
   {
      date_default_timezone_set('Asia/Jakarta');
      $InvoiceNumber = $this->input->post('invoice');
      $orders = $this->transaction->getOrders($InvoiceNumber);

      $customer = $this->db->select('CustomerName, CustomerEmail')->get_where('customers', ['CustomerUniqueID' => $orders[0]['CustomerUniqueID']])->row_array();
      for ($i = 0; $i < count($orders); $i++) {
         $product[] = $this->product->getProductPrice($orders[$i]['ProductUniqueID']);
         $detailOrder[] = [
            'OrderNumber'     => $orders[$i]['OrderNumber'],
            'ProductUniqueID' => $orders[$i]['ProductUniqueID'],
            'ProductName'     => $product[$i]['ProductName'],
            'Harga'           => $product[$i]['ProductPrice'],  
            'Jumlah'          => $orders[$i]['OrderProductQuantity'],
            'Bayar'           => $orders[$i]['OrderTotalPrice']
         ];
      }
      $this->db->set('OrderStatus', 3);
      $this->db->set('OrderSendDate', date('Y-m-d H:i:s'));
      $this->db->where('InvoiceNumber', $this->input->post('invoice'));
      $this->db->update('orders'); 
      $this->_sendEmail($customer['CustomerEmail'], "Kirim", $orders[0]['Invoice'], $detailOrder);
   }

   public function getDetailOrder()
   {
      $orderNumber = $this->input->post('orderNumber');
      if ($orderNumber != ""){
         $order = $this->transaction->getDetailOrder($orderNumber);
         echo json_encode($order);
      }
   }

   public function deleteOrder() {
      $invNumber = $this->input->post('invNumber');
      $this->db->delete('orders', ['InvoiceNumber' => $invNumber]);
      $this->db->delete('orders_address', ['InvoiceNumber' => $invNumber]);
      $this->db->delete('transactions', ['InvoiceNumber' => $invNumber]);
   }

   // public function inquiryPaymentStatus() {
   //    $orders = $this->transaction->getTransactionsData();
   //    for ($i=0; $i < count($orders); $i++) { 
   //       $client = new Client();
   //       $response = $client->request('POST', 'https://dev.faspay.co.id/cvr/100004/10', [
   //          'json'   => [
   //             "request"      => "Pengecekan Status Pembayaran",
   //             "trx_id"       => $orders[$i]['FaspayTransactionID'],
   //             "merchant_id"  => "33081",
   //             "bill_no"      => $orders[$i]["InvoiceNumber"],
   //             "signature"    => sha1(md5("bot33081p@ssw0rd".$orders[$i]['InvoiceNumber']))
   //          ]
   //       ]);
   //       $result = json_decode($response->getBody()->getContents(), true);
         
   //       if ($result['payment_status_code'] == "0") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       } elseif ($result['payment_status_code'] == "1") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       } elseif ($result['payment_status_code'] == "2") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       } elseif ($result['payment_status_code'] == "3") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       } elseif ($result['payment_status_code'] == "4") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       } elseif ($result['payment_status_code'] == "5") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       } elseif ($result['payment_status_code'] == "7") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       } elseif ($result['payment_status_code'] == "8") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       } elseif($result['payment_status_code'] == "9") {
   //          $res[$i] = [
   //             'trx_id'                => $orders[$i]['FaspayTransactionID'],
   //             'invoice_number'        => $orders[$i]['InvoiceNumber'],
   //             'productUniqueID'       => $orders[$i]['ProductUniqueID'],
   //             'payment_status_desc'   => $result['payment_status_desc'],
   //          ];
   //       }

   //       if ($orders[$i]['InvoiceNumber'] == $orders[$i-1]['InvoiceNumber']) {
   //          $i++;
   //       }
   //    }
   //    echo json_encode($res);
   // }

   public function show_list_transactions()
   {
      $orders = $this->db->get_where('orders', ['OrderStatus' => 1])->result_array();
      // dd($orders);
      $list = $this->transaction->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $field) {
         $btnDetail = '<button type="button" title="Detail Pesanan" class="btn btn-primary mt-1 btnDetailOrder" data-order="'.$field->OrderNumber.'"><i class="fas fa-folder"></i></button>';
         $btnDelete = '<a href="javascript:void(0)" class="btn btn-danger mt-1 btnDeleteOrder" data-invnumber="'.$field->InvoiceNumber.'" data-invoice="'.$field->Invoice.'" data-order="'.$field->OrderNumber.'"><i class="fas fa-trash-alt"></i></a>';
         $btnProses = '<a href="javascript:void(0)" class="btn btn-success mt-1 btnProsesOrder '.$field->InvoiceNumber.'" data-invoice="'.$field->InvoiceNumber.'">Proses</a>';
         $btnKirim = '<a href="javascript:void(0)" class="btn btn-info mt-1 btnKirimOrder '.$field->InvoiceNumber.'" data-invoice="'.$field->InvoiceNumber.'">Kirim</a>';
         $date = date_create($field->OrderDate);

         $no++;
         $row = array();
         $row[] = $no;
         $row[] = $field->InvoiceNumber;
         $row[] = $field->OrderNumber;
         $row[] = $field->ProductName;
         $row[] = $field->CompanyName;
         $row[] = $field->CustomerName;
         $row[] = $field->OrderProductQuantity . " Buah";
         $row[] = "Rp " . number_format($field->OrderTotalPrice,0,',','.');
         if ($field->OrderStatus == 1) {
            $row[] = '<span class="badge badge-danger">Pending</span>';
         } elseif ($field->OrderStatus == 2) {
            $row[] = '<span class="badge badge-warning">Proses</span>';
         } elseif ($field->OrderStatus == 3) {
            $row[] = '<span class="badge badge-info">Kirim</span>';
         } else {
            $row[] = '<span class="badge badge-success">Selesai</span>';
         }
         if ($field->OrderStatus > 3) {
            $row[] = $btnDetail;
         } elseif ($field->OrderStatus == 1) {
            $row[] = $btnDetail. "&nbsp". $btnDelete . "&nbsp" . $btnProses;
         } elseif ($field->OrderStatus == 2) {
            $row[] = $btnDetail. "&nbsp". $btnDelete . "&nbsp" . $btnKirim;
         } elseif ($field->OrderStatus == 3) {
            $row[] = $btnDetail;
         }

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

   public function cekStatusPembayaran() {
      $client = new Client();

      $response = $client->request('POST', 'https://dev.faspay.co.id/cvr/100004/10', [
         'json' => [
            'request'      => "Pengecekan Status Pembayaran",
            'trx_id'       => "3308130200000182",
            'merchant_id'  => "33081",
            "bill_no"      => "665882170445",
            "signature"    => "1896ac5079d9dd89f70dda52c44be482da6566a9"
         ]
      ]);

      $result = json_decode($response->getBody()->getContents(), true);
      dd($result['response']);
   }
}