<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_m extends CI_Model {
   public function getAllTransactionsCustomer($customerUniqueID) {
      $query = $this->db->query('SELECT orders.OrderNumber, orders.Invoice, orders.InvoiceNumber, orders.ProductUniqueID, products.ProductName, products.ProductImage, orders.OrderProductQuantity, orders.OrderTotalPrice, orders.OrderStatus, orders.OrderDate, orders.OrderProcessDate, orders.OrderSendDate, orders.OrderCompletedDate, orders.Rating FROM products INNER JOIN orders ON orders.ProductUniqueID = products.ProductUniqueID WHERE orders.CustomerUniqueID = "'.$customerUniqueID.'" ORDER BY orders.OrderDatE DESC');
      return $query->result_array();
   }
}