		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <form action="<?= site_url('admin/baja_dispositivos_almacen_imei_update') ?>" method="post">
					<input type="hidden" name="owner_dipositivo_almacen" value="ET"/>
                <div class="panel-body incidenciaEstado">
                    <div class="row">
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Dispositivo</th>
		                            <th>IMEIs</th>
									<th>Destino</th>
		                           <!-- <th>Dueño</th>-->
		                        </tr>
		                        </thead>
		                        <tbody>
		                            <tr>
		                                <td>
		                                <select id="dipositivo_almacen" name="dipositivo_almacen" style="width:300px">
		                              	<?php
		                        		foreach ($devices as $device_almacen) {
		                            	?>
		                                	<option value="<?php echo $device_almacen->id_device ?>"><?php echo $device_almacen->device ?></option>
				                        <?php
				                        }
				                        ?>		                                
		                                </select>
		                                </td>
										<td>
											<textarea name="imeis" id="imeis" cols="30" rows="10" style="width:250px"></textarea>
										</td>
										<td>
											<select id="destino_dipositivo_almacen" name="destino_dipositivo_almacen" style="width:150px">
												<option value="3">Enviado a tienda</option>
												<option value="4">Baja</option>
											</select>
										</td>
		                            </tr>	                            
		                        </tbody>
		                    </table>

		                </div>

		       		</div>
                    <div class="row">

                        <input type="submit" value="Envíar" name="submit" class="btn btn-success" />
                    </div>		       		 
                </div>
                </form>
            </div>
        </div>
        <!-- /#page-wrapper -->