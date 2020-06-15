<?php
use Restserver\Libraries\REST_Controller;
use GuzzleHttp\Client;

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
      $this->load->model('api/Partner_m', 'partner');
      $this->load->model('Invoice_m', 'invoice');
   }

   public function createSignature_get() {
      $invoice = $this->get('invoice');
      $this->response([
         'signature' => sha1(md5("bot33081p@ssw0rd".$invoice))
      ], REST_Controller::HTTP_OK);
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
            $customer = $this->db->select('CustomerName, CustomerEmail, CustomerPhone, CustomerAddress1')->get_where('customers', ['CustomerUniqueID' => $this->post('customerUniqueID')])->row_array();
            
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
               $billItems[] = [
                  "product"      => $product[$i]['ProductName'],
                  "qty"          => $ordersData[$i]['jumlah'],
                  "amount"       => $product[$i]['ProductPrice'],
                  "payment_plan" => "01",
                  "merchant_id"  => "33081",
                  "tenor"        => "00"
               ];
               $billTotal = $billTotal + $amount[$i];
            }
            $emailInvoice = "INV/" . date('Ymd') . "/#" . "/" . $invoiceNumber;
            $this->_sendEmail($customer['CustomerEmail'], 'Pending', $emailInvoice ,$detailOrder);

            $user_id = "bot33081";
            $pass = "p@ssw0rd";
            $signature = sha1(md5($user_id.$pass.$invoiceNumber));

            $client = new Client();
            $response = $client->request('POST', 'https://dev.faspay.co.id/cvr/300011/10', [
               'json'   => [
                  "request"            => "Transmisi Info Detil Pembelian",
                  "merchant_id"        => "33081",
                  "merchant"           => "Bantuku",
                  "bill_no"            => $invoiceNumber,
                  "bill_reff"          => "123",
                  "bill_date"          => "2020-06-09 13:34:09",
                  "bill_expired"       => "2020-06-10 13:34:09",
                  "bill_desc"          => "Pembayaran #" . $invoiceNumber,
                  "bill_currency"      => "IDR",
                  "bill_gross"         => "0",
                  "bill_miscfee"       => "0",
                  "bill_total"         => $billTotal,
                  "cust_no"            => $this->post('customerUniqueID'),
                  "cust_name"          => $customer['CustomerName'],
                  "payment_channel"    => "302",
                  "pay_type"           => "1",
                  "bank_userid"        => "",
                  "msisdn"             => $customer['CustomerPhone'],
                  "email"              => $customer['CustomerEmail'],
                  "terminal"           => "10",
                  "billing_name"       => "0",
                  "billing_lastname"   => "0",
                  "billing_address"    => "jalan pintu air raya",
                  "billing_address_city"  => "Jakarta Pusat",
                  "billing_address_region"   => "DKI Jakarta",
                  "billing_address_state"    => "Indonesia",
                  "billing_address_poscode"  => "10710",
                  "billing_msisdn"           => "",
                  "billing_address_country_code"   => "ID",
                  "receiver_name_for_shipping"     => "Faspay Test",
                  "shipping_lastname"              => "",
                  "shipping_address"               => "jalan pintu air raya",
                  "shipping_address_city"          => "Jakarta Pusat",
                  "shipping_address_region"        => "DKI Jakarta",
                  "shipping_address_state"         => "Indonesia",
                  "shipping_address_poscode"       => "10710",
                  "shipping_msisdn"                => "",
                  "shipping_address_country_code"  => "ID",
                  "item"   => $billItems,
                  "reserve1"  => "",
                  "reserve2"  => "",
                  "signature" => $signature
               ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            $trx_id = $result['trx_id'];
            $bill_no = $result['bill_no'];
            $url = $result['redirect_url'];

            $this->db->insert('transactions', [
               'FaspayTransactionID'   => $trx_id,
               'InvoiceNumber'         => $bill_no,
               'PaymentUrl'            => $url
            ]);

            if ($this->post('latitude') == "") {
               $latitude = null;
            } else {
               $latitude = $this->post('latidude');
            }

            if ($this->post('longitude') == "") {
               $longitude = null;
            } else {
               $longitude = $this->post('longitude');
            }

            if ($this->post('note_address') == "") {
               $noteAddress = null;
            } else {
               $noteAddress = $this->post('note_address');
            }

            $this->db->insert('orders_address', [
               'InvoiceNumber'   => $invoiceNumber,
               'ShippingAddress' => $this->post('address'),
               'NoteAddress'     => $noteAddress, 
               'Latitude'        => $latitude,
               'Longitude'       => $longitude
            ]);

            if ($order > 0) {
               $this->response([
                  'status'             => true,
                  'message'            => 'Order baru berhasil ditambahkan',
                  'FaspayTrxID'        => $trx_id,
                  'InvoiceNumber'      => $invoiceNumber,
                  'Invoice'            => "INV/" . date('Ymd') . "/#" . "/" . $invoiceNumber,
                  'CustomerUniqueID'   => $this->post('customerUniqueID'),
                  'CustomerName'       => $customer['CustomerName'],
                  'items'              => $detailOrder,
                  'TotalBayar'         => $billTotal,
                  'StatusPesanan'      => 'Pending',
                  'TanggalPesan'       => $orderDate,
                  'AlamatPengiriman'   => $this->post('address'),
                  'Latitude'           => $latitude,
                  'Longitude'          => $longitude,
                  'RedirectUrl'        => $url
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
            $customer = $this->db->select('CustomerName, CustomerEmail')->get_where('customers', ['CustomerUniqueID' => $orders[0]['CustomerUniqueID']])->row_array();
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
            $this->_sendEmail($customer['CustomerEmail'], 'Proses', $orders[0]['Invoice'] ,$detailOrder);
            $this->_sendEmailDriver($orders[0]['InvoiceNumber']);
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
            $customer = $this->db->select('CustomerName, CustomerEmail')->get_where('customers', ['CustomerUniqueID' => $orders[0]['CustomerUniqueID']])->row_array();
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
            $this->_sendEmail($customer['CustomerEmail'], 'Kirim', $orders[0]['Invoice'] ,$detailOrder);
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
            $customer = $this->db->select('CustomerName, CustomerEmail')->get_where('customers', ['CustomerUniqueID' => $orders[0]['CustomerUniqueID']])->row_array();
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
            $this->_sendEmail($customer['CustomerEmail'], 'Selesai', $orders[0]['Invoice'] ,$detailOrder);
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

   public function getShopLikesByCustomer_get()
   {
      $token = $this->get('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);

         if ($customerToken) {
            $partners = $this->order->getShopLikesByCustomer($this->get('customerUniqueID'));
            $customer = $this->db->select('CustomerUniqueID, CustomerName')->get_where('customers', ['CustomerUniqueID' => $this->get('customerUniqueID')])->row_array();
            if ($partners) {
               $this->response([
                  'status'             => true,
                  'CustomerUniqueID'   => $customer['CustomerUniqueID'],
                  'CustomerName'       => $customer['CustomerName'],
                  'data'               => $partners
               ], REST_Controller::HTTP_OK);
            } else {
               $this->response([
                  'status'             => true,
                  'CustomerUniqueID'   => $customer['CustomerUniqueID'],
                  'CustomerName'       => $customer['CustomerName'],
                  'message'            => 'Anda belum menyukai toko manapun',
                  'data'               => $partners
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

   public function customerDislikeShop_delete()
   {
      $token = $this->delete('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $partner = $this->partner->getDetailShop($this->delete('partnerUniqueID'));
            $customer = $this->db->select('CustomerUniqueID, CustomerName')->get_where('customers', ['CustomerUniqueID' => $this->delete('customerUniqueID')])->row_array();

            $cekStatusLike = $this->db->get_where('customerslikesshop', ['CustomerUniqueID' => $this->delete('customerUniqueID'), 'PartnerUniqueID' => $this->delete('partnerUniqueID')])->num_rows();
            if ($cekStatusLike > 0) {
               $this->db->delete('customerslikesshop', ['CustomerUniqueID' => $this->delete('customerUniqueID'), 'PartnerUniqueID' => $this->delete('partnerUniqueID')]);

               $this->response([
                  'status'             => true,
                  'CustomerUniqueID'   => $customer['CustomerUniqueID'],
                  'CustomerName'       => $customer['CustomerName'],
                  'data'               => $partner,
                  'message'            => "Anda tidak menyukai toko ini"
               ], REST_Controller::HTTP_OK);
            } else {
               $this->response([
                  'status'    => true,
                  'message'   => 'Data Tidak Ditemukan'
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

   public function customerDislikeProduct_delete()
   {
      $token = $this->delete('token');
      $customerUniqueID = $this->delete('customerUniqueID');
      $productUniqueID = $this->delete('productUniqueID');

      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $product = $this->product->getDetailProduct($productUniqueID);
            $customer = $this->db->select('CustomerUniqueID, CustomerName')->get_where('customers', ['CustomerUniqueID' => $customerUniqueID])->row_array();
             
            $cekStatusLike = $this->db->get_where('customerslikesproducts', ['CustomerUniqueID' => $customerUniqueID, 'ProductUniqueID' => $productUniqueID])->num_rows();

            if ($cekStatusLike > 0) {
               $this->db->delete('customerslikesproducts', ['CustomerUniqueID' => $customerUniqueID, 'ProductUniqueID' => $productUniqueID]);

               $this->response([
                  'status'             => true,
                  'CustomerUniqueID'   => $customer['CustomerUniqueID'],
                  'CustomerName'       => $customer['CustomerName'],
                  'data'               => $product,
                  'message'            => "Anda tidak menyukai produk ini"
               ], REST_Controller::HTTP_OK);
            } else {
               $this->response([
                  'status'    => true,
                  'message'   => 'Data Tidak Ditemukan'
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

   public function deleteOrder_post()
   {
      $token = $this->post('token');
      if (isset($token)) {
         $customerToken = $this->auth->validateToken($token);
         if ($customerToken) {
            $orderNumber = $this->post('orderNumber');
            $cekInvoice = $this->db->get_where('orders', ['OrderNumber' => $orderNumber])->num_rows();
            $order = $this->db->select('Invoice')->get_where('orders', ['OrderNumber' => $orderNumber])->row_array();
            if ($cekInvoice > 0) {
               $this->db->delete('orders', ['OrderNumber' => $orderNumber]);

               $this->response([
                  'status'          => true,
                  'OrderNumber'     => $orderNumber,
                  // 'Invoice'         => $order['Invoice'],
                  'message'         => 'Pesanan berhasil dibatalkan'
               ], REST_Controller::HTTP_OK);
            } else {
               $this->response([
                  'status'    => false,
                  'message'   => 'Data tidak ditemukan'
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

   private function _sendEmail($email, $type, $invoice, $dataOrder) {
      $config= [
         'protocol'      => 'smtp',
         'smtp_host'     => 'ssl://smtp.googlemail.com',
         'smtp_user'     => 'bantuku2020@gmail.com',
         'smtp_pass'     => '.BantukuBabelProv20',
         'smtp_port'     => 465,
         'smtp_timeout'  => '5',
         'mailtype'      => 'html',
         'charset'       => 'utf-8',
         'newline'       => "\r\n"
      ];

      $this->load->library('email', $config);

      $this->email->from('bantuku2020@gmail.com', 'Bantuku Support');
      $this->email->to($email);


      if ($type == "Pending") {
         $subject = "Menunggu Pembayaran";
      } else if ($type == "Proses") {
         $subject = "Pembayaran Anda Diterima dan Pesanan Akan Diproses";
      } else if ($type == "Kirim") {
         $subject = "Pesanan Anda Telah Dikirim";
      } else if ($type == "Selesai") {
         $subject = "Pesanan Anda Telah Selesai";
      }

      $data['subject'] = $subject;
      $data['invoice'] = $invoice;
      $data['orders'] = $dataOrder;
      // dd($data['orders']);
      $this->email->subject($subject);
      $this->email->message($this->load->view('template_payment', $data, true));

      if ($this->email->send()) {
         return true;
     } else {
         echo $this->email->print_debugger();
         die;
     }
   }

   private function _sendEmailDriver($invoice) {
      $emailReceiver = "alfajrihulvi14@gmail.com";
      $subject = "Antar Pesanan";

      $config= [
         'protocol'      => 'smtp',
         'smtp_host'     => 'ssl://smtp.googlemail.com',
         'smtp_user'     => 'bantuku2020@gmail.com',
         'smtp_pass'     => '.BantukuBabelProv20',
         'smtp_port'     => 465,
         'smtp_timeout'  => '5',
         'mailtype'      => 'html',
         'charset'       => 'utf-8',
         'newline'       => "\r\n"
      ];

      $this->load->library('email', $config);

      $this->email->from('bantuku2020@gmail.com', 'Bantuku Support');
      $this->email->to($emailReceiver);

      // if ($type == "Pending") {
      //    $subject = "Menunggu Pembayaran";
      // } else if ($type == "Proses") {
      //    $subject = "Pembayaran Anda Diterima dan Pesanan Akan Diproses";
      // } else if ($type == "Kirim") {
      //    $subject = "Pesanan Anda Telah Dikirim";
      // } else if ($type == "Selesai") {
      //    $subject = "Pesanan Anda Telah Selesai";
      // }

      // $data['subject'] = $subject;
      $data['orders'] = $this->invoice->getDetailInvoice($invoice);
      // $data['orders'] = $dataOrder;
      // dd($data['orders']);
      $this->email->subject($subject);
      $this->email->message($this->load->view('template_driver', $data, true));

      if ($this->email->send()) {
         return true;
     } else {
         echo $this->email->print_debugger();
         die;
     }
   }
}