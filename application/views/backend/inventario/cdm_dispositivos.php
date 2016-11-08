<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($stocks) ) {
                if($opcion==1) {
                    echo '<p>No hay datos.</p>';
                }
            } else {
                ?>
                <h1 class="page-header">Balance de activos <a href="<?=site_url('inventario/exportar_balance_activos/xls');?>" title="Exportar Excel">Exportar Excel</a></h1>
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Unidades tienda</th>
                            <th>Unidades en transito</th>
                            <th>Stock necesario</th>
                            <th>Deposito en almacén RMA</th>
                            <th>Deposito en almacén</th>
                            <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        foreach ($stocks as $stock) {
                            $marcar_celda = TRUE;

                            if($stock->unidades_pds ==0)
                            {
                                $necesitamos = 0;
                                $marcar_celda = FALSE;

                            }else
                            {
                                $necesitamos = round($stock->unidades_pds * 0.05) + 2;
                            }

                            $balance = $stock->unidades_almacen - $necesitamos;



                            $class_almacen = ($stock->unidades_almacen < 5 && $marcar_celda) ? 'warning': '';
                            $class_balance = ($balance < 0 && $marcar_celda) ? 'notice' : '';

                            //if($stock->unidades_pds > 0 || $stock->unidades_almacen > 0)
                            /*if(        ($stock->unidades_pds > 0 || $stock->unidades_almacen > 0) //

                            )
                            {*/
                            ?>
                            <tr>
                                <td><?php echo $stock->brand ?></td>
                                <td><?php echo $stock->device ?></td>
                                <td><?php echo $stock->unidades_pds ?></td>
                                <td><?php echo $stock->unidades_transito ?></td>
                                <td><?php echo $necesitamos ?></td>
                                <td><?php echo $stock->unidades_rma ?></td>
                                <td class="<?=$class_almacen?>"><?php echo $stock->unidades_almacen ?></td>
                                <td class="<?=$class_balance?>"><?php echo $balance ?></td>
                            </tr>
                            <?php //} ?>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($stocks_dispositivos)) {
                if($opcion==2) {
                    echo '<p>No hay datos.</p>';
                }
            } else {
                ?>
                <h1 class="page-header">Incidencias por dispositivo</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Incidencias</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($stocks_dispositivos as $stock) {
                            ?>
                            <tr>
                                <td><?php echo $stock->brand ?></td>
                                <td><?php echo $stock->device ?></td>
                                <td><?php echo $stock->incidencias ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($devices_almacen)) {
                if($opcion==3) {
                    echo '<p>No hay dispositivos.</p>';
                }
            } else {
                ?>
                <h1 class="page-header">Dispositivos almacén <a href="<?=site_url('admin/exportar_dispositivos_almacen/xls');?>" title="Exportar Excel">Exportar Excel</a></h1>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Dispositivo</th>
                            <th>Unidades</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($devices_almacen as $device_almacen) {
                            ?>
                            <tr>
                                <td><?php echo $device_almacen->device ?></td>
                                <td><?php echo $device_almacen->unidades ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($alarms_almacen)) {
                if($opcion==6) {
                    echo '<p>No hay alarmas.</p>';
                }
            } else {
                ?>
                <h1 class="page-header">Alarmas almacén <a href="<?=site_url('admin/exportar_alarmas_almacen/xls');?>" title="Exportar Excel">Exportar Excel</a></h1>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Alarma</th>
                            <th>Unidades</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($alarms_almacen as $alarma) {
                            ?>
                            <tr>
                                <td><?php echo $alarma->brand.' '.$alarma->alarm ?></td>
                                <td><?php echo $alarma->units ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($devices_pds)) {
                if($opcion==4) {
                    echo '<p>No hay dispositivos.</p>';
                }
            } else {
                ?>
                <h1 class="page-header">Dispositivos tiendas</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Dispositivo</th>
                            <th>Unidades</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($devices_pds as $device_pds) {
                            ?>
                            <tr>
                                <td><?php echo $device_pds->device ?></td>
                                <td><?php echo $device_pds->unidades ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($displays_pds) ) {
                if($opcion==5) {
                    echo '<p>No hay muebles.</p>';
                }
            } else {
                ?>
                <h1 class="page-header">Muebles tiendas</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Mueble</th>
                            <th>Unidades</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($displays_pds as $display_pds) {
                            ?>
                            <tr>
                                <td><?php echo $display_pds->display ?></td>
                                <td><?php echo $display_pds->unidades ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->