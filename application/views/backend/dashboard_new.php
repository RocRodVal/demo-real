		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row" id="incidencias_abiertas">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?> <a href="#incidencias_cerradas">Cerradas</a></h1>
                </div>
            <!-- 
            <div class="row">
                <div class="col-lg-6">
                	<form action="<?=site_url('admin/dashboard');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <label>SFID</label>
                            <input class="form-control" placeholder="SFID" name="sfid" id="sfid">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
            -->
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
    										<td><a href="<?=site_url('admin/alta_incidencia/'.$tienda->id_pds)?>"><?php echo $tienda->reference ?></a></td>
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
		            <?php
		            if (empty($incidencias)) {
		                echo '<p>No hay incidencias.</p>'; ?>


                        <?php if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                            <a href="<?=base_url()?>admin/dashboard_new/" class="reiniciar_busqueda">Reiniciar</a>
                        <?php } ?>

		            <? } else {
		                ?>
                        <?php if($show_paginator) { ?>
                            <div class="pagination">
                                <ul class="pagination">
                                    <?php echo "".$pagination_helper->create_links(); ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <div class="row buscador">
                            <form action="<?=base_url()?>admin/dashboard_new/#incidencias_abiertas" method="post">
                                <div class="col-sm-2">
                                    <label for="buscar_incidencia">Buscar incidencia: </label>
                                    <input type="text" name="buscar_incidencia" id="buscar_incidencia" class="form-control input-sm" placeholder="Ref. incidencia" <?php echo (!empty($buscar_incidencia)) ? ' value="'.$buscar_incidencia.'" ' : ''?> />
                                </div>
                                <div class="col-sm-2">
                                    <label for="buscar_sfid">Buscar SFID: </label>
                                    <input type="text" name="buscar_sfid" id="buscar_sfid" class="form-control input-sm" placeholder="SFID" <?php echo (!empty($buscar_sfid)) ? ' value="'.$buscar_sfid.'" ' : ''?> />
                                </div>
                                <div class="col-sm-1">
                                    <input type="submit" value="Buscar" class="form-control input-sm">
                                </div>
                                <div class="col-sm-1">
                                    <?php if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                                        <a href="<?=base_url()?>admin/dashboard_new/" class="reiniciar_busqueda">Reiniciar</a>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="table_incidencias_dashboard">
		                        <thead>
		                        <tr>
		                            <th>Ref.</th>
		                            <th>SFID</th>
		                            <th>Fecha alta</th>
		                            <th>Elemento afectado</th>
		                            <th>Sistema general de seguridad</th>
		                            <th>Dispositivo</th>
		                        	<th>Alarma dispositivo cableado</th>
		                            <th>Soporte sujección</th>
		                            <th>Tipo incidencia</th>
		                            <th>Interv.</th>
		                            <th>Estado</th>
		                            <th>Estado SAT</th>
		                            <th>Chat offline</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($incidencias as $incidencia) {
		                            ?>
		                            <tr onClick="window.location.href='<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>'">
		                                <td><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>"><?php echo $incidencia->id_incidencia?></a></td>
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
		                                <td><?=($incidencia->alarm_display==1)?'Mueble: '.$mueble:'Dispositivo: '.$dispositivo?></td>	
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
		                                <td><strong><?php echo $incidencia->status_pds ?></strong></td>
		                                <td><strong><?php echo $incidencia->status ?></strong></td>
		                                <td><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>#chat"><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"></i></strong></a></td>
		                            </tr>
		                        <?php
		                        }
		                        ?>
		                        </tbody>
		                    </table>
		                </div>
                        <div class="pagination">
                            <ul class="pagination">
                                <?php echo "".$pagination_helper->create_links(); ?>
                            </ul>
                        </div>
		            <?php
		            }
		            ?>                
            	</div>
            </div>

                <div class="row"  id="incidencias_cerradas">
                    <div class="col-lg-12" >
                        <h1 class="page-header"><?php echo $title_finalizadas ?> <a href="#incidencias_abiertas">Abiertas</a></h1>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        if (empty($incidencias_finalizadas)) {
                            echo '<p>No hay incidencias.</p>';
                        } else {
                            ?>

                             <?php if($show_paginator_finalizadas) { ?>
                                <div class="pagination">
                                    <ul class="pagination">
                                        <?php echo "".$pagination_finalizadas_helper->create_links(); ?>
                                    </ul>
                                </div>
                             <?php }?>
                            <div class="row filtro">
                                <form action="<?=base_url()?>admin/dashboard_new/#incidencias_cerradas" method="post">



                                    <div class="col-sm-2">
                                        <label for="filtrar_finalizadas">Mostrar: </label>
                                        <select name="filtrar_finalizadas" id="filtrar_finalizadas" class="form-control input-sm">
                                            <option value="Todas" <?php echo ($filtro_finalizadas==="Todas") ? 'selected="selected"' : ''?>>Todas</option>
                                            <option value="Cerrada" <?php echo ($filtro_finalizadas==="Cerrada") ? 'selected="selected"' : ''?>>Cerradas</option>
                                            <option value="Resuelta" <?php echo ($filtro_finalizadas==="Resuelta") ? 'selected="selected"' : ''?>>Resueltas</option>
                                            <option value="Cancelada" <?php echo ($filtro_finalizadas==="Cancelada") ? 'selected="selected"' : ''?>>Canceladas</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="submit" value="Aplicar" class="form-control input-sm">
                                    </div>
                                    <div class="col-sm-1"></div>

                                    <div class="col-sm-2">
                                        <label for="buscar_incidencia_finalizadas">Buscar incidencia: </label>
                                        <input type="text" name="buscar_incidencia" id="buscar_incidencia_finalizadas" class="form-control input-sm" placeholder="Ref. incidencia" <?php echo (!empty($buscar_incidencia)) ? ' value="'.$buscar_incidencia.'" ' : ''?> />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="buscar_sfid_finalizadas">Buscar SFID: </label>
                                        <input type="text" name="buscar_sfid" id="buscar_sfid_finalizadas" class="form-control input-sm" placeholder="SFID" <?php echo (!empty($buscar_sfid)) ? ' value="'.$buscar_sfid.'" ' : ''?> />
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="submit" value="Buscar" class="form-control input-sm">



                                    </div>
                                    <div class="col-sm-1">
                                        <?php if(! empty($buscar_sfid) || ! empty($buscar_incidencia)) { ?>
                                            <a href="<?=base_url()?>admin/dashboard_new/" class="reiniciar_busqueda">Reiniciar</a>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="table_incidencias_dashboard">
                                    <thead>
                                    <tr>
                                        <th>Ref.</th>
                                        <th>SFID</th>
                                        <th>Fecha alta</th>
                                        <th>Elemento afectado</th>
                                        <th>Sistema general de seguridad</th>
                                        <th>Dispositivo</th>
                                        <th>Alarma dispositivo cableado</th>
                                        <th>Soporte sujección</th>
                                        <th>Tipo incidencia</th>
                                        <th>Interv.</th>
                                        <th>Estado</th>
                                        <th>Estado SAT</th>
                                        <th>Chat offline</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($incidencias_finalizadas as $incidencia) {
                                        ?>
                                        <tr onClick="window.location.href='<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>'">
                                            <td><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>"><?php echo $incidencia->id_incidencia?></a></td>
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
                                            <td><?=($incidencia->alarm_display==1)?'Mueble: '.$mueble:'Dispositivo: '.$dispositivo?></td>
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
                                            <td><strong><?php echo $incidencia->status_pds ?></strong></td>
                                            <td><strong><?php echo $incidencia->status ?></strong></td>
                                            <td><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>#chat"><strong><i class="fa fa-whatsapp <?=($incidencia->nuevos['nuevos']<>'0')?'chat_nuevo':'chat_leido'?>"></i></strong></a></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination">
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