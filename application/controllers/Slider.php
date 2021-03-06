<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Slider_m', 'slider');

        if (!$this->session->userdata('AdminName'))
            redirect('auth');
    }

    public function index()
    {
        $data['main_title'] = "Home";
        $data['title'] = "Slider";

        $data['js'] = [
            'assets/dist/js/slider.js'
        ];

        $this->load->view('template/header', $data);
        $this->load->view('slider/index');
        $this->load->view('template/footer', $data);
    }
    
    public function slider_save()
    {
        if (isset($_FILES['picture']['name'])) {
            $config['upload_path'] = "./assets/dist/img/sliders"; //path folder file upload
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload
            $config['max_size'] = 5048;
            $config['max_height'] = 252;
            $config['min_height'] = 252;
            $config['max_width'] = 750;
            $config['min_width'] = 750;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('picture')) {
                $res['status'] = false;
                $res['msg'] = $this->upload->display_errors();
            } else {
                $data = $this->upload->data();
                resizeImage($data['file_name'], 'sliders');

                $input = [
                    'SliderName'        => $this->input->post('name'),
                    'SliderDescription' => $this->input->post('description'),
                    'start_date'        => $this->input->post('start_date'),
                    'end_date'          => $this->input->post('end_date'),
                    'SliderPicture'     => $data['file_name'],
                    'SliderThumbnail'   => $data['raw_name'] . '_thumb' . $data['file_ext']
                ];

                $this->db->insert('sliders', $input);
                $res['status'] = true;
            }
        }
        echo json_encode($res);
    }

    public function slider_edit()
    {
        date_default_timezone_set('Asia/Jakarta');

        $slider = $this->db->get_where('sliders', ['SliderID' => $this->input->post('slider_id')])->row_array();
        $image = $_FILES['edit_slider_picture']['name'];
        if ($image != "") {
            $config['upload_path']="./assets/dist/img/sliders"; //path folder file upload
            $config['allowed_types']='gif|jpg|jpeg|png'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload
            $config['max_size'] = 5048;
            $config['max_height'] = 252;
            $config['min_height'] = 252;
            $config['max_width'] = 750;
            $config['min_width'] = 750;

            $this->load->library('upload',$config); //call library upload 

            if ($this->upload->do_upload('edit_slider_picture')) {
                $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
                resizeImage($data['upload_data']['file_name'], 'sliders');

                $new_image = $data['upload_data']['file_name'];
                if ($new_image != $slider['SliderPicture']) {
                    unlink(FCPATH . "/assets/dist/img/sliders/" . $slider['SliderPicture']);
                    unlink(FCPATH . "/assets/dist/img/sliders/thumbnail/" . $slider['SliderThumbnail']);
                    $this->db->set('SliderPicture', $new_image);
                    $this->db->set('SliderThumbnail', $data['upload_data']['raw_name'] . '_thumb' . $data['upload_data']['file_ext']);
                }
            } else {
                $res['status'] = false;
                $res['msg'] = $this->upload->display_errors();
            }
        } 
        $res['status'] = true;
        
        $this->db->set('SliderName', $this->input->post('edit_slider_name'));
        $this->db->set('SliderDescription', $this->input->post('edit_slider_description'));
        $this->db->set('start_date', $this->input->post('edit_slider_start_date'));
        $this->db->set('end_date', $this->input->post('edit_slider_end_date'));
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('SliderID', $this->input->post('slider_id'));
        $this->db->update('sliders');

        echo json_encode($res);
    }

    public function slider_delete()
    {
        $slider = $this->db->get_where('sliders', ['SliderID' => $this->input->post('SliderID')])->row_array();
        unlink(FCPATH . "/assets/dist/img/sliders/" . $slider['SliderPicture']);
        unlink(FCPATH . "/assets/dist/img/sliders/thumbnail/" . $slider['SliderThumbnail']);
        $this->db->delete('sliders', ['SliderID' => $this->input->post('SliderID')]);
    }

    public function show_list_slider()
    {
        $list = $this->slider->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $btn_detail = '<button type="button" class="btn btn-primary btn-detail-slider" data-toggle="tooltip" data-placement="top" title="Detail" data-name="'.$field->SliderName.'" data-description="'.$field->SliderDescription.'" data-start="'.$field->start_date.'" data-end="'.$field->end_date.'" data-picture="'.$field->SliderPicture.'"><i class="fas fa-folder"></i></button>';
            
            $btn_edit = '<button type="button" class="btn btn-info btn-edit-slider" data-toggle="tooltip" data-placement="top" title="Edit" data-id="'.$field->SliderID.'" data-name="'.$field->SliderName.'" data-description="'.$field->SliderDescription.'" data-start="'.$field->start_date.'" data-end="'.$field->end_date.'" data-picture="'.$field->SliderPicture.'"><i class="fas fa-pencil-alt"></i></button>';
            
            $btn_delete = '<a class="btn btn-danger btn-delete-slider" data-toggle="tooltip" data-placement="top" title="Delete" data-id="'.$field->SliderID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i></a>';

            $date_start = date_create($field->start_date);
            $date_end = date_create($field->end_date);

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->SliderName;
            $row[] = $field->SliderDescription;
            $row[] = date_format($date_start, 'd-m-Y');
            $row[] = date_format($date_end, 'd-m-Y');
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