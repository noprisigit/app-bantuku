<?php

class Order extends CI_Controller {
   public function __construct() {
      parent::__construct();
      $this->load->model('Transaction_m', 'transaction');
   }

   public function sendOrderPage() {
      $invoice = $this->input->get('invoice');
      $customerUniqueID = $this->input->get('customer');

      $data['order'] = $this->transaction->getDetailOrderByInvoice($invoice);
      $this->load->view('order/send-order', $data);
   }

   public function sendOrder() {
      $invoice = $this->input->get('invoice');
      $customerUniqueID = $this->input->get('customer');

      if(isset($invoice) && isset($customerUniqueID)) {
         $order = $this->transaction->getDetailOrderByInvoice($invoice);
         if (count($order) > 0) {
            $this->db->set('OrderStatus', 3);
            $this->db->where('InvoiceNumber', $invoice);
            $this->db->update('orders');

            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
               <h5><i class="icon fas fa-check"></i> Pesan!</h5>
               Pesanan akan anda kirimkan.
            </div>');
         } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
               <h5><i class="icon fas fa-ban"></i> Pesan!</h5>
               Pesanan tidak ditemukan.
            </div>');
         }
      } else {
         $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Pesan!</h5>
            Pesanan tidak ditemukan.
         </div>');
      }
      $this->load->view('order/send-order-page');
   }
}