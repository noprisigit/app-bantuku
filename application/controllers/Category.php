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

    public function enable_category($id)
    {
        $update = $this->category->enableCategory($id);

        if ($update > 0) {
            echo "berhasil diubah";
            redirect('category');
        } else {
            echo $this->db->error();
        }
    }

    public function disable_category($id)
    {
        $update = $this->category->disableCategory($id);

        if ($update > 0) {
            echo "berhasil diubah";
            redirect('category');
        } else {
            echo $this->db->error();
        }
    }

    public function show_list_category()
    {
        $categories = $this->category->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($categories as $field) {
            $button1 = '<a class="btn btn-sm btn-danger" href="category/disable-category/'.$field->CategoryID.'"><i class="fas fa-power-off"></i> Disable</a>' .
            '&nbsp;<a class="btn btn-sm btn-info" href="category/edit-category/'.$field->CategoryID.'"><i class="fas fa-pencil-alt"></i> Edit</a>' . 
            '&nbsp;<a class="btn btn-sm btn-danger" href="category/delete-category/'.$field->CategoryID.'" ><i class="fas fa-trash-alt"></i> Delete</a>';

            $button2 = '<a class="btn btn-sm btn-primary" href="category/enable-category/'.$field->CategoryID.'"><i class="fas fa-power-off"></i> Enable</a>' .
            '&nbsp;<a class="btn btn-sm btn-info" href="category/edit-category/'.$field->CategoryID.'"><i class="fas fa-pencil-alt"></i> Edit</a>' . 
            '&nbsp;<a class="btn btn-sm btn-danger" href="category/delete-category/'.$field->CategoryID.'" ><i class="fas fa-trash-alt"></i> Delete</a>';
           
            $url = "<?= base_url('category/edit-category/') ?>" . $field->CategoryID;
            
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