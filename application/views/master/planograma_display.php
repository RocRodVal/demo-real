		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
		        <div class="col-lg-12">
		        	<h1 class="page-header"><?php echo $title ?>
						<a href="<?=site_url('master/dashboard')?>" class="btn btn-danger right">Volver</a>
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
                            PLANOGRAMA MUEBLE
                        </div>
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