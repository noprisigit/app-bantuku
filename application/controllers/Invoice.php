<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {
   public function __construct()
   {
      parent::__construct();
      $this->load->model('Invoice_m', 'invoice');
      if (!$this->session->userdata('AdminName'))
            redirect('auth');
   }

   public function index()
   {
      $data['main_title'] = "Home";
      $data['title'] = "Cetak Invoice";
      $data['js'] = [
         'assets/dist/js/invoice.js'
      ];

      $this->load->view('template/header', $data);
      $this->load->view('invoice/index');
      $this->load->view('template/footer', $data);
   }

   public function print() 
   {
      $invoiceNumber = $this->uri->segment(3);
      $data['invoice'] = $this->invoice->getDetailInvoice($invoiceNumber);
      $tglPesan = $data['invoice'][0]['OrderDate'];
      $parseTgl = explode(' ', $tglPesan);
      $hasilParse = explode('-', $parseTgl[0]);
      $data['tgl'] = $hasilParse;
      // dd($invoice);
      $this->load->view('invoice/cetak-invoice', $data);
   }

   public function cariInvoice()
   {
      $invoice = $this->invoice->getDetailInvoice($this->input->post('invoiceNumber'));
      echo json_encode($invoice);
   }
}