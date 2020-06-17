<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use GuzzleHttp\Client;

class Transaction_m extends CI_Model {
   var $table = 'orders'; //nama tabel dari database
   var $column_order = array(null, 'InvoiceNumber', 'OrderNumber', 'ProductName', 'CompanyName', 'CustomerName', 'OrderProductQuantity', 'OrderTotalPrice', 'OrderStatus', 'OrderDate'); //field yang ada di table user
   var $column_search = array('InvoiceNumber, OrderNumber', 'ProductName', 'CompanyName', 'CustomerName', 'OrderProductQuantity', 'OrderTotalPrice', 'OrderStatus', 'OrderDate'); //field yang diizin untuk pencarian 
   var $order = array('OrderID' => 'asc'); // default order 

   public function __construct()
   {
      parent::__construct();
      $this->load->database();
   }

   private function _get_datatables_query()
   {
      $this->db->select('InvoiceNumber, Invoice, OrderNumber, ProductName, CompanyName, CustomerName, OrderProductQuantity, OrderTotalPrice, OrderStatus, OrderDate');
      $this->db->from($this->table);
      $this->db->join('products', 'products.ProductUniqueId = orders.ProductUniqueID');
      $this->db->join('partners', 'partners.PartnerID = products.PartnerID');
      $this->db->join('customers', 'customers.CustomerUniqueID = orders.CustomerUniqueID');

      $i = 0;
   
      foreach ($this->column_search as $item) // looping awal
      {
         if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
         {
               
               if($i===0) // looping awal
               {
                  $this->db->group_start(); 
                  $this->db->like($item, $_POST['search']['value']);
               }
               else
               {
                  $this->db->or_like($item, $_POST['search']['value']);
               }

               if(count($this->column_search) - 1 == $i) 
                  $this->db->group_end(); 
         }
         $i++;
      }
      
      if(isset($_POST['order'])) 
      {
         $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      } 
      else if(isset($this->order))
      {
         $order = $this->order;
         $this->db->order_by(key($order), $order[key($order)]);
      }
   }

   function get_datatables()
   {
      $this->_get_datatables_query();
      if($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
      return $query->result();
   }

   function count_filtered()
   {
      $this->_get_datatables_query();
      $query = $this->db->get();
      return $query->num_rows();
   }

   public function count_all()
   {
      $this->db->from($this->table);
      return $this->db->count_all_results();
   }

   public function getOrders($invoiceNumber) {
      $this->db->select('InvoiceNumber, Invoice, OrderNumber, CustomerUniqueID, ProductUniqueID, OrderProductQuantity, OrderTotalPrice');
      $this->db->from('orders');
      $this->db->where('InvoiceNumber', $invoiceNumber);
      return $this->db->get()->result_array();
   }

   public function cekStatusPembayaran() {
      $client = new Client();

      $response = $client->request('POST', 'https://dev.faspay.co.id/cvr/100004/10', [
         'form_params' => [
            'request'   => "Pengecekan Status Pembayaran",
            'trx_id'    => "3308130200000182",
            'merchant_id'  => "33081",
            "bill_no"      => "665882170445",
            "signature"    => "1896ac5079d9dd89f70dda52c44be482da6566a9"
         ]
      ]);

      $result = json_decode($response->getBody()->getContents());
      // return $result;
   }

   public function getDetailOrder($orderNumber) {
      $this->db->select('orders.InvoiceNumber, orders.Invoice, customers.CustomerName, partners.CompanyName, products.PartnerID, products.ProductName, orders.OrderProductQuantity, orders.OrderTotalPrice, orders.OrderStatus, orders.OrderDate, orders_address.ShippingAddress');
      $this->db->from('orders');
      $this->db->join('customers', 'orders.CustomerUniqueID = customers.CustomerUniqueID');
      $this->db->join('products', 'orders.ProductUniqueID = products.ProductUniqueID');
      $this->db->join('partners', 'partners.PartnerID = products.PartnerID');
      $this->db->join('orders_address', 'orders.InvoiceNumber = orders_address.InvoiceNumber');
      $this->db->where('orders.OrderNumber', $orderNumber);
      return $this->db->get()->row_array();
   }

   public function getTransactionsData() {
      $this->db->from('orders');
      $this->db->join('orders_address', 'orders.InvoiceNumber = orders_address.InvoiceNumber');
      $this->db->join('transactions', 'orders.InvoiceNumber = transactions.InvoiceNumber');
      $this->db->where('orders.OrderStatus',1);
      return $this->db->get()->result_array();
   }

   public function getDetailOrderByInvoice($invoice) {
      $this->db->select('orders.InvoiceNumber, orders.Invoice, orders.CustomerUniqueID, customers.CustomerName, customers.CustomerPhone, customers.CustomerEmail, partners.CompanyName, partners.PartnerName, partners.Phone, partners.Address, partners.District, partners.Province, partners.PostalCode, products.PartnerID, products.ProductName, products.ProductPrice, orders.OrderProductQuantity, orders.OrderTotalPrice, orders.OrderNote, orders.OrderStatus, orders.OrderDate, orders_address.ShippingAddress, orders_address.NoteAddress');
      $this->db->from('orders');
      $this->db->join('customers', 'orders.CustomerUniqueID = customers.CustomerUniqueID');
      $this->db->join('products', 'orders.ProductUniqueID = products.ProductUniqueID');
      $this->db->join('partners', 'partners.PartnerID = products.PartnerID');
      $this->db->join('orders_address', 'orders.InvoiceNumber = orders_address.InvoiceNumber');
      $this->db->where('orders.InvoiceNumber', $invoice);
      return $this->db->get()->result_array();
   }
}