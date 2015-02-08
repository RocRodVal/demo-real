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
                <div class="col-lg-6">                         
 					<p>
 						Recuede, si el mueble ha sido dañado o roto póngase en contacto primero con el equipo de mantenimiento del mismo en +XX XXX YY ZZ y una vez
 						realizada la intervención proceda a crear la incidencia.
 					</p>.
 					<p><a href="<?=site_url('admin/dashboard')?>" class="btn btn-lg btn-danger btn-block">Cancelar</a></p>
 					<br clear="all" />
 				</div>	
            </div>
            <div class="row">
                <div class="col-lg-12">
 					
 					<?php
                    if(empty($displays)){
                    	echo '<p>No tenemos resultados para sus datos.</p>';
                    }
                    else
                    {					
 					?> 
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Seleccione el mueble de la incidencia.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <?php
                                        $i = 1;
   										foreach($displays as $display)
   										{	
   										if ($i == 1) { echo '<tr>'; }
   										
   										if (($this->tienda_model->count_devices_display($display->id_display)) != 0)
   										{		
    									?>
                                        	<td><a href="<?=site_url('admin/alta_incidencia_mueble')?>/<?php echo $display->id_pds.'/'.$display->id_display ?>"><?php echo $display->display ?></a><br clear="all" /><a href="<?=site_url('admin/alta_incidencia')?>/<?php echo $display->id_pds.'/'.$display->id_display ?>"><img src="<?=site_url('application/uploads/'.$display->picture_url.'')?>" title="<?php echo $display->display ?>" width="200" /></a></td>
					    				<?php
					    				++$i;
   										}
					    				if ($i % 4 == 0) { echo '</tr></tr>'; }					    				
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
        </div>
        <!-- /#page-wrapper -->