		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Balance de sistemas de seguridad
                        <?php if(!empty($stock_balance)) { ?><a href="<?=site_url('master/exportar_balance_alarmas');?>" title="Exportar Excel">Exportar Excel</a><?php } ?>

                    </h1>
                    <?php
                    if (empty($stock_balance)) {
                        echo '<p>No hay datos.</p>';
                    } else {
                        ?>
                        <h1></h1>
                        <div class="table-responsive">

                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>Fabricante</th>
                                    <th>Modelo</th>
                                    <th>Imagen</th>
                                    <th>Punto de pedido</th>
                                    <th>Stock</th>
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($stock_balance as $stock) {

                                        $necesitamos = $stock->punto_pedido;
                                        $balance = round($stock->unidades_almacen - $stock->punto_pedido);

                                        // Parche de stock = 0; cuando es negativo
                                        $num_stock = ($stock->unidades_almacen < 0) ? 0 :  $stock->unidades_almacen;
                                        $balance = round($num_stock - $stock->punto_pedido);
                                        // FIN PARCHE





                                        $class_almacen = ($stock->unidades_almacen < 5 ) ? 'warning': '';
                                        $class_balance = ($balance < 0) ? 'notice' : '';

                                        ?>
                                        <tr>
                                            <td><?php echo $stock->brand ?></td>
                                            <td><?php echo $stock->alarm ?></td>
                                            <td><?php

                                                $imagen = 'application/uploads/'.$stock->imagen;
                                                if(!empty($stock->imagen) && file_exists($imagen)) { ?>
                                                    <img src="<?=base_url().$imagen?>" width="50">
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $stock->punto_pedido?></td>
                                            <td class="<?=$class_almacen?>"><?php echo $num_stock ?></td>
                                            <td class="<?=$class_balance?>"><?php echo $balance ?></td>
                                        </tr>
                                    <?php  ?>
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


		    <div class="row">
		        <div class="col-lg-12">
                    <h1 class="page-header">Incidencias por sistema de seguridad</h1>
		            <?php
		            if (empty($stocks)) {
		                echo '<p>No hay datos.</p>';
		            } else {
		            ?>

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
		                        foreach ($stocks as $stock) {
		                            ?>
		                            <tr>
		                                <td><?php echo $stock->brand ?></td>
		                                <td><?php echo $stock->alarm ?></td>
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
        </div>
        <!-- /#page-wrapper -->
