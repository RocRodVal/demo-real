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
		            	<h1 class="page-header">Balance de activos</h1>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Dispositivo</th>
		                            <th>Unidades tienda</th>
		                            <th>Stock necesario</th>
		                            <th>Deposito en almacén</th>
		                            <th>Balance</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($stocks as $stock) {
		                            ?>
		                            <tr>
		                                <td><?php echo $stock->device ?></td>
		                                <td><?php echo $stock->unidades_pds ?></td>
		                                <td><?php echo $stock->stock_necesario ?></td>
		                                <td><?php echo $stock->deposito_almacen ?></td>
		                                <td <?=($stock->balance<0)?'style="background-color:red;color:white;"':''?>><?php echo $stock->balance ?></td>
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
		            if (empty($devices_almacen)) {
		                echo '<p>No hay dispositivos.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Dispositivos almacén</h1>
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
		                        foreach ($devices_almacen as $device_almacen) {
		                            ?>
		                            <tr>
		                                <td><?php echo $device_almacen->device ?></td>
		                                <td><?php echo $device_almacen->unidades ?></td>
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
		        	<?php echo $alarms_almacen; ?>
		        	<!--//
		            <?php
		            if (empty($alarms_almacen)) {
		                echo '<p>No hay alarmas.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Alarmas almacén</h1>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Alarma</th>
		                            <th>Unidades</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($alarms_almacen as $alarm_almacen) {
		                            ?>
		                            <tr>
		                                <td><?php echo $alarm_almacen->alarm ?></td>
		                                <td><?php echo $alarm_almacen->unidades ?></td>
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
		            //-->
		        </div>
		    </div> 
			    <div class="row">
		        <div class="col-lg-12">
		            <?php
		            if (empty($devices_pds)) {
		                echo '<p>No hay dispositivos.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Dispositivos tiendas</h1>
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
		                        foreach ($devices_pds as $device_pds) {
		                            ?>
		                            <tr>
		                                <td><?php echo $device_pds->device ?></td>
		                                <td><?php echo $device_pds->unidades ?></td>
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
		            if (empty($displays_pds)) {
		                echo '<p>No hay muebles.</p>';
		            } else {
		            ?>
		            	<h1 class="page-header">Muebles tiendas</h1>
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
		                        foreach ($displays_pds as $display_pds) {
		                            ?>
		                            <tr>
		                                <td><?php echo $display_pds->display ?></td>
		                                <td><?php echo $display_pds->unidades ?></td>
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
