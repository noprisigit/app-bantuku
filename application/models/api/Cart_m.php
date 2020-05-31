<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_m extends CI_Model {
   public function addCart($data)
   {
      $this->db->insert('carts', $data);
      return $this->db->affected_rows();
   }

   public function getCartByCustomer($id)
   {
      return $this->db->get_where('carts', ['CustomerUniqueID' => $id])->result_array();
   }

   public function getProductFromCart($id)
   {
      $this->db->select('ProductName, CompanyName, ProductImage, ProductThumbnail, ProductStatusPromo, ProductPromo');
      $this->db->from('products');
      $this->db->join('partners', 'partners.PartnerID = products.PartnerID');
      $this->db->where('ProductUniqueID', $id);
      return $this->db->get()->row_array();
   }
}