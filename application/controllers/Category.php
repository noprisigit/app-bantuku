<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_m', 'category');

        if (!$this->session->userdata('AdminName'))
            redirect('auth');
    }

    public function index()
    {
        $data['main_title'] = "Home";
        $data['title'] = "Kategori";

        $data['js'] = [
            'assets/dist/js/category.js'
        ];

        $this->load->view('template/header', $data);
        $this->load->view('category/index');
        $this->load->view('template/footer', $data);
    }

    public function create_category()
    {
        $config['upload_path']="./assets/dist/img/categories"; //path folder file upload
        $config['allowed_types']='gif|jpg|png'; //type file yang boleh di upload
        $config['encrypt_name'] = TRUE; //enkripsi file name upload
        $config['max_size'] = 5048;
         
        $this->load->library('upload',$config); //call library upload 
        if($this->upload->do_upload("category_icon")){ //upload file
            $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
            resizeImage($data['upload_data']['file_name'], 'categories');

            $name = $this->input->post('category_name'); //get judul image
            $description = $this->input->post('category_description');
            $image = $data['upload_data']['file_name']; //set file name ke variable image

            $input = [
                'CategoryName'          => $name,
                'CategoryDescription'   => $description,
                'CategoryIcon'          => $image,
                'CategoryThumbnail'     => $data['upload_data']['raw_name'] . '_thumb' . $data['upload_data']['file_ext'],
                'CategoryStatus'        => 1
            ];

            $this->db->insert('categories', $input);
        }
    }
    
    public function edit_category()
    {
        date_default_timezone_set('Asia/Jakarta');
        $category = $this->db->get_where('categories', ['CategoryID' => $this->input->post('category_id')])->row_array();

        $image = $_FILES['category_icon_edit']['name'];
        if ($image != "") {
            $config['upload_path']="./assets/dist/img/categories"; //path folder file upload
            $config['allowed_types']='gif|jpg|png'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload
            $config['max_size'] = 5048;

            $this->load->library('upload',$config); //call library upload 

            if ($this->upload->do_upload('category_icon_edit')) {
                $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
                resizeImage($data['upload_data']['file_name'], 'categories');

                $new_image = $data['upload_data']['file_name'];
                if ($new_image != $category['CategoryIcon']) {
                    unlink(FCPATH . "/assets/dist/img/categories/" . $category['CategoryIcon']);
                    unlink(FCPATH . "/assets/dist/img/categories/thumbnail/" . $category['CategoryThumbnail']);
                    $this->db->set('CategoryIcon', $new_image);
                    $this->db->set('CategoryThumbnail', $data['upload_data']['raw_name'] . '_thumb' . $data['upload_data']['file_ext']);
                }
            }
        } 
        $this->db->set('CategoryName', $this->input->post('category_name_edit'));
        $this->db->set('CategoryDescription', $this->input->post('category_description_edit'));
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('CategoryID', $this->input->post('category_id'));
        $this->db->update('categories');
        
    }

    public function enable_category()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->set('CategoryStatus', 1);
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('CategoryID', $this->input->post('category_id'));
        $this->db->update('categories');
    }

    public function disable_category()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->set('CategoryStatus', 0);
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('CategoryID', $this->input->post('category_id'));
        $this->db->update('categories');
    }

    public function delete_category()
    {
        $category = $this->db->get_where('categories', ['CategoryID' => $this->input->post('category_id')])->row_array();

        unlink(FCPATH . "/assets/dist/img/categories/" . $category['CategoryIcon']);
        unlink(FCPATH . "/assets/dist/img/categories/thumbnail/" . $category['CategoryThumbnail']);

        $this->db->delete('categories', ['CategoryID' => $this->input->post('category_id')]);
    }

    public function show_list_category()
    {
        $categories = $this->category->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($categories as $field) {
            $button1 = '<a class="btn btn-danger btn-disable-category" data-toggle="tooltip" data-placement="top" title="Tooltip on top" data-id="'.$field->CategoryID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i></a>'.'&nbsp;<button type="button" data-id="'.$field->CategoryID.'" data-name="'.$field->CategoryName.'" data-description="'.$field->CategoryDescription.'" class="btn btn-info btn-edit-category" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></button>'.'&nbsp;<a class="btn btn-danger btn-delete-category" data-toggle="tooltip" data-placement="top" title="Delete" data-id="'.$field->CategoryID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i></a>';

            $button2 = '<a class="btn btn-primary btn-enable-category" data-toggle="tooltip" data-placement="top" title="Enable Kategori" data-id="'.$field->CategoryID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i></a>'.'&nbsp;<button type="button" data-id="'.$field->CategoryID.'" data-name="'.$field->CategoryName.'" data-description="'.$field->CategoryDescription.'" class="btn btn-info btn-edit-category" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></button>'.'&nbsp;<a class="btn btn-danger btn-delete-category" data-toggle="tooltip" data-placement="top" title="Delete" data-id="'.$field->CategoryID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i></a>';
            
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