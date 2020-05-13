<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_m extends CI_Model {
   public function getProductsByCategory($categoryID)
   {
      $this->db->where('CategoryID', $categoryID);
      $this->db->where('ProductStatus', 1);
      return $this->db->get('products')->result();
   }
}