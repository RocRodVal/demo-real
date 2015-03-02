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
				<div class="col-lg-6">
 					<?php
                    if(empty($devices)){
						echo '<p>No hay resultados para estos datos.</p>';
                    }
                    else
                    {					
 					?>                
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Dispositivos
                        </div>
                        <div class="panel-body">
							<div class="row">
								<div class="col-lg-6">
									<div class="list-group">
										<?php
										foreach($devices as $device)
										{
										?>
										<a class="list-group-item" href="#" data-toggle="modal" data-target="#myModal-<?php echo $device->position;?>" >
											<?php echo $device->position.'. '.$device->device ?>
										</a>
			                            <!-- modal -->
			                            <div class="modal fade" id="myModal-<?php echo $device->position;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			                                <div class="modal-dialog">
			                                    <div class="modal-content">
			                                        <div class="modal-header">
			                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			                                            <h4 class="modal-title" id="myModalLabel"><?php echo $device->position.'. '.$device->device ?></a></h4>
			                                        </div>
			                                        <div class="modal-body">
			                                        </div>
			                                        <div class="modal-footer">
			                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			                                        </div>
			                                    </div>
			                                    <!-- /.modal-content -->
			                                </div>
			                                <!-- /.modal-dialog -->
			                            </div>
			                            <!-- /.modal -->											
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