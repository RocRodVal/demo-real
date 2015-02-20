		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
		        <div class="col-lg-12">
		        	<h1 class="page-header"><?php echo $title ?>
						<a href="<?=site_url('tienda/dashboard')?>" class="btn btn-danger right">Volver</a>
					</h1>
		        </div>
            </div>
    		<div class="row botonera_up">
        		<div class="col-lg-6 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
            		<a href="<?=site_url('tienda/alta_incidencia/'.$id_pds_url)?>">
                	<button type="button" class="btn btn-primary btn-accion">Nueva ALTA INCIDENCIA</button>
           			</a>
        		</div>
        		<div class="col-lg-6 col-md-3 col-sm-6 col-xs-6" style="text-align:center;">
            		<a href="<?=site_url('tienda/planograma/'.$id_pds_url)?>">
                		<button type="button" class="btn btn-default btn-accion">Mi  tienda</button>
            		</a>
        		</div>
    		</div>
    		<div class="row">
		        <div class="col-lg-12">
		            <?php
		            if (empty($incidencias)) {
		                echo '<p>No hay incidencias.</p>';
		            } else {
		                ?>
		                <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                        <thead>
		                        <tr>
		                            <th>Ref.</th>
		                            <th>Fecha</th>
		                            <th>Descripción</th>
		                            <th>Introducida por</th>
		                            <th>Dispositivo</th>
		                            <th>Mueble</th>
		                            <th>Tipo</th>
		                            <th>Estado</th>
		                            <th>Acciones</th>
		                        </tr>
		                        </thead>
		                        <tbody>
		                        <?php
		                        foreach ($incidencias as $incidencia) {
		                            ?>
		                            <tr>
		                                <td>#<?php echo $incidencia->id_incidencia ?></td>
		                                <td><?php echo date_format(date_create($incidencia->fecha), 'd-m-Y'); ?></td>
		                                <td><?php echo $incidencia->description ?></td>
		                                <td><?php echo $incidencia->contacto ?></td>
		                                <td><i class="fa fa-eye" data-toggle="tooltip" data-placement="top"
		                                       title="<img class='tooltip_image' src='<?php echo base_url().'application/uploads/'.
		                                           $incidencia->device['picture_url']; ?>'/><br/>
		                                           <?php echo $incidencia->device['device'];?>"></i></td>
		                                <td><i class="fa fa-eye" data-toggle="tooltip" data-placement="top"
		                                       title="<img class='tooltip_image' src='<?php echo base_url().'application/uploads/'.
		                                           $incidencia->display['picture_url']; ?>'/><br/>
		                                           <?php echo $incidencia->display['display'];?>"></i></td></td>
		                                <td><?php echo $incidencia->tipo_averia ?></td>
		                                <td><strong><?php echo $incidencia->status_pds ?></strong></td>
		                                <td><i class="fa fa-whatsapp"></i></td>
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