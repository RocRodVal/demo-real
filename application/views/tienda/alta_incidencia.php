		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?>
		            	<a href="#" onclick="history.go(-1);return false;" class="btn btn-danger right">Volver</a>
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
							foreach($displays as $display){
								if($display->devices_count != 0){
									?>
									<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 textoColumna">
									<?php
									if ($display->picture_url != '')
									{
									?>
									<a href="<?=site_url('tienda/alta_incidencia_mueble'.'/'.$display->id_display) ?>">
										<div class="caption">
											<img src="<?=site_url('application/uploads/'.$display->picture_url.'')?>" title="<?php echo strtoupper($display->display) ?>"/>
										</div>
									</a>
									<?php
									}
									else{
									?>
									<a href="<?=site_url('tienda/alta_incidencia_mueble/'.$display->id_display) ?>"><?php echo strtoupper($display->display); ?>"</a>
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
        </div>
        <!-- /#page-wrapper -->
		<?php $this->load->view('tienda/modal_alert');?>