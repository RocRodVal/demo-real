
    <div class="row">
        <div class="col-lg-12">
            <h2><?=$subtitle?></h2>
            <?php
            if(empty($devices)){
                echo '<p>No tenemos resultados para sus datos.</p>';
            }
            else
            {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Seleccione dispositivo
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="list-group">
                                    <?php
                                    foreach($devices as $device)
                                    {
                                        ?>
                                        <a class="list-group-item" href="<?=site_url('admin/informe_planograma_terminal/'.
                                            $id_pds_url.'/'.$id_dis_url.'/'.$device->id_devices_pds)?>">
                                            <?php echo $device->position.'. '.$device->device ?>
                                            <?php
                                            //print_r($device);exit;
                                            if ($device->estado == 'Incidencia')
                                            {
                                                ?>
                                                <i class="fa fa-exclamation-triangle" style="color:red"></i>
                                            <?php
                                            }
                                            
                                             if ($device->id_muebledisplay != 0 && $device->id_muebledisplay!=null)
                                            {
                                                ?>
                                                <i class="fa fa-spin fa-star-half-o" style="color:orange"></i> - <?=$device->muebledisplayname;?>
                                            <?php 
                                            }
                                            ?>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <?php
                                if ($picture_url != '')
                                {
                                    ?>
                                    <img src="<?=site_url('application/uploads/'.$picture_url.'')?>" title="<?php echo strtoupper($display) ?> " style='width:100%'; />
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <p><strong><?php echo strtoupper($display); ?></strong></p>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->