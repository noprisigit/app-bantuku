<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link href="https://fonts.googleapis.com/css2?family=Concert+One&family=Noto+Sans+HK:wght@400;500&display=swap" rel="stylesheet">

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
				<td align="center" style="padding: 10px 20px 10px 20px; background-image: radial-gradient(#00C6FF, #0072FF)">
					<img src="http://bantuku2020.babelprov.go.id/assets/dist/img/bantuku.png" alt="Bantuku" width="360"">
				</td>
			</tr>
         <tr>
            <td align="center" style="padding: 20px 0 10px 0;">
               <h1 style="color: black; margin-top: 0; margin-bottom: 5px; font-size: 40px; font-family: 'Concert One', cursive; letter-spacing: 3px;">TERIMA KASIH</h1>
					<h3 style="color: black; margin: 10px 0 10px 0; font-weight: 400;">Anda telah berbelanja dengan aplikasi bantuku.</h4>
            </td>
         </tr>
         <tr>
            <td style="padding-left: 20px;">
               <h2><?= $subject ?></h2>
            </td>
         </tr>
         <tr>
            <td style="padding-left: 20px;">
               <h4 style="margin-top: 5px; margin-bottom: 5px;">Ringkasan Pembayaran</h4>
            </td>
         </tr>
         <tr>
            <td style="padding-left: 20px; padding-right: 20px;">
               <table width="100%" cellspacing="0" cellpading="0" style="border-collapse: collapse;">
                  <?php
                     $jumlahBarang = 0;
                     $totalBayar = 0;
                     foreach($orders as $item) {
                        $jumlahBarang += $item['Jumlah'];
                        $totalBayar += $item['Bayar'];
                     }
                  ?>
                  <tr>
                     <td style="padding: 8px 0 8px 0;">Total Harga (<?= $jumlahBarang ?> Barang)</td>
                     <td align="right">Rp <?= number_format($totalBayar, 0, ',', '.') ?></td>
                  </tr>
                  <!-- <tr>
                     <td style="padding: 8px 0 8px 0;">Total Ongkos Kirim</td>
                     <td align="right">Rp 30.000</td>
                  </tr> -->
                  <tr style="border-top: 2px solid black;">
                     <td style="font-weight: 600;padding: 8px 0 8px 0;">Total Tagihan</td>
                     <td align="right" style="font-weight: 600;">Rp <?= number_format($totalBayar, 0, ',', '.') ?></td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
            <td style="padding-left: 20px;">
               <h4 style="margin-top: 30px; margin-bottom: 5px;">Rincian Pesanan</h4>
            </td>
         </tr>
         <tr>
            <td style="padding-left: 20px;">
               <h3 style="margin-top: 15px; margin-bottom: 10px; color: #00C6FF;"><?= $invoice ?></h4>
            </td>
         </tr>
         <tr>
            <td style="padding: 0 20px 30px 20px;">
               <table align="center" cellpading="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                  <?php foreach($orders as $row) : ?>
                  <tr>
                     <td style="padding-top: 0; padding-bottom: 0;" width="80%"><?= $row['ProductName'] ?></td>
                     <td style="padding-top: 5px; padding-left: 10px;" align="right" rowspan="2">Rp <?= number_format($row['Bayar'], 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                     <td style="padding-top: 5px; padding-bottom: 15px;"><?= $row['Jumlah'] ?> x Rp <?= number_format($row['Harga'], 0, ',', '.') ?></td>
                  </tr>
                  <?php endforeach; ?>
                  <tr style="border-top: 2px solid black;">
                     <td style="font-weight: 600; padding-top: 10px; padding-bottom: 5px;">Total Pembayaran</td>
                     <td align="right" style="padding-top: 10px; padding-bottom: 5px; padding-left: 10px; font-weight: 600;">Rp <?= number_format($totalBayar, 0, ',', '.') ?></td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
            <tr>
               <td align="center" style="padding: 10px 10px 10px 10px; background-image: radial-gradient(#00C6FF, #0072FF); color: #FFFFFF; font-size: 14px; font-weight: 400; font-family: 'Concert One', cursive;">
                  <span style="float: left;">copyright &copy; 2020. Bantuku</span> <img src="http://bantuku2020.babelprov.go.id/assets/dist/img/bantuku.png" width="64" style="float: right;" alt="bantuku">
               </td>
            </tr> 
         </tr>
      </table>

   </div>
</body>
</html>