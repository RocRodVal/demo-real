		<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
		        </div>
		    </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $content ?>
                </div>
            </div>
		 <!--   <div class="row">
		        <div class="col-lg-12">
		            <?php
		            if (empty($devices)) {
		                echo '<p>No hay dispositivos.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Dispositivos</h1>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Dispositivo</th>
		                            <th>Unidades</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($devices as $device) {
		                            ?>
		                            <tr>
		                                <td><?php echo $device->device ?></td>
		                                <td><?php echo $device->unidades ?></td>
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
		    <div class="row">
		        <div class="col-lg-12">
		            <?php
		            if (empty($displays)) {
		                echo '<p>No hay muebles.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Muebles</h1>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Mueble</th>
		                            <th>Unidades</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($displays as $display) {
		                            ?>
		                            <tr>
		                                <td><?php echo $display->display ?></td>
		                                <td><?php echo $display->unidades ?></td>
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
		    </div>    -->


        </div>
        <!-- /#page-wrapper -->
