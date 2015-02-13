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
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab|Roboto' rel='stylesheet' type='text/css'>
    <link href="<?=site_url('assets/css/orange.css')?>" rel="stylesheet">
    <?=Xcrud::load_css();?> 
    <?=Xcrud::load_js();?>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link rel="shortcut icon" href="<?=site_url('favicon.ico')?>">
    <!-- Carga de los JS/CSS dependiendo de donde estemos-->
    <?php if($this->uri->segment(2)==="dashboard"){?>
        <link href="<?=site_url('assets/css/dashboard/dashboard.css')?>" rel="stylesheet">
        <link href="<?=site_url('assets/css/intervencion/modal_intervencion.css')?>" rel="stylesheet">
        <script src="<?php echo base_url();?>assets/js/plugins/dataTables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/plugins/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/plugins/Highcharts/highcharts.js"></script>
        <script src="<?php echo base_url();?>assets/js/plugins/Highcharts/modules/exporting.js"></script>
        <script src="<?php echo base_url();?>assets/js/plugins/Highcharts/highcharts-3d.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/intervencion/addIntervencion.js"></script>
        <script src="<?php echo base_url();?>assets/js/dashboard/dashboard.js"></script>
    <?php }?>
    <?php if($this->uri->segment(2)==="dashboard_pds"){?>
        <link href="<?=site_url('assets/css/dashboard/dashboard_pds.css')?>" rel="stylesheet">
        <link href="<?=site_url('assets/css/plugins/tooltipster/tooltipster.css')?>" rel="stylesheet">
        <script src="<?php echo base_url();?>assets/js/plugins/tooltipster/jquery.tooltipster.js"></script>
        <script src="<?php echo base_url();?>assets/js/dashboard/dashboard_pds.js"></script>
    <?php }?>
    <?php if($this->uri->segment(2)==="operar_incidencia"){?>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/intervencion/addIntervencion.js"></script>
        <link href="<?=site_url('assets/css/incidencia/operar_incidencia.css')?>" rel="stylesheet">
        <link href="<?=site_url('assets/css/intervencion/modal_intervencion.css')?>" rel="stylesheet">
        <script src="<?php echo base_url();?>assets/js/plugins/dataTables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/plugins/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
    <?php }?>
    <?php if($this->uri->segment(2)==="alta_incidencia" || $this->uri->segment(2)==="alta_incidencia_mueble" ||
            $this->uri->segment(2)==="alta_incidencia_device"){?>
        <link href="<?=site_url('assets/css/incidencia/alta_incidencia.css')?>" rel="stylesheet">
        <script src="<?php echo base_url();?>assets/js/plugins/growl/bootstrap-growl.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/incidencia/alerta.js" type="text/javascript"></script>
    <?php }?>
    <?php if($this->uri->segment(2)==="alta_incidencia_robo"){?>
        <link href="<?=site_url('assets/css/plugins/fileInput/fileinput.css"')?>" rel="stylesheet">
        <link href="<?=site_url('assets/css/incidencia/alta_robo.css"')?>" rel="stylesheet">
        <script src="<?php echo base_url();?>assets/js/plugins/fileInput/fileInput.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/incidencia/alta_robo.js" type="text/javascript"></script>
    <?php }?>
    <?php if($this->uri->segment(2)==="alta_incidencia_device"){?>
        <script src="<?php echo base_url();?>assets/js/incidencia/formulario_incidencia.js" type="text/javascript"></script>
    <?php }?>


    <?php if($this->uri->segment(1)==="intervencion"){?>
        <link href="<?=site_url('assets/css/intervencion/intervencion.css')?>" rel="stylesheet">
        <link href="<?=site_url('assets/css/intervencion/modal_intervencion.css')?>" rel="stylesheet">
        <script src="<?php echo base_url();?>assets/js/plugins/dataTables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/plugins/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/bootstrap-tooltip.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/bootstrap-confirmation.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/intervencion/intervencion.js" type="text/javascript"></script>
    <?php }?>
</head>  

<body>
<!-- /#header -->

	<!-- #wrapper -->
	<div id="wrapper-login">
