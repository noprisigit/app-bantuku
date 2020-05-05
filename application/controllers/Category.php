<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_m', 'category');
    }

    public function index()
    {
        $data['main_title'] = "Category";
        $data['title'] = "Category";

        $this->load->view('template/header', $data);
        $this->load->view('category/index');
        $this->load->view('template/footer');
    }

    public function enable_category()
    {
        $this->db->set('CategoryStatus', 1);
        $this->db->where('CategoryID', $this->input->post('category_id'));
        $this->db->update('categories');
    }

    public function disable_category()
    {
        $this->db->set('CategoryStatus', 0);
        $this->db->where('CategoryID', $this->input->post('category_id'));
        $this->db->update('categories');
    }

    public function delete_category()
    {
        $this->db->delete('categories', ['CategoryID' => $this->input->post('category_id')]);
    }

    public function show_list_category()
    {
        $categories = $this->category->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($categories as $field) {
            $button1 = '<a class="btn btn-sm btn-danger btn-disable-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i> Disable</a>'.'&nbsp;<a class="btn btn-sm btn-info" href="category/edit-category/'.$field->CategoryID.'"><i class="fas fa-pencil-alt"></i> Edit</a>'.'&nbsp;<a class="btn btn-sm btn-danger btn-delete-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';

            $button2 = '<a class="btn btn-sm btn-primary btn-enable-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i> Enable</a>'.'&nbsp;<a class="btn btn-sm btn-info" href="category/edit-category/'.$field->CategoryID.'"><i class="fas fa-pencil-alt"></i> Edit</a>'.'&nbsp;<a class="btn btn-sm btn-danger btn-delete-category" data-id="'.$field->CategoryID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';
            
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->CategoryName;
            $row[] = $field->CategoryDescription;
            $row[] = $field->CategoryIcon;
            if ($field->CategoryStatus == 1) {
                $row[] = '<p class="text-success">Active</p>';
            } else {
                $row[] = '<p class="text-danger">Not Active</p>';
            }
            if ($field->CategoryStatus == 1) {
                $row[] = $button1;
            } else {
                $row[] = $button2;
            }
 
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->category->count_all(),
            "recordsFiltered" => $this->category->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
}