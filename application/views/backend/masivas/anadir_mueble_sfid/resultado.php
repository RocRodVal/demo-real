
            <?php


            if (!$anadiendo_mueble)
            {
            ?>
            <div class="row">
                <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> Introduce SFIDs y el mueble a ser añadido a su planograma de tienda.</p>
            </div>
            <?php
            }else{ ?>
            <div class="row">
                <h2>Mueble añadido <small><a href="anadir_mueble_sfid">Añadir otro</a></small></h2>
                <p>Se ha añadido el mueble <strong><?=$mueble?></strong> en la posición <strong><?=$position?></strong>, en los siguientes SFIDs:</p>
                <ul>
                    <?php foreach($checked_sfids as $sfid=>$pds)
                    {
                        if(is_null($pds)){
                            echo '<li>' . $sfid . ' <span class="error">(SFID no encontrado)</span></li>';
                        }else {
                            echo '<li>' . $sfid . ' <span class="exito">(Mueble añadido)</span></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php }?>
        </div>
        <!-- /#page-wrapper -->
