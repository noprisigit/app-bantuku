<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link href="https://fonts.googleapis.com/css2?family=Concert+One&family=Noto+Sans+HK:wght@400;500&display=swap"
      rel="stylesheet">

   <style>
      .container {
         margin: auto;
         padding: 5px;
         border: 1px solid #0077b6;
         border-radius: 5px;
         width: 600px;
      }
   </style>
</head>

<body style="margin: 0; padding: 0; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">
   <div class="container">

      <table align="center" cellpading="0" cellspacing="0" width="600" style="border-collapse: collapse;">
         <tr>
            <td align="center"
               style="padding: 10px 20px 10px 20px; background-image: radial-gradient(#00C6FF, #0072FF)">
               <img src="http://bantuku2020.babelprov.go.id/assets/dist/img/bantuku.png" alt="Bantuku" width="360"">
				</td>
			</tr>
         <!-- <tr>
            <td align=" center" style="padding: 20px 0 10px 0;">
               <h1
                  style="color: black; margin-top: 0; margin-bottom: 5px; font-size: 40px; font-family: 'Concert One', cursive; letter-spacing: 3px;">
                  TERIMA KASIH</h1>
               <h3 style="color: black; margin: 10px 0 10px 0; font-weight: 400;">Anda telah berbelanja dengan aplikasi
                  bantuku.</h4>
            </td>
         </tr> -->
         <tr>
            <td style="padding-left: 20px;">
               <h2>Detail Pesanan</h2>
            </td>
         </tr>
         <tr>
            <td style="padding-left: 20px; padding-right: 20px;">
               <table width="100%" cellspacing="0" cellpading="0" style="border-collapse: collapse;">
                  <tr>
                     <td style="padding: 8px; font-weight: bold;" width="30%">Invoice</td>
                     <td style="padding: 8px;"><?= $orders[0]['Invoice'] ?></td>
                  </tr>
                  <tr>
                     <td style="padding: 8px; font-weight: bold;">Nama Customer</td>
                     <td style="padding: 8px;"><?= $orders[0]['CustomerName'] ?></td>
                  </tr>
                  <tr>
                     <td style="padding: 8px; font-weight: bold;">No. Telpon</td>
                     <td style="padding: 8px;"><?= $orders[0]['CustomerPhone'] ?></td>
                  </tr>
                  <tr>
                     <td style="padding: 8px; font-weight: bold;">Alamat</td>
                     <td style="padding: 8px;"><?= $orders[0]['CustomerAddress1'] ?></td>
                  </tr>
               </table>
            </td>
         </tr>
         <!-- <tr>
            <td style="padding-left: 20px; padding-top: 30px;">
               <h4 style="margin-top: 5px; margin-bottom: 5px;">Ringkasan Pembayaran</h4>
            </td>
         </tr> -->
         <!-- <tr>
            <td style="padding-left: 20px; padding-right: 20px;">
               <table width="100%" cellspacing="0" cellpading="0" style="border-collapse: collapse;">
                  <tr>
                     <td style="padding: 8px 0 8px 0;">Total Harga (6 Barang)</td>
                     <td align="right">Rp 30.000</td>
                  </tr>
                  <tr>
                     <td style="padding: 8px 0 8px 0;">Total Ongkos Kirim</td>
                     <td align="right">Rp 30.000</td>
                  </tr>
                  <tr style="border-top: 2px solid black;">
                     <td style="font-weight: 600;padding: 8px 0 8px 0;">Total Tagihan</td>
                     <td align="right" style="font-weight: 600;">Rp 33.000</td>
                  </tr>
               </table>
            </td>
         </tr> -->
         <tr>
            <td style="padding-left: 20px;">
               <h4 style="margin-top: 30px; margin-bottom: 5px;">Rincian Pesanan</h4>
            </td>
         </tr>
         <tr>
            <td style="padding-left: 20px;">
               <h4 style="margin-top: 15px; margin-bottom: 10px; color: #00C6FF;"><?= $orders[0]['Invoice'] ?></h4>
            </td>
         </tr>
         <tr>
            <td style="padding: 0 20px 0 20px;">
               <table align="center" cellpading="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                  <?php $subTotal = 0; ?>
                  <?php foreach($orders as $row) : ?>
                     <tr>
                        <td style="padding-top: 0; padding-bottom: 0;" width="80%"><?= $row['ProductName'] ?></td>
                        <td style="padding-top: 5px; padding-left: 10px;" align="right" rowspan="2">Rp <?= number_format($row['OrderTotalPrice']) ?></td>
                     </tr>
                     <tr>
                        <td style="padding-top: 5px; padding-bottom: 15px;"><?= $row['OrderProductQuantity'] ?> x Rp <?= number_format($row['ProductPrice']) ?></td>
                     </tr>
                     <?php $subTotal = $subTotal + $row['OrderTotalPrice'] ?>
                  <?php endforeach; ?>
                  <tr style="border-top: 2px solid black;">
                     <td style="font-weight: 600; padding-top: 10px; padding-bottom: 5px;">Total Pembayaran</td>
                     <td align="right" style="padding-top: 10px; padding-left: 10px; font-weight: 600;">Rp <?= number_format($subTotal) ?></td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
      <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px;">
         <tr>
            <td align="center"
               style="padding: 10px 10px 10px 10px; background-image: radial-gradient(#00C6FF, #0072FF); color: #FFFFFF; font-size: 14px; font-weight: 400;">
               <span style="float: left;">copyright &copy; 2020. Bantuku</span> <img src="http://bantuku2020.babelprov.go.id/assets/dist/img/bantuku.png" width="64"
                  style="float: right;" alt="bantuku">
            </td>
         </tr>
      </table>
   </div>
</body>

</html>