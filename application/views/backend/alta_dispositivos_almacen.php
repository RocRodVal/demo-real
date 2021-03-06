		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <form action="<?= site_url('admin/alta_dispositivos_almacen_update') ?>" method="post">
                <div class="panel-body incidenciaEstado">
                    <div class="row">
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Dispositivo</th>
		                            <th>Unidades</th>
		                            <th>Dpto.</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                            <tr>
		                                <td>
		                                <select id="dipositivo_almacen" name="dipositivo_almacen" style="width:500px">
		                              	<?php
		                        		foreach ($devices as $device_almacen) {
		                            	?>
		                                	<option value="<?php echo $device_almacen->id_device ?>"><?php echo $device_almacen->device ?></option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
		                                <td><input type="text" id="units_dipositivo_almacen" name="units_dipositivo_almacen" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></input></td>
		                                <td>
		                                <select id="owner_dipositivo_almacen" name="owner_dipositivo_almacen" style="width:50px">
		                              	<option value="ET">ET</option>                               
		                                <option value="OT">OT</option>
		                                </select>
		                                </td>		                                
		                            </tr>	                            
		                        </tbody>
		                    </table>
		                </div>
		       		</div>
                    <div class="row">
                        <input type="submit" value="Env??ar" name="submit" class="btn btn-success" />
                    </div>		       		 
                </div>
                </form>
            </div>
        </div>
        <!-- /#page-wrapper -->