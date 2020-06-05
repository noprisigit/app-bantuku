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
			padding: 10px;
			border: 1px solid #0077b6;
			border-radius: 5px;
			width: 600px;
		}
	</style>
</head>
<body style="margin: 0; padding: 0; font-family: 'Noto Sans HK', sans-serif; font-weight: 400;">
	<div class="container">
		<table align="center" cellpading="0" cellspacing="0" width="600" style="border-collapse: collapse;">
			<tr>
				<td align="center" style="padding: 10px 20px 10px 20px; background-image: radial-gradient(#00C6FF, #0072FF)">
					<img src="http://bantuku2020.babelprov.go.id/assets/dist/img/bantuku.png" alt="Bantuku" width="360"">
				</td>
			</tr>
			<tr>
				<td align="center" style="padding: 30px 0 30px 0;">
					<h1 style="color: black; margin-top: 0; margin-bottom: 5px; font-size: 40px; font-family: 'Concert One', cursive; letter-spacing: 3px;">TERIMA KASIH</h1>
					<h4 style="color: black; margin: 10px 0 10px 0; font-weight: 400;">Anda sudah terdaftar pada aplikasi bantuku.</h4>
				</td>
			</tr>
			<tr>
				<td bgcolor="#FFFFFF" style="padding: 10px 20px 10px 20px;">
					<table cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px;">
						<tr>
							<td align="center" colspan="2" style="background-image: radial-gradient(#00C6FF, #0072FF); padding: 10px 0 10px 0; font-size: 22px; color: #FFFFFF; font-family: 'Concert One', cursive;">Detail Pelanggan</td>
						</tr>
						<tr>
							<td  style="padding: 5px 0 5px 5px;">Nama Customer</td>
							<td>: <?= $customer['CustomerName'] ?></td>
						</tr>
						<tr>
								<td style="padding: 5px 0 5px 5px;">Jenis Kelamin</td>
								<td>: <?= $customer['CustomerGender'] ?></td>
						</tr>
						<tr>
								<td style="padding: 5px 0 5px 5px;">Email</td>
								<td>: <?= $customer['CustomerEmail'] ?></td>
						</tr>
						<tr>
								<td style="padding: 5px 0 5px 5px;">No. Telp</td>
								<td>: <?= $customer['CustomerPhone'] ?></td>
						</tr>
						<tr>
								<td style="padding: 5px 0 5px 5px;">Alamat</td>
								<td>: <?= $customer['CustomerAddress1'] ?></td>
						</tr>
						<tr>
							<td align="center" colspan="2" style="padding: 10px 0 10px 0;">Silahkan masukkan kode berikut untuk memverifikasi akun anda.</td>
						</tr>
						<tr>
							<td align="center" colspan="2" style="padding: 10px 0 10px 0;">
								<span style="border-radius: 5px;padding: 3px; background-image: radial-gradient(#0072FF, #00C6FF); font-size: 18px; color: #FFFFFF;"><?= $customer['CustomerVerificationCode'] ?></span>  
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="padding: 10px 10px 10px 10px; background-image: radial-gradient(#00C6FF, #0072FF); color: #FFFFFF; font-size: 14px; font-weight: 400; font-family: 'Concert One', cursive;">
					<span style="float: left;">copyright &copy; 2020. Bantuku</span> <img src="http://bantuku2020.babelprov.go.id/assets/dist/img/bantuku.png" width="64" style="float: right;" alt="bantuku">
				</td>
			</tr>
		</table>
	</div>
</body>
</html>