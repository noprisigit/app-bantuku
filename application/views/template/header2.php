<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <title>AdminLTE 3 | Fixed Sidebar</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <!-- Favicon -->
      <link rel="shorcut icon" href="<?= base_url('assets') ?>/dist/img/icon.ico">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
      <!-- DataTables -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
      <!-- SweetAlert2 -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
      <!-- Toastr -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/toastr/toastr.min.css">
      <!-- Select2 -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2/css/select2.min.css">
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
   </head>
   <body class="hold-transition sidebar-mini layout-fixed">
      <!-- Site wrapper -->
      <div class="wrapper">
         <!-- Navbar -->
         <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
               <li class="nav-item">
                  <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
               </li>
               <li class="nav-item d-none d-sm-inline-block">
                  <a href="../../index3.html" class="nav-link">Home</a>
               </li>
               <li class="nav-item d-none d-sm-inline-block">
                  <a href="#" class="nav-link">Contact</a>
               </li>
            </ul>

            <ul class="navbar-nav ml-auto">
               <li class="nav-item dropdown">
                  <a class="nav-link text-white" data-toggle="dropdown" href="#">
                     <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                     <div class="dropdown-divider"></div>
                     <a href="<?= base_url('auth/logout'); ?>" class="dropdown-item">
                           <i class="fas fa-power-off mr-2"></i> Logout
                     </a>
                  </div>
               </li>
            </ul>
         </nav>
         <!-- /.navbar -->

         <!-- Main Sidebar Container -->
         <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= base_url(); ?>" class="brand-link" style="background-image: linear-gradient(to left bottom, #00C6FF, #0072FF)">
                <img src="<?= base_url('assets') ?>/dist/img/bantuku.png" alt="AdminLTE Logo" class="img-fluid">
                <span class="brand-text font-weight-light text-white"></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
               <!-- Sidebar user (optional) -->
               <div class="user-panel mt-3 pb-3 mb-3 d-flex">
						<div class="info">
							<a href="#" class="d-block"><strong><?= $this->session->userdata('AdminName'); ?></strong></a>
						</div>
					</div>

               <!-- Sidebar Menu -->
               <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                     <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" id="link" data-widget="treeview" role="menu" data-accordion="false">
							<li class="nav-item">
									<a href="<?= base_url('dashboard') ?>" class="nav-link <?= ($title == "Dashboard") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-tachometer-alt"></i>
										<p>
											Dashboard
										</p>
									</a>
							</li>
							<li class="nav-item">
									<a href="<?= base_url('category') ?>" class="nav-link <?= ($title == "Kategori") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-clipboard-list"></i>
										<p>
											Master Kategori
										</p>
									</a>
							</li>
							<li class="nav-item">
									<a href="<?= base_url('product') ?>" class="nav-link <?= ($title == "Produk") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-cubes"></i>
										<p>
											Master Produk
										</p>
									</a>
							</li>
							<li class="nav-item">
									<a href="<?= base_url('partner') ?>" class="nav-link <?= ($title == "Toko") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-store-alt"></i>
										<p>
											Master Toko
										</p>
									</a>
							</li>
							<li class="nav-item">
									<a href="<?= base_url('transaction') ?>" class="nav-link <?= ($title == "Pesanan") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-dollar-sign"></i>
										<p>
											Master Pesanan
										</p>
									</a>
							</li>
							<li class="nav-item">
									<a href="<?= base_url('cart') ?>" class="nav-link <?= ($title == "Keranjang") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-cart-plus"></i>
										<p>
											Master Keranjang
										</p>
									</a>
							</li>
							<li class="nav-item">
									<a href="<?= base_url('customer') ?>" class="nav-link <?= ($title == "Customer") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-users"></i>
										<p>
											Master Customers
										</p>
									</a>
							</li>
							<li class="nav-item">
									<a href="<?= base_url('slider') ?>" class="nav-link <?= ($title == "Slider") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-th"></i>
										<p>
											Master Sliders
										</p>
									</a>
							</li>
							<li class="nav-item">
									<a href="<?= base_url('invoice') ?>" class="nav-link <?= ($title == "Cetak Invoice") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-print"></i>
										<p>
											Cetak Invoice
										</p>
									</a>
							</li>
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon far fa-envelope"></i>
									<p>
										Payment
										<i class="fas fa-angle-left right"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<a href="<?= base_url('payment/payment-channel') ?>" class="nav-link">
											<i class="far fa-circle nav-icon"></i>
											<p>Payment Channel</p>
										</a>
									</li>
								</ul>
							</li>
							<?php if ($this->session->userdata('AccessID') == 1) : ?>
							<li class="nav-item">
									<a href="<?= base_url('user') ?>" class="nav-link <?= ($title == "Management Users") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-users-cog"></i>
										<p>
											Management Users
										</p>
									</a>
							</li>
							<!-- <li class="nav-item">
									<a href="<?= base_url('role') ?>" class="nav-link <?= ($title == "Role Access") ? 'active' : '' ?>">
										<i class="nav-icon fas fa-th"></i>
										<p>
											Role Access
										</p>
									</a>
							</li> -->
							<?php endif; ?>
                  </ul>
               </nav>
            </div>
         </aside>

         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
               <div class="container-fluid">
                  <div class="row mb-2">
                     <div class="col-sm-6">
                        <h1>Fixed Layout</h1>
                     </div>
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Layout</a></li>
                           <li class="breadcrumb-item active">Fixed Layout</li>
                        </ol>
                     </div>
                  </div>
               </div>
               <!-- /.container-fluid -->
            </section>
