<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Product extends REST_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('api/Product_m', 'product');
      $this->load->model('api/Auth_m', 'auth');
   }

   public function getProductsByCategory_get()
   {
      $token = $this->get('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $categoryID = $this->get('categoryID');
            if ($categoryID) {
               $products = $this->product->getProductsByCategory($categoryID);
               if ($products) {
                  $this->response([
                     'status'    => true,
                     'data'      => $products
                  ], REST_Controller::HTTP_OK);
               } else {
                  $this->response([
                     'status'    => false,
                     'message'   => 'Products is empty in this category'
                  ], REST_Controller::HTTP_NOT_FOUND);
               }
            } else {
               $this->response([
                  'status'    => false,
                  'message'   => 'Missing Category ID'
               ], REST_Controller::HTTP_BAD_REQUEST);
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
}