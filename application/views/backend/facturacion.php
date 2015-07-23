		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
		    <div class="row">
		        <div class="col-lg-12">
		        	<p>Seleccione un rango de fechas, dueño y/o instalador.</p>
					<form action="<?=site_url('admin/facturacion');?>" method="post" class="form-inline form-sfid">

                        <div class="form-group">
                            <label>Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo date('Y-m-01'); ?>">
                        </div>

                        <div class="form-group">
                            <label>Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label>Dueño</label>
                            <select name="dueno" id="dueno">
                                <option value="">Todos</option>
                                <?php
                                foreach($select_duenos as $elem_dueno){
                                    $option_selected = ($dueno == $elem_dueno->id_client) ? 'selected ="selected" ' : '';
                                    echo '<option value="'.$elem_dueno->id_client.'" '.$option_selected.'>'.$elem_dueno->client.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Instalador</label>

                            <select name="instalador" id="instalador">
                                <option value="">Todos</option>
                                <?php
                                    foreach($select_instaladores as $inst){
                                        $option_selected = ($instalador == $inst->id_contact) ? 'selected ="selected" ' : '';
                                        echo '<option value="'.$inst->id_contact.'" '.$option_selected.'>'.$inst->contact.'</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>				  
					</form>
		        </div>
		    </div>            
		    <div class="row">
		        <div class="col-lg-12">
		            <?php
		            if ((isset($_POST['fecha_inicio'])) || (isset($_POST['fecha_fin'])))
		            {
		            if (empty($facturacion)) {
		                echo '<p>No hay información sobre facturación.</p>';
		            } else {



		            ?>
		            	<h1 class="page-header">Intervenciones [descargar <a href="<?=site_url('admin/facturacion_csv/'.$fecha_inicio.'/'.$fecha_fin.'/'.$instalador.'/'.$dueno);?>" target="_blank">CSV</a>]</h1>
		                <div class="table-responsive">
		                	<p><strong>Rango:</strong> <?php echo $fecha_inicio ?>/<?php echo $fecha_fin ?></p>

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
		                            <th>Incidencias</th>
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
		                                <td><?php echo $item_facturacion->fecha; ?></td>
		                                <td><?php echo $item_facturacion->SFID ?></td>
		                                <td><?php echo $item_facturacion->pds ?></td>
		                                <td><?php echo $item_facturacion->visita ?></td>
		                                <td><?php echo $item_facturacion->incidencias ?></td>
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
