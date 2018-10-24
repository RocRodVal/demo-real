
            <?php


            if (!$cancelando_incidencias)
            {
            ?>
            <div class="row">
                <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> Introduce IDs de las incidencias a cancelar.</p>
            </div>
            <?php
            }else{ ?>
            <div class="row">
                <p>Se han cancelado las incidencias con IDs:</p>
                <ul>
                    <?php foreach($checked_incidencias as $inc=>$incidencia)
                    {
                        if(is_null($incidencia)){
                            echo '<li>' . $inc . ' <span class="error">(Incidencia no encontrada)</span></li>';
                        }else {
//print_r($incidencia);
                            if(!is_object($incidencia) && $incidencia==0){
                                echo '<li>' . $inc . ' <span class="error">(Incidencia ya está cancelada)</span></li>';
                            }else {
                                if(!is_object($incidencia) && $incidencia==-1){
                                    echo '<li>' . $inc . ' <span class="error">(Incidencia no se puede cancelar)</span></li>';
                                }else {
                                    if(!is_object($incidencia) && $incidencia==-2){
                                        echo '<li>' . $inc . ' <span class="error">(Incidencia no se puede cancelar)</span></li>';
                                    }else {
                                        echo '<li>' . $inc . ' <span class="exito">(Incidencia cancelada)</span></li>';
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php }?>
        </div>
        <!-- /#page-wrapper -->
