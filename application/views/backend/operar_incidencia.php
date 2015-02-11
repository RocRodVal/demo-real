<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Datos puntos de venta
                </div>
                <div class="panel-body">
                    <strong>SFID:</strong> <?php echo $reference ?> [<?php echo $id_pds ?>]<br/>
                    <strong>Nombre comercial:</strong> <?php echo $commercial ?><br/>
                    <strong>Dirección:</strong> <?php echo $address ?>, <?php echo $zip ?> -  <?php echo $city ?><br/>
                    <strong>Zona:</strong> <?php echo $territory ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información de la incidencia
                </div>
                <div class="panel-body">
                    <strong>Fecha alta:</strong> <?php echo $incidencia['fecha'] ?><br/>
                    <strong>Estado PdS:</strong> <?php echo $incidencia['status_pds'] ?><br/>
                    <strong>Estado SAT:</strong> <?php echo $incidencia['status'] ?><br/>
                    <strong>Comentario:</strong> <?php echo $incidencia['description'] ?><br/>
                    <strong>Intervención:</strong>
                    <?php
                        if ($incidencia['status'] == 'Comunicada' || $incidencia['status'] == 'Resuelta' ||
                            $incidencia['status'] == 'Instalador asignado') {
                            ?>
                            <a onClick="showModalViewIntervencion(<?php echo $incidencia['intervencion']; ?>)">
                                #<?php echo $incidencia['intervencion']; ?></a>
                    <?php
                        }
                        else{
                            echo "-";
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cambiar el estado de la incidencia
                </div>
                <div class="panel-body incidenciaEstado">

                    <div class="row">
                        <div class="col-lg-7 labelText grey">Revisión de incidencia</div><div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/2') ?>"
                               class="btn btn-success" <?php if ($incidencia['status'] != 'Nueva') {
                                echo 'disabled';
                            } ?>>Revisar</a>
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/5/9') ?>"
                               class="btn btn-danger" <?php if ($incidencia['status'] != 'Nueva') {
                                echo 'disabled';
                            } ?>>Cancelar</a>
                        </div>
                        <div class="col-lg-7 labelText white">Asignar materiales</div><div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>"
                               class="btn btn-success" <?php if ($incidencia['status'] != 'Revisada') {
                                echo 'disabled';
                            } ?>>Asignar mat.</a></td>
                        </div>
                        <div class="col-lg-7 labelText grey">Asignar instalador e intervención</div><div class="col-lg-5 labelBtn grey">
                            <a onClick="showModalNewIntervencion(<?php echo $id_pds_url . ',' . $id_inc_url ?>)"
                               class="btn btn-success" <?php if ($incidencia['status'] != 'Material asignado') {
                                echo 'disabled';
                            } ?>>Asignar instalador</a>
                        </div>
                        <div class="col-lg-7 labelText white">Imprimir documentación</div><div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5') ?>"
                               class="btn btn-success" <?php if ($incidencia['status'] != 'Instalador asignado') {
                                echo 'disabled';
                            } ?>>Imprimir</a>
                        </div>
                        <div class="col-lg-7 labelText grey">Resolver incidencia</div><div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/6') ?>"
                               class="btn btn-success" <?php if ($incidencia['status'] != 'Comunicada') {
                                echo 'disabled';
                            } ?>>Resolver</a>
                        </div>
                        <div class="col-lg-7 labelText white">Emisión de recogida de material</div><div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/7') ?>"
                               class="btn btn-success" <?php if ($incidencia['status'] != 'Resuelta') {
                                echo 'disabled';
                            } ?>>Recogida</a>
                        </div>
                        <div class="col-lg-7 labelText grey">Material recogido</div><div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/8') ?>"
                               class="btn btn-success" <?php if ($incidencia['status'] != 'Resuelta') {
                                echo 'disabled';
                            } ?>>Cerrar</a>
                        </div>

                    </div>

                    <!--
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <tbody>
                            <tr class="odd gradeX">
                                <td>Revisión de incidencia</td>
                                <td>
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/2') ?>"
                                       class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Nueva') {
                                        echo 'disabled';
                                    } ?>>Envíar</a><a
                                        href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/5/9') ?>"
                                        class="btn btn-lg btn-danger btn-block" <?php if ($incidencia['status'] != 'Nueva') {
                                        echo 'disabled';
                                    } ?>>Cancelar</a></td>
                            </tr>
                            <tr class="odd gradeX">
                                <td>Asignar materiales</td>
                                <td>
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>"
                                       class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Revisada') {
                                        echo 'disabled';
                                    } ?>>Envíar</a></td>
                            </tr>
                            <tr class="odd gradeX">
                                <td>Asignar instalador e intervencion</td>
                                <td><a onClick="showModalNewIntervencion(<?php echo $id_pds_url . ',' . $id_inc_url ?>)"
                                       class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Material asignado') {
                                        echo 'disabled';
                                    } ?>>Envíar</a></td>
                            </tr>
                            <tr class="odd gradeX">
                                <td>Imprimir documentación</td>
                                <td>
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5') ?>"
                                       class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Instalador asignado') {
                                        echo 'disabled';
                                    } ?>>Envíar</a></td>
                            </tr>
                            <tr class="odd gradeX">
                                <td>Resolución de incidencia</td>
                                <td>
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/6') ?>"
                                       class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Comunicada') {
                                        echo 'disabled';
                                    } ?>>Envíar</a></td>
                            </tr>
                            <tr class="odd gradeX">
                                <td>Emisión de recogida de material</td>
                                <td>
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/7') ?>"
                                       class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Resuelta') {
                                        echo 'disabled';
                                    } ?>>Envíar</a></td>
                            </tr>
                            <tr class="odd gradeX">
                                <td>Material recogido</td>
                                <td>
                                    <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/8') ?>"
                                       class="btn btn-lg btn-success btn-block" <?php if ($incidencia['status'] != 'Pendiente recogida') {
                                        echo 'disabled';
                                    } ?>>Envíar</a></td>
                            </tr>
                            </tbody>
                        </table>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /#page-wrapper -->

<?php $this->load->view('backend/intervenciones/nueva_intervencion'); ?>
<?php $this->load->view('backend/intervenciones/ver_intervencion');?>