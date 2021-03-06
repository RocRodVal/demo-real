		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?>
		            	<a onclick="history.go(-1);return false;" class="btn btn-danger right">Volver</a>
		            </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
 					<?php
                    if(empty($displays)){
                    	echo '<p>No hay resultados para estos datos.</p>';
                    }
                    else
                    {					
 					?> 
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Seleccione mueble
                        </div>
						<div class="panel-body">
							<div class="row">
							<?php
							$counter = 0;
							$sep     = array(6,12,18,24);
							foreach($displays as $display){
								if($display->devices_count != 0){
									$counter++; 
									?>
									<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 textoColumna">
									<?php
									if ($display->picture_url != '')
									{
									?>
									<a href="<?=site_url('tienda/alta_incidencia_mueble'.'/'.$display->id_displays_pds) ?>">
										<div class="caption">
											<img src="<?=site_url('application/uploads/'.$display->picture_url.'')?>" title="<?php echo strtoupper($display->display) ?>"/>
										</div>
									</a>
									<?php
									}
									else{
									?>
									<a href="<?=site_url('tienda/alta_incidencia_mueble/'.$display->id_displays_pds) ?>"><?php echo strtoupper($display->display); ?></a>
									<?php
									}
									?>
									</div>
								<?php
									if (in_array($counter, $sep))
									{
										$counter = 0;
										echo '<br clear="all" />';
									}		
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
        </div>
        <!-- /#page-wrapper -->
		<?php $this->load->view('common/modal_alert');?>