<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_m', 'dashboard');
		$this->load->model('Invoice_m', 'invoice');

		if (!$this->session->userdata('AdminName'))
			redirect('auth');
	}

	public function index()
	{
		$data['main_title'] = "Home";
		$data['title'] = "Dashboard";
		$data['js'] = [
			'assets/plugins/chart.js/Chart.min.js',
			'assets/dist/js/dashboard.js'
		];

		$this->load->view('template/header', $data);
		$this->load->view('dashboard/index');
		$this->load->view('template/footer', $data);
	}

	public function template_email()
	{
		$customerEmail = $this->input->get('email');
		$data['customer'] = $this->db->get_where('customers', ['CustomerEmail' => $customerEmail])->row_array();
		$this->load->view('template', $data);
	}

	public function template_payment()
	{
		$invoice = $this->input->get('inv');
		$data['orders'] = $this->invoice->getDetailInvoice($invoice);
		// dd($data['orders']);
		$this->load->view('template_payment', $data);
	}

	public function getDashboardData()
	{
		$res['countPartners'] = $this->dashboard->countingPartner();
		$res['countCustomers'] = $this->dashboard->countingCustomer();
		$res['countCategories'] = $this->dashboard->countingCategories();
		$res['jumlahPendapatan'] = $this->dashboard->jumlahPendapatan();
		$res['produkYangDisukai'] = $this->dashboard->productLikes();
		$res['tokoYangDisukai'] = $this->dashboard->shopLikes();
		$res['countAccount'] = $this->dashboard->countingAccount();
		$res['countOrdersThisMonth'] = $this->dashboard->countingOrdersThisMonth();

 		echo json_encode($res);
	}

	public function countingCustomersByCurrentYear() {
		$total = $this->dashboard->countingCustomersByCurrentYear();
		
		echo json_encode($total);
	}

	public function countingPendapatanThisYear() {
		$total = $this->dashboard->countingPendapatanThisYear();

		echo json_encode($total);
	}
}