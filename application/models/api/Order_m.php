<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_m extends CI_Model {
   public function createOrder($data) {
      $this->db->insert('orders', $data);
      return $this->db->affected_rows();
   }

   public function getProductLikesByCustomer($customerUniqueID)
   {
      $query = $this->db->query("SELECT partners.CompanyName, categories.CategoryID, categories.CategoryName, products.PartnerID, products.ProductUniqueID, products.ProductName, products.ProductPrice, products.ProductStock, products.ProductWeight, products.ProductDesc, products.ProductImage, products.ProductThumbnail, products.ProductStatus, products.ProductPromo, products.ProductStatusPromo, products.ProductPromoDate, products.ProductPromoDateEnd, IFNULL(ROUND(AVG(orders.Rating), 0), 0) AS Rating FROM products LEFT JOIN categories ON products.CategoryID = categories.CategoryID LEFT JOIN orders ON products.ProductUniqueID = orders.ProductUniqueID LEFT JOIN customerslikesproducts ON products.ProductUniqueID = customerslikesproducts.ProductUniqueID LEFT JOIN partners ON partners.PartnerID = products.PartnerID WHERE customerslikesproducts.CustomerUniqueID = '".$customerUniqueID."' GROUP BY products.ProductUniqueID");
      return $query->result_array();
   }
}