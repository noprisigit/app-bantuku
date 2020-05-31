<?php

class Invoice_m extends CI_Model {
   public function getDetailInvoice($invoiceNumber) {
      $this->db->select('customers.CustomerName, customers.CustomerAddress1, customers.CustomerPhone, customers.CustomerEmail, products.ProductName, products.ProductPrice, products.ProductWeight, orders.OrderNumber, orders.InvoiceNumber, orders.Invoice, orders.OrderProductQuantity, orders.OrderTotalPrice, orders.OrderDate');
      $this->db->from('orders');
      $this->db->join('products', 'products.ProductUniqueID = orders.ProductUniqueID');
      $this->db->join('customers', 'customers.CustomerUniqueID = orders.CustomerUniqueID');
      $this->db->where('orders.InvoiceNumber', $invoiceNumber);
      return $this->db->get()->result_array();
   }
}