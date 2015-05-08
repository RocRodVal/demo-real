		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
		    <div class="row">
		        <div class="col-lg-12">
		        	<p>Seleccione un rango de fechas.</p>
					<form action="<?=site_url('admin/facturacion');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <label>Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo date('Y-m-01'); ?>">
                        </div>					
                        <div class="form-group">
                            <label>Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>">
                        </div>						
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>				  
					</form>
		        </div>
		    </div>            
		    <div class="row">
		        <div class="col-lg-12">
		            <?php
		            if ((isset($_POST['fecha_inicio'])) || (isset($_POST['fecha_fin'])))
		            {
		            if (empty($facturacion)) {
		                echo '<p>No hay información sobre facturación.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Intervenciones [descargar <a href="<?=site_url('admin/facturacion_csv/'.$fecha_inicio.'/'.$fecha_fin)?>" target="_blank">CSV</a>]</h1>
		                <div class="table-responsive">
		                	<p>Rango: <?php echo $fecha_inicio ?>/<?php echo $fecha_fin ?></p>
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-dashboard">
		                        <thead>
		                        <tr>
		                            <th>Fecha</th>
		                            <th>SFID</th>
		                            <th>Tipo</th>
		                            <th>Intervención</th>
		                            <th>Mueble</th>
		                            <th>Dispositivos</th>
		                            <th>Alarmas</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($facturacion as $item_facturacion) {
		                            ?>
		                            <tr>
		                                <td><?php echo $item_facturacion->fecha; ?></td>
		                                <td><?php echo $item_facturacion->SFID ?></td>
		                                <td><?php echo $item_facturacion->pds ?></td>
		                                <td><?php echo $item_facturacion->visita ?></td>
		                                <td><?php echo $item_facturacion->display ?></td>
		                                <td><?php echo $item_facturacion->dispositivos ?></td>
		                                <td><?php echo $item_facturacion->otros ?></td>
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