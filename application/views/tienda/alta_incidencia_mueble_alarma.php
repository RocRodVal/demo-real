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
        action="<?= site_url('tienda/insert_incidencia_mueble_alarma/'.$id_display) ?>"
        method="post" class="content_auto form_login" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-offset-1 col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="row" id="description_textArea_device">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Describe brevemente el problema <small>(Mín. 20 caracteres)</small></label>
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
                                                       placeholder="Teléfono" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <?php echo $display; ?>
                                        </div>
                                        <div class="panel-body">

                                            <div class="col-lg-12">
                                                <?php
                                                if ($picture_url_dis <> '') {
                                                    ?>
                                                    <img
                                                        src="<?= site_url('application/uploads/'.$picture_url_dis.'') ?>"
                                                        style="width:100%;" title="<?php echo $display ?>"/>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-lg-offset-8 col-lg-4">
                                <input type="button" value="Cancelar" class="btn btn-danger"
                                       onclick="window.location='<?= site_url('tienda/dashboard') ?>'"/>
                                <input type="submit" value="Envíar" name="submit" class="btn btn-success" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /#page-wrapper -->