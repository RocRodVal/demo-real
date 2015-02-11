<script type="text/javascript" src="<?php echo base_url();?>assets/js/intervencion/addIntervencion.js"></script>;
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
                	<p>
                		<strong>Fecha alta:</strong> <?php echo $incidencia['fecha'] ?><br />
                		<strong>Estado PdS:</strong> <?php echo $incidencia['status_pds'] ?><br />
						<strong>Estado SAT:</strong> <?php echo $incidencia['status'] ?>
                	</p>
					<p>	
						<strong>Comentario:</strong> <?php echo $incidencia['description'] ?><br />
					</p>
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
                                    	<tr class="odd gradeX"><td>Revisión de incidencia</td><td><a href="<?=site_url('admin/update_incidencia/'.$id_pds_url.'/'.$id_inc_url.'/2/2')?>" class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Nueva') {echo 'disabled';} ?>>Envíar</a><a href="<?=site_url('admin/update_incidencia/'.$id_pds_url.'/'.$id_inc_url.'/5/9')?>" class="btn btn-lg btn-danger btn-block" <?php if ($incidencia['status'] != 'Nueva') {echo 'disabled';}  ?>>Cancelar</a></td></tr>
                                    	<tr class="odd gradeX"><td>Asignar materiales</td><td><a href="<?=site_url('admin/update_incidencia/'.$id_pds_url.'/'.$id_inc_url.'/2/3')?>" class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Revisada') {echo 'disabled';} ?>>Envíar</a></td></tr>
                                    	<tr class="odd gradeX"><td>Asignar instalador e intervencion</td><td><a onClick="showModalNewIntervencion(<?php echo $id_pds_url.','.$id_inc_url ?>)" class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Material asignado') {echo 'disabled';} ?>>Envíar</a></td></tr>
                                    	<tr class="odd gradeX"><td>Imprimir documentación</td><td><a href="<?=site_url('admin/update_incidencia/'.$id_pds_url.'/'.$id_inc_url.'/3/5')?>" class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Instalador asignado') {echo 'disabled';} ?>>Envíar</a></td></tr>
                                    	<tr class="odd gradeX"><td>Resolución de incidencia</td><td><a href="<?=site_url('admin/update_incidencia/'.$id_pds_url.'/'.$id_inc_url.'/4/6')?>" class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Comunicada') {echo 'disabled';} ?>>Envíar</a></td></tr>
                                    	<tr class="odd gradeX"><td>Emisión de recogida de material</td><td><a href="<?=site_url('admin/update_incidencia/'.$id_pds_url.'/'.$id_inc_url.'/4/7')?>" class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Resuelta') {echo 'disabled';} ?>>Envíar</a></td></tr>
                                    	<tr class="odd gradeX"><td>Material recogido</td><td><a href="<?=site_url('admin/update_incidencia/'.$id_pds_url.'/'.$id_inc_url.'/4/8')?>" class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Pendiente recogida') {echo 'disabled';} ?>>Envíar</a></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            	</div>        
            </div>     	            
        </div>                 	            
        </div>
        <!-- /#page-wrapper -->

<?php $this->load->view('backend/intervenciones/nueva_intervencion');?>