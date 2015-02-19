		<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
		        </div>
		    </div>
		    <div class="row botonera_up">
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('admin/descripcion/')?>">
		                <button type="button" class="btn btn-primary btn-accion">Ver DESCRIPCIÓN<br/>tiendas</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('admin/inventarios_panelados/')?>">
		                <button type="button" class="btn btn-success btn-accion">Ver PANELADOS<br/>tiendas</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('admin/inventarios_planogramas/')?>">
		                <button type="button" class="btn btn-info btn-accion">Ver PLANOGRAMAS<br/>muebles</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('admin/inventarios/')?>">
		                <button type="button" class="btn btn-primary btn-accion">Ver INVENTARIOS<br/>tiendas</button>
		            </a>
		        </div>		        
		    </div>  
            <div class="row">
                <div class="col-lg-12">
                	<form action="<?=site_url('admin/inventarios_planogramas');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <select class="form-control" id="id_display" name="id_display">
					        <option value="">-- Tipo mueble --</option>
					        <?php foreach ($displays as $display): ?>
					        <option value="<?=$display->id_display ?>"><?=$display->display ?></option>
					        <?php endforeach ?>
					        </select>
					        <button type="submit" class="btn btn-default">Buscar</button>  					        
                        </div>
                    </form>
                </div>
            </div>
            <?php 
            if (isset($_POST['id_display']))
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
								<div class="col-lg-6">
									<div class="list-group">
										<?php
										foreach($devices as $device)
										{
										?>
										<a class="list-group-item" href="#">
											<?php echo $device->device ?></a>
										<?php
										}
										?>
									</div>
								</div>
								<div class="col-lg-6">
									<?php
									if ($picture_url != '')
									{
									?>
									<img src="<?=site_url('application/uploads/'.$picture_url.'')?>" style="width:200px;" />
									<?php
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
