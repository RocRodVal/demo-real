		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>

		    <?php
            // Mostrar el buscador/filtro
            $this->load->view("backend/facturacion/buscador") ?>

		    <div class="row">
		        <div class="col-lg-12">
		            <?php
		            if ((isset($_POST['fecha_inicio'])) || (isset($_POST['fecha_fin'])))
		            {
		            if (empty($facturacion)) {
		                echo '<p>No hay información sobre facturación por intervenciones.</p>';
		            } else {



		            ?>
		            	<h1 class="page-header">Intervenciones [descargar <a href="<?=site_url('admin/facturacion_estado_intervencion_csv/'.$fecha_inicio.'/'.$fecha_fin.'/'.$instalador.'/'.$dueno);?>" target="_blank">CSV</a>]</h1>
		                <div class="table-responsive">
		                	<p><strong>Rango:</strong> <?php echo date("d/m/Y",strtotime($fecha_inicio)); ?> - <?php echo date("d/m/Y",strtotime($fecha_fin)); ?></p>

                            <?php if(!empty($dueno)) { ?>
                                <p><strong>Dueño:</strong> <?=$facturacion[0]->dueno?></p>
                            <?php } ?>
                            <?php if(!empty($instalador)) { ?>
                                <p><strong>Instalador:</strong> <?=$facturacion[0]->instalador?></p>
                            <?php } ?>

		                    <table class="table table-striped table-bordered table-hover" id="dataTables-dashboard">
		                        <thead>
		                        <tr>
		                            <th>Fecha</th>
		                            <th>SFID</th>
		                            <th>Tipo</th>
		                            <th>Intervención</th>
		                            <th>Instalador</th>
                                    <th>Dueño</th>
		                            <th>Dispositivos</th>
		                            <th>Alarmas</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($facturacion as $item_facturacion) {
		                            ?>
		                            <tr>
		                                <td><?php echo date("d/m/Y",strtotime($item_facturacion->fecha)); ?></td>
		                                <td><?php echo $item_facturacion->SFID ?></td>
		                                <td><?php echo $item_facturacion->pds ?></td>
		                                <td><?php echo $item_facturacion->visita ?></td>
		                                <td><?php echo $item_facturacion->instalador ?></td>
                                        <td><?php echo $item_facturacion->dueno  ?></td>
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
