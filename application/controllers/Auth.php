<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->form_validation->set_rules('username', 'username', 'trim|required', [
            'required'  => 'Please fill your %s',
        ]);
        $this->form_validation->set_rules('password', 'password', 'trim|required', [
            'required'  => 'Please fill your %s'
        ]);

        if ( $this->form_validation->run() == false ) {
            $this->load->view('auth/login');
        } else {
            date_default_timezone_set('Asia/Jakarta');
            $username = htmlspecialchars($this->input->post('username'), TRUE);
            $password = htmlspecialchars($this->input->post('password'), TRUE);

            $data = $this->db->get_where('admins', ['AdminUsername' => $username])->row_array();

            if ( $data ) {
                if ( password_verify($password, $data['AdminPassword']) ) {
                    if ( $data['AdminStatusAccount'] == 1 ) {
                        $session = [
                            'AdminID'       => $data['AdminID'],
                            'AdminName'     => $data['AdminName'],
                            'AccessID'   => $data['AccessID']
                        ];

                        $this->session->set_userdata($session);

                        $this->db->set('login_time', date('Y-m-d H:i:s'));
                        $this->db->where('AdminUsername', $username);
                        $this->db->update('admins');

                        redirect('dashboard');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">
                            This account is not active.
                        </div>');
                        redirect('auth');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">
                        Check your password.
                    </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger text-center" role="alert">
                    Username not found.
                </div>');
                redirect('auth');
            }
        }
    }

    public function logout() {
        $data = [
            'AdminID',
            'AdminName',
            'AdminStatus',
        ];
        $this->session->unset_userdata($data);

        $this->session->set_flashdata('message', '<div class="alert alert-success text-center" role="alert">
            You have been logout.
        </div>');
        redirect('auth');
    }
}