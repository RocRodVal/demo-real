		<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
		        </div>
		    </div>
		    <div class="row botonera_up">
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('admin/descripcion/')?>">
		                <button type="button" class="btn btn-primary btn-accion">Ver DESCRIPCIÓN<br/>tiendas</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('admin/inventarios_panelados/')?>">
		                <button type="button" class="btn btn-success btn-accion">Ver PANELADOS<br/>tiendas</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('admin/inventarios_planogramas/')?>">
		                <button type="button" class="btn btn-info btn-accion">Ver PLANOGRAMAS<br/>muebles</button>
		            </a>
		        </div>
		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
		            <a href="<?=site_url('admin/inventarios/')?>">
		                <button type="button" class="btn btn-primary btn-accion">Ver INVENTARIOS<br/>tiendas</button>
		            </a>
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
		    </div>             
		    <div class="row">
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
                    <?php echo $content ?>
                </div>
            </div>
            
            
            
        </div>
        <!-- /#page-wrapper -->
