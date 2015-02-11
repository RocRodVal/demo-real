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
            <form action="<?=site_url('admin/insert_incidencia/'.$id_pds_url.'/'.$denuncia.'/'.$id_dis_url.'/'.$id_dev_url)?>" method="post" class="content_auto form_login">
            <div class="row">
                <div class="col-lg-12">
 					<div class="panel panel-default">
               	 		<div class="panel-heading">
                            Rellene todos los datos de la incidencia.
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                        	<td>
                                        		<p><strong><?php echo $device ?></strong></p>
	                                            <label>Tipo de incidencia</label>
	                                            <div class="radio">
	                                                <label>
	                                                    <input type="radio" name="tipo_averia" id="tipo_averia" value="Rotura" checked>Rotura
	                                                </label>
	                                            </div>
	                                            <div class="radio">
	                                                <label>
	                                                    <input type="radio" name="tipo_averia" id="tipo_averia" value="Avería">Avería
	                                                </label>
	                                            </div>
	                                            <h3>Datos teléfono</h3>
	                                            <p>
	                                            <strong>Modelo</strong><br>
	                                            <pre><?php echo $device ?></pre>
	                                            <label>¿La alarma central del mueble está afectada?</label>
	                                            <div class="radio">
	                                                <label>
	                                                    <input type="radio" name="alarm_display" id="alarm_display" value="1">Sí
	                                                </label>
	                                            </div>
	                                            <div class="radio">
	                                                <label>
	                                                    <input type="radio" name="alarm_display" id="alarm_display" value="0" checked>No
	                                                </label>
	                                            </div>	                                            
	                                            
	                                            <label>¿La alarma que soporta el telefono está afectada?</label>
	                                            <div class="radio">
	                                                <label>
	                                                    <input type="radio" name="alarm_device" id="alarm_device" value="1">Sí
	                                                </label>
	                                            </div>
	                                            <div class="radio">
	                                                <label>
	                                                    <input type="radio" name="alarm_device" id="alarm_device" value="0" checked>No
	                                                </label>
	                                            </div>
		                                        <div class="form-group">
		                                            <label>Describe brevemente el problema</label>
		                                            <textarea class="form-control" rows="5" name="description" id="description"></textarea>
		                                        </div>
		                                        <div class="form-group">
		                                            <label>Persona de contacto</label>
		                                            <input class="form-control" name="contacto" id="contacto" placeholder="Nombre y apellidos">
		                                        </div>	
		                                        <div class="form-group">
		                                            <label>Teléfono de contacto</label>
		                                            <input class="form-control" name="phone" id="phone" placeholder="Teléfono">
		                                        </div>			                                        
		                                        <div class="form-group">
		                                            <label>Email de contacto</label>
		                                            <input class="form-control" name="email" id="email" placeholder="Email">
		                                        </div>
		                                        <input type="submit" class="submit" value="Envíar"  class="btn btn-lg btn-success btn-block" />
		                                        <a href="<?=site_url('admin/dashboard')?>" class="btn btn-lg btn-danger btn-block">Cancelar</a>			                                                                                    
                                        	</td>
                                        	<?php 
                                        	if ($picture_url_dev <> '') {
                                        	?>
                                        	<td><img src="../../../orange/application/uploads/<?php echo $picture_url_dev ?>" width="600" title="<?php echo $device ?>"/></td>
                                        	<?php 
                                        	}
                                        	?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            	</div>        
            </div>
            </form>    	            
        </div>
        <!-- /#page-wrapper -->