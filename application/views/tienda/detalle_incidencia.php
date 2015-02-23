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
                           		<p>Suba una copia de la denuncia por robo:</p>
								<input id="file-0" class="file" type="file" multiple=false name="userfile">
                                <div class="row" hidden id="alarmaDisplay">
                                    <div class="col-lg-12">
                                        <label>¿La alarma central del mueble está afectada?</label>

                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="alarm_display" id="alarm_display" value="1">Sí
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="alarm_display" id="alarm_display" value="0">No
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Describe brevemente el problema <small>(Mín. 20 caracteres)</small></label>
                                            <p><?php echo $description_2 ?></p>
                                        </div>
                                    </div>
                                </div>                                                           
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>¿La alarma que soporta el telefono está afectada?</label>
                                        <p>
                                        <?php 
                                        if ($alarm_device == 1)
										{
											echo 'Sí';
										}
										else {
											echo 'No';
										}
                                        ?>
                                        </p>
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
                                        <?php echo $device; ?>
                                    </div>
                                    <div class="panel-body">

                                        <div class="col-lg-12">
                                            <?php
                                            if ($picture_url_dev <> '') {
                                                ?>
                                                <img
                                                    src="<?= site_url('application/uploads/' . $picture_url_dev . '') ?>"
                                                    style="width:100%;" title="<?php echo $device ?>"/>
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