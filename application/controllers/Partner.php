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
        $data['main_title'] = "Home";
        $data['title'] = "Toko";

        $data['js'] = [
            'assets/dist/js/partner.js'
        ];

        $data['provinsi'] = $this->db->get('provinces')->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('partner/index', $data);
        $this->load->view('template/footer', $data);
    }

    public function save_partner()
    {
        date_default_timezone_set('Asia/Jakarta');

        if (isset($_FILES['gambar_toko']['name'])) {
            $config['upload_path'] = "./assets/dist/img/partners"; //path folder file upload
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload
            $config['max_size'] = 5048;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('gambar_toko')) {
                $res['status'] = false;
                $res['msg'] = $this->upload->display_errors();
            } else {
                $data = $this->upload->data();
                resizeImage($data['file_name'], 'partners');

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
                    'ShopPicture'       => $data['file_name'],
                    'ShopThumbnail'     => $data['raw_name'] . '_thumb' . $data['file_ext']
                ];
                $res['status'] = true;
                $res['provinsi'] = $this->db->get('provinces')->result_array();
                $this->db->insert('partners', $input);
            }
        }

        echo json_encode($res);
    }

    public function edit_partner()
    {
        date_default_timezone_set('Asia/Jakarta');
        $partner = $this->db->get_where('partners', ['PartnerID' => $this->input->post('partner_id')])->row_array();
        $image = $_FILES['partner_gambar_toko_edit']['name'];
        if ($image != "") {
            $config['upload_path']="./assets/dist/img/partners"; //path folder file upload
            $config['allowed_types']='gif|jpg|jpeg|png'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload
            $config['max_size'] = 5048;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('partner_gambar_toko_edit')) {
                $data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
                resizeImage($data['upload_data']['file_name'], 'partners');

                $new_image = $data['upload_data']['file_name'];
                if ($new_image != $partner['ShopPicture']) {
                    unlink(FCPATH . "/assets/dist/img/partners/" . $partner['ShopPicture']);
                    unlink(FCPATH . "/assets/dist/img/partners/thumbnail/" . $partner['ShopThumbnail']);
                    $this->db->set('ShopPicture', $new_image);
                    $this->db->set('ShopThumbnail', $data['upload_data']['raw_name'] . '_thumb' . $data['upload_data']['file_ext']);
                }
            } else {
                $res['status'] = false;
                $res['msg'] = $this->upload->display_errors();
            }
        }
        $res['status'] = true;

        $province = $this->db->get_where('provinces', ['ProvinceID' => $this->input->post('partner_provinsi_edit')])->row_array();

        $this->db->set('CompanyName', $this->input->post('partner_nama_toko_edit'));
        $this->db->set('PartnerName', $this->input->post('partner_nama_pemilik_edit'));
        $this->db->set('Address', $this->input->post('partner_alamat_edit'));
        $this->db->set('Phone', $this->input->post('partner_phone_edit'));
        $this->db->set('Email', $this->input->post('partner_email_edit'));
        $this->db->set('Province', $province['ProvinceName']);
        $this->db->set('District', $this->input->post('partner_kabupaten_edit'));
        $this->db->set('PostalCode', $this->input->post('partner_kode_pos_edit'));
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('PartnerUniqueID', $this->input->post('partner_uniqueid_edit'));
        $this->db->update('partners');

        echo json_encode($res);
    }

    public function delete_partner()
    {
        $partner = $this->db->get_where('partners', ['PartnerID' => $this->input->post('partner_id')])->row_array();
        unlink(FCPATH . "/assets/dist/img/partners/" . $partner['ShopPicture']);
        unlink(FCPATH . "/assets/dist/img/partners/thumbnail/" . $partner['ShopThumbnail']);
        $this->db->delete('partners', ['PartnerID' => $this->input->post('partner_id')]);
    }

    function show_list_partner()
    {
        $list = $this->partner->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $btn_detail = '<span data-toggle="modal" data-target="#modal-detail-partner"><button type="button" data-toggle="tooltip" data-placement="top" title="Detail" class="btn btn-primary btn-detail-partner" data-uniqueID="'.$field->PartnerUniqueID.'" data-nama_toko="'.$field->CompanyName.'" data-nama_pemilik="'.$field->PartnerName.'" data-alamat="'.$field->Address.'" data-kabupaten="'.$field->District.'" data-provinsi="'.$field->Province.'" data-pos="'.$field->PostalCode.'" data-gambar="'.$field->ShopPicture.'" data-phone="'.$field->Phone.'" data-email="'.$field->Email.'"><i class="fas fa-folder"></i></button></span>';
            
            $btn_edit = '<button type="button" data-toggle="tooltip" data-placement="top" title="Edit" data-id="'.$field->PartnerID.'" data-uniqueID="'.$field->PartnerUniqueID.'" data-nama_toko="'.$field->CompanyName.'" data-nama_pemilik="'.$field->PartnerName.'" data-alamat="'.$field->Address.'" data-kabupaten="'.$field->District.'" data-provinsi="'.$field->Province.'" data-pos="'.$field->PostalCode.'" data-gambar="'.$field->ShopPicture.'" data-phone="'.$field->Phone.'" data-email="'.$field->Email.'" class="btn btn-info btn-edit-partner"><i class="fas fa-pencil-alt"></i></button>';
            
            $btn_delete = '<a class="btn btn-danger btn-delete-partner" data-toggle="tooltip" data-placement="top" title="Delete" data-id="'.$field->PartnerID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i></a>';
            
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->PartnerUniqueID;
            $row[] = $field->CompanyName;
            $row[] = $field->PartnerName;
            $row[] = $field->Phone;
            $row[] = $field->Email;
            // $row[] = $field->Address . ' ' . $field->District . ' ' . $field->Province;
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

    public function province_get_by_name()
    {
        $data['all_provinsi'] = $this->db->get('provinces')->result_array();
        $data['provinsi'] = $this->db->get_where('provinces', ['ProvinceName' => $this->input->post('ProvinceName')])->row_array();
        $data['kabupaten'] = $this->db->get_where('districts', ['ProvinceID' => $data['provinsi']['ProvinceID']])->result_array();
        echo json_encode($data);
    }

    public function getPartner() {
        $partners = $this->db->get('partners')->result_array();
        echo json_encode($partners);
    }

    public function getProvince() {
        $province = $this->db->get('provinces')->result_array();
        echo json_encode($province);
    }
}