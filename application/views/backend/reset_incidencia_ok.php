		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?> <font color="red">[Beta]</font></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Resultado de la operación de reseteo de incidencia:</p>

                    <p>&nbsp;</p>
                </div>
            </div>            
            <div class="row">
                <div class="col-lg-12">


                    <?php if(!empty($mensaje_error))  { ?>
                        <p class="message error"><i class="glyphicon glyphicon-remove"></i> <?=$mensaje_error?></p>
                    <?php } ?>
                    <?php if(!empty($mensaje_exito))  { ?>
                        <p class="message success"><i class="glyphicon glyphicon-ok"></i> <?=$mensaje_exito?></p>
                    <?php } ?>


                </div>
            </div>

        </div>
        <!-- /#page-wrapper -->

