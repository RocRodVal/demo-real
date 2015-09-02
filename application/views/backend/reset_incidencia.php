		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?> <font color="red">[Beta]</font></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Este proceso es irreversible. Introduce el Id. de la incidencia y se procederá a resetear o eliminar la relación con esta incidencia de:</p>
                    <ul>
                        <li>El instalador/intervención asignado, que tendrá que ser asignado de nuevo.</li>
                        <li>El material asignado, que tendrá que ser asignado de nuevo.</li>
                        <li>El estado y fecha de cierre de la incidencia volverá a su estado inicial.</li>
                        <li>Se eliminará la referencia en facturación para evitar duplicados al volver a procesarla con posterioridad.</li>
                        <li>Se eliminarán todos los registros históricos de cambio de estado.</li>
                    </ul>
                    <p>&nbsp;</p>
                </div>
            </div>            
            <div class="row">
                <div class="col-lg-12">
                	<form action="<?=base_url()?>admin/reset_incidencia_status" method="post" class="form-inline form-sfid" id="form_reset_incidencia" onsubmit="return confirmar_reset_incidencia('form_reset_incidencia','<?=$mensaje_alerta?>');">



                        <div class="form-group">
                            <label>Identificador incidencia</label>
                            <input class="form-control" placeholder="Id. Incidencia" name="id_inc" id="id_inc">
                            <input type="hidden" name="resetear_incidencia" value="si">
                            <button type="submit" class="btn btn-default">Resetear</button>
                        </div>


                    </form>

                    <?php if(!empty($mensaje_error))  { ?>
                        <p class="message error"><i class="glyphicon glyphicon-remove"></i> <?=$mensaje_error?></p>
                    <?php } ?>
                </div>
            </div>

        </div>
        <!-- /#page-wrapper -->

        <?php $this->load->view('backend/intervenciones/ver_intervencion');?>

