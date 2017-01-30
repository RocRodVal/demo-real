<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <div class="data_tienda"><strong>[<?php echo $reference ?>]</strong> <?php echo $commercial ?><br />
                    <?php echo $address ?><br />
                    <?php echo $zip ?> -  <?php echo $city ?> (<?php echo $province ?>)<br />
                    <?php 
                    if ($phone_pds <>'')
                    {	
                    ?>
                    Tel. <?php echo $phone_pds ?>
                    <?php 
                    }
                    ?>
                </div>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información de la incidencia
                </div>
                <div class="panel-body">
                    <strong>Tipo tienda:</strong> <?php echo $pds["tipo"] . "-" . $pds["subtipo"] ."-" . $pds["segmento"] . "-" . $pds["tipologia"] ?><br/>
                    <strong>Fecha alta:</strong> <?php echo $incidencia['fecha'] ?><br/>
                    <strong>Estado:</strong> <?php echo $incidencia['status'] ?><br/>
                    <strong>Tipo:</strong> <?php echo $incidencia['tipo_averia'] ?>
                    <?php
                    if ($incidencia['tipo_averia'] == 'Robo') {
                        ?>
                        [<a href="<?= site_url('uploads/' . $incidencia['denuncia']) ?>" target="_blank">ver denuncia</a>]
                    <?php
                    }
                    ?>
                    <br />
                    <?php
                    if (!isset($incidencia['device']['device'])) {$dispositivo = 'Retirado';}
                    else { $dispositivo = $incidencia['device']['device']; }
                    if (!isset($incidencia['display']['display'])) { $mueble = 'Retirado'; }
                    else { $mueble = $incidencia['display']['display']; }
                    ?>
                    <strong>Mueble:</strong> <?php echo $mueble ?><br/>
                    <strong>Dispositivo:</strong> <?php echo $dispositivo ?><br/>
                    <strong>Contacto:</strong> <?php echo $incidencia['contacto'].' Tel. '.$incidencia['phone'] ?><br/>
                    <strong>Intervención:</strong>
                    <?php
                    //Si el estado es superior a Instalador asignado e intervención!=null->Esto nunca debería darse pero se contempla
                    if (($incidencia['status'] == 'Comunicada' || $incidencia['status'] == 'Resuelta' ||
                            $incidencia['status'] == 'Instalador asignado' || $incidencia['status'] == 'Material asignado') && $incidencia['intervencion'] != null)
                    {
                        ?>
                        <a onClick="showModalViewIntervencion(<?php echo $incidencia['intervencion']; ?>)">
                            #<?php echo $incidencia['intervencion']; ?></a>
                    <?php
                    } else {
                        echo "-";
                    }

                    ?><br/>
                    <strong>Comentario:</strong> <?php echo $incidencia['description_1'] ?>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Asignación de material para la incidencia
                </div>
                <form action="<?= site_url('admin/update_materiales_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>" method="post">
                <div class="panel-body incidenciaEstado">
                    <div class="row">
                        <div class="col-lg-7 labelText white">Asignar materiales</div>
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
                                    <th>IMEI</th>
                                    <th>MAC</th>
                                    <th>Serial</th>
                                    <th>Barcode</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                            <tr>
		                                <td>
                                            <select id="dipositivo_almacen_1" name="dipositivo_almacen_1" width="375" style="width:375px">
                                            <?php
                                            foreach ($devices_almacen as $device_almacen) {
                                            ?>
                                                <option value="<?php echo $device_almacen->id_devices_almacen ?>"><?php echo $device_almacen->device ?> [<?php echo $device_almacen->IMEI ?>] (<?php echo $device_almacen->owner ?>)</option>
                                            <?php
                                            }
                                            ?>
                                            </select>
		                                </td>
		                                <td><input type="text" size="2" maxlength="1" id="units_dipositivo_almacen_1" name="units_dipositivo_almacen_1" value="1" readonly="readonly" /></td>
                                        <td><input type="text" size="20" maxlength="20" id="imei_1" name="imei_1" /></td>
                                        <td><input type="text" size="20" id="mac_1" name="mac_1" /></td>
                                        <td><input type="text" size="20" id="serial_1" name="serial_1" /></td>
                                        <td><input type="text" size="20" id="barcode_1" name="barcode_1" /></td>
		                            </tr>

		                         <!--   <tr>
		                                <td>
                                            <select id="dipositivo_almacen_2" name="dipositivo_almacen_2" width="375" style="width:375px">
                                            <?php
                                            foreach ($devices_almacen as $device_almacen) {
                                            ?>
                                                <option value="<?php echo $device_almacen->id_devices_almacen ?>"><?php echo $device_almacen->device ?> [<?php echo $device_almacen->serial ?>] (<?php echo $device_almacen->owner ?>)</option>
                                            <?php
                                            }
                                            ?>
                                            </select>
		                                </td>
		                                <td><input type="text" size="2" maxlength="1" id="units_dipositivo_almacen_2" name="units_dipositivo_almacen_2" onkeypress='this.value=1' /></td>
                                        <td><input type="text" size="20" maxlength="20" name="imei_2" /></td>
                                        <td><input type="text" size="20" id="mac_2" name="mac_2" /></td>
                                        <td><input type="text" size="20" id="serial_2" name="serial_2" /></td>
                                        <td><input type="text" size="20" id="barcode_2" name="barcode_2" /></td>
		                            </tr>
		                            <tr>
		                                <td>
                                            <select id="dipositivo_almacen_3" name="dipositivo_almacen_3" width="375" style="width:375px">
                                            <?php
                                            foreach ($devices_almacen as $device_almacen) {
                                            ?>
                                                <option value="<?php echo $device_almacen->id_devices_almacen ?>"><?php echo $device_almacen->device ?> [<?php echo $device_almacen->serial ?>] (<?php echo $device_almacen->owner ?>)</option>
                                            <?php
                                            }
                                            ?>
                                            </select>
		                                </td>
		                                <td><input type="text" size="2" maxlength="1" id="units_dipositivo_almacen_3" name="units_dipositivo_almacen_3" onkeypress='this.value=1' /></td>
                                        <td><input type="text"  size="20" maxlength="20" id="imei_3" name="imei_3" /></td>
                                        <td><input type="text" size="20" id="mac_3" name="mac_3" /></td>
                                        <td><input type="text" size="20" id="serial_3" name="serial_3" /></td>
                                        <td><input type="text" size="20" id="barcode_3" name="barcode_3" /></td>
		                            </tr>-->
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
                                <?php

                                // GENERACION DINAMICA DE 10 CAMPOS ALARMA, SELECTOR DE ALARMA + CAMPO UNIDADES
                                    for($i = 1; $i <= 10; $i++){ ?>
                                        <tr>
                                            <td>
                                                <?php  // var_dump($alarms_almacen["Sony"]); ?>
                                                <select id="alarma_almacen_<?=$i?>" name="alarma_almacen_<?=$i?>" width="500" style="width:500px" onchange="comprobar_stock(this,'units_alarma_almacen_<?=$i?>');">
                                                    <?php // Optgroups de dueños


                                                        foreach($duenos_alarm as $dueno_alarm){?>
                                                            <optgroup label="<?=$dueno_alarm->client?>">
                                                                <?php // Listado de alarmas del dueño actual...
                                                                    foreach($alarms_almacen[$dueno_alarm->client] as $alarm_almacen){ ?>
                                                                        <option value="<?php echo $alarm_almacen->id_alarm ?>" data-stock="<?=$alarm_almacen->units?>"><?php echo $alarm_almacen->client_alarm.' '.$alarm_almacen->brand.' '.$alarm_almacen->code.' '.$alarm_almacen->alarm?></option>
                                                                    <?php }
                                                                ?>
                                                            </optgroup>
                                                        <?php }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><input type="text" id="units_alarma_almacen_<?=$i?>" name="units_alarma_almacen_<?=$i?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' /></td>
                                        </tr>


                                <?php } // End For ?>
		                        </tbody>
		                    </table>
		                </div>
		            <?php
		            }
		            ?>
		       		</div>		       		
                    <div class="row">
                        <div class="col-lg-7 labelText white">Asignar materiales</div>
                        <input type="submit" value="Envíar" name="submit" class="btn btn-success" />
                    </div>		       		 
                </div>
                </form>
            </div>
        </div>


            <div class="col-lg-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Notas incidencia
                </div>
                <form action="<?= site_url('admin/insert_comentario_incidencia/' . $id_pds_url .'/' . $id_inc_url) ?>" method="post">
                <div class="panel-body">
                    <strong>Comentarios:</strong>
                    <textarea class="form-control" rows="10" name="description_2" id="description_2"><?php echo $incidencia['description_2'] ?></textarea>
                    <br clear="all" />
                    <p>
                    <input type="submit" value="Envíar" name="submit" class="btn btn-success" />
                    </p>
                </div>
                </form>
            </div>            
        </div>
    </div>
</div>
</div>
</div>
<!-- /#page-wrapper -->

<?php $this->load->view('backend/intervenciones/nueva_intervencion'); ?>
<?php $this->load->view('backend/intervenciones/ver_intervencion'); ?>