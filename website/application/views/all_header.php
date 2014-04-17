<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CGI Challenge</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("static/css/bootstrap.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("static/css/font-awesome.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("static/css/styles-common.css") ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("static/css/styles-dark.css") ?>" id="skin" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("static/css/responsive.css") ?>" />	
<!--[if lt IE 9]>
	<script type="text/javascript">
		alert("Attention, ce site ne fonctionne qu'avec IE 9 et supérieur ou un vrai navigateur.");
	</script>
<![endif]-->
	<script type="text/javascript" src="<?php echo base_url("static/js/base64.js") ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("static/js/jquery-2.0.2.js") ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("static/js/logo.js") ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("static/js/challenge.js") ?>"></script>
</head>
<body>
<?php if (!isset($no_header)) { ?>
	<header class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<h1 class="pull-left">
				<a href="<?php echo base_url() ?>">CGI <i class="icon-rocket icon-large"></i> Challenge</a>
			</h1>
			<div class="logo pull-right">
				<img id="logo_cgi" src="<?php echo base_url("static/images/logo/logo.png") ?>" style="position: absolute; z-index: 1;" />
				<img id="planet_1" src="<?php echo base_url("static/images/logo/planet_1.png") ?>" style="display: none;" />
				<img id="planet_2" src="<?php echo base_url("static/images/logo/planet_2.png") ?>" style="display: none;" />
				<canvas id="logo" width="158" height="100"></canvas>
			</div>
		</div>
	</header>
<?php } ?>
	<div class="container">
		<div class="row-fluid">
			<div class="span9 pull-right">
				<?php // FIXME Utiliser plusieurs sections ?>
				<section>
					<h2><?php 				
	if (isset($page_icon)) { ?><i class="icon-<?php echo $page_icon;?> icon-large"></i>&nbsp;<?php } 
	
?><?php echo isset($page_title) ? $page_title : ""; ?></h2>
