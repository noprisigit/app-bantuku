<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Role_m', 'role');

        if (!$this->session->userdata('AdminName'))
            redirect('auth');
    }

    public function index()
    {
        $data['main_title'] = 'Role';
        $data['title'] = 'Role Access';

        $this->load->view('template/header', $data);
        $this->load->view('role/index');
        $this->load->view('template/footer');
    }

    public function role_edit()
    {
        $this->db->set('AccessName', $this->input->post('name'));
        $this->db->where('AccessID', $this->input->post('id'));
        $this->db->update('admins_access');
    }

    public function show_list_role()
    {
        $list = $this->role->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $btn_edit = '<button class="btn btn-xs btn-success btn-edit-role" data-id="'.$field->AccessID.'" data-name="'.$field->AccessName.'"><i class="fas fa-pencil-alt"></i> Edit</button>';
            $btn_delete = '<button class="btn btn-xs btn-danger" data-id="'.$field->AccessID.'"><i class="fas fa-trash"></i> Delete</button>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->AccessName;
            $row[] = $btn_delete;

            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->role->count_all(),
            "recordsFiltered" => $this->role->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}