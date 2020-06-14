<!DOCTYPE html>
<html>

   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>BANTUKU | Sending Order Page</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      
      <!-- Favicon -->
      <link rel="shorcut icon" href="<?= base_url('assets') ?>/dist/img/icon.ico">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
   </head>
      <div class="container align-items-center d-flex justify-content-center my-2">
         <div class="row">
            <div class="col-12">
               <?= $this->session->flashdata('message'); ?>
            </div>
         </div>
      </div>
   <body>
      <!-- jQuery -->
      <script type="text/javascript" src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
      <!-- Bootstrap 4 -->
      <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- AdminLTE App -->
      <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
      <!-- AdminLTE for demo purposes -->
      <script src="<?= base_url('assets') ?>/dist/js/demo.js"></script>
   </body>
</html>