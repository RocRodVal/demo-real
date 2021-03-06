		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <p>Introduce el SFID que quieres cambiar:</p>
                	<form action="<?=site_url('admin/cambio_sfid');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <label>SFID</label>
                            <input class="form-control" placeholder="SFID" name="sfid" id="sfid">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php

            if(!is_null($enuso)){

                echo ($enuso=="FALSE")
                    ? '<p class="message success"><i class="glyphicon glyphicon-success"></i> El cambio de SFID se ha realizado correctamente. </p>'
                    : '<p class="message error"><i class="glyphicon glyphicon-error"></i>  El  SFID indicado ya está en uso. El cambio no se ha realizado.</p>';

            }

            if (isset($_POST['sfid']))
            {	
            ?>
            <div class="row">
                <div class="col-lg-12">
 					<?php
                    if(empty($tiendas)){
                    	echo '<p>No hay resultados para esa cadena de búsqueda.</p>';
                    }
                    else
                    {					
 					?>
 					<div class="panel panel-default">
                        <div class="panel-body">
                        	<div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Ref.</th>
                                            <th>SFID actual</th>
                                            <th>SFID nuevo.</th>
                                            <th>Canal</th>
                                            <th>Tipología</th>
                                            <th>Concepto</th>
                                            <th>Categorización</th>

                                            <th>Nombre comercial</th>
                                            <th>Territorio</th>
                                            <th>Operaciones</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($tiendas as $tienda)
    									{
    									?>
    									<form action="<?=site_url('admin/update_sfid');?>" method="post" class="form-inline form-sfid">
    									<input type="hidden" name="id_pds" id="id_pds" value="<?php echo $tienda->id_pds ?>">
    									<input type="hidden" name="sfid_old" id="sfid_old" value="<?php echo $tienda->reference ?>">
    									<tr>
    										<td><?php echo $tienda->id_pds ?></td>
    										<td><?php echo $tienda->reference ?></a></td>
    										<td><input class="form-control" placeholder="nuevo SFID" name="sfid_new" id="sfid_new"></a></td>
    										<td><?php echo $tienda->tipo ?></td>
                                            <td><?php echo $tienda->subtipo ?></td>
                                            <td><?php echo $tienda->segmento ?></td>
                                            <td><?php echo $tienda->tipologia ?></td>


    										<td><?php echo $tienda->commercial ?></td>
    										<td><?php echo $tienda->territory ?></td>
    										<td><button type="submit" class="btn btn-default">Cambiar</button></td>
    									</tr>
    									</form>
					    				<?php
					    				}
					    				?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php 
					}
                    ?>                    
            	</div>        
            </div>
            <?php 
            }
            ?>                
        </div>
        <!-- /#page-wrapper -->

        <?php $this->load->view('backend/intervenciones/ver_intervencion');?>

