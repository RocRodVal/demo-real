		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                	<h3>Datos puntos de venta</h3>
                	<p>
	                	<strong>SFID:</strong> <?php echo $reference ?> [<?php echo $id_pds ?>]<br />
	                	<strong>Nombre comercial:</strong> <?php echo $commercial ?><br />
						<strong>Dirección:</strong> <?php echo $address ?>, <?php echo $zip ?> -  <?php echo $city ?><br />
	                	<strong>Zona:</strong> <?php echo $territory ?>
            	</div>
            </div>              
            <div class="row">
                <div class="col-lg-12">
 					<?php
                    if(empty($devices)){
                    	echo '<p>No tenemos resultados para sus datos.</p>';
                    }
                    else
                    {					
 					?>                
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Seleccione el dispositivo de la incidencia.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                           	<td width="250px;">
                                        	<p><strong>Terminales</strong></p>
                                        	<ol>
					                            <?php 
					   							foreach($devices as $device)
					    						{
					    						?>
					    						<li><a href="<?=site_url('admin/alta_incidencia_device/'.$id_pds_url.'/'.$denuncia.'/'.$id_dis_url.'/'.$device->id_device)?>"><?php echo $device->device ?></a></li>
										    	<?php
										    	}
										    	?>
                                        	</ol>
                                        	</td>
                                           	<td>
                                           		<strong><?php echo $display ?><br clear="all" />
                                           		<?php 
                                        		if ($picture_url != '')
                                        		{	
                                        		?>
                                           		<img src="<?=site_url('application/uploads/'.$picture_url.'')?>" title="<?php echo $display ?>" />
                                           		<?php 
                                        		}
                                           		?>
                                           	</td>
                                        </tr>                                    
                                    </tbody>
                                </table>
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