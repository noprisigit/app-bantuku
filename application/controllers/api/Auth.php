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
                        $this->response([
                            'status' => false,
                            'message' => 'login has been successfully',
                            'data' => [
                                'CustomerUniqueID'  => $customer['CustomerUniqueID'],
                                'CustomerName'  => $customer['CustomerName'],
                                'CustomerEmail' => $customer['CustomerEmail'],
                                'CustomerPhone' => $customer['CustomerPhone'],
                                'CustomerAddress'   => $customer['CustomerAddress1'],
                            ],
                            'token' => $customer['CustomerLoginToken']
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
        $gender = $this->post('gender');

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
                    'CustomerGender'            => $gender,
                    'CustomerEmail'             => $email,
                    'CustomerPhone'             => $phone,
                    'CustomerPassword'          => password_hash($password, PASSWORD_DEFAULT),
                    'CustomerAddress1'          => $address,
                    'CustomerVerifiedEmail'     => 0,
                    'CustomerVerificationCode'  => $generateCode,
                    'CustomerLoginToken'        => $token
                ];
    
                $this->_sendEmail($email, 'verify', $data);
                $message = "Terima kasih " .$full_name. ", kamu telah telah terdaftar pada aplikasi Bantuku. Silahkan masukkan kode berikut ini untuk memverifikasi akun anda. kode PIN anda : " . $generateCode . ". Harap jangan sebarkan kode PIN anda ke orang lain.";
                sendMessage($phone, $message);
    
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

        $this->response([
            'status' => true,
            'message' => 'Logout berhasil',
        ], REST_Controller::HTTP_OK);
    }

    private function _sendEmail($email, $type, $dataCustomer) {
        $config = [
            'protocol'      => 'smtp',
            'smtp_host'     => 'ssl://smtp.googlemail.com',
            'smtp_user'     => 'bantuku2020@gmail.com',
            'smtp_pass'     => '.BantukuBabelProv20',
            'smtp_port'     => 465,
            'mailtype'      => 'html',
            'charset'       => 'utf-8',
            'newline'       => "\r\n"
        ];

        $this->load->library('email', $config);

        $this->email->from('bantuku2020@gmail.com', 'Bantuku Support');
        $this->email->to($email);

        $data['customer'] = $dataCustomer;
    
        if ($type == 'verify') {
            $this->email->subject('Konfirmasi Pendaftaran');
            $this->email->message($this->load->view('template', $data, true));
        } else if ($type == 'verification code') {
            $this->email->subject('Kode Verifikasi');
            $this->email->message($this->load->view('template_verification', $data, true));
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    // public function sendMessage_get() {
    //     sendMessage();
    // }
    
    public function verifiedEmail_post()
    {
        $token = $this->post('token');
        if (isset($token)) {
            $customerToken = $this->auth->validateToken($token);
            if ($customerToken) {
                $customer = $this->db->select('CustomerUniqueID, CustomerName, CustomerEmail, CustomerPhone, CustomerAddress1, CustomerVerificationCode')->get_where('customers', ['CustomerEmail' => $this->post('customerEmail')])->row_array();
                if ($this->post('verificationCode') == $customer['CustomerVerificationCode']) {
                    $this->db->set('CustomerVerifiedEmail', 1);
                    $this->db->where('CustomerEmail', $this->post('customerEmail'));
                    $this->db->update('customers');

                    $this->response([
                        'status'    => true,
                        'data'      => [
                            'CustomerUniqueID'  => $customer['CustomerUniqueID'],
                            'CustomerName'      => $customer['CustomerName'],
                            'CustomerEmail'     => $customer['CustomerEmail'],
                            'CustomerPhone'     => $customer['CustomerPhone'],
                            'CustomerAddress'   => $customer['CustomerAddress1']
                        ],
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

    public function reqVerificationCode_post()
    {
        $token = $this->post('token');
        if (isset($token)) {
            $customerToken = $this->auth->validateToken($token);
            if ($customerToken) {
                $customerUniqueID = $this->post('customerUniqueID');
                $generateCode = generate_code(6);
                $this->db->set('CustomerVerificationCode', $generateCode);
                $this->db->where('CustomerUniqueID', $customerUniqueID);
                $this->db->update('customers');
                $customer = $this->db->select('CustomerUniqueID, CustomerName, CustomerGender, CustomerEmail, CustomerPhone, CustomerAddress1, CustomerVerificationCode')->get_where('customers', ['CustomerUniqueID' => $this->post('customerUniqueID')])->row_array();
                $this->_sendEmail($customer['CustomerEmail'], 'verification code', $customer);
                $arrName = explode(' ', $customer['CustomerName']);

                $message = "HAI " . strtoupper($arrName[0]) . ", KODE PIN ANDA ADALAH " . $generateCode . ". HARAP JANGAN MEMBERITAHUKAN PIN ANDA KE ORANG LAIN";
                sendMessage($customer['CustomerPhone'], $message);
                $this->response([
                    'status'    => true,
                    'data'      => [
                        'CustomerUniqueID'  => $customer['CustomerUniqueID'],
                        'CustomerName'      => $customer['CustomerName'],
                        'CustomerGender'    => $customer['CustomerGender'],
                        'CustomerEmail'     => $customer['CustomerEmail'],
                        'CustomerPhone'     => $customer['CustomerPhone'],
                        'CustomerAddress'   => $customer['CustomerAddress1'],
                        'CustomerVerificationCode' => $generateCode
                    ],
                    'message'   => 'Kode verifikasi berhasil dikirimkan'
                ], REST_Controller::HTTP_OK);
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

    public function verifiedCustomer_get() {
        $customerUniqueID = $this->get('customerUniqueID');
        if ($customerUniqueID) {
            $cekCustomer = $this->db->get_where('customers', ['CustomerUniqueID' => $customerUniqueID])->num_rows();
            if ($cekCustomer > 0) {
                $customer = $this->db->select('CustomerName, CustomerLoginToken')->get_where('customers', ['CustomerUniqueID' => $customerUniqueID])->row_array();
                $this->response([
                    'status'    => true,
                    'data'      => [
                        'CustomerUniqueID'  => $customerUniqueID,
                        'CustomerName'      => $customer['CustomerName'],
                    ],
                    'token'     => $customer['CustomerLoginToken']
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Data customer tidak ditemukan'
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Missing parameter'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}