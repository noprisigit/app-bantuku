<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Order extends REST_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('api/Order_m', 'order');
      $this->load->model('api/Auth_m', 'auth');
      $this->load->model('api/Product_m', 'product');
   }

   public function createOrder_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $product = $this->product->getProductPrice($this->post('productUniqueID'));
            $uniqueID = date('YmdHis') . random_strings(4);
            $totalBayar = $this->post('jumlah') * $product[0]->ProductPrice;
            $input = [
               'OrderNumber'           => $uniqueID,
               'CustomerUniqueID'      => $this->post('customerUniqueID'),
               'ProductUniqueID'       => $this->post('productUniqueID'),
               'OrderProductQuantity'  => $this->post('jumlah'),
               'OrderTotalPrice'       => $totalBayar,
               'OrderStatus'           => "Pending",
               'OrderDate'             => date('Y-m-d H:i:s')
            ];
            $order = $this->order->createOrder($input);
            if ($order > 0) {
               $this->response([
                  'status'    => true,
                  'message'   => 'Order baru berhasil ditambahkan',
               ], REST_Controller::HTTP_CREATED);
            } else {
               $this->response([
                  'status'    => false,
                  'message'   => 'Order gagal ditambahkan',
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

   public function processOrder_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $order = $this->db->get_where('orders', ['OrderNumber' => $this->post('orderNumber')])->row_array();
            // dd($order);
            $productStock = $this->db->select('ProductStock')->get_where('products', ['ProductUniqueID' => $order['ProductUniqueID']])->row_array();

            $this->db->set('OrderStatus', 'Proses');
            $this->db->set('OrderProcessDate', date('Y-m-d H:i:s'));
            $this->db->where('OrderNumber', $this->post('orderNumber'));
            $this->db->update('orders');

            $sisaStok = $productStock['ProductStock'] - $order['OrderProductQuantity'];
            $this->db->set('ProductStock', $sisaStok);
            $this->db->where('ProductUniqueID', $order['ProductUniqueID']);
            $this->db->update('products');
            
            $this->response([
               'status'    => true,
               'message'   => 'Pesanan anda akan diproses'
            ], REST_Controller::HTTP_CREATED);
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

   public function sendOrder_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $order = $this->db->get_where('orders', ['OrderNumber' => $this->post('orderNumber')])->row_array();

            $this->db->set('OrderStatus', 'Kirim');
            $this->db->set('OrderSendDate', date('Y-m-d H:i:s'));
            $this->db->where('OrderNumber', $this->post('orderNumber'));
            $this->db->update('orders');

            $this->response([
               'status'    => true,
               'message'   => 'Pesanan telah dikirim'
            ], REST_Controller::HTTP_CREATED);
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

   public function completedOrder_post()
   {
      $token = $this->post('token');
      if ($token) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $order = $this->db->get_where('orders', ['OrderNumber' => $this->post('orderNumber')])->row_array();

            $this->db->set('OrderStatus', 'Selesai');
            $this->db->set('OrderSendDate', date('Y-m-d H:i:s'));
            $this->db->where('OrderNumber', $this->post('orderNumber'));
            $this->db->update('orders');

            $this->response([
               'status'    => true,
               'message'   => 'Pesanan telah selesai'
            ], REST_Controller::HTTP_CREATED);            
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

   public function createOrderByCart_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $uniqueID = date('YmdHis') . random_strings(4);
            $cart = $this->db->get_where('carts', ['CartNumber' => $this->post('cartNumber')])->row_array();
            
            $input = [
               'OrderNumber'           => $uniqueID,
               'CustomerUniqueID'      => $cart['CustomerUniqueID'],
               'ProductUniqueID'       => $cart['ProductUniqueID'],
               'OrderProductQuantity'  => $cart['CartProductQuantity'],
               'OrderTotalPrice'       => $cart['CartPrice'],
               'OrderStatus'           => "Pending",
               'OrderDate'             => date('Y-m-d H:i:s'),
            ];
            $order = $this->order->createOrder($input);
            $this->db->delete('carts', ['CartNumber' => $this->post('cartNumber')]);
            
            if ($order > 0) {
               $this->response([
                  'status'    => true,
                  'message'   => 'Order baru berhasil ditambahkan',
               ], REST_Controller::HTTP_CREATED);
            } else {
               $this->response([
                  'status'    => false,
                  'message'   => 'Order gagal ditambahkan',
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