
            <?php
            if (!$generado_visual)
            {
            ?>
            <div class="row">
                <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> Selecciona algún criterio para la generación del informe "Visual".</p>
            </div>
            <?php
            }else{ ?>
            <div class="row">


            </div>
            <?php }?>

            <?php if($error_panelado) {?>
                <p class="message error"><i class="glyphicon glyphicon-remove"></i> Debes escoger la categoría de tienda completa (Tipo, Subtipo, Segmento y Tipología) para poder mostrar sus muebles.</p>
            <?php } ?>
        </div>
        <!-- /#page-wrapper -->