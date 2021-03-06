<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div id="incidencias_abiertas">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
           </div>

            <div class="row" >
                <div class="col-lg-12">
                    <div class="filtro">
                        <form action="<?=base_url()?>tienda/estado_incidencias/abiertas" method="post" class="filtros form-mini autosubmit col-lg-12">

                            <div class="col-lg-3">
                                <label for="status_pds">Estado PDS: </label>
                                <select name="status_pds" id="status_pds" class="form-control input-sm">
                                    <option value="" <?php echo ($status_pds==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                    <option value="Alta realizada" <?php echo ($status_pds==="Alta realizada") ? 'selected="selected"' : ''?>>Alta realizada</option>
                                    <option value="En proceso" <?php echo ($status_pds==="En proceso") ? 'selected="selected"' : ''?>>En proceso</option>
                                    <option value="En visita" <?php echo ($status_pds==="En visita") ? 'selected="selected"' : ''?>>En visita</option>
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

                            <div class="col-lg-2">
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

                            <div class="col-lg-2">
                                <label for="id_incidencia">Id. Incidencia: </label>
                                <input type="text" name="id_incidencia" id="id_incidencia" class="form-control input-sm" placeholder="Id. incidencia" <?php echo (!empty($id_incidencia)) ? ' value="'.$id_incidencia.'" ' : ''?> />
                            </div>

                            <div class="col-lg-1">
                                <div class="form-group">
                                    <input type="hidden" name="do_busqueda" value="si">
                                    <input type="submit" value="Buscar" id="submit_button" class="form-control input-sm">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="col-lg-12">
		            <?php
		            if (empty($incidencias)) {
                        echo '<p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> No hay incidencias abiertas.</p>'; ?>
		            <?php } else {
		                 if($show_paginator) { ?>
                            <div class="pagination">
                                <ul class="pagination">
                                    <?php echo "".$pagination_helper->create_links(); ?>
                                </ul>
                                <p>Encontrados <?=$num_resultados?> resultados. Mostrando del <?=$n_inicial?> al <?=$n_final?>.</p>
                            </div>
                        <?php } ?>

                        <p><a href="<?=base_url()?>tienda/exportar_incidencias/abiertas/xls" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a></p>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover table-sorting" id="table_incidencias_dashboard" data-order-form="form_orden">
		                        <thead>
		                        <tr>
		                            <th class="sorting" data-rel="incidencias.id_incidencia"    data-order="">Ref.</th>
		                            <th class="sorting" data-rel="pds.reference"                data-order="">SFID</th>
		                            <th class="sorting" data-rel="incidencias.fecha"            data-order="desc">Fecha alta</th>
		                            <th class="principal">Elemento afectado</th>
                                    <th class="">Territorio</th>
                                    <th class="">Fabricante</th>
                                    <th>??ltima modificaci??n</th>
                                    <th class="sorting" data-rel="incidencias.tipo_averia"    data-order="">Tipo incidencia</th>
		                            <!--<th                                                                     >Interv.</th>-->
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

                                            <a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>">
                                                <?php echo $incidencia->id_incidencia?></a></td>

		                                <td><?php echo $incidencia->reference ?></td>
		                                <td><?php echo date_format(date_create($incidencia->fecha), 'd/m/Y'); ?></td>
		                                <?php 
		                                if (!isset($incidencia->device)){
		                                	$dispositivo = 'Retirado';
		                                }else{
		                                	$dispositivo = $incidencia->device;
		                                }
		                                if (!isset($incidencia->display)){
		                                	$mueble = 'Retirado';
		                                }else{
		                                	$mueble = $incidencia->display;
		                                }?>
                                        <td class="principal"><?=($incidencia->alarm_display==1)?'Mueble: '.$mueble:'Dispositivo: '.$dispositivo?></td>
                                        <td><?=(!empty($incidencia->territory)? $incidencia->territory : '-')?></td>
                                        <td><?=(!empty($incidencia->brand)? $incidencia->brand : '-')?></td>
                                        <td>
                                            <?php $last_updated = $incidencia->last_updated;
                                            echo (is_null($last_updated)) ? "-" : date("d/m/Y", strtotime($last_updated));
                                            ?>
                                        </td>
		                                <td><?php echo $incidencia->tipo_averia ?></td>
                                        <?php /*
                                        <td>
	                                        <?php if($incidencia->intervencion != NULL){?>
	                                        <i onClick="showModalViewIntervencion(<?php echo $incidencia->intervencion ?>);" class="fa fa-eye"></i>
	                                        <?php }
	                                        else{
	                                        echo "-";
	                                        }
	                                        ?>
                                        </td> */?>
		                                <td><strong><?php echo $incidencia->status_pds ?></strong></td>
		                                <td>
                                            <a href="<?=site_url('tienda/detalle_incidencia/'.$incidencia->id_incidencia.'/'.$incidencia->id_pds)?>#chat"><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos >0)?'chat_nuevo':'chat_leido'?>"></i></strong></a></td>
		                            </tr>
		                        <?php
		                        }
		                        ?>
		                        </tbody>
		                    </table>
                            <form action="<?=base_url()?>tienda/estado_incidencias/abiertas" method="post" id="form_orden">
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
        <?php //$this->load->view('backend/intervenciones/ver_intervencion_incidencia');?>