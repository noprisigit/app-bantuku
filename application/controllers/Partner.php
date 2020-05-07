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

        if (isset($_FILES['gambar_toko']['name'])) {
            $config['upload_path'] = "./assets/dist/img/partners"; //path folder file upload
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('gambar_toko')) {
                $res['status'] = false;
                $res['msg'] = $this->upload->display_errors();
            } else {
                $data = $this->upload->data();

                $provinsi = $this->db->get_where('provinces', ['ProvinceID' => $this->input->post('provinsi')])->row_array();
                
                $uniqueID = random_strings(4) . date('YmdHis');
                $input = [
                    'PartnerUniqueID'   => $uniqueID, 
                    'CompanyName'       => $this->input->post('nama_toko'),
                    'PartnerName'       => $this->input->post('nama_pemilik'),
                    'Address'           => $this->input->post('alamat'),
                    'Province'          => $provinsi['ProvinceName'],
                    'District'          => $this->input->post('kabupaten'),
                    'PostalCode'        => $this->input->post('kode_pos'),
                    'Phone'             => $this->input->post('phone'),
                    'Email'             => $this->input->post('email'),
                    'ShopPicture'       => $data['file_name']
                ];
                $res['status'] = true;
                $this->db->insert('partners', $input);
            }
        }

        echo json_encode($res);
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
            $btn_detail = '<button type="button" data-toggle="modal" data-target="#modal-detail-partner" class="btn btn-sm btn-primary btn-detail-partner" data-uniqueID="'.$field->PartnerUniqueID.'" data-nama_toko="'.$field->CompanyName.'" data-nama_pemilik="'.$field->PartnerName.'" data-alamat="'.$field->Address.'" data-kabupaten="'.$field->District.'" data-provinsi="'.$field->Province.'" data-pos="'.$field->PostalCode.'" data-gambar="'.$field->ShopPicture.'" data-phone="'.$field->Phone.'" data-email="'.$field->Email.'"><i class="fas fa-folder"></i> Detail</button>';
            
            $btn_edit = '<button type="button" data-id="'.$field->PartnerID.'" data-uniqueID="'.$field->PartnerUniqueID.'" data-nama_toko="'.$field->CompanyName.'" data-nama_pemilik="'.$field->PartnerName.'" data-alamat="'.$field->Address.'" data-phone="'.$field->Phone.'" data-email="'.$field->Email.'" class="btn btn-sm btn-info btn-edit-partner"><i class="fas fa-pencil-alt"></i> Edit</button>';
            
            $btn_delete = '<a class="btn btn-sm btn-danger btn-delete-partner" data-id="'.$field->PartnerID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';
            
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->PartnerUniqueID;
            $row[] = $field->CompanyName;
            $row[] = $field->PartnerName;
            $row[] = $field->Phone;
            $row[] = $field->Email;
            $row[] = $field->Address . ' ' . $field->District . ' ' . $field->Province;
            $row[] = $btn_detail . '&nbsp' . $btn_edit . '&nbsp' . $btn_delete;

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