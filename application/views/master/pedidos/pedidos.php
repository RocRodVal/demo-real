<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div id="incidencias_abiertas">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
           </div>

            <!-- BUSCADOR DE PEDIDOS -->
            <div class="row" >
                <div class="col-lg-12">
                    <div class="filtro">
                        <form action="<?=base_url()?>admin/pedidos/<?=$tipo?>" method="post" class="filtros form-mini autosubmit col-lg-12">

                            <div class="col-lg-3">
                              <!--  <label for="status">Estado: </label>
                                <select name="status" id="status" class="form-control input-sm">
                                    <option value="" <?php echo ($status==="") ? 'selected="selected"' : ''?>>Cualquier estado</option>

                                    <option value="Nuevo" <?php echo ($status==="Alta realizada") ? 'selected="selected"' : ''?>>Nuevo</option>
                                    <option value="En proceso" <?php echo ($status==="En proceso") ? 'selected="selected"' : ''?>>En proceso</option>
                                    <option value="Pendiente material" <?php echo ($status==="En visita") ? 'selected="selected"' : ''?>>Pendiente material</option>
                                    <option value="Enviado" <?php echo ($status==="Enviado") ? 'selected="selected"' : ''?>>Enviado</option>
                                    <option value="Recibido" <?php echo ($status==="Recibido") ? 'selected="selected"' : ''?>>Recibido</option>
                                    <option value="Cancelado" <?php echo ($status==="Cancelado") ? 'selected="selected"' : ''?>>Cancelado</option>
                                    <option value="Finalizado" <?php echo ($status==="Finalizado") ? 'selected="selected"' : ''?>>Finalizado</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="territory">Territorio: </label>
                                <select name="territory" id="territory" class="form-control input-sm">
                                    <option value="" <?php echo ($territory==="") ? 'selected="selected"' : ''?>>Cualquiera</option>
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
-->

                            <div class="col-lg-4">
                                <label for="id_pedido">Pedido: </label>
                                <input type="text" name="id_pedido" id="id_pedido" class="form-control input-sm" placeholder="Id. pedido" <?php echo (!empty($id_pedido)) ? ' value="'.$id_pedido.'" ' : ''?> />
                            </div>
                            <div class="col-lg-4">
                                <label for="reference">SFID: </label>
                                <input type="text" name="reference" id="reference" class="form-control input-sm" placeholder="SFID" <?php echo (!empty($reference)) ? ' value="'.$reference.'" ' : ''?> />
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <input type="hidden" name="do_busqueda" value="si">
                                    <input type="submit" value="Buscar" id="submit_button" class="form-control input-sm">
                                </div>
                            </div>
                            <div class="clearfix"></div>

                        </form>
                    </div>
                </div>

            <div class="row" >
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <?php
                        if (empty($pedidos)) {
                            echo '<p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> No hay '.$title2.'.</p>'; ?>


                            <?php  /*if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                            <a href="<?=base_url()?>admin/dashboard/borrar_busqueda/#incidencias_abiertas" class="reiniciar_busqueda"> <i class="glyphicon glyphicon-remove"></i> Reiniciar</a>
                        <?php }*/  ?>

                        <?php }
                        else {
                        ?>
                        <?php if($show_paginator) { ?>
                            <div class="pagination">
                                <ul class="pagination">
                                    <?php echo "".$pagination_helper->create_links(); ?>
                                </ul>
                                <p>Encontrados <?=$num_resultados?> resultados. Mostrando del <?=$n_inicial?> al <?=$n_final?>.</p>
                            </div>
                        <?php

                            }
                            else { ?>
                                <div class="pagination">
                                    <p>Encontrados <?=$num_resultados?> resultados. </p>
                                </div>
                            <?php }
                            $url=base_url().'admin/exportar_pedidos/'.$tipo.'/xls';
                            ?>
                        <p><a href="<?=$url?>" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a></p>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover table-sorting" id="table_pedidos_dashboard" data-order-form="form_orden">
		                        <thead>
		                        <tr>
		                            <th class="sorting" data-rel="pedidos.id" data-order="">Ref.</th>
                                    <th class="">Fecha de alta</th>
                                    <th class="sorting" data-rel="pds.reference" data-order="">SFID</th>
                                    <th class=""">Tienda</th>
                                    <th class="">Territorio</th>
                                    <th class="">Estado</th>
                                    <th class="sorting" data-rel="nuevos" data-order="desc">Chat offline</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($pedidos as $pedido) {
                                    ?>
                                    <tr>
                                        <td>

                                            <a href="<?=site_url('master/detalle_pedido/'.$pedido->id.'/'.$pedido->id_pds)?>">
                                                <?php echo $pedido->id?></a></td>
                                        <td><?php echo date_format(date_create($pedido->fecha), 'd/m/Y'); ?></td>
                                        <td><?php echo $pedido->reference ?></td>
                                        <td><?php echo $pedido->commercial ?></td>
                                        <td><?=(!empty($pedido->territory)? $pedido->territory : '-')?></td>
                                        <td><strong><?php echo $pedido->status ?></strong></td>
                                        <td><strong><i class="fa fa-whatsapp <?=($pedido->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"><?=$pedido->nuevos['nuevos']?></i></strong></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        <form action="<?=base_url()?>admin/pedidos/<?=$tipo?>" method="post" id="form_orden">
                            <input type="hidden" name="form_orden_campo_orden"  value="">
                            <input type="hidden" name="form_orden_orden_campo" value="">
                            <input type="hidden" name="form"  value="">
                            <input type="hidden" name="ordenar" value="true">
                            <?php //<input type="submit"> ?>
                        </form>
                        <script>
                            <?php if(!empty($campo_orden) && !empty($orden_campo)) {?>
                            marcarOrdenacion('table_pedidos_dashboard','<?=$campo_orden?>','<?=$orden_campo?>');
                            <?php } ?>
                        </script>
                    </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>
        </div>