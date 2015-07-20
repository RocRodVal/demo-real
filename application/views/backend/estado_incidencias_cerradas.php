<!-- #page-wrapper -->
        <div id="page-wrapper">

                <div  id="incidencias_cerradas">

                    <div class="col-lg-12" >
                        <h1 class="page-header"><?php echo $title_cerradas ?> </h1>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="row filtro">
                            <form action="<?=base_url()?>admin/estado_incidencias_cerradas" method="post" class="filtros">

                                <div class="col-sm-2">
                                    <label for="filtrar_cerradas">Estado SAT: </label>
                                    <select name="filtrar_cerradas" id="filtrar_cerradas" class="form-control input-sm">
                                        <option value="" <?php echo ($filtro_cerradas==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                        <option value="Resuelta" <?php echo ($filtro_cerradas==="Resuelta") ? 'selected="selected"' : ''?>>Resuelta</option>
                                        <option value="Pendiente recogida" <?php echo ($filtro_cerradas==="Pendiente recogida") ? 'selected="selected"' : ''?>>Pendiente recogida</option>
                                        <option value="Cerrada" <?php echo ($filtro_cerradas==="Cerrada") ? 'selected="selected"' : ''?>>Cerrada</option>
                                        <option value="Cancelada" <?php echo ($filtro_cerradas==="Cancelada") ? 'selected="selected"' : ''?>>Cancelada</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="filtrar_cerradas_pds">Estado PDS: </label>
                                    <select name="filtrar_cerradas_pds" id="filtrar_cerradas_pds" class="form-control input-sm">
                                        <option value="" <?php echo ($filtro_cerradas_pds==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                        <option value="Finalizada" <?php echo ($filtro_cerradas_pds==="Finalizada") ? 'selected="selected"' : ''?>>Finalizada</option>
                                        <option value="Cancelada" <?php echo ($filtro_cerradas_pds==="Cancelada") ? 'selected="selected"' : ''?>>Cancelada</option>
                                    </select>
                                </div>

                                <div class="col-sm-1">
                                    <input type="submit" value="Aplicar" class="form-control input-sm">
                                </div>
                                <div class="col-sm-1">
                                    <?php /* if(! empty($filtro_finalizadas) || ! empty($filtro_finalizadas_pds)) { ?>
                                        <a href="<?=base_url()?>admin/dashboard/borrar_busqueda/#incidencias_cerradas" class="reiniciar_busqueda"><i class="glyphicon glyphicon-remove"></i>  Reiniciar</a>
                                    <?php }*/ ?>


                                </div>

                                <div class="col-sm-2">
                                    <label for="buscar_incidencia_cerradas">Buscar incidencia: </label>
                                    <input type="text" name="buscar_incidencia" id="buscar_incidencia_cerradas" class="form-control input-sm" placeholder="Ref. incidencia" <?php echo (!empty($buscar_incidencia)) ? ' value="'.$buscar_incidencia.'" ' : ''?> />
                                </div>
                                <div class="col-sm-2">
                                    <label for="buscar_sfid_cerradas">Buscar SFID: </label>
                                    <input type="text" name="buscar_sfid" id="buscar_sfid_cerradas" class="form-control input-sm" placeholder="SFID" <?php echo (!empty($buscar_sfid)) ? ' value="'.$buscar_sfid.'" ' : ''?> />
                                </div>
                                <div class="col-sm-1">
                                    <input type="hidden" name="do_busqueda" value="si">
                                    <input type="submit" value="Buscar" class="form-control input-sm">
                                </div>
                                <div class="col-sm-1">
                                    <?php /* if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                                        <a href="<?=base_url()?>admin/dashboard/borrar_busqueda/#incidencias_cerradas" class="reiniciar_busqueda"><i class="glyphicon glyphicon-remove"></i>  Reiniciar</a>
                                    <?php }*/ ?>
                                </div>

                            </form>
                        </div>


                        <?php
                        if (empty($incidencias_cerradas)) {
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

                            <p><a href="<?=base_url()?>admin/exportar_incidencias_cerradas" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar CSV</a></p>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-sorting" id="table_incidencias_cerradas"  data-order-form="form_orden_cerradas">
                                    <thead>
                                    <tr>
                                        <th class="sorting" data-rel="incidencias.id_incidencia"    data-order="">Ref.</th>
                                        <th class="sorting" data-rel="pds.reference"                data-order="">SFID</th>
                                        <th class="sorting" data-rel="incidencias.fecha"            data-order="desc">Fecha alta</th>
                                        <th class="principal"                                                                   >Elemento afectado</th>
                                        <th class="sorting" data-rel="incidencias.alarm_display"    data-order="">Sistema general de seguridad</th>
                                        <th class="sorting" data-rel="incidencias.fail_device"    data-order="">Dispositivo</th>
                                        <th class="sorting" data-rel="incidencias.alarm_device"    data-order="">Alarma dispositivo cableado</th>
                                        <th class="sorting" data-rel="incidencias.alarm_garra"    data-order="">Soporte sujección</th>
                                        <th class="sorting" data-rel="incidencias.tipo_averia"    data-order="">Tipo incidencia</th>
                                        <th                                                                     >Interv.</th>
                                        <th class="sorting" data-rel="incidencias.status"    data-order="">Estado SAT</th>
                                        <th class="sorting" data-rel="incidencias.status_pds"    data-order="">Estado PDS</th>
                                        <th>Chat offline</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($incidencias_cerradas as $incidencia) {
                                        ?>
                                        <tr>
                                            <td><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>"><?php echo $incidencia->id_incidencia?></a></td>
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
                                            <td class="principal"><?=($incidencia->alarm_display==1)?'Mueble: '.$mueble:'Dispositivo: '.$dispositivo?></td>
                                            <td><?=($incidencia->alarm_display==1)?'&#x25cf;':''?></td>
                                            <td><?=($incidencia->fail_device==1)?'&#x25cf;':''?></td>
                                            <td><?=($incidencia->alarm_device==1)?'&#x25cf;':''?></td>
                                            <td><?=($incidencia->alarm_garra==1)?'&#x25cf;':''?></td>
                                            <td><?php echo $incidencia->tipo_averia ?></td>
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

                                            <td  onClick="window.location.href='<?=site_url('admin/operar_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>'"><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>#chat"><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"></i></strong></a></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <form action="<?=base_url()?>admin/estado_incidencias_cerradas" method="post" id="form_orden_cerradas">
                                    <input type="hidden" name="form_orden_cerradas_campo"  value="">
                                    <input type="hidden" name="form_orden_cerradas_orden" value="">
                                    <input type="hidden" name="form"  value="">
                                    <input type="hidden" name="ordenar_cerradas" value="true">
                                    <?php //<input type="submit"> ?>
                                </form>
                                <script>
                                    <?php if(!empty($campo_orden_cerradas) && !empty($orden_cerradas)) {?>
                                        marcarOrdenacion('table_incidencias_cerradas','<?=$campo_orden_cerradas?>','<?=$orden_cerradas ?>');
                                    <?php } ?>
                                </script>
                            </div>
                            <div class="pagination">
                                <p>Encontrados <?=$num_resultados?> resultados. Mostrando del <?=$n_inicial?> al <?=$n_final?>.</p>
                                <ul class="pagination">
                                    <?php echo "".$pagination_helper->create_links(); ?>
                                </ul>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

        </div>
        <!-- /#page-wrapper -->
        <?php $this->load->view('backend/intervenciones/ver_intervencion_incidencia');?>