<?php

class Invoice_m extends CI_Model {
   public function getDetailInvoice($invoiceNumber) {
      $this->db->select('customers.CustomerUniqueID, customers.CustomerName, customers.CustomerAddress1, customers.CustomerPhone, customers.CustomerEmail, products.ProductName, products.ProductPrice, products.ProductWeight, partners.CompanyName, partners.PartnerName, partners.Address, partners.Province, partners.District, partners.PostalCode, partners.Phone, partners.Email, partners.Latitude, partners.Longitude, orders.OrderNumber, orders.InvoiceNumber, orders.Invoice, orders.OrderProductQuantity, orders.OrderTotalPrice, orders.OrderDate, orders_address.ShippingAddress, orders_address.latitude, orders_address.longitude');
      $this->db->from('orders');
      $this->db->join('products', 'products.ProductUniqueID = orders.ProductUniqueID');
      $this->db->join('customers', 'customers.CustomerUniqueID = orders.CustomerUniqueID');
      $this->db->join('orders_address', 'orders.InvoiceNumber = orders_address.InvoiceNumber');
      $this->db->join('partners', 'partners.PartnerID = products.PartnerID');
      $this->db->where('orders.InvoiceNumber', $invoiceNumber);
      $this->db->where('orders.OrderStatus > 1');
      return $this->db->get()->result_array();
   }

   public function getInvoiceNumber($invoiceNumber) {
      $query = $this->db->query('SELECT InvoiceNumber FROM orders WHERE InvoiceNumber LIKE "%'.$invoiceNumber.'%" AND OrderStatus > 1');
      return $query->result_array();
   }  
}