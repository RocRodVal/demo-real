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
                	<form action="<?=site_url('admin/subir_denuncia/'.$id_pds_url)?>" method="post" class="content_auto form_login" enctype="multipart/form-data">
					    <p>Suba una copia de la denuncia por robo:</p>
					    <input type="file" name="userfile"  />
					    <br />
					    <input type="submit" name="submit" value=" Cargar " class="btn-lg btn-success" />
					</form>
            	</div>        
            </div>     	            
        </div>
        <!-- /#page-wrapper -->