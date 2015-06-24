		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <?php if(! is_null($email_sent)) { ?>
		    <div class="row">
		        <div class="col-lg-12">

                    <h2>Envío del parte por email al operador:</h2>
                    <?php
                    if($email_sent)
                    {
                        echo '<p class="message success"><i class="glyphicon glyphicon-ok"></i> El email con el parte de incidencia se ha enviado correctamente al operador.</p>';
                    }
                    else
                    {
                        echo '<p class="message error"><i class="glyphicon glyphicon-remove"></i> El email con el parte de incidencia <strong>NO</strong> se ha podido enviar al operador.</p>';
                    }
                    ?>
		        </div>
		    </div>
            <?php } ?>
		    <div class="row">
		        <div class="col-lg-12">
		           <h2>Descarga del parte:</h2>
                    <p>Pulsa en el siguiente enlace para descargar en tu equipo el parte de incidencia:</p>
                    <p class="message"><i class="fa fa-file-pdf-o"></i> <a href="<?=base_url().'admin/descargar_parte/'.$filename_pdf?>" target="_blank"><?=$filename_pdf?>.pdf</a></p>

		        </div>
		    </div>
            <div class="row">
                <div class="col-lg-12">
                    <h2>Volver a la incidencia:</h2>
                    <p><a href="<?=base_url().'admin/operar_incidencia/'.$id_pds_url.'/'.$id_inc_url?>" class="btn btn-warning" target><span class="glyphicon glyphicon-chevron-left"></span> Volver</a></p>

                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
