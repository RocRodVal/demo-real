		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                	<form action="<?=site_url('master/dashboard');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <label>SFID</label>
                            <input class="form-control" placeholder="SFID" name="sfid" id="sfid">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php 
            if (isset($_POST['sfid']))
            {	
            ?>
            <div class="row">
                <div class="col-lg-12">
 					<?php
                    if(empty($tiendas)){
                    	echo '<p>No hay resultados para esa cadena de búsqueda.</p>';
                    }
                    else
                    {					
 					?>
 					<div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>SFID</th>
                                            <th>Tipo</th>
                                            <th>Panelado</th>
                                            <th>Nombre comercial</th>
                                            <th>Territorio</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($tiendas as $tienda)
    									{
    									?>
    									<tr>
    										<td><a href="<?=site_url('master/exp_alta_incidencia/'.$tienda->id_pds)?>"><?php echo $tienda->reference ?></a></td>
    										<td><?php echo $tienda->pds ?></td>
    										<td><?php echo $tienda->panelado ?></td>
    										<td><?php echo $tienda->commercial ?></td>
    										<td><?php echo $tienda->territory ?></td>
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
            <?php 
            }
            ?>                
            <div class="row">
                <div class="col-lg-12">
		            <?php
		            if (empty($incidencias)) {
		                echo '<p>No hay incidencias.</p>';
		            } else {
		                ?>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="table_incidencias_dashboard">
		                        <thead>
		                        <tr>
		                            <th>Ref.</th>
		                            <th>SFID</th>
		                            <th>Fecha alta</th>
		                            <th>Elemento afectado</th>
		                            <th>Sistema general alarma</th>
		                            <th>Dispositivo</th>
		                        	<th>Alarma dispositivo cableado</th>
		                            <th>Soporte sujección</th>
		                            <th>Tipo incidencia</th>
		                            <th>Estado</th>
		                            <th>Fecha cierre</th>		                            
		                            <th>Más info.</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($incidencias as $incidencia) {
									if ((!isset($incidencia->device['device'])) && (!isset($incidencia->display['display'])))
									{
									?>
		                            <tr>
		                                <td><?php echo $incidencia->id_incidencia ?></td>
		                            <?php
		                            }
		                            else
		                            {
		                            ?>	
		                            <tr onClick="window.location.href='<?=site_url('master/detalle_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>'">
		                                <td><a href="<?=site_url('master/detalle_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>"><?php echo $incidencia->id_incidencia ?></a></td>
		                            <?php
		                            }
		                            ?>	
		                                <td><?php echo $incidencia->reference ?></td>
		                                <td><?php echo date_format(date_create($incidencia->fecha), 'd/m/Y'); ?></td>
		                                <?php                               	
		                                if (!isset($incidencia->device['device']))
		                                {
		                                	$dispositivo = 'Retirado';
		                                }
		                                else
		                                {
		                                	$dispositivo = $incidencia->device['device']; 
		                                }
		                                if (!isset($incidencia->display['display']))
		                                {
		                                	$mueble = 'Retirado';
		                                }
		                                else
		                                {
		                                	$mueble = $incidencia->display['display'];
		                                }		                                		
		                                ?>
		                                <td><?=($incidencia->alarm_display==1)?'Mueble: '.$mueble:'Dispositivo: '.$dispositivo?></td>	
		                                <td><?=($incidencia->alarm_display==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->fail_device==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->alarm_device==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->alarm_garra==1)?'&#x25cf;':''?></td>
		                                <td><?php echo $incidencia->tipo_averia ?></td>
		                                <td><strong><?php echo $incidencia->status_pds ?></strong></td>		                                
		                                <?php
		                                if ($incidencia->fecha_cierre <> NULL)
		                                {
		                                ?>
		                                <td><?php echo date_format(date_create($incidencia->fecha_cierre), 'd/m/Y'); ?></td>
		                                <?php 
		                                }
		                                else
		                                {
		                                ?>
		                                <td></td>
		                                <?php
		                                }		                                
		                            	if ((!isset($incidencia->device['device'])) && (!isset($incidencia->display['display'])))
			                            {
			                            ?>	
		                                <td><strong><i class="fa fa-whatsapp chat_leido"></i></strong></td>
			                            <?php
			                            }
			                            else
			                            {
			                            ?>	
		                                <td><a href="<?=site_url('master/detalle_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>#chat"><strong><i class="fa fa-whatsapp chat_leido"></i></strong></a></td>
			                            <?php
			                            }
			                            ?>			                                

		                            </tr>
		                        <?php
		                        }
		                        ?>
		                        </tbody>
		                    </table>
		                </div>
		            <?php
		            }
		            ?>                
            	</div>        
            </div> 
        </div>
        <!-- /#page-wrapper -->

        <?php $this->load->view('backend/intervenciones/ver_intervencion');?>
