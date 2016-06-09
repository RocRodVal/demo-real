<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div id="incidencias_abiertas">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
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

                                            <a href="<?=site_url('admin/operar_pedido/'.$pedido->id.'/'.$pedido->id_pds)?>">
                                                <?php echo $pedido->id?></a></td>
                                        <td><?php echo date_format(date_create($pedido->fecha), 'd/m/Y'); ?></td>
                                        <td><?php echo $pedido->reference ?></td>
                                        <td><?php echo $pedido->commercial ?></td>
                                        <td><?=(!empty($pedido->territory)? $pedido->territory : '-')?></td>
                                        <td><strong><?php echo $pedido->status ?></strong></td>
                                        <td>
                                            <a href="<?=site_url('admin/operar_pedido/'.$pedido->id.'/'.$pedido->id_pds)?>#chat"><strong> <i class="fa fa-whatsapp <?=($pedido->nuevos['nuevos'] > 0) ? 'chat_nuevo' :'chat_leido' ?>"> <?=$pedido->nuevos['nuevos']?></i></strong></a>
                                        </td>

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