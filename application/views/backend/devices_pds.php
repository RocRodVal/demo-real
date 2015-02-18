<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        	<h1 class="page-header"><?php echo $title ?>
				<a href="<?=site_url('admin/dashboard')?>" class="btn btn-danger right">Volver</a>
			</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($devices)) {
                echo '<p>No hay dispositivos.</p>';
            } else {
                ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Ref.</th>
                            <th>Device</th>
                            <th>Unidades</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($devices as $device) {
                            ?>
                            <tr>
                                <td>#<?php echo $device->id_devices_pds ?></td>
                                <td><?php echo $device->device ?></td>
                                <td><?php echo $device->unidades ?></td>
                                <td><?php echo $device->status ?></td>
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
