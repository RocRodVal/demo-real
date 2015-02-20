		<!-- #container -->
		<div id="bg">
  			<img src="<?=site_url('assets/images/bg-oficina.jpg')?>" alt="">
		</div>		
		
		
	    <div class="container">
	        <div class="row">
	            <div class="col-md-4 col-md-offset-4">
	                <div class="login-panel panel panel-default">
	                    <div class="panel-heading">
	                        <center><img src="<?=site_url('assets/images/logo-orange_big.png')?>" title="<?=lang('comun.titulo')?>" width="50%" /></center>
	                    </div>
	                    <div class="panel-body">
	                        <form action="<?=site_url('tienda');?>" method="post" class="content_auto form_login">
	                        	<div id="infoMessage"><?php echo $message;?></div>
	                            <fieldset>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="SFID" name="sfid" type="text" value="<?=$this->form_validation->set_value('sfid')?>">
	                                </div>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="contraseña" name="password" type="password" value="<?=$this->form_validation->set_value('password')?>">
	                                </div>
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