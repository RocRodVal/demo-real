		<!-- #page-wrapper -->
        <div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?php echo $title ?>
						<a href="<?= site_url('admin/dashboard') ?>" class="btn btn-danger right">Volver</a>
					</h1>
				</div>
			</div>
			<!--
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							DATOS DEL PUNTO DE VENTA
						</div>
						<div class="panel-body">
							<strong>SFID:</strong> <?php echo $reference ?> [<?php echo $id_pds ?>]<br/>
							<strong>Nombre comercial:</strong> <?php echo $commercial ?><br/>
							<strong>Dirección:</strong> <?php echo $address ?>, <?php echo $zip ?> -  <?php echo $city ?><br/>
							<strong>Territorio:</strong> <?php echo $territory ?>
						</div>
					</div>
				</div>


				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="panel panel-alert-orange">
						<div class="panel-body">
							<strong>RECUERDE</strong></br>
							si el mueble ha sido dañado o roto póngase en contacto primero con el equipo de mantenimiento del
							mismo en <strong>+XX XXX YYY ZZZ</strong> y una vez
							realizada la intervención proceda a crear la incidencia.
						</div>

					</div>
				</div>
			</div>
			-->
            <div class="row">
                <div class="col-lg-5">
                	<form action="<?=site_url('admin/subir_denuncia/'.$id_pds_url)?>" method="post" class="content_auto form_login" enctype="multipart/form-data">
					    <p>Suba una copia de la denuncia por robo:</p>
						<input id="file-0" class="file" type="file" multiple=false name="userfile">

						<!--<input type="file" name="userfile"/>
					    <br/>
					    <input type="submit" name="submit" value=" Cargar " class="btn-lg btn-success" />
					    -->
					</form>
            	</div>        
            </div>     	            
        </div>
        <!-- /#page-wrapper -->