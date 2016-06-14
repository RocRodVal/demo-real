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
                    if (empty($stocks)) {
                        echo '<p>No hay datos.</p>';
                    } else {
                        ?>
                        <h1 class="page-header">Balance de activos <a href="<?=site_url('master/exportar_balance_activos');?>" title="Exportar Excel">Exportar Excel</a></h1>
                        <div class="table-responsive">

                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Unidades tienda</th>
                                    <th>Unidades en transito</th>
                                    <th>Stock necesario</th>
                                    <th>Deposito en almacén</th>
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($stocks as $stock) {
                                    $marcar_celda = TRUE;

                                    $balance = $stock->unidades_almacen - $stock->stock_necesario;

                                    $class_almacen = ($stock->unidades_almacen < 5 && $marcar_celda) ? 'warning': '';
                                    $class_balance = ($balance < 0 && $marcar_celda) ? 'notice' : '';


                                    if(
                                            ($stock->unidades_pds > 0 || $stock->unidades_almacen > 0) //

                                    )
                                    {
                                    ?>
                                    <tr>
                                        <td><?php echo $stock->brand ?></td>
                                        <td><?php echo $stock->device ?></td>
                                        <td><?php echo $stock->unidades_pds ?></td>
                                        <td><?php echo $stock->unidades_transito ?></td>
                                        <td><?php echo $stock->stock_necesario ?></td>
                                        <td class="<?=$class_almacen?>"><?php echo $stock->unidades_almacen ?></td>
                                        <td class="<?=$class_balance?>"><?php echo $balance ?></td>
                                    </tr>
                                        <?php } ?>
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
