<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_m extends CI_Model {
   public function getProductsByCategory($categoryID)
   {
      $query = $this->db->query('SELECT categories.CategoryID, categories.CategoryName, products.PartnerID, products.ProductUniqueID, products.ProductName, products.ProductPrice, products.ProductStock, products.ProductWeight, products.ProductDesc, products.ProductImage, products.ProductThumbnail, products.ProductStatus, products.ProductPromo, products.ProductStatusPromo, products.ProductPromoDate, IFNULL(ROUND(AVG(orders.Rating), 0), 0) AS Rating FROM products LEFT JOIN categories ON products.CategoryID = categories.CategoryID LEFT JOIN orders ON products.ProductUniqueID = orders.ProductUniqueID WHERE products.CategoryID = '. $categoryID .' AND products.ProductStatus = 1 GROUP BY products.ProductUniqueID');
      return $query->result();
   }

   public function getProductsByPromoToday()
   {
      $query = $this->db->query('SELECT categories.CategoryID, categories.CategoryName, products.PartnerID, products.ProductUniqueID, products.ProductName, products.ProductPrice, products.ProductStock, products.ProductWeight, products.ProductDesc, products.ProductImage, products.ProductThumbnail, products.ProductStatus, products.ProductPromo, products.ProductStatusPromo, products.ProductPromoDate, products.ProductPromoDateEnd, IFNULL(ROUND(AVG(orders.Rating), 0), 0) AS Rating FROM products LEFT JOIN categories ON products.CategoryID = categories.CategoryID LEFT JOIN orders ON products.ProductUniqueID = orders.ProductUniqueID WHERE products.ProductStatus = 1 AND products.ProductStatusPromo = 1 AND CURRENT_DATE BETWEEN products.ProductPromoDate AND products.ProductPromoDateEnd GROUP BY products.ProductUniqueID');
      return $query->result();
   }

   public function getProductPrice($id)
   {
      $this->db->select('ProductName, ProductPrice, ProductStock');
      $this->db->where('ProductUniqueID', $id);
      $this->db->where('ProductStatus', 1);
      return $this->db->get('products')->row_array();
   }

   public function getProductsByShop($id)
   {
      $this->db->where('PartnerID', $id);
      $this->db->where('ProductStatus', 1);
      return $this->db->get('products')->result();
   }
}