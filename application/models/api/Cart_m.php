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
      return $this->db->get_where('carts', ['CustomerUniqueID' => $id])->result();
   }
}