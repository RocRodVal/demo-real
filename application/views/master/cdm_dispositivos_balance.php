		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="filtro">
                        <form action="<?=base_url()?>master/cdm_dispositivos_balance" method="post" class="filtros form-mini autosubmit col-lg-12">
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
                                <a href="<?=base_url()?>master/cdm_dispositivos_balance/borrar_busqueda" class="reiniciar_busqueda form-control input-sm">Reiniciar</a>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="do_busqueda" value="si">
                            </div>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                    <?php
                    if (empty($stocks)) {
                        echo '<p>No hay datos.</p>';
                    } else {
                        ?>
                        <h1 class="page-header">Balance de activos <a href="<?=site_url('master/exportar_balance_activos');?>" title="Exportar Excel">Exportar Excel</a></h1>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-borde-lineal table-sistemas-seguridad" id="dataTables-example">
                            <!--<table class="table table-striped table-bordered table-hover" id="dataTables-example">-->
                                <thead>
                                <tr>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Uds. tienda</th>
                                    <th>Uds. en transito</th>
                                    <th>Uds. reservadas</th>
                                    <th>Deposito en almacén RMA</th>
                                    <th>Deposito en almacén</th>
                                    <th>Uds. Robadas</th>
                                    <th>Total</th>
                                    <th>Stock necesario</th>
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($stocks as $stock) {
                                    $marcar_celda = TRUE;

                                    if($stock->unidades_pds ==0) {
                                        $necesitamos = 0;
                                        $marcar_celda = FALSE;

                                    }else {
                                        $necesitamos = $stock->stock_necesario;
                                    }

                                    $balance = $stock->unidades_almacen - $stock->stock_necesario;

                                    $class_almacen = ($stock->unidades_almacen < 5 && $marcar_celda) ? 'warning': '';
                                    $class_balance = ($balance < 0 && $marcar_celda) ? 'notice' : '';


                                   /* if(
                                            ($stock->unidades_pds > 0 || $stock->unidades_almacen > 0) //

                                    )
                                    {*/
                                    ?>
                                    <tr>
                                        <th class="balance"><?php echo $stock->brand ?></th>
                                        <th class="balance"><?php echo $stock->device ?></th>
                                        <td><?php echo $stock->unidades_pds ?></td>
                                        <td><?php echo $stock->unidades_transito ?></td>
                                        <td><?php echo $stock->unidades_reservado ?></td>
                                        <td><?php echo $stock->unidades_rma ?></td>
                                        <td class="<?=$class_almacen?>"><?php echo $stock->unidades_almacen ?></td>
                                        <td><?php echo $stock->unidades_robadas ?></td>
                                        <td><?=$stock->total?></td>
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

        </div>
        <!-- /#page-wrapper -->
