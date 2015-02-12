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
            <div class="col-lg-6">
                <a href="<?= site_url('admin/alta_incidencia/' . $id_pds_url) ?>">
                    <button type="button" class="btn btn-primary" style="width:200px;">Dar de alta nueva incidencia</button>
                </a>
            </div>
            <div class="col-lg-6">
                <a href="<?= site_url('admin/alta_incidencia_robo/' . $id_pds_url) ?>">
                    <button type="button" class="btn btn-danger" style="width:200px;">Dar de alta nuevo robo</button>
                </a>
            </div>
        </div>

        <div class="col-lg-6">
            <div id="donut_pds"></div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($incidencias)) {
                echo '<p>No hay incidencias.</p>';
            } else {
                ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Fecha</th>
                            <th>SFID</th>
                            <th>Incidencia</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($incidencias as $incidencia) {
                            ?>
                            <tr>
                                <td>#<?php echo $incidencia->id_incidencia ?></td>
                                <td><?php echo $incidencia->fecha ?></td>
                                <td><?php echo $incidencia->reference ?></td>
                                <td><?php echo $incidencia->description ?></td>
                                <td><?php echo $incidencia->contacto ?></td>
                                <td><?php echo $incidencia->phone ?></td>
                                <td><?php echo $incidencia->email ?></td>
                                <td><?php echo $incidencia->status_pds ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!--
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Seleccione la incidencia sobre la que operar.
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Fecha</th>
                                    <th>SFID</th>
                                    <th>Incidencia</th>
                                    <th>Contacto</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($incidencias as $incidencia) {
                                    ?>
                                    <tr>
                                        <td>#<?php echo $incidencia->id_incidencia ?></td>
                                        <td><?php echo $incidencia->fecha ?></td>
                                        <td><?php echo $incidencia->reference ?></td>
                                        <td><?php echo $incidencia->description ?></td>
                                        <td><?php echo $incidencia->contacto ?></td>
                                        <td><?php echo $incidencia->phone ?></td>
                                        <td><?php echo $incidencia->email ?></td>
                                        <td><?php echo $incidencia->status_pds ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                -->
            <?php
            }
            ?>
        </div>
    </div>

</div>
<!-- /#page-wrapper -->
