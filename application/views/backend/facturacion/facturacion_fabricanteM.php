<!-- #page-wrapper -->
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $title ?></h1>
		</div>
	</div>

	<?php
	// Mostrar el buscador/filtro
	$this->load->view("backend/facturacion/buscadorF") ?>

	<div class="row">
		<div class="col-lg-12">
			<?php
			if ((isset($_POST['fecha_inicio'])) || (isset($_POST['fecha_fin'])))
			{
				if (empty($facturacion)) {
					echo '<p>No hay información sobre facturación.</p>';
				} else {



					?>
					<h1 class="title">Fabricante [descargar]
						<a href="<?=site_url('admin/exportar_facturacion_fabricanteM/'.$fecha_inicio.'/'.$fecha_fin.'/'.$fabricante);?>">Exportar Excel</a>
						<a href="<?=site_url('admin/exportar_fotosCierre/'.$fecha_inicio.'/'.$fecha_fin.'/'.$fabricante);?>">Exportar Fotos</a></h1>

					<div class="table-responsive">
						<p><strong>Rango:</strong> <?php echo date("d/m/Y",strtotime($fecha_inicio)); ?> - <?php echo date("d/m/Y",strtotime($fecha_fin)); ?></p>

						<?php if(!empty($fabricante)) { ?>
							<p><strong>Fabricante:</strong> <?=$facturacion[0]->fabricante?></p>
						<?php } ?>

						<p><?=count($facturacion)?> elementos</p>
						<table class="table table-striped table-bordered table-hover" id="dataTables-dashboard">
							<thead>
							<tr>
								<th>Intervención</th>
								<th>Incidencia</th>
								<th>Fecha</th>
								<th>SFID</th>
								<th>Tienda</th>
								<th>Dirección</th>
								<th>Ciudad</th>
								<th>Mueble</th>
								<th>Fabricante</th>
								<th>Nº terminales</th>
								<th>Descripción de tienda del error</th>
								<th>Solución</th>
								<th>Cierre</th>

							</tr>
							</thead>
							<tbody>
							<?php
							foreach ($facturacion as $item_facturacion) {
								?>
								<tr>
									<td><?php echo $item_facturacion->intervencion; ?></td>
									<td><?php echo $item_facturacion->incidencia; ?></td>
									<td><?php echo date("d/m/Y",strtotime($item_facturacion->fecha)); ?></td>
									<td><?php echo $item_facturacion->SFID ?></td>
									<td><?php echo $item_facturacion->nombre ?></td>
									<td><?php echo $item_facturacion->direccion ?></td>
									<td><?php echo $item_facturacion->ciudad ?></td>
									<td><?php echo $item_facturacion->mueble ?></td>
									<td><?php echo $item_facturacion->fabricante ?></td>
									<td><?=(!empty($item_facturacion->dispositivos) ) ? $item_facturacion->dispositivos :"0" ?></td>
									<td><?php echo $item_facturacion->descripcion ?></td>
									<td><?php echo $item_facturacion->solucion ?></td>
									<td><?=!empty($item_facturacion->cierre) ? date("d/m/Y",strtotime($item_facturacion->cierre)) : "--"; ?></td>

								</tr>
								<?php
							}
							?>
							</tbody>
						</table>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
</div>
<!-- /#page-wrapper -->
