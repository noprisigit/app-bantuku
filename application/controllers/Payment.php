<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {
   public function __construct()
   {
      parent::__construct();
   }

   public function payment_channel()
   {
      $data['main_title'] = "Home";
      $data['title'] = "Daftar Payment Channel";

      $data['js'] = [
         'assets/dist/js/payment.js'
     ];

      $this->load->view('template/header', $data);
      $this->load->view('payment/payment-channel');
      $this->load->view('template/footer', $data);
   }

   public function savePaymentChannel()
   {
      $config['upload_path'] = "./assets/dist/img/payment"; //path folder file upload
      $config['allowed_types'] = 'gif|jpg|jpeg|png'; //type file yang boleh di upload
      $config['encrypt_name'] = TRUE; //enkripsi file name upload
      $config['max_size'] = 2048;

      $this->load->library('upload', $config);
      if (!$this->upload->do_upload('inputLogoPaymentChannel')) {
         $res['status'] = false;
         $res['msg'] = $this->upload->display_errors();
      } else {
         $data = $this->upload->data();

         $input = [
            'PaymentChannelCode' => $this->input->post('inputKodePaymentChannel'),
            'PaymentChannelName' => $this->input->post('inputNamaPaymentChannel'),
            'PaymentChannelLogo' => $data['file_name'],
         ];

         $this->db->insert('paymentchannel', $input);
         $res['status'] = true;
      }
      echo json_encode($res);
   }

   public function editPaymentChannel()
   {
      $payment = $this->db->get_where('paymentchannel', ['PaymentChannelCode' => $this->input->post('editKodePaymentChannel')])->row_array();
      $logo = $_FILES['editLogoPaymentChannel']['name'];
      if ($logo != "") {
         $config['upload_path'] = "./assets/dist/img/payment"; //path folder file upload
         $config['allowed_types'] = 'gif|jpg|jpeg|png'; //type file yang boleh di upload
         $config['encrypt_name'] = TRUE; //enkripsi file name upload
         $config['max_size'] = 2048;

         $this->load->library('upload',$config); //call library upload 

         if ($this->upload->do_upload('editLogoPaymentChannel')) {
            $data = $this->upload->data();

            $new_image = $data['file_name'];
            if ($new_image != $payment['PaymentChannelLogo']) {
               unlink(FCPATH . "/assets/dist/img/payment/" . $payment['PaymentChannelLogo']);
               $this->db->set('PaymentChannelLogo', $new_image);
            }
         } else {
            $res['status'] = false;
            $res['msg'] = $this->upload->display_errors();
         }
      } 
      $res['status'] = true;
        
      $this->db->set('PaymentChannelName', $this->input->post('editNamaPaymentChannel'));
      $this->db->where('PaymentChannelCode', $this->input->post('editKodePaymentChannel'));
      $this->db->update('paymentchannel');

      echo json_encode($res);
   }

   public function deletePaymentChannel()
   {
      $payment = $this->db->select('PaymentChannelLogo')->get_where('paymentchannel', ['PaymentChannelCode' => $this->input->post('kode')])->row_array();
      unlink(FCPATH . "/assets/dist/img/payment/" . $payment['PaymentChannelLogo']);
      $this->db->delete('paymentchannel', ['PaymentChannelCode' => $this->input->post('kode')]);
   }

   public function getPaymentChannel()
   {
      $paymentChannel = $this->db->get('paymentchannel')->result_array();
      echo json_encode($paymentChannel);
   }
}
