		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
		        <div class="col-lg-12">
		        	<h1 class="page-header"><?php echo $title ?>
						<a href="<?=site_url('tienda/dashboard')?>" class="btn btn-danger right">Volver</a>
					</h1>
		        </div>
            </div>
    		<div class="row botonera_up">
        		<div class="col-lg-6 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
            		<a href="<?=site_url('tienda/alta_incidencia')?>">
                	<button type="button" class="btn btn-primary btn-accion">Nueva ALTA INCIDENCIA</button>
           			</a>
        		</div>
        		<div class="col-lg-6 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
            		<a href="<?=site_url('tienda/planograma')?>">
                		<button type="button" class="btn btn-default btn-accion">Mi  tienda</button>
            		</a>
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
		                            <th>Sistema general alarma</th>
		                            <th>Dispositivo</th>
		                        	<th>Alarma dispositivo cableado</th>
		                            <th>Soporte sujección</th>

		                            <th>Tipo incidencia</th>
		                            <th>Estado</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($incidencias as $incidencia) {
		                            ?>
		                            <tr>
		                                <!--//<td><a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_incidencia)?>">#<?php echo $incidencia->id_incidencia ?></a></td>//-->
		                                <td>#<?php echo $incidencia->id_incidencia ?></td>
		                                <td><?php echo $sfid ?></td>
		                                <td><?php echo date_format(date_create($incidencia->fecha), 'd/m/Y'); ?></td>
		                                <td><?=($incidencia->alarm_display==1)?'Mueble: '.$incidencia->display['display']:'Dispositivo: '.$incidencia->device['device']?>
		                                </td>
		                                <td><?=($incidencia->alarm_display==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->id_devices_pds!=NULL)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->alarm_device==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->alarm_garra==1)?'&#x25cf;':''?></td>
		                                <td><?php echo $incidencia->tipo_averia ?></td>
		                                <td><strong><?php echo $incidencia->status_pds ?></strong></td>
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