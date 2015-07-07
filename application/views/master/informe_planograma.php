
            <?php
            if (!$generado_planograma)
            {
            ?>
            <div class="row">
                <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> Selecciona algún criterio para la generación del informe "Planogramas".</p>
            </div>
            <?php
            }else{ ?>
            <div class="row">
                <?php
                     /*if(is_null($resultados)){
                         echo "<p class='message error'><i class='glyphicon glyphicon-remove'></i> Debes introducir algún criterio para poder generar el informe.</p>";
                     }elseif(count($resultados) == 0){
                         echo "<p class='message error'><i class='glyphicon glyphicon-remove'></i> No existen datos para mostrar con los criterios escogidos para generar el informe.</p>";
                     }else{ ?>

                    <?php  */
                ?>
            </div>
            <?php }?>
        </div>
        <!-- /#page-wrapper -->
