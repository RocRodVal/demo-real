<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <a href="<?= site_url('tienda/dashboard') ?>" class="btn btn-danger right">Volver</a>
            </h1>
        </div>
    </div>

    <form
        action="<?= site_url('tienda/insert_incidencia/' . $id_display . '/' . $id_device) ?>"
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
                                                <input type="radio" name="tipo_averia" id="tipo_averia_averia" value="0">Avería
                                            </label>

                                            <div id="denuncia" hidden>
                                                <p>Suba una copia de la denuncia por robo:</p>
                                                <input id="denuncia" class="file" type="file" multiple=false
                                                       name="userfile">
                                            </div>
                                        </div>
                                        <div class="radio">

                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="alarmaDisplay">
                                    <div class="col-lg-12">
                                        <label>Indica los elementos que están afectados</label>

                                        <p>
                                            <input type="checkbox" name="device" value=0>Dispositivo
                                            <input type="checkbox" name="alarm_garra" value=0>Soporte/Anclaje
                                            <input type="checkbox" name="alarm_device" value=0>Alarma/Cableado
                                        </p>
                                    </div>
                                </div>
                                <div class="row" id="description_textArea_device">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Describe brevemente el problema
                                                <small>(Mín. 20 caracteres)</small>
                                            </label>
                                            <textarea class="form-control" rows="5" name="description_1"
                                                      id="description_1"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!--//
                           		<p>Suba una foto de la incidencia #1:</p>
								<input id="photo_1" class="file" type="file" multiple=false name="photo_1">
                           		<p>Suba una foto de la incidencia #2:</p>
								<input id="photo_2" class="file" type="file" multiple=false name="photo_2">
                           		<p>Suba una foto de la incidencia #3:</p>
								<input id="photo_3" class="file" type="file" multiple=false name="photo_3">
								//-->
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
                        <div class="row right">
                            <div class="right col-lg-12">
                                <input type="button" value="Cancelar" class="btn btn-danger"
                                       onclick="window.location='<?= site_url('tienda/dashboard') ?>'"/>
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