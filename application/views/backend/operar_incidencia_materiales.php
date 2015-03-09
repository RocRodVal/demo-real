<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <div class="data_tienda"><?php echo $commercial ?> /
                    <?php echo $address ?> , <?php echo $zip ?> -  <?php echo $city ?></div>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Aignación de material para la incidencia
                </div>
                <div class="panel-body incidenciaEstado">
                    <div class="row">
                        <div class="col-lg-7 labelText white">Asignar materiales</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Revisada') {
                                echo 'disabled';
                            } ?>>Asignar mat.</a></td>
                        </div>
                    </div>
                    <div class="row">
		            <?php
		            if (empty($devices_almacen)) {
		                echo '<p>No hay dispositivos.</p>';
		            } else {
		            ?>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Dispositivo</th>
		                            <th>Unidades</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                            <tr>
		                                <td>
		                                <select id="dipositivo_almacen_1" name="dipositivo_almacen_1" width="500" style="width:500px">
		                              	<?php
		                        		foreach ($devices_almacen as $device_almacen) {
		                            	?>
		                                	<option id="<?php echo $device_almacen->id_devices_almacen ?>">[<?php echo $device_almacen->serial.'] '.$device_almacen->device ?> (<?php echo $device_almacen->owner ?>)</option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
		                                <td><input type="text" id="units_dipositivo_almacen_1" name="units_dipositivo_almacen_1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td>
		                            </tr>
		                            <tr>
		                                <td>
		                                <select id="dipositivo_almacen_2" name="dipositivo_almacen_2" width="500" style="width:500px">
		                              	<?php
		                        		foreach ($devices_almacen as $device_almacen) {
		                            	?>
		                                	<option id="<?php echo $device_almacen->id_devices_almacen ?>">[<?php echo $device_almacen->serial.'] '.$device_almacen->device ?> (<?php echo $device_almacen->owner ?>)</option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
		                                <td><input type="text" id="units_dipositivo_almacen_2" name="units_dipositivo_almacen_2" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td>
		                            </tr>
		                        </tbody>
		                    </table>
		                </div>
		            <?php
		            }
		            ?>
		       		</div>
                    <div class="row">
		            <?php
		            if (empty($alarms_almacen)) {
		                echo '<p>No hay alarmas.</p>';
		            } else {
		            ?>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Alarma</th>
		                            <th>Unidades</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                            <tr>
		                                <td>
		                                <select id="alarma_almacen_1" name="alarma_almacen_1" width="500" style="width:500px">
		                              	<?php
		                        		foreach ($alarms_almacen as $alarm_almacen) {
		                            	?>
		                                	<option id="<?php echo $alarm_almacen->id_alarm ?>">[<?php echo $alarm_almacen->code.'] '.$alarm_almacen->alarm ?></option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
		                                <td><input type="text" id="units_alarma_almacen_1" name="units_alarma_almacen_1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td>
		                            </tr>
		                            <tr>
		                                <td>
		                                <select id="alarma_almacen_2" name="alarma_almacen_2" width="500" style="width:500px">
		                              	<?php
		                        		foreach ($alarms_almacen as $alarm_almacen) {
		                            	?>
		                                	<option id="<?php echo $alarm_almacen->id_alarm ?>">[<?php echo $alarm_almacen->code.'] '.$alarm_almacen->alarm ?></option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
		                                <td><input type="text" id="units_alarma_almacen_2" name="units_alarma_almacen_2" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td>
		                            </tr>
		                            <tr>
		                                <td>
		                                <select id="alarma_almacen_3" name="alarma_almacen_3" width="500" style="width:500px">
		                              	<?php
		                        		foreach ($alarms_almacen as $alarm_almacen) {
		                            	?>
		                                	<option id="<?php echo $alarm_almacen->id_alarm ?>">[<?php echo $alarm_almacen->code.'] '.$alarm_almacen->alarm ?></option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
		                                <td><input type="text" id="units_alarma_almacen_3" name="units_alarma_almacen_3" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td>
		                            </tr>
		                            <tr>
		                                <td>
		                                <select id="alarma_almacen_4" name="alarma_almacen_4" width="500" style="width:500px">
		                              	<?php
		                        		foreach ($alarms_almacen as $alarm_almacen) {
		                            	?>
		                                	<option id="<?php echo $alarm_almacen->id_alarm ?>">[<?php echo $alarm_almacen->code.'] '.$alarm_almacen->alarm ?></option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
		                                <td><input type="text" id="units_alarma_almacen_4" name="units_alarma_almacen_4" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td>
		                            </tr>
		                            <tr>
		                                <td>
		                                <select id="alarma_almacen_5" name="alarma_almacen_5" width="500" style="width:500px">
		                              	<?php
		                        		foreach ($alarms_almacen as $alarm_almacen) {
		                            	?>
		                                	<option id="<?php echo $alarm_almacen->id_alarm ?>">[<?php echo $alarm_almacen->code.'] '.$alarm_almacen->alarm ?></option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
		                                <td><input type="text" id="units_alarma_almacen_5" name="units_alarma_almacen_5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td>
		                            </tr>			                            		                            	                            
		                        </tbody>
		                    </table>
		                </div>
		            <?php
		            }
		            ?>
		       		</div>		       		
                    <div class="row">
                        <div class="col-lg-7 labelText white">Asignar materiales</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Revisada') {
                                echo 'disabled';
                            } ?>>Asignar mat.</a></td>
                        </div>
                    </div>		       		 
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información de la incidencia
                </div>
                <div class="panel-body">
                    <strong>Fecha alta:</strong> <?php echo $incidencia['fecha'] ?><br/>
                    <strong>Estado:</strong> <?php echo $incidencia['status'] ?><br/>
                    <strong>Mueble:</strong> <?php echo $incidencia['display']['display'] ?><br/>
                    <strong>Teléfono:</strong> <?php echo $incidencia['device']['brand_name']." / ".$incidencia['device']['device'] ?><br/>
                    <strong>Intervención:</strong>
                    <?php
                    //Si el estado es superior a Instalador asignado e intervención!=null->Esto nunca debería darse pero se contempla
                    if (($incidencia['status'] == 'Comunicada' || $incidencia['status'] == 'Resuelta' ||
                            $incidencia['status'] == 'Instalador asignado') && $incidencia['intervencion'] != null
                    ) {
                        ?>
                        <a onClick="showModalViewIntervencion(<?php echo $incidencia['intervencion']; ?>)">
                            #<?php echo $incidencia['intervencion']; ?></a>
                    <?php
                    } else {
                        echo "-";
                    }

                    ?><br/>
                    <strong>Comentario:</strong> <?php echo $incidencia['description_1'] ?><br/>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- /#page-wrapper -->

<?php $this->load->view('backend/intervenciones/nueva_intervencion'); ?>
<?php $this->load->view('backend/intervenciones/ver_intervencion'); ?>