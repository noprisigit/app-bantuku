<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partner extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Partner_m', 'partner');
    }

    public function index()
    {
        $data['main_title'] = "Partner";
        $data['title'] = "Partner";

        $this->load->view('template/header', $data);
        $this->load->view('partner/index');
        $this->load->view('template/footer');
    }

    public function create_partner()
    {
        $data['main_title'] = "Partner";
        $data['title'] = "Tambah Partner";

        $this->load->view('template/header', $data);
        $this->load->view('partner/create-partner');
        $this->load->view('template/footer');
    }

    public function save_partner()
    {
        date_default_timezone_set('Asia/Jakarta');

        $uniqueID = random_strings(4) . date('YmdHis');
        $data = [
            'PartnerUniqueID'   => $uniqueID, 
            'CompanyName'       => $this->input->post('nama_toko'),
            'PartnerName'       => $this->input->post('nama_pemilik'),
            'Address'           => $this->input->post('alamat'),
            'Phone'             => $this->input->post('phone'),
            'Email'             => $this->input->post('email')
        ];
        $this->db->insert('partners', $data);
    }

    function show_list_partner()
    {

        $list = $this->partner->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $button = '<button type="button" data-toggle="modal" data-target="#modal-detail-partner" class="btn btn-sm btn-primary btn-detail-partner" data-uniqueID="'.$field->PartnerUniqueID.'" data-nama_toko="'.$field->CompanyName.'" data-nama_pemilik="'.$field->PartnerName.'" data-alamat="'.$field->Address.'" data-phone="'.$field->Phone.'" data-email="'.$field->Email.'"><i class="fas fa-folder"></i> Detail</button>'.'&nbsp;<button type="button" data-id="'.$field->PartnerID.'" class="btn btn-sm btn-info btn-edit-category"><i class="fas fa-pencil-alt"></i> Edit</button>'.'&nbsp;<a class="btn btn-sm btn-danger btn-delete-category" data-id="'.$field->PartnerID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->PartnerUniqueID;
            $row[] = $field->CompanyName;
            $row[] = $field->PartnerName;
            $row[] = $field->Phone;
            $row[] = $field->Email;
            $row[] = $field->Address;
            $row[] = $button;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->partner->count_all(),
            "recordsFiltered" => $this->partner->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}