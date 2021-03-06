<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Cart extends REST_Controller
{
   public function __construct() {
      parent::__construct();
      $this->load->model('api/Cart_m', 'cart');
      $this->load->model('api/Auth_m', 'auth');
      $this->load->model('api/Product_m', 'product');
   }

   public function addCart_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $uniqueID = 'C' . random_strings(4) . date('YmdHis');
            $product = $this->product->getProductPrice($this->post('productUniqueID'));
            
            $totalBayar = $this->post('jumlah') * $product['ProductPrice'];

            $input = [
               'CustomerUniqueID'      => $this->post('customerUniqueID'),
               'ProductUniqueID'       => $this->post('productUniqueID'),
               'CartNumber'            => $uniqueID,
               'CartProductQuantity'   => $this->post('jumlah'),
               'CartPrice'             => $totalBayar
            ];

            $cart = $this->cart->addCart($input);
            if ($cart > 0) {
               $this->response([
                  'status'    => true,
                  'data'      => $input
               ], REST_Controller::HTTP_CREATED);
            } else {
               $this->response([
                  'status'    => true,
                  'message'      => 'Keranjang gagal ditambahkan'
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

   public function getCartByCustomer_get()
   {
      $token = $this->get('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $customerUniqueID = $this->get('customerUniqueID');
            
            if (isset($customerUniqueID)) {
               $carts = $this->cart->getCartByCustomer($this->get('customerUniqueID'));
               $customer = $this->db->select('CustomerName')->get_where('customers', ['CustomerUniqueID' => $carts[0]['CustomerUniqueID']])->row_array();
               for ($i = 0; $i < count($carts); $i++) {
                  $product[] = $this->cart->getProductFromCart($carts[$i]['ProductUniqueID']);
                  $detailCart[] = [
                     'CartID'          => $carts[$i]['CartID'],
                     'CartNumber'      => $carts[$i]['CartNumber'],
                     'ProductUniqueID' => $carts[$i]['ProductUniqueID'],
                     'ProductName'     => $product[$i]['ProductName'],
                     'CompanyName'     => $product[$i]['CompanyName'],
                     'StatusPromo'     => $product[$i]['ProductStatusPromo'],
                     'Promo'           => $product[$i]['ProductPromo'],
                     'ProductImage'    => $product[$i]['ProductImage'],
                     'ProductThumbnail'=> $product[$i]['ProductThumbnail'],
                     'ProductQuantity' => $carts[$i]['CartProductQuantity'],
                     'Price'           => $carts[$i]['CartPrice'],
                     'DateCreated'     => $carts[$i]['date_created']
                  ];
               }
               // dd($product);
               if ($carts) {
                  $this->response([
                     'status'             => true,
                     'CustomerUniqueID'   => $carts[0]['CustomerUniqueID'],
                     'CustomerName'       => $customer['CustomerName'],
                     'data'               => $detailCart
                  ], REST_Controller::HTTP_OK);
               } else {
                  $this->response([
                     'status'    => false,
                     'message'   => 'Belum ada keranjang untuk customer ini',
                     'data'      => $carts
                  ], REST_Controller::HTTP_OK);
               }
            } else {
               $this->response([
                  'status'    => false,
                  'message'   => 'Missing customer unique ID'
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