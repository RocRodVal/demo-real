<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 9/2/15
 * Time: 17:18
 */

?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
        </div>
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


<?php $this->load->view('backend/intervenciones/ver_intervencion');?>

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

