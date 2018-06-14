
            <?php

            if (!$eliminando_mueble)
            {
            ?>
            <div class="row">
                <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> Introduce SFIDs y el mueble a ser eliminado a su planograma de tienda.</p>
            </div>
            <?php
            }else{ ?>
            <div class="row">
                <h2>Mueble Eliminado <small><a href="anadir_mueble_sfid">Eliminar otro</a></small></h2>
                <p>Se ha eliminado el mueble <strong><?=$mueble?></strong>, en los siguientes SFIDs:</p>
                <ul>
                    <?php foreach($checked_sfids as $sfid=>$pds)
                    {
                        if(is_null($pds)){
                            echo '<li>' . $sfid . ' <span class="error">(SFID no encontrado)</span></li>';
                        }else {
                            echo '<li>' . $sfid . ' <span class="exito">(Mueble eliminado)</span></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php }?>
        </div>
        <!-- /#page-wrapper -->
