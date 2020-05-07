<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partner extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Partner_m', 'partner');

        if (!$this->session->userdata('AdminName'))
            redirect('auth');
    }

    public function index()
    {
        $data['main_title'] = "Partner";
        $data['title'] = "Partner";

        $data['provinsi'] = $this->db->get('provinces')->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('partner/index', $data);
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

    public function edit_partner()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->set('CompanyName', $this->input->post('nama_toko'));
        $this->db->set('PartnerName', $this->input->post('nama_pemilik'));
        $this->db->set('Address', $this->input->post('alamat'));
        $this->db->set('Phone', $this->input->post('phone'));
        $this->db->set('Email', $this->input->post('email'));
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('PartnerUniqueID', $this->input->post('uniqueid'));
        $this->db->update('partners');
    }

    public function delete_partner()
    {
        $this->db->delete('partners', ['PartnerID' => $this->input->post('partner_id')]);
    }

    function show_list_partner()
    {

        $list = $this->partner->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $button = '<button type="button" data-toggle="modal" data-target="#modal-detail-partner" class="btn btn-sm btn-primary btn-detail-partner" data-uniqueID="'.$field->PartnerUniqueID.'" data-nama_toko="'.$field->CompanyName.'" data-nama_pemilik="'.$field->PartnerName.'" data-alamat="'.$field->Address.'" data-phone="'.$field->Phone.'" data-email="'.$field->Email.'"><i class="fas fa-folder"></i> Detail</button>'.'&nbsp;<button type="button" data-id="'.$field->PartnerID.'" data-uniqueID="'.$field->PartnerUniqueID.'" data-nama_toko="'.$field->CompanyName.'" data-nama_pemilik="'.$field->PartnerName.'" data-alamat="'.$field->Address.'" data-phone="'.$field->Phone.'" data-email="'.$field->Email.'" class="btn btn-sm btn-info btn-edit-partner"><i class="fas fa-pencil-alt"></i> Edit</button>'.'&nbsp;<a class="btn btn-sm btn-danger btn-delete-partner" data-id="'.$field->PartnerID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';

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

    public function district_get()
    {
        $data = $this->db->get_where('districts', ['ProvinceID' => $this->input->post('ProvinceID')])->result_array();
    
        echo json_encode($data);
    }
}