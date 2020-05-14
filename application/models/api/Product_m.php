<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_m extends CI_Model {
   public function getProductsByCategory($categoryID)
   {
      $this->db->where('CategoryID', $categoryID);
      $this->db->where('ProductStatus', 1);
      return $this->db->get('products')->result();
   }

   public function getProductsByPromoToday()
   {
      $query = $this->db->query('SELECT * FROM `products` WHERE ProductStatus = 1 and ProductStatusPromo = 1 and ProductPromoDate = CURRENT_DATE()');
      return $query->result();
   }

   public function getProductPrice($id)
   {
      $this->db->select('ProductName, ProductPrice');
      $this->db->where('ProductUniqueID', $id);
      $this->db->where('ProductStatus', 1);
      return $this->db->get('products')->result();
   }

   public function getProductsByShop($id)
   {
      $this->db->where('PartnerID', $id);
      $this->db->where('ProductStatus', 1);
      return $this->db->get('products')->result();
   }
}