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
                        <h1 class="page-header">Balance de activos <a href="<?=site_url('master/cdm_balance_activos_csv');?>" title="Exportar CSV" target="_blank">CSV</a></h1>
                        <div class="table-responsive">

                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Unidades tienda</th>
                                    <th>Stock necesario</th>
                                    <th>Deposito en almacén</th>
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($stocks as $stock) {
                                    if($stock->unidades_pds ==0)
                                    {
                                        $necesitamos = 0;

                                    }else
                                    {
                                        $necesitamos = round($stock->unidades_pds * 0.05) + 2;
                                    }

                                    $balance = $stock->unidades_almacen - $necesitamos;

                                    if($stock->unidades_pds > 0 || $stock->unidades_almacen > 0)
                                    {
                                    ?>
                                    <tr>
                                        <td><?php echo $stock->brand ?></td>
                                        <td><?php echo $stock->device ?></td>
                                        <td><?php echo $stock->unidades_pds ?></td>
                                        <td><?php echo $necesitamos ?></td>
                                        <td><?php echo $stock->unidades_almacen ?></td>
                                        <td <?=($balance<0)?'style="background-color:red;color:white;"':''?>><?php echo $balance ?></td>
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
            <div class="row">
		        <div class="col-lg-12">
		            <?php
		            if (empty($stocks_dispositivos)) {
		                echo '<p>No hay datos.</p>';
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
        </div>
        <!-- /#page-wrapper -->
