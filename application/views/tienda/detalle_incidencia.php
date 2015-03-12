<!-- #page-wrapper -->
<?php

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <a href="<?=site_url('tienda/dashboard')?>" class="btn btn-danger right">Volver</a>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Persona de contacto</label>

                                        <p><?php echo $contacto ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Teléfono de contacto</label>

                                        <p><?php echo $phone ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Tipo de incidencia</label>

                                    <p>
                                        <?php echo $tipo_averia ?>
                                        <?php
                                        if ($tipo_averia == 'Robo') {
                                            ?>
                                            [<a href="<?= site_url('uploads/' . $denuncia) ?>" target="_blank">ver
                                                denuncia</a>]
                                        <?php
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Descripción del problema
                                        </label>

                                        <p><?php echo $description_1 ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php $this->load->view('tienda/chat.php'); ?>
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    $value_incidencia = 0;
                                    switch ($status_pds) {
                                        case "Alta realizada":
                                            $value_incidencia = 1;
                                            break;
                                        case "En proceso":
                                            $value_incidencia = 2;
                                            break;
                                        case "En visita":
                                            $value_incidencia = 3;
                                            break;
                                        case "Finalizada":
                                            $value_incidencia = 4;
                                            break;
                                        case "Cancelada":
                                            $value_incidencia = 5;
                                            break;
                                    }
                                    ?>
                                    <ul class="timeline">
                                        <li>
                                            <div
                                                class="timeline-badge <?php echo ($value_incidencia > 1) ? 'sombreado' : '' ?>">
                                                <i class="glyphicon glyphicon-check"></i></div>
                                            <div style="padding:10px 20px 0px 20px"
                                                 class="timeline-panel <?php echo ($value_incidencia > 1) ? 'state_pasado sombreado' : '' ?>
                                            <?php echo ($value_incidencia == 1) ? 'state_actual' : '' ?>">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">Alta realizada</h4>
                                                </div>
                                            </div>
                                        </li>
                                        <?php if ($value_incidencia > 1) { ?>
                                            <li class="timeline-inverted">
                                                <div
                                                    class="timeline-badge <?php echo ($value_incidencia > 2) ? 'sombreado' : '' ?>">
                                                    <i
                                                        class="fa fa-cogs"></i></div>
                                                <div style="padding:10px 20px 0px 20px"
                                                     class="timeline-panel <?php echo ($value_incidencia > 2) ? 'state_pasado sombreado' : '' ?>
                                            <?php echo ($value_incidencia == 2) ? 'state_actual' : '' ?>">
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title">En proceso</h4>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php
                                        }
                                        if ($value_incidencia > 2) {
                                            ?>
                                            <li>
                                                <div
                                                    class="timeline-badge <?php echo ($value_incidencia > 3) ? 'sombreado' : '' ?>">
                                                    <i class="fa fa-truck"></i></div>
                                                <div style="padding:10px 20px 0px 20px"
                                                     class="timeline-panel <?php echo ($value_incidencia > 3) ? 'state_pasado sombreado' : '' ?>
                                            <?php echo ($value_incidencia == 3) ? 'state_actual' : '' ?>">
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title">En visita</h4>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php
                                        }
                                        if ($value_incidencia > 3) {
                                            ?>
                                            <li class="timeline-inverted">
                                                <div class="timeline-badge"><i
                                                        class="glyphicon glyphicon-credit-card"></i></div>
                                                <div style="padding:10px 20px 0px 20px"
                                                     class="timeline-panel <?php echo ($value_incidencia >= 4) ? 'state_actual' : '' ?>">
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title"><?php echo ($value_incidencia >= 4) ? $status_pds : 'Finalizada' ?></h4>
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
                        <div class="col-lg-3">
                            <div class="panel panel-default">
                                <?php
                                if ($device == NULL) {
                                    $name = $display;
                                    $imagen = $picture_url_dis;
                                } else {
                                    $name = $device;
                                    $imagen = $picture_url_dev;
                                }
                                ?>
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <?php
                                        if ($imagen <> '') {
                                            ?>
                                            <img
                                                src="<?= site_url('application/uploads/' . $imagen . '') ?>"
                                                style="width:100%;" title="<?php echo strtoupper($name) ?>"/>
                                            <p><strong><?php echo strtoupper($name); ?></strong></p>
                                        <?php
                                        } else {
                                            ?>
                                            <p><strong><?php echo strtoupper($name); ?></strong></p>
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