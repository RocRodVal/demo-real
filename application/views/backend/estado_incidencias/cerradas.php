<!-- #page-wrapper -->
        <div id="page-wrapper">

                <div  id="incidencias_cerradas">

                    <div class="col-lg-12" >
                        <h1 class="page-header"><?php echo $title ?> <?php $this->load->view("backend/estado_incidencias/mensajes_chat",$mensajes_nuevos) ?></h1>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">


                        <div class="filtro">
                            <form action="<?=base_url()?>admin/estado_incidencias/cerradas" method="post" class="filtros form-mini autosubmit col-lg-12">
                                <div class="col-lg-3">
                                    <label for="id_tipo">Canal PDS: </label>
                                    <select name="id_tipo" id="id_tipo" class="form-control input-sm">
                                        <option value="" <?php echo ($id_tipo==="") ? 'selected="selected"' : ''?>>Cualquiera...</option>
                                        <?php foreach($tipos as $pds_tipo){
                                            echo '<option value="'.$pds_tipo["id"].'"
                                                    '.(($id_tipo == $pds_tipo["id"]) ?  ' selected="selected" ' : '' ).'
                                                >'.$pds_tipo["titulo"].'</option>';
                                        }?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="id_subtipo">Tipología PDS: </label>
                                    <select name="id_subtipo" id="id_subtipo" class="form-control input-sm">
                                        <option value="" <?php echo ($id_subtipo==="") ? 'selected="selected"' : ''?>>Cualquiera...</option>
                                        <?php foreach($subtipos as $pds_subtipo){
                                            echo '<option value="'.$pds_subtipo["id"].'"
                                                    '.(($id_subtipo == $pds_subtipo["id"]) ?  ' selected="selected" ' : '' ).'
                                                >'.$pds_subtipo["titulo"].'</option>';
                                        }?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="id_segmento">Concepto PDS: </label>
                                    <select name="id_segmento" id=id_segmento" class="form-control input-sm">
                                        <option value="" <?php echo ($id_segmento==="") ? 'selected="selected"' : ''?>>Cualquiera...</option>
                                        <?php foreach($segmentos as $segmento){
                                            echo '<option value="'.$segmento["id"].'"
                                                    '.(($id_segmento == $segmento["id"]) ?  ' selected="selected" ' : '' ).'
                                                >'.$segmento["titulo"].'</option>';
                                        }?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="id_tipologia">Categorización PDS: </label>
                                    <select name="id_tipologia" id=id_tipologia" class="form-control input-sm">
                                        <option value="" <?php echo ($id_tipologia==="") ? 'selected="selected"' : ''?>>Cualquiera...</option>
                                        <?php foreach($tipologias as $tipologia){
                                            echo '<option value="'.$tipologia["id"].'"
                                                    '.(($id_tipologia == $tipologia["id"]) ?  ' selected="selected" ' : '' ).'
                                                >'.$tipologia["titulo"].'</option>';
                                        }?>
                                    </select>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-lg-3">
                                    <label for="status">Estado SAT: </label>
                                    <select name="status" id="status" class="form-control input-sm">
                                        <option value="" <?php echo ($status==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>
                                        <option value="Resuelta" <?php echo ($status==="Resuelta") ? 'selected="selected"' : ''?>>Resuelta</option>
                                        <option value="Sustituido" <?php echo ($status==="Sustituido") ? 'selected="selected"' : ''?>>Sustituido</option>
                                        <option value="SustituidoRMA" <?php echo ($status==="SustituidoRMA") ? 'selected="selected"' : ''?>>SustituidoRMA</option>
                                        <option value="Pendiente recogida" <?php echo ($status==="Pendiente recogida") ? 'selected="selected"' : ''?>>Pendiente recogida</option>
                                        <option value="Cerrada" <?php echo ($status==="Cerrada") ? 'selected="selected"' : ''?>>Cerrada</option>
                                        <option value="Cancelada" <?php echo ($status==="Cancelada") ? 'selected="selected"' : ''?>>Cancelada</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="status_pds">Estado PDS: </label>
                                    <select name="status_pds" id="status_pds" class="form-control input-sm">
                                        <option value="" <?php echo ($status_pds==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                        <option value="Finalizada" <?php echo ($status_pds==="Finalizada") ? 'selected="selected"' : ''?>>Finalizada</option>
                                        <option value="Cancelada" <?php echo ($status_pds==="Cancelada") ? 'selected="selected"' : ''?>>Cancelada</option>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="territory">Territorio: </label>
                                    <select name="territory" id="territory" class="form-control input-sm">
                                        <option value="" <?php echo ($territory==="") ? 'selected="selected"' : ''?>>Cualquier territorio</option>
                                        <?php
                                        foreach($territorios as $territorio)
                                        {
                                            $attr = ($territorio->id_territory === $territory) ? ' selected="selected" ' :'';
                                            echo '<option value="'.$territorio->id_territory.'" '.$attr.'>'.$territorio->territory.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="col-lg-3">
                                    <label for="brand_device">Fabricante: </label>
                                    <select name="brand_device" id="brand_device" class="form-control input-sm">
                                        <option value="" <?php echo ($brand_device==="") ? 'selected="selected"' : ''?>>Cualquier fabricante</option>
                                        <?php
                                        foreach($fabricantes as $fabricante)
                                        {
                                            $attr = ($fabricante->id_brand_device === $brand_device) ? ' selected="selected" ' :'';
                                            echo '<option value="'.$fabricante->id_brand_device.'" '.$attr.'>'.$fabricante->brand.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-lg-3">
                                    <label for="id_display">Mueble: </label>
                                    <select name="id_display" id="id_display" class="form-control input-sm">
                                        <option value="" <?php echo ($id_display==="") ? 'selected="selected"' : ''?>>Cualquier mueble</option>
                                        <?php
                                        foreach($muebles as $mueble)
                                        {
                                            $attr = ($mueble->id_display === $id_display) ? ' selected="selected" ' :'';
                                            echo '<option value="'.$mueble->id_display.'" '.$attr.'>'.$mueble->display.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="id_device">Terminal: </label>
                                    <select name="id_device" id="id_device" class="form-control input-sm">
                                        <option value="" <?php echo ($id_device==="") ? 'selected="selected"' : ''?>>Cualquiera</option>
                                        <?php
                                        foreach($terminales as $terminal)
                                        {
                                            $attr = ($terminal->id_device === $id_device) ? ' selected="selected" ' :'';
                                            echo '<option value="'.$terminal->id_device.'" '.$attr.'>'.$terminal->device.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="col-lg-3">
                                    <label for="id_supervisor">Supervisor: </label>
                                    <select name="id_supervisor" id="id_supervisor" class="form-control input-sm">
                                        <option value="" <?php echo ($id_supervisor==="") ? 'selected="selected"' : ''?>>Cualquiera</option>
                                        <?php
                                        foreach($supervisores as $supervisor)
                                        {
                                            $attr = ($supervisor->id === $id_supervisor) ? ' selected="selected" ' :'';
                                            echo '<option value="'.$supervisor->id.'" '.$attr.'>'.$supervisor->titulo.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="id_provincia">Provincia: </label>
                                    <select name="id_provincia" id="id_provincia" class="form-control input-sm">
                                        <option value="" <?php echo ($id_provincia ==="") ? 'selected="selected"' : ''?>>Cualquiera</option>
                                        <?php
                                        foreach($provincias as $provincia)
                                        {
                                            $attr = ($provincia->id === $id_provincia) ? ' selected="selected" ' :'';
                                            echo '<option value="'.$provincia->id.'" '.$attr.'>'.$provincia->titulo.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-lg-3">
                                    <label for="id_tipo_incidencia">Razon parada: </label>
                                    <select name="id_tipo_incidencia" id="id_tipo_incidencia" class="form-control input-sm">
                                        <option value="" <?php echo ($id_tipo_incidencia ==="") ? 'selected="selected"' : ''?>>Cualquiera</option>
                                        <?php

                                        foreach($tipos_incidencia as $tip)
                                        {
                                            $attr = ($tip->id_type_incidencia === $id_tipo_incidencia) ? ' selected="selected" ' :'';
                                            echo '<option value="'.$tip->id_type_incidencia.'" '.$attr.'>'.$tip->title.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-lg-2">
                                    <label for="id_incidencia">Incidencia: </label>
                                    <input type="text" name="id_incidencia" id="id_incidencia" class="form-control input-sm" placeholder="Id. incidencia" <?php echo (!empty($id_incidencia)) ? ' value="'.$id_incidencia.'" ' : ''?> />
                                </div>
                                <div class="col-lg-2">
                                    <label for="reference">SFID: </label>
                                    <input type="text" name="reference" id="reference" class="form-control input-sm" placeholder="SFID" <?php echo (!empty($reference)) ? ' value="'.$reference.'" ' : ''?> />
                                </div>
                                <div class="col-lg-2">
                                    <label for="id_intervencion">Id. intervención: </label>
                                    <input type="text" name="id_intervencion" id="id_intervencion" class="form-control input-sm" placeholder="Id. intervención" <?php echo (!empty($id_intervencion)) ? ' value="'.$id_intervencion.'" ' : ''?> />
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <input type="hidden" name="do_busqueda" value="si">
                                        <input type="submit" value="Buscar" id="submit_button" class="form-control input-sm">
                                        <a href="<?=base_url()?>admin/estado_incidencias/<?=$tipo?>/borrar_busqueda" class="reiniciar_busqueda form-control input-sm">Reiniciar</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                        </div>

                        <div class="col-lg-12">

                        <?php
                        if (empty($incidencias)) {
                            echo '<p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> No hay incidencias cerradas.</p>';
                        } else {
                            ?>



                             <?php if($show_paginator) { ?>
                                <div class="pagination">

                                    <ul class="pagination">
                                        <?php echo "".$pagination_helper->create_links(); ?>
                                    </ul>
                                    <p>Encontrados <?=$num_resultados?> resultados. Mostrando del <?=$n_inicial?> al <?=$n_final?>.</p>
                                </div>
                             <?php }?>

                            <p><a href="<?=base_url()?>admin/exportar_incidencias/cerradas" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a>
                                <a href="<?=base_url()?>admin/exportar_incidencias/cerradas/xlsx/robo" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar robos por tipo</a>
                                <a href="<?=base_url()?>admin/exportar_incidencias/cerradas/xlsx/porrazon" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar por razon de parada</a></p>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-sorting" id="table_incidencias_cerradas"  data-order-form="form_orden_cerradas">
                                    <thead>
                                    <tr>
                                        <th class="sorting" data-rel="incidencias.id_incidencia"    data-order="">Ref.</th>
                                        <th class="sorting" data-rel="pds.reference"                data-order="">SFID</th>
                                        <th class="sorting" data-rel="incidencias.fecha"            data-order="desc">Fecha alta</th>
                                        <th class="principal"                                                                   >Elemento afectado</th>
                                        <?php
                                        /*<th class="sorting" data-rel="incidencias.alarm_display"    data-order="">Sistema general de seguridad</th>
                                        <th class="sorting" data-rel="incidencias.fail_device"    data-order="">Dispositivo</th>
                                        <th class="sorting" data-rel="incidencias.alarm_device"    data-order="">Alarma dispositivo cableado</th>
                                        <th class="sorting" data-rel="incidencias.alarm_garra"    data-order="">Soporte sujección</th> */ ?>

                                        <th class=""                                                             >Territorio</th>
                                        <th class=""                                                             >Fabricante</th>

                                        <th>Última modificación</th>
                                        <th class="sorting" data-rel="incidencias.tipo_averia"    data-order="">Tipo incidencia</th>
                                        <th class="sorting" data-rel="pds_supervisor.titulo"    data-order="">Supervisor</th>
                                        <th class="sorting"  data-rel="province.province"    data-order="">Provincia</th>

                                        <th                                                                     >Interv.</th>
                                        <th class="sorting" data-rel="incidencias.status"    data-order="">Estado SAT</th>
                                        <th class="sorting" data-rel="incidencias.status_pds"    data-order="">Estado PDS</th>
                                        <th class="sorting" data-rel="nuevos"    data-order="desc">Chat offline</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($incidencias as $incidencia) {
                                        ?>
                                        <tr>
                                            <td><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>"><?php echo $incidencia->id_incidencia?></a></td>
                                            <td><?php echo $incidencia->reference ?></td>
                                            <td><?php echo date_format(date_create($incidencia->fecha), 'd/m/Y'); ?></td>
                                            <?php
                                            if (!isset($incidencia->device))
                                            {
                                                $dispositivo = 'Retirado';
                                            }
                                            else
                                            {
                                                $dispositivo = $incidencia->device;
                                            }
                                            if (!isset($incidencia->display))
                                            {
                                                $mueble = 'Retirado';
                                            }
                                            else
                                            {
                                                $mueble = $incidencia->display;
                                            }
                                            ?>
                                            <td class="principal"><?=($incidencia->alarm_display==1)?'Mueble: '.$mueble:'Dispositivo: '.$dispositivo?></td>
                                            <?php /*

		                                <td><?=($incidencia->alarm_display==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->fail_device==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->alarm_device==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->alarm_garra==1)?'&#x25cf;':''?></td> */?>
                                            <td><?=(!empty($incidencia->territory)? $incidencia->territory : '-')?></td>
                                            <td><?=(!empty($incidencia->brand)? $incidencia->brand : '-')?></td>

                                            <td>
                                                <?php $last_updated = $incidencia->last_updated;
                                                echo (is_null($last_updated)) ? "-" : date("d/m/Y", strtotime($last_updated));
                                                ?>
                                            </td>
                                            <td><?php echo $incidencia->tipo_averia ?></td>
                                            <td><?php echo $incidencia->supervisor ?></td>
                                            <td><?php echo $incidencia->provincia ?></td>

                                            <td>
                                                <?php if($incidencia->intervencion != NULL){?>
                                                    <i onClick="showModalViewIntervencion(<?php echo $incidencia->intervencion ?>);" class="fa fa-eye"></i>
                                                <?php }
                                                else{
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <td><strong><?php echo $incidencia->status ?></strong></td>
                                            <td><strong><?php echo $incidencia->status_pds ?></strong></td>

                                            <td><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>#chat"><strong> <i class="fa fa-whatsapp <?=($incidencia->nuevos > 0) ? 'chat_nuevo' :'chat_leido'  /*($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'*/ ?>"> <?=$incidencia->nuevos?></i></strong></a></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <form action="<?=base_url()?>admin/estado_incidencias/<?=$tipo?>" method="post" id="form_orden_<?=$tipo?>">
                                    <input type="hidden" name="form_orden_<?=$tipo?>_campo_orden"  value="">
                                    <input type="hidden" name="form_orden_<?=$tipo?>_orden_campo" value="">
                                    <input type="hidden" name="form"  value="">
                                    <input type="hidden" name="ordenar" value="true">
                                    <?php //<input type="submit"> ?>
                                </form>
                                <script>
                                    <?php if(!empty($campo_orden) && !empty($orden_campo)) {?>
                                        marcarOrdenacion('table_incidencias_<?=$tipo?>','<?=$campo_orden?>','<?=$orden_campo ?>');
                                    <?php } ?>
                                </script>
                            </div>
                            <div class="pagination">

                                <ul class="pagination">
                                    <?php echo "".$pagination_helper->create_links(); ?>
                                </ul>
                                <p>Encontrados <?=$num_resultados?> resultados. Mostrando del <?=$n_inicial?> al <?=$n_final?>.</p>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
        </div>
        <!-- /#page-wrapper -->
<?php $this->load->view('backend/intervenciones/ver_intervencion_incidencia');?>
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

