		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
		        <div class="col-lg-12">
		        	<h1 class="page-header"><?php echo $title ?></h1>
		        </div>
            </div>
    		<div class="row">
		        <div class="col-lg-12">
		            <?php
		            if (empty($incidencias)) {
		                echo '<p>No hay incidencias.</p>';
		            } else {
		                ?>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Ref.</th>
		                            <th>SFID</th>
		                            <th>Fecha alta</th>
		                            <th>Elemento afectado</th>
		                            <th>Sistema general de seguridad</th>
		                            <th>Dispositivo</th>
		                        	<th>Alarma dispositivo cableado</th>
		                            <th>Soporte sujección</th>
		                            <th>Tipo incidencia</th>
		                            <th>Estado</th>
		                            <th>Chat offline</th>
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
		                            <tr onClick="window.location.href='<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_incidencia)?>'">
		                                <td><a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_incidencia)?>"><?php echo $incidencia->id_incidencia ?></a></td>
		                            <?php
		                            }
		                            ?>	                            
		                                <td><?php echo $sfid ?></td>
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
		                            	if ((!isset($incidencia->device['device'])) && (!isset($incidencia->display['display'])))
			                            {
			                            ?>	
			                            <td><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"></i></strong></td>
			                            <?php
			                            }
			                            else
			                            {
			                            ?>	
		                                <td><a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_incidencia)?>#chat"><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"></i></strong></a></td>
			                            
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