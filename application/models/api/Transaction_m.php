<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_m extends CI_Model {
   public function getAllTransactionsCustomer($customerUniqueID) {
      return $this->db->get_where('orders', ['CustomerUniqueID' => $customerUniqueID])->result_array();
   }
}