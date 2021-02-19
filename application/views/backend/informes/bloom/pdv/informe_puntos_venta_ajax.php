<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 24/07/2015
 * Time: 13:41
 */
?>
<?php

if (!$generado)
{
    ?>
    <div class="row">
        <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> Selecciona algún criterio para la generación del informe "Punto de Venta".</p>
    </div>
<?php
}else{ ?>

<div class="row" style="display: block">
    <?php

    if(is_null($resultados)){
        echo "<p class='message error'><i class='glyphicon glyphicon-remove'></i> Debes introducir algún criterio para poder generar el informe.</p>";
    }elseif(count($resultados) == 0){
        echo "<p class='message error'><i class='glyphicon glyphicon-remove'></i> No existen datos para mostrar con los criterios escogidos para generar el informe.</p>";
    }else{ ?>


        <h3>Informe generado</h3>


            <div class="pagination">
                <p>Encontrados <?=$total_registros?> resultados.</p>
            </div>




        <p><a href="<?=base_url()?><?=$controlador?>/resultado_pdv/exportar" class="btn exportar" target="_blank"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a></p>
        <table class="table table-striped table-bordered table-hover table-sorting" id="table_incidencias_dashboard" data-order-form="form_orden_activas">
            <thead>
            <tr>
                <th>SFID</th>
                <th>Codigo SAT</th>
                <th>Canal</th>
                <th>Tipología</th>
                <th>Concepto</th>
                <th>Categorización</th>


                <th>Territorio</th>

                <th>Nombre</th>
                <th>Dirección</th>
                <th>Provincia</th>

            </tr>
            </thead>
            <tbody>
            <?php foreach($resultados as $resultado)
            {?>
                <tr>
                    <td><?=$resultado->reference?></td>
                    <td><?=$resultado->codigoSAT?></td>

                    <td><?=$resultado->tipo?></td>
                    <td><?=$resultado->subtipo?></td>
                    <td><?=$resultado->segmento?></td>
                    <td><?=$resultado->tipologia?></td>

                    <td><?=$resultado->territorio?></td>
                    <td><?=$resultado->commercial?></td>
                    <td><?=$resultado->tipo_via?> <?=$resultado->address?> <?=$resultado->zip?> <?=$resultado->city?></td>
                    <td><?=$resultado->provincia?></td>


                </tr>
            <?php }
            ?>
            </tbody>
        </table>
        <p><a href="<?=base_url()?><?=$controlador?>/resultado_pdv/exportar" class="btn exportar" target="_blank"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a></p>

    <?php }
    ?>
</div>
<?php }?>