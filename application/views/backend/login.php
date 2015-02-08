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
	                        <form action="<?=site_url('admin/');?>" method="post" class="content_auto form_login">
	                        	<div id="infoMessage"><?php echo $message;?></div>
	                            <fieldset>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="SFID" name="sfid" type="text" value="<?=$this->form_validation->set_value('sfid')?>">
	                                </div>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="contraseña" name="password" type="password" value="<?=$this->form_validation->set_value('password')?>">
	                                </div>
	                                <p><a href="">¿Olvidaste la contraseña?</a></p>
	                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login" />
	                            </fieldset>
	                        </form>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>    
	    </div>
		<!-- /#container -->
		