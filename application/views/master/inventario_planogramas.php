		<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
		        </div>
		    </div>
		    
            <div class="row">
                <div class="col-lg-12">
                	<form action="<?=site_url('master/inventarios_planogramas');?>" method="post" class="form-inline form-sfid">
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
				<div class="col-lg-6">
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
										?>
										<p><?php echo $device->position.'. '.$device->device ?></p>
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
