<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_m extends CI_Model {
   public function getActiveCategory()
   {
      return $this->db->get_where('categories', ['CategoryStatus' => 1])->result();
   }
}