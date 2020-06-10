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
   
   public function searchInvoice() {
      if ($this->input->post('query') != "") {
         $output = '';
         $invoice = $this->invoice->getInvoiceNumber($this->input->post('query')); 
         $output = '<ul class="pl-2 list-unstyled" style="background-color: #eee; cursor: pointer">';
         if (count($invoice) > 0) {
            foreach($invoice as $row) {
               $output .= '<li class="p-2 listInvoice">'.$row['InvoiceNumber'].'</li>';
            }
         } else {
            $output .= '<li class="p-2">Nomor invoice tidak ditemukan / Belum dilakukan pembayaran</li>';
         }
         $output .= '</ul>';
         echo $output;
      }
   }
}