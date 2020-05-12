<?php

class Auth_m extends CI_Model {
    public function login($email)
    {
        $this->db->from('customers');
        $this->db->where('customers.CustomerEmail', $email);
        return $this->db->get()->row_array();
    }

    public function registration($data)
    {
        $this->db->insert('customers', $data);

        return $this->db->affected_rows();
    }

    public function validateToken($token)
    {
        $this->db->select('CustomerLoginToken');
        return $this->db->get_where('customers', ['CustomerLoginToken' => $token])->result();
    }
}