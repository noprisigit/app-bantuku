<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Auth extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Auth_m', 'auth');
    }

    public function login_post()
    {
        $email = $this->post('email');
        $password = $this->post('password');

        if ($email === null || $password === null) {
            $this->response([
                'status'    => false,
                'message'   => 'please fill all the field'
            ]);
        } else {
            $customer = $this->auth->login($email);

            if ($customer) {
                if (password_verify($password, $customer['CustomerPassword'])) {
                    if ($customer['CustomerVerifiedEmail'] == 1) {
                        $loginToken = generateTokenLogin();

                        $this->db->set('CustomerLoginToken', $loginToken);
                        $this->db->where('CustomerUniqueID', $customer['CustomerUniqueID']);
                        $this->db->update('customers');

                        $this->response([
                            'status' => false,
                            'message' => 'login has been successfully',
                            'customerUniqueID'  => $customer['CustomerUniqueID'],
                            'token' => $loginToken
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'this email has not been verified.'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Password is wrong'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Email not found.'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function registration_post()
    {
        $full_name = $this->post('full_name');
        $email = $this->post('email');
        $phone = $this->post('phone');
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $address = $this->post('address');

        if ($full_name === null || $email === null || $phone === null || $password === null || $confirm_password === null || $address === null) {
            $this->response([
                'status'    => false,
                'message'   => 'please fill all the field'
            ]);
        } else {
            $query = $this->db->get_where('customers', ['CustomerEmail' => $email]);
            if ($query->num_rows() > 0) {
                $this->response([
                    'status' => false,
                    'message' => 'this email has been existed'
                ], REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $uniqueID = random_strings(5) . date('YmdHis');
                $generateCode = generate_code(6);
                $token = generateTokenLogin();

                $data = [
                    'CustomerUniqueID'          => $uniqueID,
                    'CustomerName'              => $full_name,
                    'CustomerEmail'             => $email,
                    'CustomerPhone'             => $phone,
                    'CustomerPassword'          => password_hash($password, PASSWORD_DEFAULT),
                    'CustomerAddress1'          => $address,
                    'CustomerVerifiedEmail'     => 0,
                    'CustomerVerificationCode'  => $generateCode,
                    'CustomerLoginToken'        => $token
                ];
    
                // $emailToken = base64_encode(random_bytes(64));

                // $access = [
                //     'CustomerUniqueID'      => $uniqueID,
                //     'CustomerEmailToken'    => $emailToken
                // ];
                // $this->auth->registration_token($access);

                // $this->_sendEmail($generateCode, 'verify');
    
                if ($this->auth->registration($data) > 0) {
                    $this->response([
                        'status' => parent::HTTP_OK,
                        'message' => 'customer account has been created',
                        'data' => $data
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'failed to create new account!'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        }
    }

    public function logout_post()
    {
        $customerUniqueID = $this->post('customerUniqueID');
        $this->db->set('CustomerLoginToken', NULL);
        $this->db->where('CustomerUniqueID', $customerUniqueID);
        $this->db->update('customers');

        $this->response([
            'status' => true,
            'message' => 'Logout berhasil',
        ], REST_Controller::HTTP_OK);
    }

    private function _sendEmail($value, $type) {
        $config = [
            'protocol'      => 'smtp',
            'smtp_host'     => 'ssl://smtp.googlemail.com',
            'smtp_user'     => 'neatinw@gmail.com',
            'smtp_pass'     => '19111998',
            'smtp_port'     => 465,
            'mailtype'      => 'html',
            'charset'       => 'utf-8',
            'newline'       => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->from('neatinw@gmail.com', 'bantuku');
        $this->email->to($this->post('email'));

        if ($type == 'verify') {
            $this->email->subject('Account Verication Code');
            $this->email->message('Silahkan masukkan kode berikut ini: ' . $value);
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }
    
    public function verifiedEmail_post()
    {
        $token = $this->post('token');
        if (isset($token)) {
            $customerToken = $this->auth->validateToken($token);
            if ($customerToken) {
                $customer = $this->db->select('CustomerVerificationCode')->get_where('customers', ['CustomerUniqueID' => $this->post('customerUniqueID')])->row_array();
                if ($this->post('verificationCode') == $customer['CustomerVerificationCode']) {
                    $this->db->set('CustomerVerifiedEmail', 1);
                    $this->db->where('customerUniqueID', $this->post('customerUniqueID'));
                    $this->db->update('customers');

                    $this->response([
                        'status'    => false,
                        'CustomerUniqueID'  => $this->post('customerUniqueID'),
                        'message'   => 'Email berhasil diverifikasi'
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status'    => false,
                        'message'   => 'Kode Verifikasi Salah'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Unauthorized token'
                 ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Missing token'
             ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}