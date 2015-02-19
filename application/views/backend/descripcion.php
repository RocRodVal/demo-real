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
                <div class="col-lg-6">
                	<form action="<?=site_url('admin/descripcion');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <label>SFID</label>
                            <input class="form-control" placeholder="SFID" name="sfid" id="sfid">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php 
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
               	 		<div class="panel-heading">
                            Seleccione el punto de venta.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>SFID / Referencia</th>
                                            <th>Tipo</th>
                                            <th>Panelado</th>
                                            <th>Nombre comercial</th>
                                            <th>Zona</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($tiendas as $tienda)
    									{
    									?>
    									<tr>
    										<td><a href="<?=site_url('admin/exp_alta_incidencia/'.$tienda->id_pds)?>"><?php echo $tienda->reference ?></a></td>
    										<td><?php echo $tienda->pds ?></td>
    										<td><?php echo $tienda->panelado ?></td>
    										<td><?php echo $tienda->commercial ?></td>
    										<td><?php echo $tienda->territory ?></td>
    									</tr>
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

