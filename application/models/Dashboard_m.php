<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_m extends CI_Model {
    public function countingPartner()
    {
        return $this->db->get('partners')->num_rows();
    }

    public function countingCustomer()
    {
        return $this->db->get('customers')->num_rows();
    }

    public function countingCategories()
    {
        return $this->db->get('categories')->num_rows();
    }

    public function countingCustomersByCurrentYear()
    {
        $month = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        for ($i = 0; $i < count($month); $i++) {
            $sql = "Select COUNT(*) as jumlah from customers where month(CustomerRegistrationDate) = " . ($i + 1) . " and year(CustomerRegistrationDate) = year(curdate())";
            $data[] = $this->db->query($sql)->result_array();
        }
        // return $this->db->query($sql)->result_array();
        return $data;
    }
}