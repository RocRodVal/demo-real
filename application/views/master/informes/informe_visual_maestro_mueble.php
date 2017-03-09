

<div class="row">
    <h1 class="page-header"><?php echo $subtitle ?>
        <a onclick="history.go(-1);return false;" class="btn btn-danger right">Volver</a>
    </h1>
</div>
<div class="row">
    <?php
    if(empty($devices)){
        echo '<p>No tenemos resultados para sus datos.</p>';
    }
    else
    {
        ?>
        <div class="panel panel-default">

            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="list-group">
                            <?php
                            foreach($devices as $device)
                            {
                                ?>
                                <a class="list-group-item" href="<?=site_url('master/informe_visual_terminal/'.$id_display.'/'.$device->id_device)?>">
                                    <?php echo $device->position.'. '.$device->device ?>
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