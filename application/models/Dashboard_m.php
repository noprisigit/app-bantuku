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

    public function countingAccount()
    {
        return $this->db->get('admins')->num_rows();
    }

    public function jumlahPendapatan()
    {
        $query = $this->db->query('SELECT SUM(OrderTotalPrice) as total_bayar FROM `orders` WHERE OrderStatus > 1');
        return $query->row();
    }

    public function productLikes()
    {
        $query = $this->db->query('SELECT products.ProductUniqueID, products.ProductName, partners.CompanyName, COUNT(*) as jumlah FROM customerslikesproducts INNER JOIN products ON products.ProductUniqueID = customerslikesproducts.ProductUniqueID INNER JOIN partners ON partners.PartnerID = products.PartnerID GROUP BY customerslikesproducts.ProductUniqueID ORDER BY jumlah DESC LIMIT 5');
        return $query->result_array();
    }

    public function shopLikes()
    {
        $query = $this->db->query('SELECT partners.CompanyName, partners.PartnerName, COUNT(*) as jumlah FROM customerslikesshop INNER JOIN partners ON partners.PartnerUniqueID = customerslikesshop.PartnerUniqueID GROUP BY customerslikesshop.PartnerUniqueID ORDER BY jumlah DESC LIMIT 5');
        return $query->result_array();
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

    public function countingPendapatanThisYear()
    {
        $month = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        for ($i = 0; $i < count($month); $i++) {
            $sql = "SELECT IFNULL(SUM(OrderTotalPrice), 0) as pendapatan FROM orders WHERE OrderStatus > 1 AND month(OrderDate) = " . ($i + 1) . " AND year(OrderDate) = year(CuRDATE())";
            $data[] = $this->db->query($sql)->result_array();
        }

        // return $this->db->query($sql)->result_array();
        return $data;
    }

    public function countingOrdersThisMonth()
    {
        $query = $this->db->query('SELECT COUNT(*) as jumlah FROM `orders` WHERE OrderStatus > 1 AND month(OrderDate) = month(CURDATE())');
        return $query->row();
    }

    // public function countingCustomersByCurrentMonth()
    // {
    //     $tanggal = [];
    //     for ($i = 0; $i < 30; $i++) {
    //         $tanggal[$i] = $i + 1; 
    //     }
    //     return $tanggal;
    // }
}