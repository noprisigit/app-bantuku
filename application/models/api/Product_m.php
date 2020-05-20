<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_m extends CI_Model {
   public function getProductsByCategory($categoryID)
   {
      $query = $this->db->query('SELECT categories.CategoryID, categories.CategoryName, products.PartnerID, products.ProductUniqueID, products.ProductName, products.ProductPrice, products.ProductStock, products.ProductWeight, products.ProductDesc, products.ProductImage, products.ProductThumbnail, products.ProductStatus, products.ProductPromo, products.ProductStatusPromo, products.ProductPromoDate, ROUND(AVG(orders.Rating), 0) AS Rating FROM products INNER JOIN categories ON products.CategoryID = categories.CategoryID INNER JOIN orders ON products.ProductUniqueID = orders.ProductUniqueID WHERE categories.CategoryID = '. $categoryID .' AND products.ProductStatus = 1 AND orders.OrderStatus > 1 GROUP BY orders.ProductUniqueID');
      return $query->result();
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