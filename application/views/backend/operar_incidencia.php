<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <div class="data_tienda"><?php echo $commercial ?> /
                    <?php echo $address ?> , <?php echo $zip ?> -  <?php echo $city ?></div>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cambiar el estado de la incidencia
                </div>
                <div class="panel-body incidenciaEstado">

                    <div class="row">
                        <div class="col-lg-7 labelText grey">Revisión de incidencia</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/2/2') ?>"
                               classBtn="status_1/2" class="btn btn-success" <?php if ($incidencia['status'] != 'Nueva') {
                                echo 'disabled';
                            } ?>>Revisar</a>
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/5/9') ?>"
                               classBtn="status_1/2" class="btn btn-danger" <?php if ($incidencia['status'] != 'Nueva') {
                                echo 'disabled';
                            } ?>>Cancelar</a>
                        </div>
                        <div class="col-lg-7 labelText grey">Asignar instalador e intervención</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a onClick="showModalNewIntervencion(<?php echo $id_pds_url . ',' . $id_inc_url ?>)"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Revisada') {
                                echo 'disabled';
                            } ?>>Asignar instalador</a>
                        </div>                        
                        <div class="col-lg-7 labelText white">Asignar materiales</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia_materiales/' . $id_pds_url . '/' . $id_inc_url . '/2/3') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Instalador asignado') {
                                echo 'disabled';
                            } ?>>Asignar mat.</a></td>
                        </div>
                        <div class="col-lg-7 labelText white">Imprimir documentación</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/3/5') ?>"
                               classBtn="status" class="btn btn-success"
                                <?php if ($incidencia['status'] == 'Material asignado' ||
                                            $incidencia['status'] == 'Comunicada' ||
                                            $incidencia['status'] == 'Resuelta' ||
                                            $incidencia['status'] == 'Pendiente recogida') {
                                    echo '';
                                }
                                else{
                                    echo 'disabled';
                            } ?>>Imprimir</a>
                        </div>
                        <div class="col-lg-7 labelText grey">Resolver incidencia</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/6') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Comunicada') {
                                echo 'disabled';
                            } ?>>Resolver</a>
                        </div>
                        <div class="col-lg-7 labelText white">Emisión de recogida de material</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/7') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Resuelta') {
                                echo 'disabled';
                            } ?>>Recogida</a>
                        </div>
                        <div class="col-lg-7 labelText grey">Material recogido</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_incidencia/' . $id_pds_url . '/' . $id_inc_url . '/4/8') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($incidencia['status'] != 'Pendiente recogida') {
                                echo 'disabled';
                            } ?>>Cerrar</a>
                        </div>

                    </div>
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
                    <strong>Estado:</strong> <?php echo $incidencia['status'] ?><br/>
                    <strong>Mueble:</strong> <?php echo $incidencia['display']['display'] ?><br/>
                    <strong>Teléfono:</strong> <?php echo $incidencia['device']['brand_name']." / ".$incidencia['device']['device'] ?><br/>
                    <strong>Intervención:</strong>
                    <?php
                    //Si el estado es superior a Instalador asignado e intervención!=null->Esto nunca debería darse pero se contempla
                    if (($incidencia['status'] == 'Comunicada' || $incidencia['status'] == 'Resuelta' ||
                            $incidencia['status'] == 'Instalador asignado') && $incidencia['intervencion'] != null
                    ) {
                        ?>
                        <a onClick="showModalViewIntervencion(<?php echo $incidencia['intervencion']; ?>)">
                            #<?php echo $incidencia['intervencion']; ?></a>
                    <?php
                    } else {
                        echo "-";
                    }

                    ?><br/>
                    <strong>Comentario:</strong> <?php echo $incidencia['description_1'] ?><br/>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- /#page-wrapper -->

<?php $this->load->view('backend/intervenciones/nueva_intervencion'); ?>
<?php $this->load->view('backend/intervenciones/ver_intervencion'); ?>