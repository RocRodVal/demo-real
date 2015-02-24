		<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
		        </div>
		    </div>
		    <div class="row botonera_up">
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('master/descripcion/')?>">
		                <button type="button" class="btn btn-primary btn-accion">Ver DESCRIPCIÓN<br/>tiendas</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('master/inventarios_panelados/')?>">
		                <button type="button" class="btn btn-success btn-accion">Ver PANELADOS<br/>tiendas</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('master/inventarios_planogramas/')?>">
		                <button type="button" class="btn btn-info btn-accion">Ver PLANOGRAMAS<br/>muebles</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('master/inventarios/')?>">
		                <button type="button" class="btn btn-primary btn-accion">Ver INVENTARIOS<br/>tiendas</button>
		            </a>
		        </div>		        
		    </div>  
            <div class="row">
                <div class="col-lg-12">
                	<form action="<?=site_url('master/inventarios_panelados');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <select class="form-control" id="id_panelado" name="id_panelado">
					        <option value="">-- Tipo panelado --</option>
					        <?php foreach ($panelados as $panelado): ?>
					        <option value="<?=$panelado->id_panelado ?>"><?=$panelado->panelado_abx ?> (Orange: <?=$panelado->panelado ?>)</option>
					        <?php endforeach ?>
					        </select>
					        <button type="submit" class="btn btn-default">Buscar</button>  					        
                        </div>
                    </form>
                </div>
            </div>
            <?php 
            if (isset($_POST['id_panelado']))
            {	
            ?>		    
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
 					
 					<?php
                    if(empty($displays)){
                    	echo '<p>No tenemos resultados para sus datos.</p>';
                    }
                    else
                    {					
 					?> 
 					<div class="panel panel-default">
						<div class="panel-body">
							<div class="row">
							<?php
							foreach($displays as $display){
								if($display->devices_count != 0){
									?>
									<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 textoColumna">
									
									<?php
									if ($display->picture_url != '')
									{
										?>

										<a href="#">
											<div class="caption" title="<?php echo $display->display; ?>">
											<img
												src="<?=site_url('application/uploads/'.$display->picture_url.'')?>"
												title="<?php echo $display->display ?>"/>
											</div>
										</a>
									<?php
									}
									else{
										?>
										<a href="#"><?php echo $display->display ?></a><br clear="all" />
									<?php
									}
									?>
									</div>
								<?php
								}
							}
							?>
							</div>
						</div>
                    </div>
                    <?php 
                    }
                    ?>
            	</div>        
            </div> 
            <?php 
            }
            ?>        	            
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $content ?>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
