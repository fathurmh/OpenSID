<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$this->setting->admin_title . ' ' . ucwords($this->setting->sebutan_desa) . (($desa['nama_desa']) ? ' ' . $desa['nama_desa']: '') . 'Layanan Mandiri'; ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
	<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
	<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
	<?php endif; ?>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url()?>rss.xml" />
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/font-awesome.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url()?>assets/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. -->
	<link rel="stylesheet" href="<?= base_url()?>assets/css/skins/_all-skins.min.css">
	<!-- Style Mandiri Modification -->
	<link rel="stylesheet" href="<?= base_url()?>assets/css/mandiri-style.css">
	<link rel="stylesheet" href="<?= base_url()?>desa/css/siteman.css">
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>

	<?php $this->load->view('head_tags'); ?>
</head>

<body class="login-page">
	<div class="login-box">
		<div class="login-box-body">
			<div class="login-title">
				<a href="<?=site_url(); ?>"><img src="<?= gambar_desa($header['logo']); ?>" alt="<?=$header['nama_desa']?>" class="logo-login"/></a>
				<h1>
					LAYANAN MANDIRI<br/><?=ucwords($this->setting->sebutan_desa) . ' ' . $header['nama_desa']?>
				</h1>
				<h3>
					<br/><?=$header['alamat_kantor']?><br/>Kodepos <?=$header['kode_pos']?>
					<br/><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$header['nama_kecamatan']?><br/><?=ucwords($this->setting->sebutan_kabupaten)?> <?=$header['nama_kabupaten']?>
				</h3>
			</div>
			<hr/>
			<?php if ($this->session->mandiri_wait == 1): ?>
				<div class="notif-mandiri">
					<p id="countdown"></p>
				</div>
			<?php else: ?>
				<?php $data = $this->session->flashdata('notif'); ?>
				<?php if ($this->session->mandiri_try < 4): ?>
					<div class="callout callout-danger" id="notif">
						<p>NIK atau PIN salah.<br/>Kesempatan mencoba <?= ($this->session->mandiri_try - 1); ?> kali lagi.</p>
					</div>
				<?php endif; ?>
				<form id="validasi" action="<?= $form_action; ?>" method="post" class="form-login">
					<div class="form-group form-login">
						<input type="text" class="form-control required" name="nik" placeholder=" NIK">
					</div>
					<div class="form-group form-login">
						<input type="password" class="form-control required" name="pin" placeholder="PIN" id="pin">
					</div>
					<div class="form-group">
						<center><input type="checkbox" id="checkbox"> Tampilkan PIN</center>
					</div>
					<div class="form-group form-login">
						<button type="submit" class="btn btn-block btn-block bg-green"><b>MASUK</b></button>
					</div>
				</form>
				<p align="center">Silakan datang atau hubungi operator <?= $this->setting->sebutan_desa; ?> untuk mendapatkan kode PIN anda.</p>
			<?php endif; ?>
			<div class="form-login-footer">
				<hr/><a href="https://github.com/OpenSID/OpenSID" target="_blank" rel="noreferrer">OpenSID <?= AmbilVersi() ?></a>
				<br/>
				IP Address :
				<?php if ( ! $cek_anjungan): ?>
					<?= $this->input->ip_address(); ?>
				<?php else: ?>
					<?= $cek_anjungan['ip_address'] . "<br/>Anjungan Mandiri" ?>
					<?= jecho($cek_anjungan['keyboard'] == 1, TRUE, ' | Virtual Keyboard : Aktif'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- jQuery 3 -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="<?= base_url()?>assets/bootstrap/js/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url()?>assets/js/adminlte.min.js"></script>
	<!-- Validasi -->
	<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
	<script src="<?= base_url()?>assets/js/validasi.js"></script>
	<script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			var pass = $("#pin");
			$('#checkbox').click(function() {
				if (pass.attr('type') === "password") {
					pass.attr('type', 'text');
				} else {
					pass.attr('type', 'password')
				}
			});

			if ($('#countdown').length) {
				start_countdown();
			}

			window.setTimeout(function() {
				$("#notif").fadeTo(500, 0).slideUp(500, function(){
					$(this).remove();
				});
			}, 5000);
		});

		function start_countdown() {
			var times = eval(<?= json_encode($this->session->mandiri_timeout)?>) - eval(<?= json_encode(time())?>);
			var menit = Math.floor(times / 60);
			var detik = times % 60;

			timer = setInterval(function() {
				detik--;
				if (detik <= 0 && menit >=1) {
					detik = 60;
					menit--;
				}
				if (menit <= 0 && detik <= 0) {
					clearInterval(timer);
					location.reload();
				} else {
					document.getElementById("countdown").innerHTML = "<b>Gagal 3 kali silakan coba kembali dalam " + menit + " MENIT " + detik + " DETIK </b>";
				}
			}, 500);
		}
	</script>
</body>
</html>
