<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Partner extends REST_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('api/Partner_m', 'partner');
      $this->load->model('api/Auth_m', 'auth');
   }

   public function getDetailShop_get()
   {
      $token = $this->get('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $partner = $this->partner->getDetailShop($this->get('partnerUniqueID'));
            $this->response([
               'status'    => true,
               'data'      => $partner
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

   public function getAllPartners_get() {
      $token = $this->get('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $partners = $this->partner->getAllPartners();
            if ($partners) {
               $this->response([
                  'status'    => true,
                  'data'      => $partners
               ], REST_Controller::HTTP_OK);
            } else {
               $this->response([
                  'status'    => false,
                  'message'   => 'Belum ada toko',
                  'data'      => $partners
               ], REST_Controller::HTTP_OK);
            }
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

   public function getPaymentChannel_get()
   {
      $token = $this->get('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $paymentChannel = $this->db->get('paymentchannel')->result_array();
            $this->response([
               'status'    => true,
               'data'      => $paymentChannel
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