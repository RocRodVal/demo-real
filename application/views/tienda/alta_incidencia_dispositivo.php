<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
            	<a onclick="history.go(-1);return false;" class="btn btn-danger right">Volver</a>
            </h1>
        </div>
    </div>
    <form
        action="<?= site_url('tienda/insert_incidencia/'.$id_displays_pds.'/'.$id_devices_pds) ?>"
        method="post" class="content_auto form_login" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-offset-1 col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Tipo de incidencia</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_averia" id="tipo_averia_robo" value="1"> Robo
                                            </label>
                                            <label>
                                                <input type="radio" name="tipo_averia" id="tipo_averia_averia" value="0"> Avería
                                            </label>
                                            <div id="denuncia" hidden>
                                                <p>Suba una copia de la denuncia por robo:</p>
                                                <input id="denuncia" class="file" type="file" multiple=false name="userfile">
                                                <p>Suba una imagen del robo:</p>
                                                <input id="denunciaI" class="file" type="file" multiple=false name="userfileI" accept="image/png, .jpeg, .jpg, image/gif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="alarmaDisplay">
                                    <div class="col-lg-12">
                                        <label>Indica los elementos que están afectados</label>
                                        <ul style="list-style:none">
                                            <li><input type="checkbox" name="alarm_garra" value="1"> Soporte/Anclaje</li>
                                            <li><input type="checkbox" name="alarm_device" value="1"> Alarma/Cableado</li>
                                            <li><input type="checkbox" name="device" value="1"> Dispositivo</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row" id="description_textArea_device">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Describe brevemente el problema
                                                <small>(Mín. 10 caracteres)</small>
                                            </label>
                                            <textarea class="form-control" rows="5" name="description_1"
                                                      id="description_1"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Persona de contacto</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                                <input class="form-control" name="contacto" id="contacto"
                                                       placeholder="Nombre y apellidos">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Teléfono de contacto</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                                <input type="phone" class="form-control" name="phone" id="phone"
                                                       placeholder="Teléfono">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="col-lg-12">
                                                <?php
                                                if ($picture_url_dev <> '') {
                                                ?>
                                                <img src="<?= site_url('application/uploads/' . $picture_url_dev . '') ?>" style="width:100%;" title="<?php echo strtoupper($device) ?>"/>
                                                <?php
                                                }
												else
												{
												?>
												<p><strong><?php echo strtoupper($device); ?></strong></p>
												<?php
												}	
												?>
		                                        <h3>Datos teléfono</h3>
		                                        <p>
			                                        Modelo: <?php echo $device ?><br />
			                                        Modelo de marca: <?php echo $brand_name ?><br />
			                                        IMEI: <?php echo $IMEI ?><br />
			                                        MAC: <?php echo $mac ?><br />
			                                        Número de serie: <?php echo $serial ?><br />
			                                       	Código de barras: <?php echo $barcode ?><br />
			                                        Descripción: <?php echo $description ?>
		                                        </p>												
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row right">
                            <div class="right col-lg-12">
                                <input type="button" value="Cancelar" class="btn btn-danger"
                                       onclick="history.go(-1);return false;"/>
                                <input type="submit" value="Envíar" name="submit" class="btn btn-success"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /#page-wrapper -->