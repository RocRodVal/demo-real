		<!-- #container -->
		<div id="bg">
  			<img src="<?=site_url('assets/images/')?>" alt="">
		</div>		
	    <div class="container">
	        <div class="row">
	            <div class="col-md-4 col-md-offset-4">
	                <div class="login-panel panel panel-default">
	                    <div class="panel-heading">
	                        <center><img src="<?=site_url('assets/images/logo-orange_big.png')?>" title="<?=lang('comun.titulo')?>" width="50%" /></center>
	                    </div>
	                    <div class="panel-body">

                            <?php $this->view('common/login') ?>
                            <?php /*echo validation_errors(); ?>
	                        <form action="<?=site_url('tienda');?>" method="post" class="content_auto form_login">
	                        	<?php 
	                        	if (isset($message)) 
								{ 
	                        	?>
	                        	<div id="infoMessage"><?php echo $message;?></div>
	                            <?php 
								}
	                            ?>
	                            <fieldset>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="SFID hijo" name="sfid-login" type="text" value="<?=$this->form_validation->set_value('sfid-login')?>">
	                                </div>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="contraseña" name="password" type="password" value="<?=$this->form_validation->set_value('password')?>">
	                                </div>
	                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Entrar" />
	                            </fieldset>
	                        </form><?php */ ?>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
		<!-- /#container -->		