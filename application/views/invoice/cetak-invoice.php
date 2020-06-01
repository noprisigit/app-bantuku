<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <title>Bantuku | Cetak Invoice</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <!-- Bootstrap 4 -->

      <!-- Favicon -->
      <link rel="shorcut icon" href="<?= base_url('assets') ?>/dist/img/icon.ico">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css" />
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
      <!-- Theme style -->
      <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css" />

      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
   </head>
   <body>
      <div class="wrapper">
         <!-- Main content -->
         <section class="invoice">
            <!-- title row -->
            <div class="row">
               <div class="col-12">
                  <h2 class="page-header">
                     <img src="<?= base_url('assets/dist/img/logo.png') ?>" alt="Logo-Bantuku" width="50"> Bantuku.
                     <small class="float-right">Nomor Invoice: <strong id="invoiceNumber"><?= $invoice[0]['Invoice'] ?></strong></small>
                  </h2>
               </div>
               <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
               <div class="col-sm-6 invoice-col">
                  Dari
                  <address>
                     <strong>Bantuku.</strong><br />
                     
                     Phone: (804) 123-5432<br />
                     Email: support@bantuku2020.babelprov.go.id
                  </address>
               </div>
               <!-- /.col -->
               <div class="col-sm-6 invoice-col">
                  Kepada
                  <address>
                  <span><strong id="customerName"><?= $invoice[0]['CustomerName'] ?></strong></span><br />
                  <span id="customerAddress"><?= $invoice[0]['CustomerAddress1'] ?></span> <br />
                  <span id="customerPhone">Phone: <?= $invoice[0]['CustomerPhone'] ?></span> <br />
                  <span id="customerEmail">Email: <?= $invoice[0]['CustomerEmail'] ?></span>
                  </address>
               </div>
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
               <div class="col-12 table-responsive">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>Nama Barang</th>
                           <th class="text-center">Jumlah</th>
                           <th class="text-center">Berat</th>
                           <th class="text-center">Harga Barang</th>
                           <th class="text-center">Subtotal</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $billTotal = 0;
                           $tax = 0.1;
                           foreach($invoice as $row) : 
                              $billTotal += $row['OrderTotalPrice'];
                        ?>
                           
                           <tr>
                              <td><?= $row['ProductName'] ?></td>
                              <td class="text-center"><?= $row['OrderProductQuantity'] ?></td>
                              <td class="text-center"><?= $row['ProductWeight'] ?></td>
                              <td class="text-center">Rp <?= number_format($row['ProductPrice'],0,',','.') ?></td>
                              <td class="text-center">Rp <?= number_format($row['OrderTotalPrice'],0,',','.') ?></td>
                           </tr>
                        <?php 
                           endforeach; 
                           $pajak = $billTotal * $tax;
                           $grandTotal = $billTotal + $pajak;
                        ?>
                     </tbody>
                  </table>
               </div>
               <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
               <!-- accepted payments column -->
               <div class="col-6">
                  <!-- <p class="lead">Payment Methods:</p>
                  <img src="../../dist/img/credit/visa.png" alt="Visa" />
                  <img src="../../dist/img/credit/mastercard.png" alt="Mastercard" />
                  <img src="../../dist/img/credit/american-express.png" alt="American Express" />
                  <img src="../../dist/img/credit/paypal2.png" alt="Paypal" />

                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                     Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                  </p> -->
               </div>
               <!-- /.col -->
               <div class="col-6">
                  <p class="lead">Tanggal: <?= $tgl[2] . "/" . $tgl[1] . "/" . $tgl[0]?></p>

                  <div class="table-responsive">
                     <table class="table">
                        <tr>
                           <th style="width: 50%;">Subtotal:</th>
                           <td>Rp <?= number_format($billTotal,0,',','.'); ?></td>
                        </tr>
                        <tr>
                           <th>Pajak (10%)</th>
                           <td>Rp <?= number_format($pajak,0,',','.'); ?></td>
                        </tr>
                        <!-- <tr>
                           <th>Shipping:</th>
                           <td>$5.80</td>
                        </tr> -->
                        <tr>
                           <th>Total:</th>
                           <td>Rp <?= number_format($grandTotal,0,',','.'); ?></td>
                        </tr>
                     </table>
                  </div>
               </div>
               <!-- /.col -->
            </div>
            <!-- /.row -->
         </section>
         <!-- /.content -->
      </div>
      <!-- ./wrapper -->

      <script type="text/javascript">
         window.addEventListener("load", window.print());
      </script>
   </body>
</html>
