		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
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
               	 		<div class="panel-heading">
                            Seleccione el punto de venta de la incidencia.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>SFID / Referencia</th>
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
            <div class="row">
                <div class="col-lg-12">
 					<?php
                    if(empty($incidencias)){
                    	echo '<p>No hay incidencias.</p>';
                    }
                    else
                    {					
 					?>
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Seleccione la incidencia sobre la que operar.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="table_incidencias_dashboard">
                                    <thead>
                                        <tr>
                                            <th>Ref.</th>
                                            <th>Fecha</th>
                                            <th>SFID</th>
                                            <th>Descripción</th>
                                            <th>Contacto</th>
                                            <th>Teléfono</th>
                                            <th>Tipo</th>
                                            <th>Interv.</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php
   										foreach($incidencias as $incidencia)
    									{
    									?>
    									<tr>
    										<td><a href="<?=site_url('admin/operar_incidencia/'.$incidencia->id_pds.'/'.$incidencia->id_incidencia)?>"><?php echo $incidencia->id_incidencia?></a></td>
    										<td><?php echo date_format(date_create($incidencia->fecha),'d-m-Y') ?></td>
    										<td><?php echo $incidencia->reference ?></td>
    										<td>

                                                <?php


                                                if(strlen($incidencia->description)>30){?>
                                                    <span  data-toggle="tooltip" title="<?php echo $incidencia->description; ?>">
                                                <?php
                                                    echo  substr($incidencia->description, 0, 30).'...';
                                                }
                                                else{
                                                    echo $incidencia->description;
                                                }
                                                ?>
                                                </span>
                                            </td>
    										<td><?php echo $incidencia->contacto ?></td>
    										<td><?php echo $incidencia->phone ?></td>
                                            <td><?php echo $incidencia->tipo_averia ?></td>
                                            <td>
                                                <?php if($incidencia->intervencion !=0){?>
                                                <i onClick="showModalViewIntervencion(<?php echo $incidencia->intervencion ?>);" class="fa fa-eye"></i>
                                                <?php }
                                                else{
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
    										<td><?php echo $incidencia->status ?></td>
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
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $content ?>
                </div>
            </div>                                    
        </div>
        <!-- /#page-wrapper -->

        <?php $this->load->view('backend/intervenciones/ver_intervencion');?>

