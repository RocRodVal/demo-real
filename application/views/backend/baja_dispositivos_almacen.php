<!-- #page-wrapper -->
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $title ?></h1>
		</div>
	</div>
	<div class="row">
		<form action="<?= site_url('admin/baja_dispositivos_almacen_update') ?>" method="post">
			<div class="panel-body incidenciaEstado">
				<div class="row">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
							<tr>
								<th>Dispositivo</th>
								<th>Unidades</th>
								<th>Estado inicial</th>
								<th>Estado final</th>
								<th>IMEIs</th>
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
								<td><input type="text" id="units_dipositivo_almacen" name="units_dipositivo_almacen" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/></td>
								<td>
									<select id="inicio_dipositivo_almacen" name="inicio_dipositivo_almacen" style="width:150px">
										<option value="1">En stock</option>
										<option value="2">Reservado</option>
										<option value="3">Televenta</option>
										<option value="4">En transito</option>
									</select>
								</td>
								<td>
									<select id="destino_dipositivo_almacen" name="destino_dipositivo_almacen" style="width:150px">
										<option value="1">En stock</option>
										<option value="2">Reservado</option>
										<option value="3">Televenta</option>
										<option value="4">En transito</option>
										<option value="5">Baja</option>
									</select>
								</td>
								<td>
									<textarea id="imeis" name="imeis" rows="8" cols="40"></textarea>
								</td>

								<td hidden>
									<select id="owner_dipositivo_almacen" name="owner_dipositivo_almacen" style="width:50px">
										<option value="ET">ET</option>

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