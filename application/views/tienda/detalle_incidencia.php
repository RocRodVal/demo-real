<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <a href="<?= site_url('tienda/dashboard') ?>" class="btn btn-danger right">Volver</a>
            </h1>
        </div>
    </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Tipo de incidencia</label>
                                        <p><?php echo $tipo_averia ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Describe brevemente el problema <small>(Mín. 20 caracteres)</small></label>
                                            <p><?php echo $description_1 ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Fecha de alta</label>
											<p><p><?php echo date_format(date_create($fecha), 'd/m/Y') ?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Estado</label>
											<p><?php echo $status_pds ?></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Persona de contacto</label>
											<p><p><?php echo $contacto ?></p>
                                        </div>
                                    </div>                                    
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Teléfono de contacto</label>
											<p><?php echo $phone ?></p>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                    	<?php 
                                    	if ($device == NULL)
                                    	{
                                    		$name   = $display;
                                    		$imagen = $picture_url_dis;
                                    	}
                                    	else
                                    	{
                                    		$name   = $device;
                                    		$imagen = $picture_url_dev;
                                    	}	
                                    	?>
                                    	
                                        <?php echo $name; ?>
                                    </div>
                                    <div class="panel-body">

                                        <div class="col-lg-12">
                                            <?php
                                            if ($imagen <> '') {
                                                ?>
                                                <img
                                                    src="<?= site_url('application/uploads/' . $imagen . '') ?>"
                                                    style="width:100%;" title="<?php echo $name ?>"/>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- /#page-wrapper -->