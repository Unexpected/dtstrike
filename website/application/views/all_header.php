<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Six Challenge</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("static/css/bootstrap.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("static/css/font-awesome.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("static/css/styles-dark.css") ?>" id="skin" />
	<script type="text/javascript" src="<?php echo base_url("static/js/jquery-2.0.2.js") ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("static/js/challenge.js") ?>"></script>
</head>
<body>
	<header class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<h1 class="pull-left">
				<a href="<?php echo base_url() ?>">SIX <i class="icon-rocket icon-large"></i> Challenge</a>
			</h1>
			<div class="logo pull-right">
				<img id="logo_fixed" src="<?php echo base_url("static/images/logo/logo_six_full.png") ?>" style="display: none;" />
				<img id="logo_front" src="<?php echo base_url("static/images/logo/logo_six_front.png") ?>" style="display: none;" />
				<img id="logo_back_1" src="<?php echo base_url("static/images/logo/logo_six_back_1.png") ?>" style="display: none;" />
				<img id="logo_back_2" src="<?php echo base_url("static/images/logo/logo_six_back_2.png") ?>" style="display: none;" />
				<img id="logo_back_3" src="<?php echo base_url("static/images/logo/logo_six_back_3.png") ?>" style="display: none;" />
				<img id="logo_back_4" src="<?php echo base_url("static/images/logo/logo_six_back_4.png") ?>" style="display: none;" />
				<img id="logo_back_5" src="<?php echo base_url("static/images/logo/logo_six_back_5.png") ?>" style="display: none;" />
				<canvas id="logo" width="100" height="100"></canvas>
			</div>
		</div>
	</header>
	<div class="container">
		<div class="row-fluid">
			<div class="span9 pull-right">
				<?php // FIXME Utiliser plusieurs sections ?>
				<section>
					<h2><i class="icon-<?php echo $page_icon;?> icon-large"></i> <?php echo $page_title;?></h2>