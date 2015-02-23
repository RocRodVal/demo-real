		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?>
						<a href="<?=site_url('admin/dashboard')?>" class="btn btn-danger right">Volver</a>
					</h1>
                </div>
            </div>
            <div class="row">
				<div class="col-lg-6">
 					<?php
                    if(empty($devices)){
                    	echo '<p>No tenemos resultados para sus datos.</p>';
                    }
                    else
                    {					
 					?>                
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Seleccione dispositivo
                        </div>
                        <div class="panel-body">
							<div class="row">
								<div class="col-lg-8">
									<div class="list-group">
										<?php
										foreach($devices as $device)
										{
										?>
										<a class="list-group-item" href="<?=site_url('tienda/alta_incidencia_dispositivo/'.$id_display.'/'.$device->id_device)?>">
											<?php echo $device->position.'. '.$device->device ?>
										</a>
										<?php
										}
										?>
										<a class="list-group-item" href="<?=site_url('tienda/alta_incidencia_alarma/'.$id_display)?>"> &gt; Afecta al sistema de alarma central &lt; </a>
									</div>
								</div>
								<div class="col-lg-4">
									<?php
									if ($picture_url != '')
									{
									?>
									<img src="<?=site_url('application/uploads/'.$picture_url.'')?>" title="<?php echo $display ?> " style='width:100%'; />
									<?php
									}
									?>
								</div>
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