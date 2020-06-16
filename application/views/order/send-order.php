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
      
      <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
   </head>
      <div class="container my-2">         
         <div class="row">
            <div class="col-sm-12 col-md-6">
               <h4 class="text-bold">Detail Customer</h4>
               <table class="table">
                  <tr>
                     <td width="40%" class="text-bold">Invoice</td>
                     <td><?= $order[0]['Invoice'] ?></td>
                  </tr>
                  <tr>
                     <td class="text-bold">Nama Customer</td>
                     <td><?= $order[0]['CustomerName'] ?></td>
                  </tr>
                  <tr>
                     <td class="text-bold">No. Telpon</td>
                     <td><a href="tel:<?= $order[0]['CustomerPhone'] ?>"><?= $order[0]['CustomerPhone'] ?></a></td>
                  </tr>
                  <tr>
                     <td class="text-bold">Alamat</td>
                     <td><?= $order[0]['ShippingAddress'] ?></td>
                  </tr>
                  <tr>
                     <td class="text-bold">Catatan Alamat</td>
                     <td><?= ($order[0]['NoteAddress'] == "" or $order[0]['NoteAddress'] == null) ? "Tidak ada catatan" : $order[0]['NoteAddress'] ?></td>
                  </tr>
               </table>
            </div>
            <div class="col-sm-12 col-md-6">
               <h4 class="text-bold">Detail Toko</h4>
               <table class="table">
                  <tr>
                     <td width="40%" class="text-bold">Nama Toko</td>
                     <td><?= $order[0]['CompanyName'] ?></td>
                  </tr>
                  <tr>
                     <td class="text-bold">Nama Pemilik</td>
                     <td><?= $order[0]['PartnerName'] ?></td>
                  </tr>
                  <tr>
                     <td class="text-bold">No. Telpon</td>
                     <td><a href="tel:<?= $order[0]['Phone'] ?>"><?= $order[0]['Phone'] ?></a></td>
                  </tr>
                  <tr>
                     <td class="text-bold">Alamat</td>
                     <td><?= $order[0]['Address'] . " " . $order[0]['District'] . " " . $order[0]['Province'] . " " . $order[0]['PostalCode'] ?></td>
                  </tr>
               </table>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-12 col-md-12">               
               <table width="100%">
                  <thead>
                     <tr>
                        <th style="vertical-align: middle;">Nama Barang</th>
                        <th style="vertical-align: middle;">Subtotal</th>
                        <th style="vertical-align: middle;">Catatan</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $jumlah = 0;
                        $subTotal = 0; 
                     ?>
                     <?php foreach($order as $row) : ?>
                        <tr>
                           <td width="45%"><?= $row['ProductName'] ?></td>
                           <td width="25%" rowspan="2">Rp <?= number_format($row['OrderTotalPrice'],0,',','.') ?></td>
                           <td rowspan="2"><?= ($row['OrderNote'] == "" || $row['OrderNote'] == null) ? "Tidak ada catatan" : $row['OrderNote'] ?></td>
                        </tr>
                        <tr>
                           <td style="padding-bottom: 8px"><?= $row['OrderProductQuantity'] ?> x Rp <?= number_format($row['ProductPrice'],0,',','.') ?></td>
                        </tr>
                        <?php 
                           $subTotal = $subTotal + $row['OrderTotalPrice']
                        ?>
                     <?php endforeach; ?>
                     <tr>
                        <td class="text-bold">Jumlah</td>
                        <td class="text-bold">Rp <?= number_format($subTotal,0,',','.') ?></td>
                     </tr>
                  </tbody>
               </table>
               <div class="text-center mt-4">               
                  <a href="<?= base_url('order/sendOrder?invoice=') . $order[0]['InvoiceNumber'] .'&customer=' . $order[0]['CustomerUniqueID'] ?>" class="btn btn-primary align-items-center">Antar Pesanan</a>
               </div>
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