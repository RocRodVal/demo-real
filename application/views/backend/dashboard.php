		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                	<form action="<?=site_url('admin/dashboard');?>" method="post">
                	<label>SFID</label>
		            <p><input class="form-control" placeholder="SFID" name="sfid" id="sfid"></p>
                    <p class="botonera"><input type="submit" class="submit" value="Buscar" /></p>                 
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
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Referencia</th>
                                            <th>Fecha</th>
                                            <th>SFID</th>
                                            <th>Incidencia</th>
                                            <th>Contacto</th>
                                            <th>Teléfono</th>
                                            <th>Email</th>
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
    										<td><?php echo $incidencia->fecha ?></td>
    										<td><?php echo $incidencia->reference ?></td>
    										<td><?php echo $incidencia->description ?></td>
    										<td><?php echo $incidencia->contacto ?></td>
    										<td><?php echo $incidencia->phone ?></td>
    										<td><?php echo $incidencia->email ?></td>
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
        </div>
        <!-- /#page-wrapper -->

