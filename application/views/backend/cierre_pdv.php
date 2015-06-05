		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?> <font color="red">[Beta]</font></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Este proceso es irreversible. Se procede al borrado de los dispositivos, muebles y punto de servicio de la app. También se elimina el acceso a la misma.</p>
                    <p>Mientras se ajusta os recomendaría antes de realizar la operación:</p>
                    <ul>
                    <li>Cerrar las incidencias abiertas</li>
                    <li>Exportar el listado de dispositivos de la tienda desde "Exposición &gt; Inventario tiendas" usando el SFID para filtrar</li>
                    </ul>
                    <p>&nbsp;</p>
                </div>
            </div>            
            <div class="row">
                <div class="col-lg-6">
                	<form action="<?=site_url('admin/cierre_pdv');?>" method="post" class="form-inline form-sfid">
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
                        <div class="panel-body">
                        	<div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Ref.</th>
                                            <th>SFID</th>
                                            <th>Tipo</th>
                                            <th>Panelado</th>
                                            <th>Nombre comercial</th>
                                            <th>Zona</th>
                                            <th>Operaciones</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($tiendas as $tienda)
    									{
    									?>
    									<form action="<?=site_url('admin/update_cierre_pdv');?>" method="post" class="form-inline form-sfid">
    									<input type="hidden" name="id_pds" id="id_pds" value="<?php echo $tienda->id_pds ?>">
    									<input type="hidden" name="reference" id="reference" value="<?php echo $tienda->reference ?>">
    									<tr>
    										<td><?php echo $tienda->id_pds ?></td>
    										<td><?php echo $tienda->reference ?></a></td>
    										<td><?php echo $tienda->pds ?></td>
    										<td><?php echo $tienda->panelado ?></td>
    										<td><?php echo $tienda->commercial ?></td>
    										<td><?php echo $tienda->territory ?></td>
    										<td><button type="submit" class="btn btn-default">Revisar y cerrar</button></td>
    									</tr>
    									</form>
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
        </div>
        <!-- /#page-wrapper -->

        <?php $this->load->view('backend/intervenciones/ver_intervencion');?>

