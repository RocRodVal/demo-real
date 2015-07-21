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
                        </div>
                        <div class="col-lg-7 labelText white">Asignar instalador e intervención</div>
                        <div class="col-lg-5 labelBtn white">
                            <a onClick="showModalNewIntervencion(<?php echo $id_pds_url . ',' . $id_inc_url ?>)"
                               classBtn="status" class="btn btn-success" <?php if (!$material_editable) {
                                echo 'disabled';
                            } ?>>Asignar instalador</a>
                        </div>                        
                        <div class="col-lg-7 labelText grey">Asignar material <?$incidencia['status']?> </div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>"
                               classBtn="status" class="btn btn-success" <?php if (!$material_editable) {
                                echo 'disabled';
                            } ?>>Asignar mat.</a></td>
                        </div>                       
                        <div class="col-lg-7 labelText white">Imprimir documentación</div>


                            <?php if($incidencia['status']==='Comunicada'){ ?>
                                <div class="col-lg-5 labelBtn white">
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5') ?>" classBtn="status" class="btn btn-success">Volver a imprimir</a>
                                </div>
                                <div class="col-lg-7 labelText white">&nbsp;</div>
                                <div class="col-lg-5 labelBtn white">
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5/notificacion') ?>" classBtn="status" class="btn btn-success">Volver a notificar</a>
                                </div>
                            <?php }else{ ?>

                            <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5/notificacion') ?>"
                               classBtn="status" class="btn btn-success"
                                <?php

                                if (($incidencia['status'] === 'Instalador asignado') || ($incidencia['status'] === 'Material asignado' || $incidencia['status'] === 'Comunicada') && (isset($incidencia['intervencion']) && !empty($incidencia['intervencion'])))
                                {
                                    echo '';
                                }
                                else
								{
                                    echo 'disabled';
                            	}
                            	?>
                            	>
                                <?php if ($incidencia['status'] === 'Comunicada')
                                {
                                    echo 'Volver a imprimir';
                                }
                                else
								{
                                    echo 'Imprimir y notificar';
                            	}
                            	?>
                            	</a>
                            </div>

                                <?php } ?>

                        <div class="col-lg-7 labelText grey">Resolver incidencia<br /><br /></div>
		                <form action="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/6') ?>" method="post">
		                <div class="col-lg-5 labelBtn grey">
		                    <input type="date" name="fecha_cierre" id="fecha_cierre" value="Fecha"><br />
		                    <input type="submit" value="Resolver" name="submit" class="btn btn-success" classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Comunicada') {
                                echo 'disabled';
                            } ?> />
		                </div>
		                </form>                        
                        <!-- //
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/6') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Comunicada') {
                                echo 'disabled';
                            } ?>>Resolver</a>
                        </div>
                        //-->
                        <div class="col-lg-7 labelText white">Emisión de recogida de material</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/7') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Resuelta') {
                                echo 'disabled';
                            } ?>>Recogida</a>
                        </div>
                        <div class="col-lg-7 labelText grey">Material recogido</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/8') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Pendiente recogida') {
                                echo 'disabled';
                            } ?>>Cerrar</a>
                        </div>
                        <div class="col-lg-12 labelText white"><i class="fa fa-fire-extinguisher fa-fw"></i> Usar con cuidado</div>
                        <!--//
                        <div class="col-lg-7 labelText grey">Puesta a cero (borrado pasos previos)</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia_puesta_a_cero/' . $id_pds_url . '/' . $id_inc_url . '/1/1') ?>"
                               classBtn="status" class="btn btn-danger">Puesta a cero</a></td>
                        </div>
                        //-->                         
                        <div class="col-lg-7 labelText white">Cierre forzoso (act. externas)</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/8/ext') ?>"
                               classBtn="status" class="btn btn-danger">Cierre forzoso</a>
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
                	<strong>Tipo tienda:</strong> <?php echo $type_pds ?><br/>
                    <strong>Fecha alta:</strong> <?php echo date_format(date_create($incidencia['fecha']), 'd/m/Y'); ?><br/>
                    <?php
                    // var_dump(date_format(date_create($incidencia['fecha_cierre']), 'd/m/Y'));
                    if (strtotime($incidencia['fecha_cierre']) AND (date_format(date_create($incidencia['fecha_cierre']), 'd/m/Y') <> '30/11/-0001'))
                    {
                    ?>
                    <strong>Fecha cierre:</strong> <?php echo date_format(date_create($incidencia['fecha_cierre']), 'd/m/Y'); ?><br/>
                    <?php
                    }
                    else 
                    {
                    ?>
                    <strong>Fecha cierre:</strong> ---<br/>
                    <?php 	
                    }	
                    ?>
                    <strong>Estado:</strong> <?php echo $incidencia['status'] ?><br/>
                    <?php
                    if ($historico_material_asignado <> '---')
					{
					?>
					<strong>Fecha asignación material:</strong> <?php echo date_format(date_create($historico_material_asignado), 'd/m/Y'); ?><br/>
					<?php
					}
                    if ($historico_fecha_comunicada <> '---')
					{
					?>
					<strong>Fecha comunicación:</strong> <?php echo date_format(date_create($historico_fecha_comunicada), 'd/m/Y'); ?><br/>
					<?php 	
					}
                    ?>
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
                    <br clear="all" />
                    
                    <h3>Material asignado</h3>
                    <?php  if($material_editable && (count($material_alarmas) > 0 || count($material_dispositivos) > 0)) { ?>
                        <p class="message"><td><a href="<?= site_url('admin/desasignar_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url.'/todo') ?>"><i class="glyphicon glyphicon-remove"></i> Desasignar todos los materiales</a></td></p>
                    <?php } ?>
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
		                                <td><?php echo $material_dispositivos_item->barcode ?></td>
		                                <td><?php echo $material_dispositivos_item->device ?></td>
		                                <td><?php echo $material_dispositivos_item->cantidad ?></td>
                                        <?php if($material_editable) { ?>
                                            <td><a href="<?= site_url('admin/desasignar_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url.'/device/'.$material_dispositivos_item->id_material_incidencias) ?>"><i class="glyphicon glyphicon-remove"></i></a></td>
                                        <?php }else{ ?>
                                            <td>-</td>
                                        <?php  } ?>

		                            </tr>
		                        <?php
		                        }
		                        ?>
		                        </tbody>
		                    </table>
		                </div>
		            <?php
		            }
		            if (empty($material_alarmas)) {
		                echo '<p class="message"><i class="glyphicon glyphicon-remove"></i> No hay alarmas asociadas.</p>';
		            } else {
		                ?>
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