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
                    Cambiar el estado de la incidencia
                </div>
                <div class="panel-body incidenciaEstado">
                    <div class="row">
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
                        <div class="col-lg-7 labelText grey">Asignar instalador e intervención</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a onClick="showModalNewIntervencion(<?php echo $id_pds_url . ',' . $id_inc_url ?>)"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Revisada') {
                                echo 'disabled';
                            } ?>>Asignar instalador</a>
                        </div>                        
                        <div class="col-lg-7 labelText white">Asignar materiales</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Instalador asignado') {
                                echo 'disabled';
                            } ?>>Asignar mat.</a></td>
                        </div>
                        <div class="col-lg-7 labelText white">Imprimir documentación</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5') ?>"
                               classBtn="status" class="btn btn-success"
                                <?php if ($incidencia['status'] == 'Material asignado' ||
                                            $incidencia['status'] == 'Comunicada' ||
                                            $incidencia['status'] == 'Resuelta' ||
                                            $incidencia['status'] == 'Pendiente recogida') {
                                    echo '';
                                }
                                else{
                                    echo 'disabled';
                            } ?>>Imprimir</a>
                        </div>
                        <div class="col-lg-7 labelText grey">Resolver incidencia</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/6') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Comunicada') {
                                echo 'disabled';
                            } ?>>Resolver</a>
                        </div>
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
                    </div>
                </div>
            </div>
            <div class="form-group">
            	<h2>Chat offline</h2>
            	<?php
			 	if(empty($chats))
				{
					echo '<p>No tiene mensajes en el chat.</p>';
				}
				else
				{					
					foreach($chats as $chat)
					{
				?>
					<div <?=($chat->agent==='altabox')?'class="sat"':'class="sfid"'?>>
						<p>
							<small><?php echo $chat->fecha; ?></small>
							<em>(<?=($chat->agent==='altabox')?'SAT':'Tienda'?>)</em>
						</p>
						<p><?php echo $chat->texto; ?></p>
						<?php 
						if ($chat->foto <> '')
						{
						?>
							<p><img src="<?= site_url('chats/'.$chat->foto) ?>" width="200" />
						<?php 
						}
						?>
					</div>	
					<?php
					}
				}
				?>                                       
			</div>
            <form action="<?= site_url('admin/insert_chat/'.$id_pds_url.'/'.$id_inc_url) ?>" method="post" class="content_auto form_login" enctype="multipart/form-data">
            <div class="form-group">
            	<label>Añade comentarios a la incidencia
            		<small>(Mín. 10 caracteres)</small>
            	</label>
            	<textarea class="form-control" rows="5" name="texto_chat" id="texto_chat"></textarea>
            </div>
            <div class="form-group">
            	<label>Adjuntar imagen o documento
            		<small>(JPG, PNG, PDF, DOC)</small>
            	</label>
            	<input id="foto" class="file" type="file" multiple=false name="userfile">
            </div>
            <div class="form-group">
            	<input type="submit" value="Envíar" name="submit" class="btn btn-success"/>
            </div>                                    
            </form>            
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