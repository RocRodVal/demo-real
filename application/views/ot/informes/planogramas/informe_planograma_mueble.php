
            
            <?php 
            if (isset($_POST['mueble_plano']))
            {	
            ?>
            <div class="row">
				<div class="col-lg-9">
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
								<div class="col-lg-9">
									<div class="list-group">
										<?php
										foreach($devices as $device)
										{
										?>
										<p><?php echo $device->position.'. '.$device->device ?>
										<?php

										if ($device->id_muebledisplay != 0 && $device->id_muebledisplay!=null)
										{
											?>
											<i class="fa fa-spin fa-star-half-o" style="color:orange"></i> - <?=$device->muebledisplayname;?>
										<?php 
										}
										?></p>
										<?php
										}
										?>
									</div>
								</div>								
								<div class="col-lg-3">
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
