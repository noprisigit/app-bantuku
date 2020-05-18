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
            $tglPesan = date('Y-m-d H:i:s');

            $input = [
               'OrderNumber'           => $uniqueID,
               'CustomerUniqueID'      => $this->post('customerUniqueID'),
               'ProductUniqueID'       => $this->post('productUniqueID'),
               'OrderProductQuantity'  => $this->post('jumlah'),
               'OrderTotalPrice'       => $totalBayar,
               'OrderStatus'           => 1,
               'OrderDate'             => $tglPesan
            ];

            $order = $this->order->createOrder($input);
            if ($order > 0) {
               $this->response([
                  'status'    => true,
                  'data'      => [
                     'OrderNumber'        => $uniqueID,
                     'CustomerUniqueId'   => $this->post('customerUniqueID'),
                     'ProductUniqueID'    => $this->post('productUniqueID'),
                     'Jumlah'             => $this->post('jumlah'),
                     'Total Bayar'        => $totalBayar,
                     'Status Pesanan'     => "Pending",
                     'Tanggal Pesan'      => $tglPesan
                  ],
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
            $tglProses = date('Y-m-d H:i:s');

            $this->db->set('OrderStatus', 2);
            $this->db->set('OrderProcessDate', $tglProses);
            $this->db->where('OrderNumber', $this->post('orderNumber'));
            $this->db->update('orders');

            $sisaStok = $productStock['ProductStock'] - $order['OrderProductQuantity'];
            $this->db->set('ProductStock', $sisaStok);
            $this->db->where('ProductUniqueID', $order['ProductUniqueID']);
            $this->db->update('products');
            
            $this->response([
               'status'    => true,
               'data'      => [
                  'OrderNumber'        => $order['OrderNumber'],
                  'CustomerUniqueId'   => $order['CustomerUniqueID'],
                  'ProductUniqueID'    => $order['ProductUniqueID'],
                  'Jumlah'             => $order['OrderProductQuantity'],
                  'Total Bayar'        => $order['OrderTotalPrice'],
                  'Status Pesanan'     => "Proses",
                  'Tanggal Pesan'      => $tglProses
               ],
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
            $tglKirim = date('Y-m-d H:i:s');

            $this->db->set('OrderStatus', 3);
            $this->db->set('OrderSendDate', $tglKirim);
            $this->db->where('OrderNumber', $this->post('orderNumber'));
            $this->db->update('orders');

            $this->response([
               'status'    => true,
               'data'      => [
                  'OrderNumber'        => $order['OrderNumber'],
                  'CustomerUniqueId'   => $order['CustomerUniqueID'],
                  'ProductUniqueID'    => $order['ProductUniqueID'],
                  'Jumlah'             => $order['OrderProductQuantity'],
                  'Total Bayar'        => $order['OrderTotalPrice'],
                  'Status Pesanan'     => "Kirim",
                  'Tanggal Pesan'      => $tglKirim
               ],
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
            $tglSelesai = date('Y-m-d H:i:s');

            $this->db->set('OrderStatus', 4);
            $this->db->set('OrderCompletedDate', $tglSelesai);
            $this->db->where('OrderNumber', $this->post('orderNumber'));
            $this->db->update('orders');

            $this->response([
               'status'    => true,
               'data'      => [
                  'OrderNumber'        => $order['OrderNumber'],
                  'CustomerUniqueId'   => $order['CustomerUniqueID'],
                  'ProductUniqueID'    => $order['ProductUniqueID'],
                  'Jumlah'             => $order['OrderProductQuantity'],
                  'Total Bayar'        => $order['OrderTotalPrice'],
                  'Status Pesanan'     => "Selesai",
                  'Tanggal Pesan'      => $tglSelesai
               ],
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

   public function setRatingOrder_post()
   {
      $token = $this->post('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $this->db->set('Rating', $this->post('rating'));
            $this->db->where('OrderNumber', $this->post('orderNumber'));
            $this->db->update('orders');

            $this->response([
               'status'       => true,
               'orderNumber'  => $this->post('orderNumber'),
               'rating'       => $this->post('rating'),
               'message'      => 'Rating pesanan berhasil ditambahkan'
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

   public function likesProduct_post()
   {
      $token = $this->post('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $cekLike = $this->db->get_where('customerslikesproducts', ['CustomerUniqueID' => $this->post('customerUniqueID'), 'ProductUniqueID' => $this->post('productUniqueID')])->num_rows();

            if ($cekLike > 0) {
               $this->response([
                  'status'    => false,
                  'message'   => 'Anda sudah menyukai produk ini'
               ], REST_Controller::HTTP_BAD_REQUEST);
            } else {
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

   public function likesShop_post()
   {
      $token = $this->post('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $cekLike = $this->db->get_where('customerslikesshop', ['CustomerUniqueID' => $this->post('customerUniqueID'), 'PartnerUniqueID' => $this->post('partnerUniqueID')])->num_rows();

            if ($cekLike > 0) {
               $this->response([
                  'status'    => false,
                  'message'   => 'Anda sudah menyukai toko ini'
               ], REST_Controller::HTTP_BAD_REQUEST);
            } else {
               $input = [
                  'CustomerUniqueID'   => $this->post('customerUniqueID'),
                  'PartnerUniqueID'    => $this->post('partnerUniqueID')
               ];
               $this->db->insert('customerslikesshop', $input);
   
               $this->db->select('PartnerUniqueID, CompanyName');
               $partner = $this->db->get_where('partners', ['PartnerUniqueID' => $this->post('partnerUniqueID')])->row_array();
               
               $data = [
                  'PartnerUniqueID' => $partner['PartnerUniqueID'],
                  'CompanyName'     => $partner['CompanyName']
               ];
   
               $this->response([
                  'status'    => true,
                  'data'      => $data,
                  'message'   => 'Anda menyukai toko ini'
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
}