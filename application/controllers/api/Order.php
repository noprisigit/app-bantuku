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

   public function createSignature_get() {
      $this->response([
         'signature' => sha1(md5("bot33081p@ssw0rd714374856316958"))
      ]);
   }

   public function createOrder_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $ordersData = $this->post('orders');
            $orderDate = date('Y-m-d H:i:s');
            $invoiceNumber = generateInvoiceNumber();
            $checkInvoiceNumber = $this->db->get_where('orders', ['InvoiceNumber' => $invoiceNumber])->num_rows();
            if ($checkInvoiceNumber > 0) {
               $invoiceNumber = generateInvoiceNumber();
            }
            $billTotal = 0;
            $customer = $this->db->select('CustomerName')->get_where('customers', ['CustomerUniqueID' => $this->post('customerUniqueID')])->row_array();
            
            for ($i = 0; $i < count($ordersData); $i++) {
               $product[] = $this->product->getProductPrice($ordersData[$i]['productUniqueID']);
               if ($product[$i]['ProductStock'] < $ordersData[$i]['jumlah']) {
                  $stock[] = [
                     'ProductUniqueID' => $ordersData[$i]['ProductUniqueID'],
                     'ProductName'     => $product[$i]['ProductName']
                  ]; 
                  $this->response([
                     'status'    => false,
                     'message'   => 'Order Gagal Ditambahkan, jumlah stok barang tidak mencukupi'
                  ], REST_Controller::HTTP_BAD_REQUEST);
               } else {
                  $orderNumber = generateOrderNumber();
                  $checkOrderNumber = $this->db->get_where('orders', ['OrderNumber' => $orderNumber])->num_rows();
                  if ($checkOrderNumber > 0) {
                     $orderNumber = generateOrderNumber();
                  }
                  $amount[] = $ordersData[$i]['jumlah'] * $product[$i]['ProductPrice'];
                  $input = [
                     'OrderNumber'           => $orderNumber,
                     'InvoiceNumber'         => $invoiceNumber,
                     'Invoice'               => "INV/" . date('Ymd') . "/#" . "/" . $invoiceNumber,
                     'CustomerUniqueID'      => $this->post('customerUniqueID'),
                     'ProductUniqueID'       => $ordersData[$i]['productUniqueID'],
                     'OrderProductQuantity'  => $ordersData[$i]['jumlah'],
                     'OrderTotalPrice'       => $amount[$i],
                     'OrderStatus'           => 1,
                     'OrderDate'             => $orderDate
                  ];
                  $order = $this->order->createOrder($input);
               }
               $detailOrder[] = [
                  'OrderNumber'     => $orderNumber,
                  'ProductUniqueID' => $ordersData[$i]['productUniqueID'],
                  'ProductName'     => $product[$i]['ProductName'],
                  'Harga'           => $product[$i]['ProductPrice'],  
                  'Jumlah'          => $ordersData[$i]['jumlah'],
                  'Bayar'           => $amount[$i]
               ];
               $billTotal = $billTotal + $amount[$i];
            }

            if ($order > 0) {
               $this->response([
                  'status'             => true,
                  'message'            => 'Order baru berhasil ditambahkan',
                  'InvoiceNumber'      => $invoiceNumber,
                  'Invoice'            => "INV/" . date('Ymd') . "/#" . "/" . $invoiceNumber,
                  'CustomerUniqueID'   => $this->post('customerUniqueID'),
                  'CustomerName'       => $customer['CustomerName'],
                  'items'              => $detailOrder,
                  'TotalBayar'         => $billTotal,
                  'StatusPesanan'      => 'Pending',
                  'TanggalPesan'       => $orderDate
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
            $orders = $this->db->get_where('orders', ['InvoiceNumber' => $this->post('invoiceNumber')])->result_array();
            $customer = $this->db->select('CustomerName')->get_where('customers', ['CustomerUniqueID' => $orders[0]['CustomerUniqueID']])->row_array();
            $billTotal = 0;
            for($i = 0; $i < count($orders); $i++) {
               $product[] = $this->product->getProductPrice($orders[$i]['ProductUniqueID']);
               if ($product[$i]['ProductStock'] < $orders[$i]['OrderProductQuantity']) {
                  $stock[] = [
                     'ProductUniqueID' => $orders[$i]['ProductUniqueID'],
                     'ProductName'     => $product[$i]['ProductName']
                  ]; 
                  $this->response([
                     'status'    => false,
                     'message'   => 'Order gagal diproses, jumlah stok barang tidak mencukupi',
                     'item'      => $stock
                  ], REST_Controller::HTTP_BAD_REQUEST);
               } else {
                  $processOrderDate = date('Y-m-d H:i:s');
                  $this->db->set('OrderStatus', 2);
                  $this->db->set('OrderProcessDate', $processOrderDate);
                  $this->db->where('InvoiceNumber', $this->post('invoiceNumber'));
                  $this->db->update('orders');

                  $remainingStock[] = $product[$i]['ProductStock'] - $orders[$i]['OrderProductQuantity'];
                  $this->db->set('ProductStock', $remainingStock[$i]);
                  $this->db->where('ProductUniqueID', $orders[$i]['ProductUniqueID']);
                  $this->db->update('products');
               }
               $billTotal = $billTotal + $orders[$i]['OrderTotalPrice'];
               $detailOrder[] = [
                  'OrderNumber'     => $orders[$i]['OrderNumber'],
                  'ProductUniqueID' => $orders[$i]['ProductUniqueID'],
                  'ProductName'     => $product[$i]['ProductName'],
                  'Harga'           => $product[$i]['ProductPrice'],  
                  'Jumlah'          => $orders[$i]['OrderProductQuantity'],
                  'Bayar'           => $orders[$i]['OrderTotalPrice']
               ];
            }
            $this->response([
               'status'             => true,
               'message'            => 'Pesanan anda akan diproses',
               'InvoiceNumber'      => $orders[0]['InvoiceNumber'],
               'Invoice'            => $orders[0]['Invoice'],
               'CustomerUniqueID'   => $orders[0]['CustomerUniqueID'],
               'CustomerName'       => $customer['CustomerName'],
               'items'              => $detailOrder,
               'TotalBayar'         => $billTotal,
               'StatusPesanan'      => 'Proses',
               'TanggalProses'      => $processOrderDate
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

   public function sendOrder_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $orders = $this->db->get_where('orders', ['InvoiceNumber' => $this->post('invoiceNumber')])->result_array();
            $customer = $this->db->select('CustomerName')->get_where('customers', ['CustomerUniqueID' => $orders[0]['CustomerUniqueID']])->row_array();
            $sendOrderDate = date('Y-m-d H:i:s');
            $billTotal = 0;
            for ($i = 0; $i < count($orders); $i++) {
               $product[] = $this->product->getProductPrice($orders[$i]['ProductUniqueID']);
               $billTotal = $billTotal + $orders[$i]['OrderTotalPrice'];
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
            $this->db->set('OrderSendDate', $sendOrderDate);
            $this->db->where('InvoiceNumber', $this->post('invoiceNumber'));
            $this->db->update('orders');
            $this->response([
               'status'             => true,
               'message'            => 'Pesanan anda telah dikirim',
               'InvoiceNumber'      => $orders[0]['InvoiceNumber'],
               'Invoice'            => $orders[0]['Invoice'],
               'CustomerUniqueID'   => $orders[0]['CustomerUniqueID'],
               'CustomerName'       => $customer['CustomerName'],
               'items'              => $detailOrder,
               'TotalBayar'         => $billTotal,
               'StatusPesanan'      => 'Kirim',
               'TanggalProses'      => $sendOrderDate
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

   public function completedOrder_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');
      if ($token) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $orders = $this->db->get_where('orders', ['InvoiceNumber' => $this->post('invoiceNumber')])->result_array();
            $customer = $this->db->select('CustomerName')->get_where('customers', ['CustomerUniqueID' => $orders[0]['CustomerUniqueID']])->row_array();
            $completeOrderDate = date('Y-m-d H:i:s');
            $billTotal = 0;
            for ($i = 0; $i < count($orders); $i++) {
               $product[] = $this->product->getProductPrice($orders[$i]['ProductUniqueID']);
               $billTotal = $billTotal + $orders[$i]['OrderTotalPrice'];
               $detailOrder[] = [
                  'OrderNumber'     => $orders[$i]['OrderNumber'],
                  'ProductUniqueID' => $orders[$i]['ProductUniqueID'],
                  'ProductName'     => $product[$i]['ProductName'],
                  'Harga'           => $product[$i]['ProductPrice'],  
                  'Jumlah'          => $orders[$i]['OrderProductQuantity'],
                  'Bayar'           => $orders[$i]['OrderTotalPrice']
               ];
            }

            $this->db->set('OrderStatus', 4);
            $this->db->set('OrderCompletedDate', $completeOrderDate);
            $this->db->where('InvoiceNumber', $this->post('invoiceNumber'));
            $this->db->update('orders');

            $this->response([
               'status'             => true,
               'message'            => 'Pesanan anda telah selesai',
               'InvoiceNumber'      => $orders[0]['InvoiceNumber'],
               'Invoice'            => $orders[0]['Invoice'],
               'CustomerUniqueID'   => $orders[0]['CustomerUniqueID'],
               'CustomerName'       => $customer['CustomerName'],
               'items'              => $detailOrder,
               'TotalBayar'         => $billTotal,
               'StatusPesanan'      => 'Selesai',
               'TanggalProses'      => $completeOrderDate
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

   public function createOrderByCart_post()
   {
      date_default_timezone_set('Asia/Jakarta');
      $token = $this->post('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $uniqueID = generateOrderNumber();
            $cekOrderNumber = $this->db->get_where('orders', ['OrderNumber' => $uniqueID])->num_rows();
            if ($cekOrderNumber > 0) {
               $uniqueID = generateOrderNumber();
            }
            $cart = $this->db->get_where('carts', ['CartNumber' => $this->post('cartNumber')])->row_array();
            
            $tglPesan = date('Y-m-d H:i:s');

            $input = [
               'OrderNumber'           => $uniqueID,
               'CustomerUniqueID'      => $cart['CustomerUniqueID'],
               'ProductUniqueID'       => $cart['ProductUniqueID'],
               'OrderProductQuantity'  => $cart['CartProductQuantity'],
               'OrderTotalPrice'       => $cart['CartPrice'],
               'OrderStatus'           => 1,
               'OrderDate'             => $tglPesan,
            ];
            $order = $this->order->createOrder($input);
            $this->db->delete('carts', ['CartNumber' => $this->post('cartNumber')]);
            
            if ($order > 0) {
               $this->response([
                  'status'    => true,
                  'data'      => [
                     'OrderNumber'        => $uniqueID,
                     'CustomerUniqueId'   => $cart['CustomerUniqueID'],
                     'ProductUniqueID'    => $cart['ProductUniqueID'],
                     'Jumlah'             => $cart['CartProductQuantity'],
                     'Total Bayar'        => $cart['CartPrice'],
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
               ], REST_Controller::HTTP_OK);
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

   public function getProductLikesByCustomer_get()
   {
      $token = $this->get('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $productLikes = $this->order->getProductLikesByCustomer($this->get('customerUniqueID'));
            $customer = $this->db->select('CustomerUniqueID, CustomerName')->get_where('customers', ['CustomerUniqueID' => $this->get('customerUniqueID')])->row_array();
            // dd($productLikes);
            if($productLikes) {
               for ($i = 0; $i < count($productLikes); $i++) {
                  $detailProduct[] = [
                     'ProductName'        => $productLikes[$i]['ProductName'],
                     'CompanyName'        => $productLikes[$i]['CompanyName'],
                     'ProductImage'       => $productLikes[$i]['ProductImage'],
                     'ProductThumbnail'   => $productLikes[$i]['ProductThumbnail'],
                     'ProductDesc'        => $productLikes[$i]['ProductDesc'],
                     'ProductWeight'      => $productLikes[$i]['ProductWeight'],
                     'ProductStock'       => $productLikes[$i]['ProductStock'],
                     'ProductPrice'       => $productLikes[$i]['ProductPrice'],
                     'StatusPromo'        => $productLikes[$i]['ProductStatusPromo'],
                     'Promo'              => $productLikes[$i]['ProductPromo'],
                     'ProductRating'      => $productLikes[$i]['Rating'],
                  ];
               }
               $this->response([
                  'status'             => true,
                  'CustomerUniqueID'   => $customer['CustomerUniqueID'],
                  'CustomerName'       => $customer['CustomerName'],
                  'data'               => $detailProduct
               ], REST_Controller::HTTP_OK);
            } else {
               $this->response([
                  'status'             => true,
                  'CustomerUniqueID'   => $customer['CustomerUniqueID'],
                  'CustomerName'       => $customer['CustomerName'],
                  'message'            => 'Anda belum menyukai produk manapun',
                  'data'               => $productLikes
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