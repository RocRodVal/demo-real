		<!-- #page-wrapper -->
        <div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?php echo $title ?>
						<a href="<?= site_url('admin/dashboard') ?>" class="btn btn-danger right">Volver</a>
					</h1>
				</div>
			</div>
            <div class="row">
                <div class="col-lg-5">
                	<form action="<?=site_url('tienda/subir_denuncia/')?>" method="post" class="content_auto form_login" enctype="multipart/form-data">
					    <p>Suba una copia de la denuncia por robo:</p>
						<input id="file-0" class="file" type="file" multiple=false name="userfile">
					</form>
            	</div>
                <div class="col-lg-7">
                	<p><br /></p>
					<p><a href="<?=site_url('tienda/alta_incidencia/')?>" class="btn btn-primary">No ha sido robo</a></p>
            	</div>             	        
            </div>
        </div>
        <!-- /#page-wrapper -->