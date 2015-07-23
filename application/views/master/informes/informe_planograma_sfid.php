
            <div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h2><?=$subtitle?></h2>
 					<?php
                    if(empty($displays)){
                    	echo '<p>No tenemos resultados para sus datos.</p>';
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

										<a href="<?=site_url('master/informe_planograma_mueble_pds')?>/<?php echo $display->id_pds.'/'.$display->id_displays_pds ?>">
											<div class="caption" title="<?php echo strtoupper($display->display); ?>">
											<img
												src="<?=site_url('application/uploads/'.$display->picture_url.'')?>"
												title="<?php echo strtoupper($display->display) ?>"/>
											</div>
										</a>
									<?php
									}
									else{
										?>
										<a href="<?=site_url('master/informe_planograma_mueble_pds')?>/<?php echo $display->id_pds.'/'.$display->id_display ?>"><?php echo strtoupper($display->display) ?></a><br clear="all" />
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
                                        	<a href="<?=site_url('master/alta_incidencia_mueble')?>/<?php echo $display->id_pds.'/'.$denuncia.'/'.$display->id_display ?>"><?php echo $display->display ?></a><br clear="all" />
                                        	<?php 
                                        	if ($display->picture_url != '')
                                        	{	
                                        	?>
                                        	<a href="<?=site_url('master/alta_incidencia_mueble')?>/<?php echo $display->id_pds.'/'.$denuncia.'/'.$display->id_display ?>"><img src="<?=site_url('application/uploads/'.$display->picture_url.'')?>" title="<?php echo $display->display ?>" width="200" /></a>
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