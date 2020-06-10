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
        $data['main_title'] = 'Home';
        $data['title'] = 'Produk';

        $data['js'] = [
            'assets/dist/js/product.js'
        ];

        $this->db->select('CategoryID, CategoryName');
        $data['categories'] = $this->db->get_where('categories', ['CategoryStatus' => 1])->result_array();

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
                
                $price = $this->input->post('product_price');
                $tax = $price * 0.15;
                $resultPrice = $price + $tax;

                $uniqueID = random_strings(4) . date('YmdHis');
                $input = [
                    'CategoryID'        => $this->input->post('product_category'),
                    'PartnerID'         => $this->input->post('product_partner'),
                    'ProductUniqueID'   => 'P' . $uniqueID,
                    'ProductName'       => $this->input->post('product_name'),
                    'ProductPrice'      => $resultPrice,
                    'ProductStock'      => $this->input->post('product_stock'),
                    'ProductWeight'     => $this->input->post('product_weight'),
                    'ProductDesc'       => $this->input->post('product_desc'),
                    'ProductImage'      => $data['file_name'],
                    'ProductThumbnail'  => $data['raw_name'] . '_thumb' . $data['file_ext'],
                    'ProductStatus'     => 1,
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

    public function product_edit()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->db->select('ProductImage, ProductThumbnail');
        $product = $this->db->get_where('products', ['ProductUniqueID' => $this->input->post('product_unique_id')])->row_array();
        
        $image = $_FILES['product_image']['name'];

        if ($image != "") {
            $config['upload_path']="./assets/dist/img/products"; //path folder file upload
            $config['allowed_types']='png|jpg|jpeg'; //type file yang boleh di upload
            $config['encrypt_name'] = TRUE; //enkripsi file name upload
            $config['max_size'] = 5048;

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('product_image')) {
                $data = $this->upload->data();
                resizeImage($data['file_name'], 'products');

                $new_image = $data['file_name'];
                if ($new_image != $product['ProductImage']) {
                    unlink(FCPATH . "/assets/dist/img/products/" . $product['ProductImage']);
                    unlink(FCPATH . "/assets/dist/img/products/thumbnail/" . $product['ProductThumbnail']);
                    $this->db->set('ProductImage', $new_image);
                    $this->db->set('ProductThumbnail', $data['raw_name'] . '_thumb' . $data['file_ext']);
                }
            } else {
                $res['status'] = false;
                $res['msg'] = $this->upload->display_errors();
            }
        }
        
        $price = $this->input->post('product_price');
        $tax = $price * 0.15;
        $resultPrice = $price + $tax;

        $this->db->set('ProductName', $this->input->post('product_name'));
        $this->db->set('ProductPrice', $resultPrice);
        $this->db->set('ProductStock', $this->input->post('product_stock'));
        $this->db->set('ProductWeight', $this->input->post('product_weight'));
        $this->db->set('CategoryID', $this->input->post('product_category'));
        $this->db->set('PartnerID', $this->input->post('product_partner'));
        $this->db->set('ProductDesc', $this->input->post('product_desc'));
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('ProductUniqueID', $this->input->post('product_unique_id'));
        $this->db->update('products');

        $res['status'] = true;

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
        $this->db->set('ProductStatusPromo', 0);
        $this->db->set('ProductPromo', 0);
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('ProductUniqueID', $this->input->post('uniqueID'));
        $this->db->update('products');
    }

    public function product_promo_deactivated() 
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->db->set('ProductStatusPromo', 0);
        $this->db->set('ProductPromo', 0);
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('ProductUniqueID', $this->input->post('uniqueID'));
        $this->db->update('products');
    }

    public function product_promo_save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->db->set('ProductStatusPromo', 1);
        $this->db->set('ProductPromo', $this->input->post('nilai_promo'));
        $this->db->set('ProductPromoDate', $this->input->post('tgl_promo'));
        $this->db->set('ProductPromoDateEnd', $this->input->post('tgl_selesai_promo'));
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('ProductUniqueID', $this->input->post('uniqueID'));
        $this->db->update('products');
    }

    public function product_promo_edit()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->db->set('ProductPromo', $this->input->post('nilai_promo'));
        $this->db->set('ProductPromoDate', $this->input->post('tgl_promo'));
        $this->db->set('ProductPromoDateEnd', $this->input->post('tgl_selesai_promo'));
        $this->db->set('date_updated', date('Y-m-d H:i:s'));
        $this->db->where('ProductUniqueID', $this->input->post('uniqueID'));
        $update = $this->db->update('products');
        if ($update) {
            $res['status'] = true;
            $res['msg'] = "Update berhasil";
        } else {
            $res['status'] = false;
            $res['msg'] = "Update gagal";
        }
        echo json_encode($res);
    }

    public function tambah_stock()
    {
        $product = $this->db->select('ProductStock')->get_where('products', ['ProductUniqueID' => $this->input->post('ProductUniqueID')])->row_array();
        $stokBaru = 0;
        $stokBaru = $product['ProductStock'] + $this->input->post('StokBaru');
        $this->db->set('ProductStock', $stokBaru);
        $this->db->where('ProductUniqueID', $this->input->post('ProductUniqueID'));
        $update = $this->db->update('products');
        if ($update) {
            $res['status'] = true;
        } else {
            $res['status'] = false;
        }
        echo json_encode($res);
    }

    public function show_list_products()
    {
        
        $list = $this->product->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $btn_activated = '<a class="btn btn-info btn-activated-product mt-1" data-placement="top" data-toggle="tooltip" title="Aktifkan Produk" data-id="'.$field->ProductUniqueID.'" href="javascript:void(0)"><i class="fas fa-power-off"></i></a>';

            $btn_deactivated = '<a class="btn btn-danger btn-deactivated-product mt-1" data-placement="top" data-toggle="tooltip" title="Nonaktifkan Produk" data-id="'.$field->ProductUniqueID.'" href="javascript:void(0)"><i class="fas fa-ban"></i></a>';

            $btn_detail = '<button type="button" class="btn btn-primary btn-detail-product mt-1" data-placement="top" data-toggle="tooltip" title="Detail" data-id="'.$field->ProductUniqueID.'" data-nama="'.$field->ProductName.'" data-price="'.$field->ProductPrice.'" data-stock="'.$field->ProductStock.'" data-weight="'.$field->ProductWeight.'" data-desc="'.$field->ProductDesc.'" data-image="'.$field->ProductImage.'" data-toko="'.$field->CompanyName.'" data-kategori="'.$field->CategoryName.'" data-promo="'.$field->ProductPromo.'" data-statuspromo="'.$field->ProductStatusPromo.'" data-startpromo="'.$field->ProductPromoDate.'" data-endpromo="'.$field->ProductPromoDateEnd.'"><i class="fas fa-folder"></i></button>';
            
            $btn_edit = '<button type="button" class="btn btn-info btn-edit-product mt-1" data-placement="top" data-toggle="tooltip" title="Edit" data-id="'.$field->ProductUniqueID.'" data-nama="'.$field->ProductName.'" data-price="'.$field->ProductPrice.'" data-stock="'.$field->ProductStock.'" data-weight="'.$field->ProductWeight.'" data-desc="'.$field->ProductDesc.'" data-image="'.$field->ProductImage.'" data-toko="'.$field->CompanyName.'" data-kategori="'.$field->CategoryName.'" data-partnerid="'.$field->PartnerUniqueID.'"><i class="fas fa-pencil-alt"></i></button>';
            
            $btn_delete = '<a class="btn btn-danger btn-delete-product mt-1" data-placement="top" data-toggle="tooltip" title="Delete" data-id="'.$field->ProductUniqueID.'" href="javascript:void(0)" ><i class="fas fa-trash-alt"></i></a>';

            $btn_activated_promo = '<button type="button" class="btn btn-success btn-activated-promo mt-1" data-placement="top" data-toggle="tooltip" title="Tambahkan Promo" data-id="'.$field->ProductUniqueID.'" data-nama="'.$field->ProductName.'" data-price="'.$field->ProductPrice.'" data-stock="'.$field->ProductStock.'" data-weight="'.$field->ProductWeight.'" data-desc="'.$field->ProductDesc.'" data-image="'.$field->ProductImage.'" data-toko="'.$field->CompanyName.'" data-kategori="'.$field->CategoryName.'"><i class="fas fa-tags"></i></button>';

            $btn_deactivated_promo = '<a class="btn btn-danger btn-deactivated-promo mt-1" data-placement="top" data-toggle="tooltip" title="Matikan Promo" data-id="'.$field->ProductUniqueID.'" href="javascript:void(0)"><i class="fas fa-tags"></i></a>';

            $btn_edit_promo = '<button type="button" class="btn btn-info btn-edit-product-promo mt-1" data-placement="top" data-toggle="tooltip" title="Edit Nilai Promo" data-id="'.$field->ProductUniqueID.'" data-nama="'.$field->ProductName.'" data-price="'.$field->ProductPrice.'" data-stock="'.$field->ProductStock.'" data-weight="'.$field->ProductWeight.'" data-desc="'.$field->ProductDesc.'" data-image="'.$field->ProductImage.'" data-toko="'.$field->CompanyName.'" data-kategori="'.$field->CategoryName.'" data-promo="'.$field->ProductPromo.'" data-tglpromo="'.$field->ProductPromoDate.'" data-tglselesaipromo="'.$field->ProductPromoDateEnd.'"><i class="fas fa-tag"></i></button>';

            $btn_tambah_stok = '<button type="button" data-toggle="tooltip" data-placement="top" title="Tambah Stok Produk" data-id="'.$field->ProductUniqueID.'" data-nama="'.$field->ProductName.'" data-stock="'.$field->ProductStock.'" data-image="'.$field->ProductImage.'" data-toko="'.$field->CompanyName.'" class="btn btn-info btn-tambah-stock mt-1"><i class="fas fa-plus"></i></button>';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->ProductName;
            $row[] = "Rp " . number_format($field->ProductPrice,0,',','.');
            $row[] = $field->ProductStock . " buah";
            $row[] = $field->CompanyName;
            if ($field->ProductStatus == 0) {
                $row[] = '<span class="badge badge-danger">Tidak Aktif</span>';
            } else {
                $row[] = '<span class="badge badge-success">Aktif</span>';
            }

            if ($field->ProductStatusPromo == 0) {
                $row[] = '<span class="badge badge-danger">Tidak Ada Promo</span>';
            } else {
                $row[] = '<span class="badge badge-success">Ada Promo '. $field->ProductPromo .' %</span>';
            }

            // if ($field->ProductStatusPromo == 0) {
            //     $row[] = '<span class="badge badge-danger">Tidak Ada Promo</span>';
            //     $row[] = '<span class="badge badge-danger">Tidak Ada Promo</span>';
            // } elseif ($field->ProductPromoDateEnd < date('Y-m-d')) {
            //     $this->db->set('ProductStatusPromo', 0);
            //     $this->db->where('ProductUniqueID', $field->ProductUniqueID);
            //     $this->db->update('products');
            //     $row[] = '<span class="badge badge-danger">Promo Sudah Berakhir</span>';
            //     $row[] = '<span class="badge badge-danger">Promo Sudah Berakhir</span>';
            // } else {
            //     $row[] = '<span class="badge badge-success">'.date_format(date_create($field->ProductPromoDate), 'd-m-Y').'</span>';
            //     $row[] = '<span class="badge badge-success">'.date_format(date_create($field->ProductPromoDateEnd), 'd-m-Y').'</span>';
            // }

            if ($field->ProductStatus == 0 && $field->ProductStatusPromo == 0) {
                $row[] = $btn_activated . "&nbsp" . $btn_detail . "&nbsp" . $btn_edit . "&nbsp" . $btn_delete;
            } elseif ($field->ProductStatus == 0 && $field->ProductStatusPromo == 1) {
                $row[] = $btn_activated . "&nbsp" . $btn_detail . "&nbsp" . $btn_edit . "&nbsp" . $btn_delete;
            } elseif ($field->ProductStatus == 1 && $field->ProductStatusPromo == 0) {
                $row[] = $btn_deactivated . "&nbsp" . $btn_tambah_stok . "&nbsp" . $btn_detail . "&nbsp" . $btn_edit . "&nbsp" . $btn_delete . "&nbsp" . $btn_activated_promo;
            } else {
                $row[] = $btn_deactivated . "&nbsp" . $btn_tambah_stok . "&nbsp" . $btn_detail . "&nbsp" . $btn_edit . "&nbsp" . $btn_delete . "&nbsp" . $btn_deactivated_promo . "&nbsp" . $btn_edit_promo;
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

    public function load_data_edit()
    {
        $this->db->select('CategoryID, CategoryName');
        $data['categories'] = $this->db->get('categories')->result_array();

        $this->db->select('PartnerID, PartnerUniqueID, CompanyName');
        $data['partners'] = $this->db->get('partners')->result_array();

        echo json_encode($data);
    }
}