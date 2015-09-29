<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row" id="incidencias_abiertas">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
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
                                            <th>Territorio</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($tiendas as $tienda)
    									{
    									?>
    									<tr>
    										<td><a href="<?=site_url('territorio/alta_incidencia/'.$tienda->id_pds)?>"><?php echo $tienda->reference ?></a></td>
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
                        <form action="<?=base_url()?>territorio/estado_incidencias/abiertas" method="post" class="filtros form-mini">
                            <?php /*<div class="col-lg-2">
                                <label for="status">Estado SAT: </label>
                                <select name="status" id="status" class="form-control input-sm">
                                    <option value="" <?php echo ($status==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>
                                    <option value="Nueva" <?php echo ($status==="Nueva") ? 'selected="selected"' : ''?>>Nuevas</option>
                                    <option value="Revisada" <?php echo ($status==="Revisada") ? 'selected="selected"' : ''?>>Revisadas</option>
                                    <option value="Instalador asignado" <?php echo ($status==="Instalador asignado") ? 'selected="selected"' : ''?>>Instalador asignado</option>
                                    <option value="Material asignado" <?php echo ($status==="Material asignado") ? 'selected="selected"' : ''?>>Material asignado</option>
                                    <option value="Comunicada" <?php echo ($status==="Comunicada") ? 'selected="selected"' : ''?>>Comunicadas</option>


                                </select>
                            </div>*/ ?>
                            <div class="col-lg-2">
                                <label for="status_pds">Estado PDS: </label>
                                <select name="status_pds" id="status_pds" class="form-control input-sm">
                                    <option value="" <?php echo ($status_pds==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                    <option value="Alta realizada" <?php echo ($status_pds==="Alta realizada") ? 'selected="selected"' : ''?>>Alta realizada</option>
                                    <option value="En proceso" <?php echo ($status_pds==="En proceso") ? 'selected="selected"' : ''?>>En proceso</option>
                                    <option value="En visita" <?php echo ($status_pds==="En visita") ? 'selected="selected"' : ''?>>En visita</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
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

                            <div class="col-lg-2">
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

                            <div class="col-lg-2">
                                <label for="id_incidencia">Id. Incidencia: </label>
                                <input type="text" name="id_incidencia" id="id_incidencia" class="form-control input-sm" placeholder="Id. incidencia" <?php echo (!empty($id_incidencia)) ? ' value="'.$id_incidencia.'" ' : ''?> />
                            </div>
                            <div class="col-lg-1">
                                <label for="reference">SFID: </label>
                                <input type="text" name="reference" id="reference" class="form-control input-sm" placeholder="SFID" <?php echo (!empty($reference)) ? ' value="'.$reference.'" ' : ''?> />
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <input type="hidden" name="do_busqueda" value="si">
                                    <input type="submit" value="Buscar" id="submit_button" class="form-control input-sm">
                                    </div>
                            </div>

                        </form>
                    </div>

		            <?php
		            if (empty($incidencias)) {
                        echo '<p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> No hay incidencias abiertas.</p>'; ?>


                        <?php  /*if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                            <a href="<?=base_url()?>admin/dashboard/borrar_busqueda/#incidencias_abiertas" class="reiniciar_busqueda"> <i class="glyphicon glyphicon-remove"></i> Reiniciar</a>
                        <?php }*/  ?>

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

                        <p><a href="<?=base_url()?>territorio/exportar_incidencias/abiertas" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a></p>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover table-sorting" id="table_incidencias_dashboard" data-order-form="form_orden">
		                        <thead>
		                        <tr>
		                            <th class="sorting" data-rel="incidencias.id_incidencia"    data-order="">Ref.</th>
		                            <th class="sorting" data-rel="pds.reference"                data-order="">SFID</th>
		                            <th class="sorting" data-rel="incidencias.fecha"            data-order="desc">Fecha alta</th>
		                            <th class="principal"                                                             >Elemento afectado</th>


                                    <?php /*
                                    <th class="sorting" data-rel="incidencias.alarm_display"    data-order="">Sistema general de seguridad</th>
                                    <th class="sorting" data-rel="incidencias.fail_device"    data-order="">Dispositivo</th>
                                    <th class="sorting" data-rel="incidencias.alarm_device"    data-order="">Alarma dispositivo cableado</th>
                                    <th class="sorting" data-rel="incidencias.alarm_garra"    data-order="">Soporte sujección</th>*/?>
                                    <th class=""                                                             >Territorio</th>
                                    <th class=""                                                             >Fabricante</th>


                                    <th>Última modificación</th>
                                    <th class="sorting" data-rel="incidencias.tipo_averia"    data-order="">Tipo incidencia</th>
		                            <th                                                                     >Interv.</th>
		                            <?php /*<th class="sorting" data-rel="incidencias.status"    data-order="">Estado SAT</th>*/?>
                                    <th class="sorting" data-rel="incidencias.status_pds"    data-order="">Estado PDS</th>
		                            <th>Chat offline</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($incidencias as $incidencia) {
		                            ?>
		                            <tr>
		                                <td>

                                            <a href="<?=site_url('territorio/detalle_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>">
                                                <?php echo $incidencia->id_incidencia?></a></td>

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
                                        <td><?=(!empty($incidencia->territory)? $incidencia->territory : '-')?></td>
                                        <td><?=(!empty($incidencia->brand)? $incidencia->brand : '-')?></td>


                                        <?php
                                        /*<td><?=($incidencia->alarm_display==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->fail_device==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->alarm_device==1)?'&#x25cf;':''?></td>
		                                <td><?=($incidencia->alarm_garra==1)?'&#x25cf;':''?></td>*/?>

                                        <td>
                                            <?php $last_updated = $incidencia->last_updated;
                                            echo (is_null($last_updated)) ? "-" : date("d/m/Y", strtotime($last_updated));
                                            ?>
                                        </td>


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
                                        <?php /*<td><strong><?php echo $incidencia->status ?></strong></td>*/?>
		                                <td><strong><?php echo $incidencia->status_pds ?></strong></td>

		                                <td><a href="<?=site_url('territorio/detalle_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>#chat"><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"></i></strong></a></td>
		                            </tr>
		                        <?php
		                        }
		                        ?>
		                        </tbody>
		                    </table>
                            <form action="<?=base_url()?>territorio/estado_incidencias/abiertas" method="post" id="form_orden">
                                <input type="hidden" name="form_orden_campo_orden"  value="">
                                <input type="hidden" name="form_orden_orden_campo" value="">
                                <input type="hidden" name="form"  value="">
                                <input type="hidden" name="ordenar" value="true">
                                <?php //<input type="submit"> ?>
                            </form>
                            <script>
                                <?php if(!empty($campo_orden) && !empty($orden_campo)) {?>
                                    marcarOrdenacion('table_incidencias_dashboard','<?=$campo_orden?>','<?=$orden_campo?>');
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