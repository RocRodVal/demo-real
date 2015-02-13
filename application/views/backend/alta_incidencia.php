		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?>
						<a href="<?=site_url('admin/dashboard')?>" class="btn btn-danger right">Volver</a>
					</h1>
                </div>
            </div>
			<!--
            <div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							DATOS DEL PUNTO DE VENTA
						</div>
						<div class="panel-body">
							<strong>SFID:</strong> <?php echo $reference ?> [<?php echo $id_pds ?>]<br/>
							<strong>Nombre comercial:</strong> <?php echo $commercial ?><br/>
							<strong>Dirección:</strong> <?php echo $address ?>, <?php echo $zip ?> -  <?php echo $city ?><br/>
							<strong>Zona:</strong> <?php echo $territory ?>
						</div>
					</div>
				</div>


                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="panel panel-alert-orange">
						<div class="panel-body">
							<strong>RECUERDE</strong></br>
							si el mueble ha sido dañado o roto póngase en contacto primero con el equipo de mantenimiento del mismo en <strong>+XX XXX YY ZZ</strong> y una vez
							realizada la intervención proceda a crear la incidencia.
						</div>

					</div>
 				</div>	
            </div>
			-->
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
               	 		<div class="panel-heading">
                            SELECCIONE EL MUEBLE DE LA INCIDENCIA
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

										<a href="<?=site_url('admin/alta_incidencia_mueble')?>/
										<?php echo $display->id_pds.'/'.$denuncia.'/'.$display->id_display ?>">
											<div class='caption' title='<?php echo $display->display; ?>'>
											<img
												src="<?=site_url('application/uploads/'.$display->picture_url.'')?>"
												title="<a href='<?=site_url('admin/alta_incidencia_mueble/'. $display->id_pds.'/'.$denuncia.'/'.$display->id_display)?>'><?php echo $display->display ?></a>"/>
											</div>
										</a>
									<?php
									}
									else{
										?>
										<a href="<?=site_url('admin/alta_incidencia_mueble')?>/
									<?php echo $display->id_pds.'/'.$denuncia.'/'.$display->id_display ?>">
											<?php echo $display->display ?></a><br clear="all" />
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
						<!--
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <?php
                                        $i = 1;
   										foreach($displays as $display)
   										{	
   										if ($i == 1) { echo '<tr>'; }

   										if (($this->tienda_model->count_devices_display($display->id_display)) != 0)
   										{		
    									?>
                                        	<td>
                                        	<a href="<?=site_url('admin/alta_incidencia_mueble')?>/<?php echo $display->id_pds.'/'.$denuncia.'/'.$display->id_display ?>"><?php echo $display->display ?></a><br clear="all" />
                                        	<?php 
                                        	if ($display->picture_url != '')
                                        	{	
                                        	?>
                                        	<a href="<?=site_url('admin/alta_incidencia_mueble')?>/<?php echo $display->id_pds.'/'.$denuncia.'/'.$display->id_display ?>"><img src="<?=site_url('application/uploads/'.$display->picture_url.'')?>" title="<?php echo $display->display ?>" width="200" /></a>
                                        	<?php 
                                        	}
                                        	?>
                                        	</td>
					    				<?php
					    				++$i;
   										}
					    				if ($i % 4 == 0) { echo '</tr></tr>'; }					    				
					    				}
					    				?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        -->
                    </div>
                    <?php 
                    }
                    ?>
            	</div>        
            </div>     	            
        </div>
        <!-- /#page-wrapper -->