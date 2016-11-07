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
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $title ?>
                    <!--<a onclick="history.go(-1);return false;" class="btn btn-danger right">Volver</a>-->
                    <a href="<?=site_url('admin/estado_incidencias/'. ((($status_pds !="Resuelta" ) && ($status_pds !="Cerrada" ) && ($status_pds !="Cancelada" ) && ($status_pds !="Pendiente recogida" ))? "abiertas": "cerradas")); ?>" class="btn btn-danger right">Volver</a>
                </h1>
            </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cambiar el estado de la incidencia
                </div>
                <div class="panel-body incidenciaEstado">
                    <div class="row">
                        <div class="col-lg-7 labelText grey">Última modificación:</div>
                        <div class="col-lg-5 labelBtn grey"><?php echo $last_updated ?></div>

                        <div class="col-lg-7 labelText grey">Revisión de incidencia</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/2') ?>"
                               classBtn="status_1/2" class="btn btn-success" <?php if ($incidencia['status'] != 'Nueva') {
                                echo 'disabled';
                            } ?>>Revisar</a>
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/5/9') ?>"
                               classBtn="status_1/2" class="btn btn-danger" <?php if ($incidencia['status'] != 'Nueva') {
                                echo 'disabled';
                            } ?>>Cancelar</a>
                            <span class="fecha_status"><?=$historico_revisada?></span>


                        </div>
                        <div class="col-lg-7 labelText white">Asignar instalador e intervención</div>
                        <div class="col-lg-5 labelBtn white">
                            <a onClick="showModalNewIntervencion(<?php echo $id_pds_url . ',' . $id_inc_url ?>)"
                               classBtn="status" class="btn btn-success" <?php if (!$material_editable) {
                                echo 'disabled';
                            } ?>>Asignar instalador</a>

                            <span class="fecha_status"><?=$historico_instalador_asignado?></span>

                        </div>                        
                        <div class="col-lg-7 labelText grey">Asignar material <?$incidencia['status']?> </div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>"
                               classBtn="status" class="btn btn-success" <?php if (!$material_editable) {
                                echo 'disabled';
                            } ?>>Asignar mat.</a>
                            <span class="fecha_status"><?=$historico_material_asignado?></span>


                        </div>                       
                        <div class="col-lg-7 labelText white">Imprimir documentación</div>


                            <?php if($incidencia['status']==='Comunicada'){ ?>
                                <div class="col-lg-5 labelBtn white">
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5') ?>" classBtn="status" class="btn btn-success">Volver a imprimir</a>
                                </div>
                                <div class="col-lg-7 labelText white">&nbsp;</div>
                                <div class="col-lg-5 labelBtn white">
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5/notificacion') ?>" classBtn="status" class="btn btn-success">Volver a notificar</a>

                                    <span class="fecha_status"><?=$historico_fecha_comunicada?></span>
                                </div>
                            <?php }else{ ?>

                            <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5/notificacion') ?>"
                               classBtn="status" class="btn btn-success"
                                <?php

                                if (($incidencia['status_pds'] === 'Finalizada') ||
                                    ($incidencia['status'] === 'Instalador asignado') ||

                                    ($incidencia['status'] === 'Material asignado' ||

                                        $incidencia['status'] === 'Comunicada') && (isset($incidencia['intervencion']) && !empty($incidencia['intervencion'])))
                                {
                                    echo '';
                                }
                                else
                                {
                                    echo 'disabled';
                                }
                                ?>
                                >
                                <?php if ($incidencia['status'] === 'Comunicada' || $incidencia['status_pds'] === 'Finalizada')
                                {
                                    echo 'Volver a imprimir';
                                }
                                else
                                {
                                    echo 'Imprimir y notificar';
                                }
                                ?>
                            	</a>
                                <span class="fecha_status"><?=$historico_fecha_comunicada?></span>
                            </div>

                                <?php } ?>

                        <div class="col-lg-7 labelText grey">Sustitución de terminales <br /></div>
                        <?php
                        if($incidencia['tipo_averia'] == 'Robo') {
                            /*Si la incidencia es un robo puede ser necesario que vuelva el terminal que lo genero com RMA*/
                            ?>
                        <form action="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/10') ?>" method="post">
                            <div class="col-lg-2 labelBtn grey">
                                <input type="hidden" value="si" id="sustituido"/>
                                <input type="submit" value="sustituir" name="submit" class="btn btn-success"
                                       classBtn="status" class="btn btn-success" <?php if (($incidencia['status'] != 'Comunicada')
                                       ||(is_null($incidencia['id_devices_pds'])) || ($status_device_incidencia=='NoSustituir')){
                                    echo 'disabled';
                                } ?> />
                                <?php // echo "ESTHER ".$status_device_incidencia;?>
                            </div>
                        </form>


                        <form action="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/11') ?>" method="post">
                            <div class="col-lg-3 labelBtn grey">
                                <input type="hidden" value="si" id="sustituidoRMA"/>
                                <input type="submit" value="sustituir / RMA" name="submit" class="btn btn-success"
                                       classBtn="status" class="btn btn-success" <?php if (($incidencia['status'] != 'Comunicada')
                            ||(is_null($incidencia['id_devices_pds'])) || ($status_device_incidencia=='NoSustituir')){
                            echo 'disabled';
                        } ?> />
                        <?php // echo "ESTHER ".$status_device_incidencia;?>
                    </div>
                    </form>
                      <?php  }
                      else { ?>
                          <form action="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/10') ?>" method="post">
                              <div class="col-lg-5 labelBtn grey">
                                  <input type="hidden" value="si" id="sustituido"/>
                                  <input type="submit" value="sustituir" name="submit" class="btn btn-success"
                                         classBtn="status" class="btn btn-success" <?php if (($incidencia['status'] != 'Comunicada')
                                      ||(is_null($incidencia['id_devices_pds'])) || ($status_device_incidencia=='NoSustituir')){
                                      echo 'disabled';
                                  } ?> />
                                  <?php // echo "ESTHER ".$status_device_incidencia;?>
                              </div>
                          </form>
                      <?php }
                      ?>

                        <div class="col-lg-7 labelText white">Resolver incidencia<br /><br /></div>
		                <form action="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/6') ?>" method="post">
		                <div class="col-lg-5 labelBtn white">
		                    <input type="date" name="fecha_cierre" id="fecha_cierre" value="Fecha"   <?php if ($incidencia['status_pds'] == 'Finalizada') { echo 'disabled'; } ?> ><br />
		                    <input type="submit" value="Resolver" name="submit" class="btn btn-success" classBtn="status" class="btn btn-success"
                                <?php if (($incidencia['status'] != 'Comunicada') && ($incidencia['status'] != 'Sustituido')  && ($incidencia['status'] != 'SustituidoRMA')){
                                echo 'disabled';
                            } ?> />
                            <span class="fecha_status"><?=$historico_fecha_resuelta?></span>
                                </div>
		                </form>
                        <div class="col-lg-7 labelText grey">Emisión de recogida de material</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/7') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Resuelta') {
                                echo 'disabled';
                            } ?>>Recogida</a>
                        </div>
                        <div class="col-lg-7 labelText white">Material recogido</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/8') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Pendiente recogida') {
                                echo 'disabled';
                            } ?>>Cerrar</a>
                        </div>
                        <div class="col-lg-12 labelText grey"><i class="fa fa-fire-extinguisher fa-fw"></i> Usar con cuidado</div>
                        <!--//
                        <div class="col-lg-7 labelText grey">Puesta a cero (borrado pasos previos)</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia_puesta_a_cero/' . $id_pds_url . '/' . $id_inc_url . '/1/1') ?>"
                               classBtn="status" class="btn btn-danger">Puesta a cero</a></td>
                        </div>
                        //-->                         
                        <div class="col-lg-7 labelText grey">Cierre forzoso (act. externas)</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/8/ext') ?>"

                               classBtn="status" class="btn btn-danger" <?php if ($incidencia['status_pds'] == 'Finalizada') { echo 'disabled'; } ?>>Cierre forzoso</a>

                        </div>                             
                    </div>
                </div>
            </div>
            <?php $this->load->view('backend/chat.php'); ?>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información de la incidencia
                </div>
                <div class="panel-body">
                    <table width="100%" cellpadding="0" cellspacing="0" id="info_incidencia">

                        <tr>
                            <td colspan="2"><h3>Información general</h3></td>
                        </tr>
                        <tr>
                            <th width="50%">Tipo tienda:</th> <td><?php echo $pds["tipo"] . "-" . $pds["subtipo"] ."-" . $pds["segmento"] . "-" . $pds["tipologia"] ?></td>
                        </tr>
                        <tr>
                            <th>Fecha alta:</th> <td><?php echo date_format(date_create($incidencia['fecha']), 'd/m/Y'); ?></td>
                        </tr>
                        <tr>
                            <th>Fecha cierre:</th>
                            <?php
                            if (strtotime($incidencia['fecha_cierre']) AND (date_format(date_create($incidencia['fecha_cierre']), 'd/m/Y') <> '30/11/-0001'))
                            { ?>
                                <td><?php echo date_format(date_create($incidencia['fecha_cierre']), 'd/m/Y'); ?></td>
                            <?php } else{?>
                                <td>---</td>
                            <?php }?>
                        </tr>
                        <tr>
                            <th>Estado:</th> <td><?php echo $incidencia['status'] ?></td>
                        </tr>
                        <tr>
                            <th>Fecha asignación material:</th>
                            <?php if (!empty($historico_material_asignado)) { ?>
                               <td><?php echo $historico_material_asignado; ?></td>
                            <?php }else{ ?>
                                <td>---</td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <th>Fecha comunicación:</th>
                            <?php if (!empty($historico_fecha_comunicada)) { ?>
                                <td><?php echo $historico_fecha_comunicada; ?></td>
                            <?php }else{ ?>
                                <td>---</td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td colspan="2"><h3>Información del fallo</h3></td>
                        </tr>
                        <tr>
                            <th>Tipo: </th>
                            <td><?php echo $incidencia['tipo_averia'] ?>
                            <?php if ($incidencia['tipo_averia'] == 'Robo') { ?>
                                [<a href="<?= site_url('uploads/' . $incidencia['denuncia']) ?>" target="_blank">ver denuncia</a>]
                            <?php } ?></td>
                        </tr>
                        <?php
                            if (!isset($incidencia['device']['device'])) {$dispositivo = '-';}
                            else {
                                $dispositivo = $incidencia['device']['device'];
                                if (!empty($incidencia['device']['IMEI'])) {
                                    $dispositivo .= " - " . $incidencia['device']['IMEI'];
                                }
                            }
                            if (!isset($incidencia['display']['display'])) { $mueble = 'Retirado'; }
                            else { $mueble = $incidencia['display']['display']; }
                        ?>
                        <tr>
                            <th>Mueble: </th> <td><?php echo $mueble ?></td>
                        </tr>
                        <tr>
                            <th>Dispositivo: </th> <td><?php echo $dispositivo ?></td>
                        </tr>

                        <tr>
                            <td colspan="2"><h3>Tipo de incidencia / Elemento afectado</h3></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <form action="<?=site_url('admin/actualizar_averia');?>" id="afecta_a">
                                    <table border="0" width="100%">
                                        <tr>
                                            <th><label for="tipo_averia">Tipo de incidencia: </label></th>
                                            <th><input type="checkbox" name="fail_device" id="fail_device" <?=($fail_device == 1) ? ' checked="checked" ' : '' ?>> <label for="fail_device">Fallo dispositivo</label></th>
                                            <th><input type="checkbox" name="alarm_display" id="alarm_display"  <?=($alarm_display == 1) ? ' checked="checked" ' : '' ?>> <label for="alarm_display">Alarma mueble</label></th>
                                            <th><input type="checkbox" name="alarm_device" id="alarm_device"  <?=($alarm_device == 1) ? ' checked="checked" ' : '' ?>> <label for="alarm_device">Alarma dispositivo</label></th>
                                            <th><input type="checkbox" name="alarm_garra" id="alarm_garra"  <?=($alarm_garra == 1) ? ' checked="checked" ' : '' ?>> <label for="alarm_garra">Alarma garra</label></th>
                                        </tr>

                                        <tr>
                                            <th colspan="1"><label for="tipo_averia">Razón de parada de incidencia: </label></th>
                                            <td colspan="4"><select id="tipo_averia" name="tipo_averia">
                                                    <option value="NULL">-- Sin asignar --</option>
                                                    <?php
                                                    /* razones de parada de incidencias*/
                                                    foreach($tipos_incidencia as $tipo)
                                                    {
                                                        $selected = ($incidencia['id_type_incidencia']==$tipo->id_type_incidencia)? ' selected = "selected" ' : '';

                                                        echo '<option value="'.$tipo->id_type_incidencia.'" '.$selected.'>'.$tipo->title.'</option>';
                                                    }

                                                    ?>
                                                </select></td>
                                        </tr>

                                        <tr>
                                            <td colspan="5"><h3>Solucion ejecutada</h3></td>
                                        </tr>
                                        <tr>
                                            <th colspan="1"><label for="tipo_averia">Solución ejecutada:</label></th>
                                            <td colspan="4"><select id="id_solucion_incidencia" name="id_solucion_incidencia">
                                                    <option value="NULL">-- Sin asignar --</option>
                                                    <?php
                                                    /* solucion ejecutada en la incidencias*/
                                                    foreach($soluciones as $sol)
                                                    {
                                                        $selected = ($incidencia['id_solucion_incidencia']==$sol->id_solucion_incidencia)? ' selected = "selected" ' : '';
                                                        echo '<option value="'.$sol->id_solucion_incidencia.'" '.$selected.'>'.substr($sol->title,0,80).'</option>';
                                                    }

                                                    ?>
                                                </select></td>
                                        </tr>

                                        <tr>
                                            <td height="10" style="font-size: 1px">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="left" colspan="4"><a href="#" class="update" onclick="update_incidencia_afecta(); return false;">Actualizar</a> <span class="result">Prueba Resultado</span>

                                                <input type="hidden" name="id_incidencia" value="<?=$incidencia["id_incidencia"]?>">
                                                <input type="hidden" name="actualizar_averia" value="si">
                                            </td>
                                        </tr>
                                    </table>

                                </form>
                            </td>
                        </tr>


                        <tr>
                            <td colspan="2"><h3>Otra información</h3></td>
                        </tr>
                        <tr>
                            <th>Contacto:</th> <td><?php echo $incidencia['contacto'].' Tel. '.$incidencia['phone'] ?></td>
                        </tr>
                        <tr>
                            <th>Intervención:</th>
                            <td>
                            <?php
                            //Si el estado es superior a Instalador asignado e intervención!=null->Esto nunca debería darse pero se contempla
                           /* if (($incidencia['status'] == 'Comunicada' || $incidencia['status'] == 'Resuelta' ||
                                    $incidencia['status'] == 'Instalador asignado' || $incidencia['status'] == 'Material asignado') && $incidencia['intervencion'] != null)*/
                            if ($incidencia['intervencion'] != null)
                            {
                                ?>
                                <a onClick="showModalViewIntervencion(<?php echo $incidencia['intervencion']; ?>)">
                                    #<?php echo $incidencia['intervencion']; ?></a>
                            <?php
                            } else {
                                echo "-";
                            }?></td>
                        </tr>
                        <tr>
                            <th>Comentario:</th> <td><?php echo $incidencia['description_1'] ?></td>
                        </tr>

                        <tr>
                            <td colspan="2"><h3>Material asignado</h3></td>
                        </tr>
                        <tr>
                            <td colspan="2">

                            <?php
                            if (empty($material_dispositivos)) {
                                echo '<p class="message"><i class="glyphicon glyphicon-remove"></i> No hay dispositivos asociados.</p>';
                            } else {
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="table_incidencias_dashboard">
                                        <thead>
                                        <tr>
                                            <th width="20%">Código</th>
                                            <th width="60%">Dispositivo</th>
                                            <th width="10%">Unidades</th>
                                            <th width="10%">Desasignar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($material_dispositivos as $material_dispositivos_item) {
                                            ?>
                                            <tr>
                                                <td><?php echo $material_dispositivos_item->imei ?></td>
                                                <td><?php echo $material_dispositivos_item->device ?></td>
                                                <td><?php echo $material_dispositivos_item->cantidad ?></td>
                                                <?php if($material_editable) { ?>
                                                    <td><a href="<?= site_url('admin/desasignar_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url.'/device/'.$material_dispositivos_item->id_material_incidencias) ?>"><i class="glyphicon glyphicon-remove"></i></a></td>
                                                <?php }else{ ?>
                                                    <td>-</td>
                                                <?php  } ?>

                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php if (empty($material_alarmas)) {
                                    echo '<p class="message"><i class="glyphicon glyphicon-remove"></i> No hay alarmas asociadas.</p>';
                                } else {
                                    if($material_editable && (count($material_alarmas) > 0 || count($material_dispositivos) > 0)) { ?>
                                        <p class="message"><a href="<?= site_url('admin/desasignar_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url.'/todo') ?>"><i class="glyphicon glyphicon-remove"></i> Desasignar todos los materiales</a></p>
                                    <?php } ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="table_incidencias_dashboard">
                                            <thead>
                                            <tr>
                                                <th width="20%">Código</th>
                                                <th width="60%">Alarma</th>
                                                <th width="20%">Dueño</th>
                                                <th width="5%">Unidades</th>
                                                <th width="5%">Desasignar</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($material_alarmas as $material_alarmas_item) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $material_alarmas_item->code ?></td>
                                                    <td><?php echo $material_alarmas_item->alarm ?></td>
                                                    <td><?php echo $material_alarmas_item->dueno ?></td>
                                                    <td><?php echo $material_alarmas_item->cantidad ?></td>
                                                    <?php if($material_editable) { ?>
                                                        <td><a href="<?= site_url('admin/desasignar_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url.'/alarm/'.$material_alarmas_item->id_material_incidencias) ?>"><i class="glyphicon glyphicon-remove"></i></a></td>
                                                    <?php }else{ ?>
                                                        <td>-</td>
                                                    <?php } ?>
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

                            </td>
                        </tr>
                    </table>


                    




                </div>
            </div>
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
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    Notas instalador
                </div>
                <form action="<?= site_url('admin/insert_comentario_incidencia_instalador/' . $id_pds_url .'/' . $id_inc_url) ?>" method="post">
                <div class="panel-body">
                    <strong>Comentarios:</strong>
                    <textarea class="form-control" rows="10" name="description_3" id="description_3"><?php echo $incidencia['description_3'] ?></textarea>
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
<?php $this->load->view('backend/intervenciones/ver_intervencion_incidencia'); ?>

<!-- Modal Ver intervencion-->
<div class="modal fade" id="modal_ver_incidencia_" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_ver_intervencion_title">Ver incidencia <span id="id_incidencia"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <strong>Fecha alta:</strong> <span id="fecha_alta_incidencia"></span><br/>
                                <strong>Estado:</strong> <span id="estado_incidencia"></span><br/>
                                <strong>Mueble:</strong> <span id="mueble_incidencia"></span><br/>
                                <strong>Teléfono:</strong> <span id="telefono_incidencia"></span><br/>
                                <strong>Comentario:</strong> <span id="comentario_incidencia"></span><br/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>