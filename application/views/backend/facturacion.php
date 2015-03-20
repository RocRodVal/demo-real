		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
		    <div class="row">
		        <div class="col-lg-12">
		            <?php
		            if (empty($facturacion)) {
		                echo '<p>No hay información sobre facturación.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Intervenciones (descargar <a href="<?=site_url('admin/facturacion_csv')?>" target="_blank">CSV</a>)</h1>
		                <div class="table-responsive">
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
		            ?>
		        </div>
		    </div>    
            
        </div>
        <!-- /#page-wrapper -->
