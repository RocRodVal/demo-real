<!-- #header -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= lang('comun.titulo') ?> &gt; <?php if (isset($title)): ?><?= $title ?><?php else: ?>CMS<?php endif; ?></title>
    <link href="<?= site_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/plugins/metisMenu/metisMenu.min.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/plugins/timeline.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/sb-admin-2.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab|Roboto' rel='stylesheet' type='text/css'>
    <link href="<?= site_url('assets/css/orange.css') ?>" rel="stylesheet">
    <?= Xcrud::load_css(); ?>
    <?= Xcrud::load_js(); ?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="<?= site_url('favicon.ico') ?>">
    <!-- Carga de los JS/CSS dependiendo de donde estemos-->
    <link href="<?= site_url('assets/css/dashboard/dashboard_pds.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/dashboard/dashboard.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/intervencion/modal_intervencion.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/plugins/tooltipster/tooltipster.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/incidencia/operar_incidencia.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/intervencion/modal_intervencion.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/incidencia/alta_incidencia.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/incidencia/alta_incidencia.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/plugins/fileInput/fileinput.css"') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/incidencia/alta_robo.css"') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/intervencion/intervencion.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/intervencion/modal_intervencion.css') ?>" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/Highcharts/highcharts.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/Highcharts/modules/exporting.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/Highcharts/highcharts-3d.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/growl/bootstrap-growl.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/fileInput/fileinput.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/tooltipster/jquery.tooltipster.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/intervencion/addIntervencion.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/intervencion/intervencion.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/dashboard/dashboard.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/dashboard/dashboard_pds.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/incidencia/alerta.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/incidencia/alta_robo.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/incidencia/formulario_incidencia.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-tooltip.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-confirmation.js" type="text/javascript"></script>
</head>

<?php
$login = $this->uri->segment(2);
if (empty($login)) { echo '<body class="login">'; } 
else { echo '<body>'; }
?>

<!-- /#header -->

<!-- #wrapper -->
<?php
if (empty($login)) { echo '<div id="login">'; } 
else { echo '<div id="wrapper">'; } 
?>
