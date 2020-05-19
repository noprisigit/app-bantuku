<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('User_m', 'user');

        if (!$this->session->userdata('AdminName'))
            redirect('auth');
    }

    public function index()
    {
        $data['main_title'] = "Home";
        $data['title'] = "Management Users";

        $data['js'] = [
            'assets/dist/js/user.js'
        ];

        $this->load->view('template/header', $data);
        $this->load->view('user/index');
        $this->load->view('template/footer', $data);
    }

    public function user_save()
    {
        $data = [
            'AdminName' => $this->input->post('name'),
            'AdminUsername' => $this->input->post('username'),
            'AdminPassword' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'AccessID'  => $this->input->post('status_akses'),
            'AdminStatusAccount' => 0,
            'date_created'  => date('Y-m-d H:i:s')
        ];

        $this->db->insert('admins', $data);
    }

    public function password_change()
    {
        $this->db->set('AdminPassword', password_hash($this->input->post('password_baru'), PASSWORD_DEFAULT));
        $this->db->where('AdminID', $this->input->post('admin_id'));
        $this->db->update('admins');
    }

    public function activate_account()
    {
        $this->db->set('AdminStatusAccount', 1);
        $this->db->where('AdminID', $this->input->post('AdminID'));
        $this->db->update('admins');
    }

    public function nonactivate_account()
    {
        $this->db->set('AdminStatusAccount', 0);
        $this->db->where('AdminID', $this->input->post('AdminID'));
        $this->db->update('admins');
    }

    public function show_list_users()
    {
        $list = $this->user->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $btn_activate_account = '<button class="btn btn-success btn-activate-account" data-toggle="tooltip" data-placement="top" title="Aktifkan Akun" data-id="'.$field->AdminID.'"><i class="fas fa-power-off"></i></button>';
            $btn_nonactivate_account = '<button class="btn btn-danger btn-nonactivate-account" data-toggle="tooltip" data-placement="top" title="Matikan Akun" data-id="'.$field->AdminID.'"><i class="fas fa-ban"></i></button>';
            $btn_change_password = '<button class="btn btn-info btn-change-password" data-toggle="tooltip" data-placement="top" title="Ubah Password" data-id="'.$field->AdminID.'"><i class="fas fa-lock"></i></button>';
            
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->AdminName;
            $row[] = $field->AdminUsername;
            $row[] = $field->AccessName;
            if ($field->AdminStatusAccount == 1) {
                $row[] = '<span class="badge badge-success">Aktif</span>';
            } else {
                $row[] = '<span class="badge badge-danger">Tidak Aktif</span>';
            }

            if ($field->AdminStatusAccount == 0) {
                $row[] = '<span class="badge badge-danger">Akun Belum Aktif</span>';
            } elseif ($field->AdminStatusAccount == 1 && $field->login_time == null) {
                $row[] = '<span class="badge badge-danger">Belum Pernah Login</span>';;
            } else {
                $row[] = $field->login_time;
            }

            if ($field->AdminStatusAccount == 0) {
                $row[] = $btn_activate_account . "&nbsp" . $btn_change_password;
            } else {
                $row[] = $btn_nonactivate_account . "&nbsp" . $btn_change_password;
            }

            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user->count_all(),
            "recordsFiltered" => $this->user->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}