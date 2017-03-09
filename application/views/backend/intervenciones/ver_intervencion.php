<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 9/2/15
 * Time: 17:31
 */

?>

<!-- Modal Ver intervencion-->
<div class="modal fade" id="modal_ver_intervencion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_ver_intervencion_title">Intervención <span id="ver_intervencion_id"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Información</h3>
                            </div>
                            <div class="panel-body">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <span class="input-group-addon data" id="fecha_ver_intervencion"></span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">ESTADO</span>
                                    <span class="input-group-addon data" id="status_ver_intervencion"></span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">DESCRIPCIÓN</span>
                                    <span class="input-group-addon data" id="description_ver_intervencion"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">CONTACTO</h3>
                            </div>
                            <div class="panel-body">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <span class="input-group-addon data" id="nombre_contacto_ver_intervencion"></span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                    <span class="input-group-addon data" id="telefono_contacto_ver_intervencion"></span>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-at"></i></span>
                                    <span class="input-group-addon data" id="email_contacto_ver_intervencion"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button type="button" id="bnt_add_incidencia_to_intervencion" class="btn btn-default" onClick='showModaladdIncidenciasIntervencion();'>Añadir incidencia</button>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="table_incidencias" class="table table-striped table-bordered table-hover"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>FECHA</th>
                                <th>SFID</th>
                                <th>DIRECCION</th>
                                <th>STATUS</th>
                                <th>ACCIONES</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_intervencion_add_incidencia" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3>Añade las incidencias que desees a esta intervención</h3>
                <select id="select_incidencias_add_intervencion" multiple class="form-control"></select>
                <div onClick="addIncidenciasIntervencion()" class="btn btn-success">Añadir las seleccionadas</div>
            </div>
        </div>
    </div>
</div>