<?php

class Auth_m extends CI_Model {
    public function login($email)
    {
        $this->db->from('customers');
        $this->db->join('customers_access', 'customers.CustomerUniqueID = customers_access.CustomerUniqueID');
        $this->db->where('customers.CustomerEmail', $email);
        return $this->db->get()->row_array();
    }

    public function registration($data)
    {
        $this->db->insert('customers', $data);

        return $this->db->affected_rows();
    }

    public function registration_token($data)
    {
        $this->db->insert('customers_access', $data);
        return $this->db->affected_rows();
    }
}