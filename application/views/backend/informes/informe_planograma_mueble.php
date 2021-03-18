
            
            <?php 
            if (isset($_POST['mueble_plano']))
            {	
            ?>
            <div class="row">
				<div class="col-lg-6">
                    <h2><?=$subtitle?></h2>
 					<?php
                    if ((empty($displays)) || (empty($devices))){
                    	echo '<p>No dispone de terminales <em>Demo Real</em>.</p>';
                    }
                    else
                    {					
 					?> 
 					<div class="panel panel-default">
                        <div class="panel-body">
							<div class="row">
								<div class="col-lg-8">
									<div class="list-group">
										<?php
										foreach($devices as $device)
										{
											//print_r($device);exit;
											if ($device->isDisplay==true) {?>
												<p style = "box-shadow: 1px 1px 10px #ef0005	">
												<?php } else{
										?>
										<p> <?php } ?>
											<?php echo $device->position.'. '.$device->device ?></p>
										
										
										
										<?php 
										
										
										}
										?>
									</div>
								</div>								
								<div class="col-lg-4">
									<?php
									if ($picture_url != '')
									{
									?>
									<img src="<?=site_url('application/uploads/'.$picture_url.'')?>" title="<?php echo strtoupper($display_name) ?>" style='width:100%'; />
									<?php
									}
									else
									{
									?>
									<p><strong><?php echo strtoupper($display_name); ?></strong></p>
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
        </div>
        <!-- /#page-wrapper -->
