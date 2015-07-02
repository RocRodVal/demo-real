		<!-- #page-wrapper -->
		<div id="page-wrapper"t>
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
		        </div>
		    </div>

            <div class="row">
                <div class="col-lg-12">
            <div class="row buscador">
                <form action="<?=site_url('master/informe_pdv');?>" method="post" class="form-inline filtros form-mini">
                <div class="col-sm-12">
                    <div class="form-group">
                         <label for="tipo_tienda">Tipo de tienda</label>
                        <select id="tipo_tienda" name="tipo_tienda"><option value="">Escoge el tipo de tienda...</option>

                            <?php foreach($tipos_tienda as $tipo)
                            {
                             $selected = ($tipo->id_type_pds == $tipo_tienda) ? ' selected = "selected" ' : '';
                              echo '<option value="'.$tipo->id_type_pds.'" '.$selected.'>'.$tipo->pds.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="panelado">Panelado</label>
                        <select id="panelado" name="panelado"><option value="">Escoge el panelado...</option>

                            <?php foreach($panelados as $panel)
                            {
                                $selected = ($panel->id_panelado == $panelado) ? ' selected = "selected" ' : '';
                                echo '<option value="'.$panel->id_panelado.'" '.$selected.'>'.$panel->panelado.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mueble">Mueble</label>
                        <select id="mueble" name="mueble"><option value="">Cualquiera...</option>

                            <?php foreach($muebles as $display)
                            {
                                $selected = ($display->id_display== $mueble) ? ' selected = "selected" ' : '';
                                echo '<option value="'.$display->id_display.'" '.$selected.'>'.$display->display.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="terminal">Terminal</label>
                        <select id="terminal" name="terminal"><option value="">Cualquiera...</option>

                            <?php foreach($terminales as $term)
                            {
                                $selected = ($term->id_device == $terminal) ? ' selected = "selected" ' : '';
                                echo '<option value="'.$term->id_device.'" '.$selected.'>'.$term->device.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sfid">SFID</label>
                        <input id="sfid" name="sfid" value="<?=$sfid?>" placeholder="Cualquiera..." class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="generar_informe" value="si">
                        <button type="submit" id="submit_button" class="form-control input-sm">Generar</button>
                    </div>

                    <div class="form-group">
                            <?php   if($generado===TRUE)    {?>
                                <a href="<?=base_url("master/informe_pdv/reset")?>" class="reiniciar_busqueda"> <i class="glyphicon glyphicon-remove"></i> Resetear informe</a>
                            <?php } ?>
                        </div>

                </div>


                </form>
            </div>
            </div>
            </div>
            <?php 
            if (!$generado)
            {	
            ?>
            <div class="row">
                <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> Selecciona algún criterio para la generación del informe "Punto de Venta".</p>
            </div> 
            <?php 
            }else{ ?>
            <div class="row">
                <?php
                     if(is_null($resultados)){
                         echo "<p class='message error'><i class='glyphicon glyphicon-remove'></i> Debes introducir algún criterio para poder generar el informe.</p>";
                     }elseif(count($resultados) == 0){
                         echo "<p class='message error'><i class='glyphicon glyphicon-remove'></i> No existen datos para mostrar con los criterios escogidos para generar el informe.</p>";
                     }else{ ?>


                         <?php if($pag["show_paginator"]) {

                             ?>
                             <div class="pagination">
                                 <ul class="pagination">
                                     <?php echo "".$pagination_helper->create_links(); ?>
                                 </ul>
                                 <p>Encontrados <?=$pag["num_resultados"]?> resultados. Mostrando del <?=$pag["n_inicial"]?> al <?=$pag["n_final"]?>.</p>
                             </div>
                         <?php } ?>

                         <h3>Informe generado</h3>

                         <p><a href="<?=base_url()?>master/informe_pdv_exportar" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar CSV</a></p>
                         <table class="table table-striped table-bordered table-hover table-sorting" id="table_incidencias_dashboard" data-order-form="form_orden_activas">
                            <thead>
                            <tr>
                                <th>SFID</th>
                                <th>Tipo de tienda</th>
                                <th>Panelado</th>
                                <th>Mueble</th>
                                <th>POS</th>
                                <th>Terminal</th>
                            </tr>
                            </thead>
                             <tbody>
                         <?php foreach($resultados as $resultado)
                         {?>
                             <tr>
                                 <td><?=$resultado->sfid?></td>
                                 <td>(<?=$resultado->id_type_pds?>) <?=$resultado->tipo_tienda?></td>
                                 <td>(<?=$resultado->id_panelado ?>) <?=$resultado->panelado?></td>
                                 <td>(Id.<?=$resultado->id_displays_pds?>) <?=$resultado->mueble?></td>
                                 <td><?=$resultado->position?></td>
                                 <td><?=$resultado->terminal?></td>
                             </tr>
                         <?php }
                         ?>
                             </tbody>
                         </table>
                         <p><a href="<?=base_url()?>master/informe_pdv_exportar" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar CSV</a></p>
                         <?php if($pag["show_paginator"]) {

                             ?>
                             <div class="pagination">
                                 <ul class="pagination">
                                     <?php echo "".$pagination_helper->create_links(); ?>
                                 </ul>
                                 <p>Encontrados <?=$pag["num_resultados"]?> resultados. Mostrando del <?=$pag["n_inicial"]?> al <?=$pag["n_final"]?>.</p>
                             </div>
                         <?php } ?>



                    <?php }
                ?>
            </div>
            <?php }?>
        </div>
        <!-- /#page-wrapper -->
