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
                     <td><?= $order[0]['CustomerPhone'] ?></td>
                  </tr>
                  <tr>
                     <td class="text-bold">Alamat</td>
                     <td><?= $order[0]['ShippingAddress'] ?></td>
                  </tr>
                  <tr>
                     <td class="text-bold">Catatan Alamat</td>
                     <td><?= $order[0]['NoteAddress'] ?></td>
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
                     <td><?= $order[0]['Phone'] ?></td>
                  </tr>
                  <tr>
                     <td class="text-bold">Alamat</td>
                     <td><?= $order[0]['Address'] . " " . $order[0]['District'] . " " . $order[0]['Province'] . " " . $order[0]['PostalCode'] ?></td>
                  </tr>
               </table>
            </div>
         </div>
         <div class="row">
            <div class="col-12">               
               <table class="table mt-4">
                  <thead>
                     <tr>
                        <th class="p-1" style="vertical-align: middle; text-align: center;">Nama Barang</th>
                        <th class="p-1" style="vertical-align: middle; text-align: center;">Jumlah</th>
                        <th class="p-1" style="vertical-align: middle; text-align: center;">Harga Barang</th>
                        <th class="p-1" style="vertical-align: middle; text-align: center;">Subtotal</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $jumlah = 0;
                        $subTotal = 0; 
                     ?>
                     <?php foreach($order as $row) : ?>
                        <tr>
                           <td width="30%" class="p-1"><?= $row['ProductName'] ?></td>
                           <td class="text-center p-1"><?= $row['OrderProductQuantity'] ?></td>
                           <td class="p-1">Rp <?= number_format($row['ProductPrice'],0,',','.') ?></td>
                           <td class="p-1">Rp <?= number_format($row['OrderTotalPrice'],0,',','.') ?></td>
                        </tr>
                        <?php 
                           $jumlah = $jumlah + $row['OrderProductQuantity']; 
                           $subTotal = $subTotal + $row['OrderTotalPrice']
                        ?>
                     <?php endforeach; ?>
                     <tr>
                        <td class="text-bold p-1">Jumlah</td>
                        <td class="text-bold p-1 text-center"><?= $jumlah ?></td>
                        <td></td>
                        <td class="text-bold p-1">Rp <?= number_format($subTotal,0,',','.') ?></td>
                     </tr>
                  </tbody>
               </table>
               <div class="text-center">               
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