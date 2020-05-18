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

   public function getProductsByPromoToday_get()
   {
      $token = $this->get('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $products = $this->product->getProductsByPromoToday();

            if ($products) {
               $this->response([
                  'status'    => true,
                  'data'      => $products
               ], REST_Controller::HTTP_OK);
            } else {
               $this->response([
                  'status'    => false,
                  'message'   => 'No discount for all products today'
               ], REST_Controller::HTTP_NOT_FOUND);
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

   public function getProductsByShop_get()
   {
      $token = $this->get('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $partnerID = $this->get('partnerID');
            if ($partnerID) {
               $products = $this->product->getProductsByShop($partnerID);
               if ($products) {
                  $this->response([
                     'status'    => true,
                     'data'      => $products
                  ], REST_Controller::HTTP_OK);
               } else {
                  $this->response([
                     'status'    => false,
                     'message'   => 'Belum ada produk pada mitra ini'
                  ], REST_Controller::HTTP_NOT_FOUND);
               }
            } else {
               $this->response([
                  'status'    => false,
                  'message'   => 'Missing Partner ID'
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

   public function likesProduct_post()
   {
      $token = $this->post('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $input = [
               'CustomerUniqueID'   => $this->post('customerUniqueID'),
               'ProductUniqueID'    => $this->post('productUniqueID'),
            ];
            $this->db->insert('customerslikesproducts', $input);

            $this->db->select('ProductUniqueID, ProductName, CompanyName');
            $this->db->join('partners', 'partners.PartnerID = products.PartnerID');
            $product = $this->db->get_where('products', ['ProductUniqueID' => $this->post('productUniqueID')])->row_array();
            
            $data = [
               'ProductUniqueID' => $product['ProductUniqueID'],
               'ProductName'     => $product['ProductName'],
               'CompanyName'     => $product['CompanyName']
            ];

            $this->response([
               'status'    => true,
               'data'      => $data,
               'message'   => 'Anda menyukai produk ini'
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