<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-danger right">Volver</a>
            </h1>
        </div>
    </div>

    <form
        action="<?= site_url('tienda/insert_incidencia/'. $denuncia . '/' . $id_display . '/' . $id_device) ?>"
        method="post" class="content_auto form_login">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Tipo de incidencia</label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_averia" id="tipo_averia" value="1"
                                                 <?php if($denuncia!="no-robo") echo "checked";?>>Robo
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_averia" id="tipo_averia" value="0"
                                                 <?php if($denuncia=="no-robo") echo "checked";?>>Avería
                                            </label>
                                        </div>
                                    </div>
                                </div>
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
                                <div class="row" hidden id="alarmaDevice">
                                    <div class="col-lg-12">
                                        <label>¿La alarma que soporta el telefono está afectada?</label>

                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="alarm_device" id="alarm_device" value="1">Sí
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="alarm_device" id="alarm_device" value="0">No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" hidden id="description_textArea">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Describe brevemente el problema <small>(Mín. 20 caracteres)</small></label>
                                            <textarea class="form-control" rows="5" name="description"
                                                      id="description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Persona de contacto</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                                <input class="form-control" name="contacto" id="contacto"
                                                       placeholder="Nombre y apellidos" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Teléfono de contacto</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                                <input type="phone" class="form-control" name="phone" id="phone"
                                                       placeholder="Teléfono" disabled>
                                            </div>
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
                        <div class="row right">
                            <div class="col-lg-12">
                                <input type="button" value="Cancelar" class="btn btn-danger"
                                       onclick="window.location='<?= site_url('tienda/dashboard') ?>'"/>
                                <input type="submit" value="Envíar" name="submit" class="btn btn-success" disabled/>
                            </div>
                        </div>
                        <!--
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>
                                        <p><strong><?php echo $device ?></strong></p>
                                        <label>Tipo de incidencia</label>

                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipo_averia" id="tipo_averia" value="Rotura"
                                                       checked>Rotura
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
                                                <input type="radio" name="alarm_display" id="alarm_display" value="0"
                                                       checked>No
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
                                                <input type="radio" name="alarm_device" id="alarm_device" value="0"
                                                       checked>No
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>Describe brevemente el problema</label>
                                            <textarea class="form-control" rows="5" name="description"
                                                      id="description"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Persona de contacto</label>
                                            <input class="form-control" name="contacto" id="contacto"
                                                   placeholder="Nombre y apellidos">
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono de contacto</label>
                                            <input type="phone" class="form-control" name="phone" id="phone"
                                                   placeholder="Teléfono">
                                        </div>
                                        <div class="form-group">
                                            <label>Email de contacto</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                   placeholder="Email">
                                        </div>
                                        <input type="submit" value="Envíar" class="btn btn-lg btn-success btn-block"/>
                                        <input type="button" value="Cancelar" class="btn btn-lg btn-danger btn-block"
                                               onclick="window.location='<?= site_url('admin/dashboard') ?>'"/>
                                    </td>
                                    <?php
                        if ($picture_url_dev <> '') {
                            ?>
                                        <td><img src="<?= site_url('application/uploads/' . $picture_url_dev . '') ?>"
                                                 width="200" title="<?php echo $device ?>"/></td>
                                    <?php
                        }
                        ?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /#page-wrapper -->