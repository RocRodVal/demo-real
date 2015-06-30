<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row" id="incidencias_abiertas">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo  $title ?></h1>
                 </div>
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title_iniciadas ?> <a href="#incidencias_cerradas" id="link_incidencias_abiertas" rel="link_incidencias_cerradas" class="scrollTo">Cerradas <span>&#9660;</span></a></h1>
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
                                            <th>Zona</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($tiendas as $tienda)
    									{
    									?>
    									<tr>
    										<td><a href="<?=site_url('tienda/alta_incidencia/'.$tienda->id_pds)?>"><?php echo $tienda->reference ?></a></td>
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
            <div class="row" >
                <div class="col-lg-12">
                    <div class="row buscador">
                        <form action="<?=base_url()?>tienda/dashboard/#incidencias_abiertas" method="post" class="filtros">
                           <?php /* <div class="col-sm-2">
                                <label for="filtrar">Estado SAT: </label>
                                <select name="filtrar" id="filtrar" class="form-control input-sm">
                                    <option value="" <?php echo ($filtro==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>
                                    <option value="Nueva" <?php echo ($filtro==="Nueva") ? 'selected="selected"' : ''?>>Nuevas</option>
                                    <option value="Revisada" <?php echo ($filtro==="Revisada") ? 'selected="selected"' : ''?>>Revisadas</option>
                                    <option value="Instalador asignado" <?php echo ($filtro==="Instalador asignado") ? 'selected="selected"' : ''?>>Instalador asignado</option>
                                    <option value="Material asignado" <?php echo ($filtro==="Material asignado") ? 'selected="selected"' : ''?>>Material asignado</option>
                                    <option value="Comunicada" <?php echo ($filtro==="Comunicada") ? 'selected="selected"' : ''?>>Comunicadas</option>


                                </select>
                            </div>*/ ?>
                            <div class="col-sm-2">
                                <label for="filtrar_pds">Estado: </label>
                                <select name="filtrar_pds" id="filtrar_pds" class="form-control input-sm">
                                    <option value="" <?php echo ($filtro_pds==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                    <option value="Alta realizada" <?php echo ($filtro_pds==="Alta realizada") ? 'selected="selected"' : ''?>>Alta realizada</option>
                                    <option value="En proceso" <?php echo ($filtro_pds==="En proceso") ? 'selected="selected"' : ''?>>En proceso</option>
                                    <option value="En visita" <?php echo ($filtro_pds==="En visita") ? 'selected="selected"' : ''?>>En visita</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <input type="submit" value="Aplicar" class="form-control input-sm">
                            </div>
                            <div class="col-sm-1">
                                <?php /* if(! empty($filtro) || ! empty($filtro_pds)) { ?>
                                    <a href="<?=base_url()?>tienda/dashboard/borrar_busqueda/#incidencias_abiertas" class="reiniciar_busqueda"><i class="glyphicon glyphicon-remove"></i>  Reiniciar</a>
                                <?php }*/ ?>


                            </div>

                            <div class="col-sm-2">
                                <label for="buscar_incidencia">Buscar incidencia: </label>
                                <input type="text" name="buscar_incidencia" id="buscar_incidencia" class="form-control input-sm" placeholder="Ref. incidencia" <?php echo (!empty($buscar_incidencia)) ? ' value="'.$buscar_incidencia.'" ' : ''?> />
                            </div>

                            <div class="col-sm-1">
                                <input type="hidden" name="do_busqueda" value="si">
                                <input type="submit" value="Buscar" class="form-control input-sm">
                            </div>
                            <div class="col-sm-1">
                                <?php /* if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                                    <a href="<?=base_url()?>tienda/dashboard/borrar_busqueda/#incidencias_abiertas" class="reiniciar_busqueda"> <i class="glyphicon glyphicon-remove"></i>  Reiniciar</a>
                                <?php } */ ?>
                            </div>
                        </form>
                    </div>

		            <?php
		            if (empty($incidencias)) {
                        echo '<p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> No hay incidencias abiertas.</p>'; ?>


                        <?php /* if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                            <a href="<?=base_url()?>tienda/dashboard/borrar_busqueda/#incidencias_abiertas" class="reiniciar_busqueda"> <i class="glyphicon glyphicon-remove"></i> Reiniciar</a>
                        <?php }*/ ?>

		            <?php } else {
		                ?>
                        <?php if($show_paginator) { ?>
                            <div class="pagination">
                                <ul class="pagination">
                                    <?php echo "".$pagination_helper->create_links(); ?>
                                </ul>
                                <p>Encontrados <?=$num_resultados?> resultados. Mostrando del <?=$n_inicial?> al <?=$n_final?>.</p>
                            </div>
                        <?php } ?>

                        <p><a href="<?=base_url()?>tienda/dashboard_exportar/abiertas" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar CSV</a></p>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover table-sorting" id="table_incidencias_dashboard" data-order-form="form_orden_activas">
		                        <thead>
		                        <tr>
		                            <th class="sorting" data-rel="incidencias.id_incidencia"    data-order="">Ref.</th>
		                            <th class="sorting" data-rel="pds.reference"                data-order="">SFID</th>
		                            <th class="sorting" data-rel="incidencias.fecha"            data-order="desc">Fecha alta</th>
		                            <th class="principal"                                                             >Elemento afectado</th>
		                            <th class="sorting" data-rel="incidencias.alarm_display"    data-order="">Sistema general alarma</th>
		                            <th class="sorting" data-rel="incidencias.fail_device"    data-order="">Dispositivo</th>
		                        	<th class="sorting" data-rel="incidencias.alarm_device"    data-order="">Alarma dispositivo cableado</th>
		                            <th class="sorting" data-rel="incidencias.alarm_garra"    data-order="">Soporte sujección</th>
		                            <th class="sorting" data-rel="incidencias.tipo_averia"    data-order="">Tipo incidencia</th>


                                    <th class="sorting" data-rel="incidencias.status_pds"    data-order="">Estado</th>
		                            <th>Chat offline</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($incidencias as $incidencia) {
		                            ?>
		                            <tr>
		                                <td><a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>"><?php echo $incidencia->id_incidencia?></a></td>
		                                <!--<td><a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_incidencia)?>">#<?php echo $incidencia->id_incidencia ?></a></td>-->
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
                                        <?php /*<td>
	                                        <?php if($incidencia->intervencion != NULL){?>
	                                        <i onClick="showModalViewIntervencion(<?php echo $incidencia->intervencion ?>);" class="fa fa-eye"></i>
	                                        <?php }
	                                        else{
	                                        echo "-";
	                                        }
	                                        ?>
                                        </td>*/ ?>

		                                <td><strong><?php echo $incidencia->status_pds ?></strong></td>

		                                <td  onClick="window.location.href='<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>'"><a href="<?=site_url('tienda/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>#chat"><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"></i></strong></a></td>
		                            </tr>
		                        <?php
		                        }
		                        ?>
		                        </tbody>
		                    </table>
                            <form action="<?=base_url()?>tienda/dashboard/#incidencias_abiertas" method="post" id="form_orden_activas">
                                <input type="hidden" name="form_orden_activas_campo"  value="">
                                <input type="hidden" name="form_orden_activas_orden" value="">
                                <input type="hidden" name="form"  value="">
                                <input type="hidden" name="ordenar" value="true">
                                <?php //<input type="submit"> ?>
                            </form>
                            <script>
                                <?php if(!empty($campo_orden_activas) && !empty($orden_activas)) {?>
                                    marcarOrdenacion('table_incidencias_dashboard','<?=$campo_orden_activas?>','<?=$orden_activas ?>');
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

                <div  id="incidencias_cerradas">
                    <hr>
                    <div class="col-lg-12" >
                        <h1 class="page-header"><?php echo $title_finalizadas ?> <a href="#incidencias_abiertas" id="link_incidencias_cerradas" rel="link_incidencias_abiertas" class="scrollTo">Abiertas <span>&#9650;</span></a></h1>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="row filtro">
                            <form action="<?=base_url()?>tienda/dashboard/#incidencias_cerradas" method="post" class="filtros">

                                <?php /*<div class="col-sm-2">
                                    <label for="filtrar_finalizadas">Estado SAT: </label>
                                    <select name="filtrar_finalizadas" id="filtrar_finalizadas" class="form-control input-sm">
                                        <option value="" <?php echo ($filtro_finalizadas==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                        <option value="Resuelta" <?php echo ($filtro_finalizadas==="Resuelta") ? 'selected="selected"' : ''?>>Resuelta</option>
                                        <option value="Pendiente recogida" <?php echo ($filtro_finalizadas==="Pendiente recogida") ? 'selected="selected"' : ''?>>Pendiente recogida</option>
                                        <option value="Cerrada" <?php echo ($filtro_finalizadas==="Cerrada") ? 'selected="selected"' : ''?>>Cerrada</option>
                                        <option value="Cancelada" <?php echo ($filtro_finalizadas==="Cancelada") ? 'selected="selected"' : ''?>>Cancelada</option>
                                    </select>
                                </div> */ ?>
                                <div class="col-sm-2">
                                    <label for="filtrar_finalizadas_pds">Estado: </label>
                                    <select name="filtrar_finalizadas_pds" id="filtrar_finalizadas_pds" class="form-control input-sm">
                                        <option value="" <?php echo ($filtro_finalizadas_pds==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                        <option value="Finalizada" <?php echo ($filtro_finalizadas_pds==="Finalizada") ? 'selected="selected"' : ''?>>Finalizada</option>
                                        <option value="Cancelada" <?php echo ($filtro_finalizadas_pds==="Cancelada") ? 'selected="selected"' : ''?>>Cancelada</option>
                                    </select>
                                </div>

                                <div class="col-sm-1">
                                    <input type="submit" value="Aplicar" class="form-control input-sm">
                                </div>
                                <div class="col-sm-1">
                                    <?php /* if(! empty($filtro_finalizadas) || ! empty($filtro_finalizadas_pds)) { ?>
                                        <a href="<?=base_url()?>tienda/dashboard/borrar_busqueda/#incidencias_cerradas" class="reiniciar_busqueda"><i class="glyphicon glyphicon-remove"></i>  Reiniciar</a>
                                    <?php }*/ ?>


                                </div>

                                <div class="col-sm-2">
                                    <label for="buscar_incidencia_finalizadas">Buscar incidencia: </label>
                                    <input type="text" name="buscar_incidencia" id="buscar_incidencia_finalizadas" class="form-control input-sm" placeholder="Ref. incidencia" <?php echo (!empty($buscar_incidencia)) ? ' value="'.$buscar_incidencia.'" ' : ''?> />
                                </div>

                                <div class="col-sm-1">
                                    <input type="hidden" name="do_busqueda" value="si">
                                    <input type="submit" value="Buscar" class="form-control input-sm">
                                </div>
                                <div class="col-sm-1">
                                    <?php /* if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                                        <a href="<?=base_url()?>tienda/dashboard/borrar_busqueda/#incidencias_cerradas" class="reiniciar_busqueda"><i class="glyphicon glyphicon-remove"></i>  Reiniciar</a>
                                    <?php }*/  ?>
                                </div>

                            </form>
                        </div>


                        <?php
                        if (empty($incidencias_finalizadas)) {
                            echo '<p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> No hay incidencias finalizadas.</p>';
                        } else {
                            ?>

                             <?php if($show_paginator_finalizadas) { ?>
                                <div class="pagination">
                                    <ul class="pagination">
                                        <?php echo "".$pagination_finalizadas_helper->create_links(); ?>
                                    </ul>
                                    <p>Encontrados <?=$num_resultados_finalizadas?> resultados. Mostrando del <?=$n_inicial_finalizadas?> al <?=$n_final_finalizadas?>.</p>
                                </div>
                             <?php }?>


                            <p><a href="<?=base_url()?>tienda/dashboard_exportar/cerradas" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar CSV</a></p>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-sorting" id="table_incidencias_cerradas_dashboard"  data-order-form="form_orden_cerradas">
                                    <thead>
                                    <tr>
                                        <th class="sorting" data-rel="incidencias.id_incidencia"    data-order="">Ref.</th>
                                        <th class="sorting" data-rel="pds.reference"                data-order="">SFID</th>
                                        <th class="sorting" data-rel="incidencias.fecha"            data-order="desc">Fecha alta</th>
                                        <th class="principal"                                                                   >Elemento afectado</th>
                                        <th class="sorting" data-rel="incidencias.alarm_display"    data-order="">Sistema general alarma</th>
                                        <th class="sorting" data-rel="incidencias.fail_device"    data-order="">Dispositivo</th>
                                        <th class="sorting" data-rel="incidencias.alarm_device"    data-order="">Alarma dispositivo cableado</th>
                                        <th class="sorting" data-rel="incidencias.alarm_garra"    data-order="">Soporte sujección</th>
                                        <th class="sorting" data-rel="incidencias.tipo_averia"    data-order="">Tipo incidencia</th>

                                        <th class="sorting" data-rel="incidencias.status_pds"    data-order="">Estado</th>
                                        <th>Chat offline</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($incidencias_finalizadas as $incidencia) {
                                        ?>
                                        <tr>
                                            <td><a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>"><?php echo $incidencia->id_incidencia?></a></td>
                                            <!--<td><a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_incidencia)?>">#<?php echo $incidencia->id_incidencia ?></a></td>-->
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
                                            <?php
                                            /*<td>
                                                <?php if($incidencia->intervencion != NULL){?>
                                                    <i onClick="showModalViewIntervencion(<?php echo $incidencia->intervencion ?>);" class="fa fa-eye"></i>
                                                <?php }
                                                else{
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <td><strong><?php echo $incidencia->status ?></strong></td>*/?>
                                            <td><strong><?php echo $incidencia->status_pds ?></strong></td>

                                            <td  onClick="window.location.href='<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>'"><a href="<?=site_url('tienda/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>#chat"><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"></i></strong></a></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <form action="<?=base_url()?>tienda/dashboard/#incidencias_cerradas" method="post" id="form_orden_cerradas">
                                    <input type="hidden" name="form_orden_cerradas_campo"  value="">
                                    <input type="hidden" name="form_orden_cerradas_orden" value="">
                                    <input type="hidden" name="form"  value="">
                                    <input type="hidden" name="ordenar_cerradas" value="true">
                                    <?php //<input type="submit"> ?>
                                </form>
                                <script>
                                    <?php if(!empty($campo_orden_cerradas) && !empty($orden_cerradas)) {?>
                                        marcarOrdenacion('table_incidencias_cerradas_dashboard','<?=$campo_orden_cerradas?>','<?=$orden_cerradas ?>');
                                    <?php } ?>
                                </script>
                            </div>
                            <div class="pagination">
                                <p>Encontrados <?=$num_resultados_finalizadas?> resultados. Mostrando del <?=$n_inicial_finalizadas?> al <?=$n_final_finalizadas?>.</p>
                                <ul class="pagination">
                                    <?php echo "".$pagination_finalizadas_helper->create_links(); ?>
                                </ul>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

        </div>
        <!-- /#page-wrapper -->
        <?php $this->load->view('backend/intervenciones/ver_intervencion_incidencia');?>