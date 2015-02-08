		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                	<h3>Datos puntos de venta</h3>
                	<p>
	                	<strong>SFID:</strong> <?php echo $reference ?> [<?php echo $id_pds ?>]<br />
	                	<strong>Nombre comercial:</strong> <?php echo $commercial ?><br />
						<strong>Dirección:</strong> <?php echo $address ?>, <?php echo $zip ?> -  <?php echo $city ?><br />
	                	<strong>Zona:</strong> <?php echo $territory ?>
            	</div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                	<p><?php echo $incidencia['fecha'] ?></p>
					<p><?php echo $incidencia['description'] ?></p>
            	</div>        
            </div>
            <div class="row">
                <div class="col-lg-12">
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Rellene todos los datos de la incidencia
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <tbody>
                                    	<tr class="odd gradeX"><td><em>Proceso</em></td><td><em>Seleccionar</em></td><td><em>Sub-estado</em></td></tr>
                                    	<tr class="odd gradeX"><td>Incidencia revisada</td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>
                                    	<tr class="odd gradeX"><td>Asignacion de instalador <select><option>-- Seleccionar --</option></select></td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>                                    	
                                        <tr class="odd gradeX"><td>Emisión de documentación</td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>
                                        <tr class="odd gradeX"><td>Incidencia ejecuta</td><td><input type="checkbox" value=""></td><td>Pendiente</td></tr>
                                        <tr class="odd gradeX"><td>Material recibido</td><td><input type="checkbox" value=""></td><td><a href="<?=site_url('admin/update_incidencia/'.$id_pds_url.'/'.$id_inc_url.'/5')?>" class="btn btn-lg btn-success btn-block">Envíar</a></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <p>
                        <a href="<?=site_url('admin/dashboard')?>" class="btn btn-lg btn-success btn-block">Envíar</a>
		                <a href="<?=site_url('admin/dashboard')?>" class="btn btn-lg btn-danger btn-block">Cancelar</a>
                        </p>
                    </div>
            	</div>        
            </div>     	            
        </div>                 	            
        </div>
        <!-- /#page-wrapper -->