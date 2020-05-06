<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Slider_m', 'slider');
    }

    public function index()
    {
        $data['main_title'] = "Slider";
        $data['title'] = "Slider";

        $this->load->view('template/header', $data);
        $this->load->view('slider/index');
        $this->load->view('template/footer');
    }
    
    public function slider_save()
    {
        if (isset($_FILES['picture']['name'])) {
            $config['upload_path'] = "./assets/dist/img/sliders"; //path folder file upload
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('picture')) {
                $res['status'] = false;
                $res['msg'] = $this->upload->display_errors();
            } else {
                $data = $this->upload->data();
                $input = [
                    'SliderName'        => $this->input->post('name'),
                    'SliderDescription' => $this->input->post('description'),
                    'start_date'        => $this->input->post('start_date'),
                    'end_date'          => $this->input->post('end_date'),
                    'SliderPicture'     => $data['file_name']
                ];

                $this->db->insert('sliders', $input);
                $res['status'] = true;
            }
        }
        echo json_encode($res);
    }

    public function show_list_slider()
    {
        $list = $this->slider->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $btn_detail = '<button type="button" class="btn btn-sm btn-primary btn-detail-slider"><i class="fas fa-folder"></i> Detail</button>';
            
            $btn_edit = '<button type="button" class="btn btn-sm btn-info btn-edit-partner"><i class="fas fa-pencil-alt"></i> Edit</button>';
            
            $btn_delete = '<a class="btn btn-sm btn-danger btn-delete-partner" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';
            
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->SliderName;
            $row[] = $field->SliderDescription;
            $row[] = $field->start_date;
            $row[] = $field->end_date;
            $row[] = $btn_detail . "&nbsp" . $btn_edit . "&nbsp" . $btn_delete;
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->slider->count_all(),
            "recordsFiltered" => $this->slider->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}