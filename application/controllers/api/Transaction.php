<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Transaction extends REST_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('api/Auth_m', 'auth');
      $this->load->model('api/Transaction_m', 'transaction');
   }

   public function getAllTransactions_get()
   {
      $token = $this->get('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $transactions = $this->transaction->getAllTransactionsCustomer($this->get('customerUniqueID'));
            for($i = 0; $i < count($transactions); $i++) {
               if ($transactions[$i]['OrderStatus'] == "1" || $transactions[$i]['OrderStatus'] == 1) {
                  $transactions[$i]['StatusPesanan'] = "Pending";
               } else if ($transactions[$i]['OrderStatus'] == "2" || $transactions[$i]['OrderStatus'] == 2) {
                  $transactions[$i]['StatusPesanan'] = "Proses";
               } else if ($transactions[$i]['OrderStatus'] == "3" || $transactions[$i]['OrderStatus'] == 3) {
                  $transactions[$i]['StatusPesanan'] = "Kirim";
               } else if ($transactions[$i]['OrderStatus'] == "4" || $transactions[$i]['OrderStatus'] == 4) {
                  $transactions[$i]['StatusPesanan'] = "Selesai";
               }
            }
            $this->response([
               'status'    => true,
               'jumlah'    => count($transactions),
               'data'      => $transactions
            ], REST_Controller::HTTP_OK);
         } else {
            $this->response([
               'status'    => false,
               'message'   => 'Unauthorized token'
            ], REST_Controller::HTTP_NOT_FOUND);
         }
      } else {
         $this->response([
            'status'    => false,
            'message'   => 'Missing token'
         ], REST_Controller::HTTP_BAD_REQUEST);
      }
   }

   public function paymentNotification_get() {
      $request = $this->get('request');
      $trx_id = $this->get('trx_id');
      $merchant_id = $this->get('merchant_id');
      $merchant = $this->get('merchant');
      $bill_no = $this->get('bill_no');
      $payment_ref = $this->get('payment_ref');
      $payment_date = $this->get('payment_date');
      $payment_status_code = $this->get('payment_status_code');
      $payment_status_desc = $this->get('payment_status_desc');
      $bill_total = $this->get('bill_total');
      $payment_total = $this->get('payment_total');
      $payment_channel_uid = $this->get('payment_channel_uid');
      $payment_channel = $this->get('payment_channel');
      $signature = $this->get('signature');

      if ($payment_status_code == "0" || $payment_status_desc == "Belum diproses") {
         $status = false;
         $message = "Belum diproses";
      } elseif ($payment_status_code == "1" || $payment_status_desc == "Dalam proses") {
         $status = true;
         $message = "Dalam proses";
      } elseif ($payment_status_code == "2" || $payment_status_desc == "Payment Sukses") {
         $status = true;
         $message = "Payment Sukses";

         $this->db->set('PaymentStatus', 2);
         $this->db->where('InvoiceNumber', $bill_no);
         $this->db->update('orders');
      } elseif ($payment_status_code == "3" || $payment_status_desc == "Payment Gagal") {
         $status = false;
         $message = "Payment Gagal";
      } elseif ($payment_status_code == "4" || $payment_status_desc == "Payment Reversal") {
         $status = false;
         $message = "Payment Reversal";
      } elseif ($payment_status_code == "5" || $payment_status_desc == "Tagihan tidak ditemukan") {
         $status = false;
         $message = "Tagihan tidak ditemukan";
      } elseif ($payment_status_code == "7" || $payment_status_desc == "Payment Expired") {
         $status = false;
         $message = "Payment Expired";
      } elseif ($payment_status_code == "8" || $payment_status_desc == "Payment Cancelled") {
         $status = false;
         $message = "Payment Canceled";
      } else {
         $status = false;
         $message = "Unknown";
      }

      $this->response([
         'status'    => $status,
         'message'   => $message,
         'trx_id'    => $trx_id,
         'bill_no'   => $bill_no
      ], REST_Controller::HTTP_OK);
   }
}