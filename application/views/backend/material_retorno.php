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
		            if (empty($material_retorno)) {
		                echo '<p>No hay materiales para retorno.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Dispositivos</h1>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Ref. interna</th>
		                            <th>SFID</th>
		                            <th>Incidencia</th>
		                            <th>Dispositivo</th>
		                            <th>Estado</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($material_retorno as $item_material_retorno) {
		                            ?>
		                            <tr>
		                                <td><?php echo $item_material_retorno->id_devices_pds ?></td>
		                                <td><?php echo $item_material_retorno->SFID ?></td>
		                                <td><?php echo $item_material_retorno->incidencia ?></td>
		                                <td><?php echo $item_material_retorno->dispositivo ?></td>
		                                <td><?php echo $item_material_retorno->estado ?></td>
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
