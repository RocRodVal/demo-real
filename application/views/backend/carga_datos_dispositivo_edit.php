		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                	<form action="<?=site_url('admin/carga_datos_dispositivo');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <label>Buscar</label>
                            <input class="form-control" placeholder="código" name="codigo" id="codigo">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php 
            if (isset($_POST['codigo']))
            {	
            ?>
            <div class="row">
                <div class="col-lg-12">
 					<?php
                    if(empty($dispositivos)){
					?>
                    	<p>No hay resultados para esa cadena de búsqueda. Añade los datos al terminal seleccionado.</p>
                    <div class="row">
		            <?php
		            if (empty($devices_almacen)) {
		                echo '<p>No hay dispositivos.</p>';
		            } else {
		            ?>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Dispositivo</th>
		                            <th>Operaciones</th>
		                        </tr>
		                        </thead>
		                        <tbody>
      									<form action="<?=site_url('admin/update_dispositivo');?>" method="post" class="form-inline form-sfid">
			                            <tr>
			                                <td>
			                                <select id="dipositivo_almacen_1" name="dipositivo_almacen_1" width="500" style="width:500px">
			                              	<?php
			                        		foreach ($devices_almacen as $device_almacen) {
			                            	?>
			                                	<option value="<?php echo $device_almacen->id_devices_almacen ?>"><?php echo $device_almacen->device ?> [<?php echo $device_almacen->serial ?>] (<?php echo $device_almacen->owner ?>)</option>
					                        <?php
					                        }
					                        ?>		                                
			                                </select>
			                                </td>
			                                <td><button type="submit" class="btn btn-default">Cambiar</button></td>
			                            </tr>
    									</form>	                        
		                        </tbody>
		                    </table>
		                </div>
		            <?php
		            }
		            ?>
		       		</div>                    	
                    <?php 
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
                                            <th>Dispositivo</th>
                                            <th>Fecha alta</th>
                                            <th>IMEI</th>
                                            <th>MAC</th>
                                            <th>Serial</th>
                                            <th>Código de barras</th>
                                            <th>Estado</th>
                                            <th>Operaciones</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($dispositivos as $dispositivo)
    									{
    									?>
    									<form action="<?=site_url('admin/update_dispositivo');?>" method="post" class="form-inline form-sfid">
    									<!--
    									<input type="hidden" name="id_pds" id="id_pds" value="<?php echo $tienda->id_pds ?>">
    									<input type="hidden" name="sfid_old" id="sfid_old" value="<?php echo $tienda->reference ?>">
    									-->
    									<tr>
    										<td><?php echo $dispositivo->id_devices_almacen ?></td>
    										<td><?php echo $dispositivo->id_device ?></td>
    										<td><?php echo $dispositivo->alta ?></td>
    										<td><?php echo $dispositivo->IMEI ?></td>
    										<td><?php echo $dispositivo->mac ?></td>
    										<td><?php echo $dispositivo->serial ?></td>
    										<td><?php echo $dispositivo->barcode ?></td>
    										<td><?php echo $dispositivo->status ?></td>
    										<!--
    										<td><input class="form-control" placeholder="nuevo SFID" name="sfid_new" id="sfid_new"></a></td>
    										<td><?php echo $tienda->pds ?></td>
    										<td><?php echo $tienda->panelado ?></td>
    										<td><?php echo $tienda->commercial ?></td>
    										<td><?php echo $tienda->territory ?></td>
    										-->
    										<td><button type="submit" class="btn btn-default">Cambiar</button></td>
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

