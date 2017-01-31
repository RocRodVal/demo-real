<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php if ( $opcion==1 ) { ?>
            <div class="filtro">
                <form action="<?=base_url()?>inventario/balance" method="post" class="filtros form-mini autosubmit col-lg-12">
                    <div class="col-lg-2">
                        <label for="id_marca">Marca: </label>
                        <select name="id_marca" id="id_marca" class="form-control input-sm">
                            <option value="" <?php echo ($id_marca==="") ? 'selected="selected"' : ''?>>Cualquiera...</option>
                            <?php foreach($marcas as $marca){
                                echo '<option value="'.$marca->id_brand_device.'"
                                                    '.(($id_marca == $marca->id_brand_device) ?  ' selected="selected" ' : '' ).'
                                                >'.$marca->brand.'</option>';
                            }?>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label for="id_modelo">Modelo: </label>
                        <select name="id_modelo" id="id_modelo" class="form-control input-sm">
                            <option value="" <?php echo ($id_modelo==="") ? 'selected="selected"' : ''?>>Cualquiera...</option>
                            <?php foreach($modelos as $modelo){
                                echo '<option value="'.$modelo->id_device.'"
                                                    '.(($id_modelo == $modelo->id_device) ?  ' selected="selected" ' : '' ).'
                                                >'.$modelo->device.'</option>';
                            }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <a href="<?=base_url()?>inventario/balance/borrar_busqueda" class="reiniciar_busqueda form-control input-sm">Reiniciar</a>
                    </div>

                <div class="form-group">
                    <input type="hidden" name="do_busqueda" value="si">
                </div>
            </form>
            </div>
                <div class="clearfix"></div>
                <h1 class="page-header">Balance de activos <a href="<?=site_url('inventario/exportar_balance_activos/xls');?>" title="Exportar Excel">Exportar Excel</a></h1>
        <?php } ?>

            <?php
            if (empty($stocks) ) {
                if($opcion==1) {
                    echo '<p>No hay datos.</p>';
                }
            } else {
                ?>
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Unidades tienda</th>
                            <th>Unidades en transito</th>
                            <th>Unidades reservadas</th>
                            <th>Deposito en almacén RMA</th>
                            <th>Deposito en almacén</th>
                            <th>Total</th>
                            <th>Stock necesario</th>
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
                                //$necesitamos = round($stock->unidades_pds * 0.05) + 2;
                                $necesitamos = $stock->stock_necesario;
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
                                <td><?php echo $stock->unidades_reservado ?></td>
                                <td><?php echo $stock->unidades_rma ?></td>
                                <td class="<?=$class_almacen?>"><?php echo $stock->unidades_almacen ?></td>
                                <td><?php echo $stock->total ?></td>
                                <td><?php echo $necesitamos ?></td>
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
    <!-- Material que esta pendiente de recoger-->
    <div class="row">
        <div class="col-lg-12">
            <?php
            if (empty($devices_recogida)) {
                if($opcion==7) {
                    echo '<p>No hay dispositivos pendientes de recogida.</p>';
                }
            } else {
                ?>
                <h1 class="page-header">Disposivos pendientes de recoger</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>Incidencia</th>
                            <th>Modelo</th>
                            <th>Descripcion</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($devices_recogida as $recogida) {
                            ?>
                            <tr>
                                <td><a href="<?=site_url("admin/operar_incidencia/".$recogida->id_pds."/".$recogida->id_incidencia)."/recogida";?>"><?php echo $recogida->id_incidencia ?></a></td>
                                <td><?php echo $recogida->device ?></td>
                                <td><?php echo $recogida->descripcion ?></td>
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