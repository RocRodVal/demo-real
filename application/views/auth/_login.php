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
    <?=Xcrud::load_css();?> 
    <?=Xcrud::load_js();?>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link rel="shortcut icon" href="<?=site_url('favicon.ico')?>"> 
</head>  

<body>
<!-- /#header -->

	<!-- #wrapper -->
	<div id="wrapper-login">

		<!-- #container -->
		<div id="bg">
  			<img src="<?=site_url('assets/images/bg-'.rand(1, 5).'.jpg')?>" alt="">
		</div>		
		
	    <div class="container">
	        <div class="row">
	            <div class="col-md-3 col-md-offset-10">
	                <div class="login-panel panel panel-default">
	                    <div class="panel-heading">
	                        <center><img src="<?=site_url('assets/images/logo-orange_big.png')?>" title="<?=lang('comun.titulo')?>" width="100%" /></center>
	                    </div>
	                    <div class="panel-body">
	                        <?php echo form_open("auth/login");?>
	                            <fieldset>
	                                <div class="form-group">
	                                	<?php echo lang('login_identity_label', 'identity');?>
    									<?php echo form_input($identity);?>
	                                    <!--<input class="form-control" placeholder="e-mail" name="email" type="email" autofocus>-->
	                                </div>
	                                <div class="form-group">
	                                    <?php echo lang('login_password_label', 'password');?>
    									<?php echo form_input($password);?>
	                                    <!--<input class="form-control" placeholder="contraseña" name="password" type="password" value="">-->
	                                </div>
	                                <p>
	                                <?php echo lang('login_remember_label', 'remember');?>
    								<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
	                                </p>
	                                <p><?php echo form_submit('submit', lang('login_submit_btn'));?></p>
	                            </fieldset>
	                        <?php echo form_close();?>
							<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>    
	    </div>
		<!-- /#container -->

	<!-- #footer -->

    </div>
    <!-- /#wrapper -->
    
    <script src="<?=site_url('assets/js/bootstrap.min.js')?>"></script>
    <script src="<?=site_url('assets/js/plugins/metisMenu/metisMenu.min.js')?>"></script>
    <script src="<?=site_url('assets/js/sb-admin-2.js')?>"></script>
	
</body>
</html>
<!-- /#footer -->