<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
            	<a onclick="window.location = '<?= site_url('tienda/pedidos/abiertos');?>'" class="btn btn-danger right">Volver</a>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
    <?php
    if(!empty($message)) {
        echo $message;
    } else {
    ?>
</div>
    <form
        action="<?= site_url('tienda/insert_pedido') ?>"
        method="post" class="content_auto form_login" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-offset-1 col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <label>Alarmas</label>
                                        <div id="alerta">
                                            <i class="fa fa-exclamation-triangle" style="color: orange"></i><code>Debe indicar una cantidad mayor que cero para alguna alarma</code>
                                        </div>
                                        <br>
                                        <div class="col-lg-12">
                                        <?php
                                        foreach($alarmas as $alarma){
                                        ?>

                                            <div class="col-lg-10">
                                                <input class="form-control" name="alarmas[]" id="alarmas" value="<?=$alarma->alarm?>"
                                                       disabled>
                                                </div>
                                            <div class="col-lg-2">
                                                <input class="form-control" name="cantidades[<?=$alarma->id_alarm?>]" id="cantidades" value="" placeholder="0">
                                            </div>
                                        <?php
                                        } ?>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Datos de contacto</label>
                                        <div class="form-group"></div>
                                        <div class="form-group"></div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><code>*</code><i class="fa fa-user"></i></div>
                                                <input class="form-control" name="contacto" id="contacto"
                                                       placeholder="Nombre y apellidos">
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-addon"><code>*</code><i class="fa fa-phone"></i></div>
                                                <input type="phone" class="form-control" name="phone" id="phone"
                                                       placeholder="Teléfono">
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-addon"><code></code><i class="fa fa-envelope-o"></i></div>
                                                <input type="phone" class="form-control" name="email" id="email"
                                                       placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row right">
                            <div class="right col-lg-12">
                                <input type="button" value="Cancelar" class="btn btn-danger" onclick="window.location = '<?= site_url('tienda/pedidos/abiertos');?>'"/>
                                <input type="submit" value="Envíar" name="submit" class="btn btn-success"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php } ?>
</div>
<!-- /#page-wrapper -->