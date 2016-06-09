<!-- #page-wrapper -->

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <?php
                if (($status=='Finalizado') || ($status=='Cancelado')) {
                    ?>
                    <a href="<?=site_url('tienda/pedidos/finalizados') ?>" class="btn btn-danger right">Volver</a>
                <?php } else { ?>
                    <a href="<?=site_url('tienda/pedidos/abiertos')?>" class="btn btn-danger right">Volver</a>

                <?php } ?>
                <!--<a href="#" onclick="history.go(-1);return false;" class="btn btn-danger right">Volver</a>-->
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
                                    <div class="form-group">
                                        <label>Fecha de alta</label>

                                        <p><?php echo date_format(date_create($fecha), 'd/m/Y') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Persona de contacto</label>

                                        <p><?php echo $contacto ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Teléfono de contacto</label>

                                        <p><?php echo $phone ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Email de contacto</label>

                                        <p><?php echo $email ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php //$this->load->view('tienda/chat.php'); ?>
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    //echo $status;
                                    $value_pedido = 0;
                                    switch ($status) {
                                        case "Nuevo":
                                            $value_pedido = 1;
                                            break;
                                        case "En proceso":
                                            $value_pedido = 2;
                                            break;
                                        case "Pendiente material":
                                            $value_pedido = 3;
                                            break;
                                        case "Enviado":
                                            $value_pedido = 4;
                                            break;
                                        case "Recibido":
                                            $value_pedido = 5;
                                            break;
                                        case "Finalizado":
                                            $value_pedido = 6;
                                            break;
                                        case "Cancelado":
                                            $value_pedido = 7;
                                            break;
                                    }
                                    ?>
                                    <ul class="timeline">
                                        <li>
                                            <div
                                                class="timeline-badge <?php echo ($value_pedido > 1) ? 'sombreado' : '' ?>">
                                                <i class="glyphicon glyphicon-check"></i></div>
                                            <div style="padding:10px 20px 0px 20px"
                                                 class="timeline-panel <?php echo ($value_pedido > 1) ? 'state_pasado sombreado' : '' ?>
                                            <?php echo ($value_pedido == 1) ? 'state_actual' : '' ?>">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">Nuevo</h4>
                                                </div>
                                            </div>
                                        </li>
                                        <?php if ($value_pedido > 1) { ?>
                                            <li class="timeline-inverted">
                                                <div
                                                    class="timeline-badge <?php echo ($value_pedido > 2) ? 'sombreado' : '' ?>">
                                                    <i class="fa fa-cogs"></i></div>
                                                <div style="padding:10px 20px 0px 20px"
                                                     class="timeline-panel <?php echo ($value_pedido > 2) ? 'state_pasado sombreado' : '' ?>
                                            <?php echo ($value_pedido == 2) ? 'state_actual' : '' ?>">
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title">En proceso</h4>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php
                                        }
                                        if (($value_pedido > 2) && ($value_pedido!=7) && ($value_pedido == 3)) {
                                            ?>
                                            <li class="timeline-inverted">
                                                <div
                                                    class="timeline-badge <?php echo ($value_pedido > 3) ? 'sombreado' : '' ?>">
                                                    <i class="fa fa-cogs"></i></div>
                                                <div style="padding:10px 20px 0px 20px"
                                                     class="timeline-panel <?php echo ($value_pedido > 3) ? 'state_pasado sombreado' : '' ?>
                                            <?php echo ($value_pedido == 3) ? 'state_actual' : '' ?>">
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title">Pendiente material</h4>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php
                                        }
                                        if (($value_pedido > 3) && ($value_pedido!=7)){
                                            ?>
                                            <li>
                                                <div
                                                    class="timeline-badge <?php echo ($value_pedido > 4) ? 'sombreado' : '' ?>">
                                                    <i class="fa fa-truck"></i></div>
                                                <div style="padding:10px 20px 0px 20px"
                                                     class="timeline-panel <?php echo ($value_pedido > 4) ? 'state_pasado sombreado' : '' ?>
                                            <?php echo ($value_pedido == 4) ? 'state_actual' : '' ?>">
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title">Enviado</h4>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                        if (($value_pedido > 4) && ($value_pedido!=7)) {
                                            ?>
                                            <li>
                                                <div
                                                    class="timeline-badge <?php echo ($value_pedido > 5) ? 'sombreado' : '' ?>">
                                                    <i class="fa fa-truck"></i></div>
                                                <div style="padding:10px 20px 0px 20px"
                                                     class="timeline-panel <?php echo ($value_pedido > 5) ? 'state_pasado sombreado' : '' ?>
                                            <?php echo ($value_pedido == 5) ? 'state_actual' : '' ?>">
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title">Recibido</h4>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                        if ($value_pedido > 5) {
                                            ?>
                                            <li class="timeline-inverted">
                                                <div class="timeline-badge"><i
                                                        class="glyphicon glyphicon-credit-card"></i></div>
                                                <div style="padding:10px 20px 0px 20px"
                                                     class="timeline-panel <?php echo ($value_pedido >= 6) ? 'state_actual' : '' ?>">
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title"><?php echo ($value_pedido >= 6) ? $status : 'Finalizado' ?></h4>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <?php $this->load->view('tienda/chat_pedido.php'); ?></div>

                    <?php
                    if (count($detalle)>0) {
                    ?>
                        <div class="col-lg-7">
                    <div class="panel panel-default">
                        <div class="panel-body">
                                <div class="col-lg-8">
                                    <label>Alarmas del pedido</label>
                                </div>
                                <?php
                                    foreach($detalle as $d) {
                                        if ($d->imagen <> '') {
                                            ?>

                                                <div class="col-lg-12">
                                                            <div class="col-lg-3">
                                                                <img
                                                                    src="<?= site_url('application/uploads/' . $d->imagen . '') ?>"
                                                                    title="<?php echo strtoupper($d->alarm) ?>" style="max-height: 45px;"/>
                                                            </div>

                                                            <div class="col-lg-8"><?=$d->alarm; ?></div>

                                                            <div class="col-lg-1"><?= $d->cantidad ?></div>
                                                    </div>
                                                            <?php
                                                        } else { ?>
                                                <div class="col-lg-12">
                                                            <div class="col-lg-6"><strong><?php echo strtoupper($d->alarm); ?></strong></div>

                                                            <div class="col-lg-6"><?= $d->cantidad ?></div>
                                                    </div>
                                                       <?php
                                                        } ?>

                                                    <?php
                                    }
                                    ?>


                            </div>

                        </div>
                    </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /#page-wrapper -->