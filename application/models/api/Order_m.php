<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_m extends CI_Model {
   public function createOrder($data) {
      $this->db->insert('orders', $data);
      return $this->db->affected_rows();
   }
}