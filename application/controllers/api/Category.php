<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Category extends REST_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('api/Auth_m', 'auth');
      $this->load->model('api/Category_m', 'category');
   }
   
   public function listCategory_get()
   {
      $token = $this->get('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $listCategory = $this->category->getActiveCategory();
   
            $this->response([
               'status'    => true,
               'data'      => $listCategory
            ], REST_Controller::HTTP_OK);
         } else {
            $this->response([
               'status'    => false,
               'message'   => 'Unauthorized token'
            ], REST_Controller::HTTP_BAD_REQUEST);
         }
      } else {
         $this->response([
            'status'    => false,
            'message'   => 'Missing token'
         ], REST_Controller::HTTP_BAD_REQUEST);
      }
   }
}