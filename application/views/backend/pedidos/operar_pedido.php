<style type="text/css">
    .zoom{
        /* Aumentamos la anchura y altura durante 2 segundos */
        transition: width 2s, height 2s, transform 2s;
        -moz-transition: width 2s, height 2s, -moz-transform 2s;
        -webkit-transition: width 2s, height 2s, -webkit-transform 2s;
        -o-transition: width 2s, height 2s,-o-transform 2s;

    }
    .zoom:hover{
        /* tranformamos el elemento al pasar el mouse por encima al doble de
        su tamaño con scale(2). */
        transform : scale(3,2);
        -moz-transform : scale(3,2); /* Firefox */
        -webkit-transform : scale(3,2); /* Chrome - Safari */
        -o-transform : scale(3,2); /* Opera */
    }
</style>
<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?>
                <div class="data_tienda"><strong>[<?php echo $reference ?>]</strong> <?php echo $commercial ?><br />
                    <?php echo $address ?><br />
                    <?php echo $zip ?> -  <?php echo $city ?> (<?php echo $province ?>)<br />
                    <?php 
                    if ($phone_pds <>'')
                    {	
                    ?>
                    Tel. <?php echo $phone_pds ?>
                    <?php 
                    }
                    ?>
                </div>
            </h1>
        </div>
    </div>


        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $title ?>
                    <?php
                    if (($pedido->status=='Finalizado') || ($pedido->status=='Cancelado')) {
                    ?>
                        <a href="<?=site_url('admin/pedidos/finalizados') ?>" class="btn btn-danger right">Volver</a>
                    <?php } else { ?>
                        <a href="<?=site_url('admin/pedidos/abiertos')?>" class="btn btn-danger right">Volver</a>

                <?php } ?>
                    
                </h1>
            </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Cambiar el estado del pedido
                </div>
                <div class="panel-body incidenciaEstado">
                    <div class="row">
                        <div class="col-lg-7 labelText grey">Última modificación:</div>
                        <div class="col-lg-5 labelText grey"><?=$last_update ?></div>
                        <div class="col-lg-7 labelText white">Revisión de pedido</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_pedido/' . $id_pedido_url . '/' . $id_pds_url . '/2') ?>"
                               classBtn="status_1/2" class="btn btn-success" <?php if (($pedido->status != 'Nuevo') && ($pedido->status != 'Pendiente material')) {
                                echo 'disabled';
                            } ?>>Procesar</a>
                            <a href="<?= site_url('admin/update_pedido/' .$id_pedido_url . '/' .  $id_pds_url . '/7') ?>"
                               classBtn="status_1/2" class="btn btn-danger" <?php if ($pedido->status != 'Nuevo') {
                                echo 'disabled';
                            } ?>>Cancelar</a>
                            <span class="fecha_status"><?=$historico_proceso?></span>


                        </div>

                        <div class="col-lg-7 labelText grey">Material enviado a tienda</div>
                        <div class="col-lg-5 labelBtn grey">
                            <a href="<?= site_url('admin/update_pedido/' . $id_pedido_url . '/' . $id_pds_url . '/4') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($pedido->status != 'En proceso') {
                                echo 'disabled';
                            } ?>>Enviado</a>
                            <span class="fecha_status"><?=$historico_fecha_enviado?></span>
                        </div>
                        <?php
                        if (($pedido->status!='Nuevo') || ($pedido->status!='En proceso') ||($pedido->status!='Pendiente material')){
                            ?>
                            <div class="col-lg-7 labelText white">Imprimir albaran</div>
                            <div class="col-lg-5 labelBtn white">
                                <a href="<?= site_url('admin/imprimir_pedido/' . $id_pedido_url . '/' . $id_pds_url ) ?>"
                                   classBtn="status" class="btn btn-success" <?php if (($pedido->status=='Nuevo') || ($pedido->status=='En proceso') ||($pedido->status=='Pendiente material')) {
                                    echo 'disabled';
                                } ?>>Imprimir</a>
                            </div>
                        <?php }
                        ?>
                        <div class="col-lg-7 labelText white">Material recibido en tienda</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_pedido/' . $id_pedido_url . '/' . $id_pds_url . '/5') ?>"
                               classBtn="status" class="btn btn-success" <?php if ($pedido->status != 'Enviado') {
                                echo 'disabled';
                            } ?>>Recibido</a>
                            <span class="fecha_status"><?=$historico_fecha_recibido?></span>
                        </div>
                        <div class="col-lg-7 labelText grey">Finalizar pedido<br/><br /></div>
		                <form action="<?= site_url('admin/update_pedido/' . $id_pedido_url . '/' . $id_pds_url . '/6') ?>" method="post">
		                <div class="col-lg-5 labelBtn grey">
		                    <input type="date" name="fecha_cierre" id="fecha_cierre" value="Fecha"   <?php if ($pedido->status == 'Finalizado') { echo 'disabled'; } ?> ><br />
		                    <input type="submit" value="Finalizar" name="submit" class="btn btn-success" classBtn="status" class="btn btn-success" <?php if ($pedido->status != 'Recibido') {
                                echo 'disabled';
                            } ?> />
                            <span class="fecha_status"><?=$historico_fecha_finalizado?></span>
		                </div>
		                </form>
                        <div class="col-lg-7 labelText white">Cierre forzoso</div>
                        <div class="col-lg-5 labelBtn white">
                            <a href="<?= site_url('admin/update_pedido/' . $id_pedido_url . '/' . $id_pds_url . '/8/ext') ?>"
                             classBtn="status" class="btn btn-danger" <?php if (($pedido->status == 'Finalizado') || ($pedido->status == 'Cancelado')|| ($pedido->status == 'Nuevo')
                                || ($pedido->status == 'Pendiente material'))
                            { echo 'disabled'; } ?>>Cierre forzoso</a>

                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('backend/pedidos/chat.php'); ?>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información del pedido
                </div>
                <div class="panel-body">
                    <table width="100%" cellpadding="0" cellspacing="0" id="info_incidencia">

                        <tr>
                            <td colspan="2"><h3>Información general</h3></td>
                        </tr>
                        <tr>
                            <th width="30%">Tipo tienda:</th> <td><?php echo $pds["tipo"] . "-" . $pds["subtipo"] ."-" . $pds["segmento"] . "-" . $pds["tipologia"] ?></td>
                        </tr>
                        <tr>
                            <th>Fecha alta:</th> <td><?php echo date_format(date_create($pedido->fecha), 'd/m/Y'); ?></td>
                        </tr>
                        <tr>
                            <th>Fecha cierre:</th>
                            <?php
                            if (strtotime($pedido->fecha_cierre) AND (date_format(date_create($pedido->fecha_cierre), 'd/m/Y') <> '30/11/-0001'))
                            { ?>
                                <td><?php echo date_format(date_create($pedido->fecha_cierre), 'd/m/Y'); ?></td>
                            <?php } else{?>
                                <td>---</td>
                            <?php }?>
                        </tr>
                        <tr>
                            <th>Estado:</th> <td><?php echo $pedido->status ?></td>
                        </tr>

                        <tr>
                            <td colspan="2"><h3>Elementos pedidos</h3></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php
                                if(!empty($detalle)) {
                                    ?>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover"
                                               id="table_incidencias_dashboard">
                                            <thead>
                                            <tr>
                                                <th width="30%">Imagen</th>
                                                <th width="15%">Codigo</th>
                                                <th width="50%">Alarma</th>
                                                <th width="5%">Cantidad</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($detalle as $d) {
                                                ?>
                                                <tr>
                                                    <td>
                                                      <!--  <img class="zoom" src="<?= site_url('application/uploads/' . $d->imagen . '')?>" height="40px" width="80px/">-->
                                                       <img src="<?= site_url('application/uploads/' . $d->imagen . '') ?>"
                                                            title="<?=strtoupper($d->alarm) ?>" style="max-width:200px; max-height: 75px;"/>
                                                    </td>
                                                    <td><?php echo $d->code ?></td>
                                                    <td><?php echo $d->alarm ?></td>
                                                    <td><?php echo $d->cantidad ?></td>

                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php
                                }
                                ?>

                            </td>
                        </tr>


                        <tr>
                            <td colspan="2"><h3>Persona de contacto</h3></td>
                        </tr>
                        <tr>
                            <th>Nombre:</th><td><?=$pedido->contacto?></td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td><?=$pedido->phone ?></td>
                        </tr>

                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
</div>
</div>
<!-- /#page-wrapper -->
