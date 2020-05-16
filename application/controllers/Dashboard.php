<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Dashboard_m', 'dashboard');

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

	public function getDashboardData()
	{
		$res['countPartners'] = $this->dashboard->countingPartner();
		$res['countCustomers'] = $this->dashboard->countingCustomer();
		$res['countCategories'] = $this->dashboard->countingCategories();
		$res['jumlahPendapatan'] = $this->dashboard->jumlahPendapatan();
	
		echo json_encode($res);
	}

	public function countingCustomersByCurrentYear() {
		$total = $this->dashboard->countingCustomersByCurrentYear();
		
		echo json_encode($total);
	}

	// public function countingCustomersByCurrentMonth() {
	// 	$total = $this->dashboard->countingCustomersByCurrentMonth();
	// 	dd($total);
	// 	echo json_encode($total);
	// }
}