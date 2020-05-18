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
}