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

    public function create_category()
    {
        $config['upload_path']="./assets/dist/img/categories"; //path folder file upload
        $config['allowed_types']='gif|jpg|png'; //type file yang boleh di upload
        $config['encrypt_name'] = TRUE; //enkripsi file name upload
         
        $this->load->library('upload',$config); //call library upload 
        if($this->upload->do_upload("category_icon")){ //upload file
            $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
 
            $name = $this->input->post('category_name'); //get judul image
            $description = $this->input->post('category_description');
            $image = $data['upload_data']['file_name']; //set file name ke variable image

            $input = [
                'CategoryName'          => $name,
                'CategoryDescription'   => $description,
                'CategoryIcon'          => $image,
                'CategoryStatus'        => 0
            ];

            $this->db->insert('categories', $input);
            // $result= $this->m_upload->simpan_upload($judul,$image); //kirim value ke model m_upload
            // echo json_decode($result);
        }
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
            $row[] = '<img src="assets/dist/img/categories/'.$field->CategoryIcon.'" width="128px" alt="Category Icon"/>';
            if ($field->CategoryStatus == 1) {
                $row[] = '<span class="badge badge-success">Active</span>';
            } else {
                $row[] = '<span class="badge badge-danger">Not Active</span>';
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