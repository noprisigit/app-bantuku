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
    
                $this->_sendEmail($generateCode, 'verify');
    
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

    private function _sendEmail($value, $type) {
        $config= [
            'protocol'      => 'smtp',
            'smtp_host'     => 'ssl://smtp.googlemail.com',
            'smtp_user'     => 'bantuku2020@gmail.com',
            'smtp_pass'     => '.BantukuBabelProv20',
            'smtp_port'     => 465,
            'smtp_timeout'  => '5',
            'mailtype'      => 'html',
            'charset'       => 'utf-8',
            'newline'       => "\r\n"
        ];

        $this->load->library('email', $config);

        $this->email->from('bantuku2020@gmail.com', 'Bantuku Support');
        $this->email->to($this->post('email'));
        $linkImage = "http://bantuku2020.babelprov.go.id/assets/dist/img/tick.png";
        
        if ($type == 'verify') {
            $this->email->subject('Konfirmasi Pendaftaran');
            $this->email->message('
                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
                </head>
                <body style="margin: 0; padding: 0; font-family: Cambria, Cochin, Georgia, Times, Times New Roman, serif;">
                <table align="center" cellpading="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                    <tr>
                        <td align="center" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF); padding: 30px 0 30px 0;">
                            <img src="'.$linkImage.'" alt="CheckList Icon" width="128">
                            <h1 style="color: #FFFFFF; margin-top: 1px; margin-bottom: 5px">Terima Kasih</h1>
                            <h4 style="color: #FFFFFF; margin: 10px 0 10px 0;">Anda Sudah Terdaftar pada Aplikasi Bantuku.</h4>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#FFFFFF" style="padding: 10px 20px 40px 20px;">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" bgcolor="#b4b8bf" colspan="2" style="padding: 20px 0 20px 0; font-size: 20px; color: #FFFFFF;">Detail Pelanggan</td>
                                </tr>
                                <tr>
                                    <td  style="padding: 5px 0 5px 5px;">Nama Customer</td>
                                    <td>: '.$this->post("full_name").'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0 5px 5px;">Jenis Kelamin</td>
                                    <td>: '.$this->post("gender").'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0 5px 5px;">Email</td>
                                    <td>: '.$this->post("email").'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0 5px 5px;">No. Telp</td>
                                    <td>: '.$this->post("phone").'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0 5px 5px;">Alamat</td>
                                    <td>: '.$this->post("address").'</td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="2" style="padding: 20px 0 5px 5px;">Silahkan Masukkan Kode Verifikasi Berikut Ini untuk Memverifikasi Akun Anda</td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="2" style="padding: 10px 0 5px 5px;">
                                        <span style="padding: 5px;background-color: #0072FF; font-size: 25px; color: #FFFFFF;">'.$value.'</span>  
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td bgcolor="#0072FF">Row 3</td>
                    </tr> -->
                </table>
                </body>
                </html>
            ');
        }

        if ($this->email->send()) {
            // echo "Email berhasil dikirim ke " . $this->post('email');
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    // private function _sendEmail($value, $type) {
    //     $config = [
    //         'protocol'      => 'smtp',
    //         'smtp_host'     => 'ssl://smtp.googlemail.com',
    //         'smtp_user'     => 'neatinw@gmail.com',
    //         'smtp_pass'     => '19111998',
    //         'smtp_port'     => 465,
    //         'mailtype'      => 'html',
    //         'charset'       => 'utf-8',
    //         'newline'       => "\r\n"
    //     ];

    //     $this->load->library('email', $config);
    //     $this->email->from('neatinw@gmail.com', 'bantuku');
    //     $this->email->to($this->post('email'));

    //     if ($type == 'verify') {
    //         $this->email->subject('Account Verication Code');
    //         $this->email->message('Silahkan masukkan kode berikut ini: ' . $value);
    //     }

    //     if ($this->email->send()) {
    //         return true;
    //     } else {
    //         echo $this->email->print_debugger();
    //         die;
    //     }
    // }
    
    public function verifiedEmail_post()
    {
        $token = $this->post('token');
        if (isset($token)) {
            $customerToken = $this->auth->validateToken($token);
            if ($customerToken) {
                $customer = $this->db->select('CustomerUniqueID, CustomerName, CustomerEmail, CustomerPhone, CustomerAddress1, CustomerVerificationCode')->get_where('customers', ['CustomerUniqueID' => $this->post('customerUniqueID')])->row_array();
                if ($this->post('verificationCode') == $customer['CustomerVerificationCode']) {
                    $this->db->set('CustomerVerifiedEmail', 1);
                    $this->db->where('customerUniqueID', $this->post('customerUniqueID'));
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
}