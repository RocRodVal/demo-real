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
                <div class="col-lg-3 col-md-6">
                	<p><a href="<?=site_url('admin/alta_incidencia/'.$id_pds_url)?>"><button type="button" class="btn btn-primary btn-lg" style="width:200px;">Incidencia</button></a></p>
                </div>
                <!-- 
                <div class="col-lg-3 col-md-6">		
                	<p><a href="<?=site_url('admin/alta_incidencia_sfid_robo')?>"><button type="button" class="btn btn-danger btn-lg" style="width:200px;">Robo</button></a></p>
                </div>
                -->
            </div> 
            <div class="row">
                <div class="col-lg-12">
 					<?php
                    if(empty($incidencias)){
                    	echo '<p>No hay incidencias.</p>';
                    }
                    else
                    {					
 					?>
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Seleccione la incidencia sobre la que operar.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Referencia</th>
                                            <th>Fecha</th>
                                            <th>SFID</th>
                                            <th>Incidencia</th>
                                            <th>Contacto</th>
                                            <th>Teléfono</th>
                                            <th>Email</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($incidencias as $incidencia)
    									{
    									?>
    									<tr>
    										<td><?php echo $incidencia->id_incidencia?></td>
    										<td><?php echo $incidencia->fecha ?></td>
    										<td><?php echo $incidencia->reference ?></td>
    										<td><?php echo $incidencia->description ?></td>
    										<td><?php echo $incidencia->contacto ?></td>
    										<td><?php echo $incidencia->phone ?></td>
    										<td><?php echo $incidencia->email ?></td>
    										<td><?php echo $incidencia->status_pds ?></td>
    									</tr>
					    				<?php
					    				}
					    				?>
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
