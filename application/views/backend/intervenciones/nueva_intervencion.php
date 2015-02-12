<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 9/2/15
 * Time: 17:31
 */

?>

<!-- Modal Nueva intervencion-->
<div class="modal fade" id="modal_nueva_intervencion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Selecciona intervencion</label>

                        <div class="col-sm-10">
                            <select class="form-control" id="nueva_intervencion_select_intervencion"></select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Selecciona instalador</label>

                        <div class="col-sm-10">
                            <select class="form-control" id="nueva_intervencion_select_operador"></select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Descripción</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" id="nueva_intervencion_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onClick="saveIntervencion();" class="btn btn-primary">Guardar intervencion</button>
            </div>
        </div>
    </div>
</div>