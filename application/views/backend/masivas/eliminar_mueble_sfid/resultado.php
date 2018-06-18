
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
                <h2>Mueble Eliminado <small><a href="eliminar_mueble_sfid">Eliminar otro</a></small></h2>
                <p>Se ha eliminado el mueble <strong><?=$mueble?></strong>, en los siguientes SFIDs:</p>
                <ul>
                    <?php foreach($checked_sfids as $sfid)
                    {
                        //print_r($sfid);
                        if($sfid["resultado"]==0){
                            echo '<li>' . $sfid["sfid"] . ' <span class="error">(SFID no actualizado)</span>'.$sfid["mensaje"].'</li>';
                        }else {
                            echo '<li>' . $sfid["sfid"] . ' <span class="exito">(Mueble eliminado)</span>'.$sfid["mensaje"].'</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php if(strpos($result,"ASSETS")) { ?>
                <p>Se ha eliminado el mueble <strong><?=$mueble?></strong>, en project:
                    <?php print_r($result); ?>
                </p>

            <?php }
            }?>

        </div>
        <!-- /#page-wrapper -->
