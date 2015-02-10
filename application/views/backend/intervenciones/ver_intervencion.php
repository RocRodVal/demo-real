<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 9/2/15
 * Time: 17:31
 */

?>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Infromación</h3>
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
                    <span class="input-group-addon">DESCRIPCION</span>
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
    <div class="col-lg-12">
        <table id="table_incidencias" class="display compact table"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>FECHA</th>
                <th>SFID</th>
                <th>DIRECCION</th>
                <th>TECNICO</th>
                <th>STATUS</th>
                <th>ACCIONES</th>
            </tr>
            </thead>
        </table>
    </div>
</div>