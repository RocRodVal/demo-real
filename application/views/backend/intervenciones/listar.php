<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 9/2/15
 * Time: 17:18
 */

?>

<!-- CSS específicos de la parte de intervenciones -->
<link rel="stylesheet" type="text/css" href="<? echo base_url();?>assets/css/intervencion/intervencion.css">

<!-- Scripts específicos de la parte de intervenciones-->
<script src="<?php echo base_url();?>assets/js/plugins/dataTables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/plugins/dataTables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-tooltip.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap-confirmation.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/intervencion/intervencion.js" type="text/javascript"></script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
        </div>
    </div>
    <div class="row">
        <div id="btn_nueva_intervencion" class="col-lg-2 btn btn-default">Nueva intervencion</div>
    </div>

    <div class="row">
        <table id="table_intervenciones" class="display compact table"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>FECHA</th>
                <th>SFID</th>
                <th>NOMBRE TIENDA</th>
                <th>TECNICO</th>
                <th>COUNT</th>
                <th>STATUS</th>
                <th>ACCIONES</th>
            </tr>
            </thead>
        </table>
    </div>
</div>


<!-- Modal Ver intervencion-->
<div class="modal fade" id="modal_ver_intervencion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_ver_intervencion_title">Intervencion title</h4>
            </div>
            <div class="modal-body">
                <?php $this->load->view('backend/intervenciones/ver_intervencion');?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver intervencion-->
<div class="modal fade" id="modal_ver_incidencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_ver_intervencion_title">Intervencion title</h4>
            </div>
            <div class="modal-body">
                AQUI LOS DATOS DE LA INCIDENCIA
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

