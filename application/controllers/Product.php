<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_m', 'product');

        if (!$this->session->userdata('AdminName'))
            redirect('auth');
    }

    public function index()
    {
        $data['main_title'] = 'Product';
        $data['title'] = 'Product';

        $this->db->select('CategoryID, CategoryName');
        $data['categories'] = $this->db->get('categories')->result_array();

        $this->db->select('PartnerID, PartnerUniqueID, CompanyName');
        $data['partners'] = $this->db->get('partners')->result_array();
        
        $this->load->view('template/header', $data);
        $this->load->view('product/index', $data);
        $this->load->view('template/footer');
    }

    public function product_save() {
        date_default_timezone_set('Asia/Jakarta');

        if ($_FILES['product_image']['name']) {
            $config['upload_path'] = "./assets/dist/img/products"; //path folder file upload
            $config['allowed_types'] = 'png|jpg|jpeg'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload
            $config['max_size'] = 5048;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('product_image')) {
                $res['status'] = false;
                $res['msg'] = $this->upload->display_errors();
            } else {
                $data = $this->upload->data();
                resizeImage($data['file_name'], 'products');

                $uniqueID = random_strings(4) . date('YmdHis');
                $input = [
                    'CategoryID'        => $this->input->post('product_category'),
                    'PartnerID'         => $this->input->post('product_partner'),
                    'ProductUniqueID'   => 'P' . $uniqueID,
                    'ProductName'       => $this->input->post('product_name'),
                    'ProductPrice'      => $this->input->post('product_price'),
                    'ProductStock'      => $this->input->post('product_stock'),
                    'ProductWeight'     => $this->input->post('product_weight'),
                    'ProductDesc'       => $this->input->post('product_desc'),
                    'ProductImage'      => $data['file_name'],
                    'ProductThumbnail'  => $data['raw_name'] . '_thumb' . $data['file_ext'],
                    'ProductStatus'     => 0,
                    'date_created'      => date('Y-m-d H:i:s'),
                    'date_updated'      => date('Y-m-d H:i:s')
                ];

                $res['insert'] = $this->db->insert('products', $input);
                $res['status'] = true;

                $this->db->select('CategoryID, CategoryName');
                $res['categories'] = $this->db->get('categories')->result_array();
                
                $this->db->select('PartnerID, PartnerUniqueID, CompanyName');
                $res['partners'] = $this->db->get('partners')->result_array();

            }
        }
        echo json_encode($res);
    }

    public function product_delete()
    {

        $product = $this->db->get_where('products', ['ProductUniqueID' => $this->input->post('uniqueID')])->row_array();

        unlink(FCPATH . "/assets/dist/img/products/" . $product['ProductImage']);
        unlink(FCPATH . "/assets/dist/img/products/thumbnail/" . $product['ProductThumbnail']);

        $this->db->delete('products', ['ProductUniqueID' => $this->input->post('uniqueID')]);
    }

    public function product_activated()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->set('ProductStatus', 1);
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('ProductUniqueID', $this->input->post('uniqueID'));
        $this->db->update('products');
    }

    public function product_deactivated()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->set('ProductStatus', 0);
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('ProductUniqueID', $this->input->post('uniqueID'));
        $this->db->update('products');
    }

    public function show_list_products()
    {
        $list = $this->product->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $btn_activated = '<a class="btn btn-xs btn-info btn-activated-product" data-id="'.$field->ProductUniqueID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i> Activated Product</a>';

            $btn_deactivated = '<a class="btn btn-xs btn-danger btn-deactivated-product" data-id="'.$field->ProductUniqueID.'" href="javascript:void(0)"><i class="fas fa-ban"></i> Deactivated Product</a>';

            $btn_detail = '<button type="button" class="btn btn-xs btn-primary btn-detail-product" data-id="'.$field->ProductUniqueID.'" data-nama="'.$field->ProductName.'" data-price="'.$field->ProductPrice.'" data-stock="'.$field->ProductStock.'" data-weight="'.$field->ProductWeight.'" data-desc="'.$field->ProductDesc.'" data-image="'.$field->ProductImage.'" data-toko="'.$field->CompanyName.'" data-kategori="'.$field->CategoryName.'"><i class="fas fa-folder"></i> Detail</button>';
            
            // $btn_edit = '<button type="button" class="btn btn-sm btn-info btn-edit-slider" data-id="'.$field->SliderID.'" data-name="'.$field->SliderName.'" data-description="'.$field->SliderDescription.'" data-start="'.$field->start_date.'" data-end="'.$field->end_date.'" data-picture="'.$field->SliderPicture.'"><i class="fas fa-pencil-alt"></i> Edit</button>';
            
            $btn_delete = '<a class="btn btn-xs btn-danger btn-delete-product" data-id="'.$field->ProductUniqueID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i> Delete</a>';

            // $date_start = date_create($field->start_date);
            // $date_end = date_create($field->end_date);

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->ProductUniqueID;
            $row[] = $field->ProductName;
            $row[] = "Rp " . number_format($field->ProductPrice,2,',','.');
            $row[] = $field->ProductWeight . " gram";
            $row[] = $field->ProductStock . " buah";
            $row[] = $field->CategoryName;
            $row[] = $field->CompanyName;
            if ($field->ProductStatus == 0) {
                $row[] = '<span class="badge badge-danger">Not Active</span>';
            } else {
                $row[] = '<span class="badge badge-success">Active</span>';
            }

            if ($field->ProductStatus == 0) {
                $row[] = $btn_activated . "&nbsp" . $btn_detail . "&nbsp" . $btn_delete;
            } else {
                $row[] = $btn_deactivated . "&nbsp" . $btn_detail . "&nbsp" . $btn_delete;
            }

            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->product->count_all(),
            "recordsFiltered" => $this->product->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function load_product_detail()
    {
        $data = $this->product->getProductsByID($this->input->post('uniqueID'));
        
        $response .= "<tr>";
        $response .= "<td width='100%'>Unique ID</td><td> :".$data['ProductName']."</td>";
        $response .= "</tr>";

        $response .= "<tr>";
        $response .= "<td>Nama Produk</td><td> :".$data['ProductName']."</td>";
        $response .= "</tr>";

        $response .= "<tr>";
        $response .= "<td>Salary : </td><td> :".$salary."</td>";
        $response .= "</tr>";

        $response .= "<tr>";
        $response .= "<td>Gender : </td><td> :".$gender."</td>";
        $response .= "</tr>";

        $response .= "<tr>";
        $response .= "<td>City : </td><td> :".$city."</td>";
        $response .= "</tr>";

        $response .= "<tr>"; 
        $response .= "<td>Email : </td><td> :".$email."</td>"; 
        $response .= "</tr>";

        dd($res['data']);

        echo json_encode($res);
    }
}