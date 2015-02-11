<!-- #header -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?=lang('comun.titulo')?> &gt; <?php if (isset($title)): ?><?=$title?><?php else: ?>CMS<?php endif; ?></title>
    <link href="<?=site_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=site_url('assets/css/plugins/metisMenu/metisMenu.min.css')?>" rel="stylesheet">
    <link href="<?=site_url('assets/css/plugins/timeline.css')?>" rel="stylesheet">
    <link href="<?=site_url('assets/css/sb-admin-2.css')?>" rel="stylesheet">
    <link href="<?=site_url('assets/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab|Roboto' rel='stylesheet' type='text/css'>
    <link href="<?=site_url('assets/css/orange.css')?>" rel="stylesheet">
    <?=Xcrud::load_css();?> 
    <?=Xcrud::load_js();?>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link rel="shortcut icon" href="<?=site_url('favicon.ico')?>">
    <!-- Carga de los CSS dependiendo de donde estemos-->
    <?php if($this->uri->segment(2)==="dashboard"){?>
        <link href="<?=site_url('assets/css/dashboard/dashboard.css')?>" rel="stylesheet">
    <?php }?>
</head>  

<body>
<!-- /#header -->

	<!-- #wrapper -->
	<div id="wrapper-login">
