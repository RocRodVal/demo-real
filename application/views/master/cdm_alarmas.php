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
		            if (empty($stocks)) {
		                echo '<p>No hay datos.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Incidencias por sistema de seguridad</h1>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Marca</th>
		                            <th>Modelo</th>
		                            <th>Incidencias</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($stocks as $stock) {
		                            ?>
		                            <tr>
		                                <td><?php echo $stock->brand ?></td>
		                                <td><?php echo $stock->alarm ?></td>
		                                <td><?php echo $stock->incidencias ?></td>
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
