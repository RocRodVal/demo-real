<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 24/07/2015
 * Time: 9:50
 */

?>
<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>

<div class="row">
    <div class="col-lg-12">
        <div class="row buscador">
            <form id="form_ajax" action="<?=site_url('admin/resultado_pdv');?>" method="post" class="form-inline filtros form-mini">
                <div id="deshabilitador"></div>
                <input type="hidden" name="generar_informe" value="si">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="tipo_tienda">Tipo de tienda</label>
                        <select id="tipo_tienda" name="tipo_tienda" class="form-control" onchange="anadir_filtro(this);"><option value="">Escoge el tipo de tienda...</option>

                            <?php foreach($tipos_tienda as $tipo)
                            {
                                echo '<option value="'.$tipo->id_type_pds.'">'.$tipo->pds.'</option>';
                            }
                            ?>
                        </select>

                        <div id="multifiltro_tipo_tienda" class="multifiltro">
                            <input name="tipo_tienda_next" id="tipo_tienda_next" type="hidden" value="0">
                            <div id="multi_tipo_tienda">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>


                    </div>

                    <div class="form-group">
                        <label for="panelado">Panelado</label>
                        <select id="panelado" name="panelado" class="form-control" onchange="anadir_filtro(this);"><option value="">Escoge el panelado...</option>

                            <?php foreach($panelados as $panel)
                            {
                                echo '<option value="'.$panel->id_panelado.'">'.$panel->panelado_abx.'</option>';
                            }
                            ?>
                        </select>

                        <div id="multifiltro_panelado" class="multifiltro">
                            <input name="panelado_next" id="panelado_next" type="hidden" value="0">
                            <div id="multi_panelado">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="id_display">Mueble</label>
                        <select id="id_display" name="id_display" class="form-control" onchange="anadir_filtro(this);"><option value="">Cualquiera...</option>

                            <?php foreach($muebles as $display)
                            {
                                echo '<option value="'.$display->id_display.'">'.$display->display.'</option>';
                            }
                            ?>
                        </select>

                        <div id="multifiltro_id_display" class="multifiltro">
                            <input name="id_display_next" id="id_display_next" type="hidden" value="0">
                            <div id="multi_id_display">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="id_device">Terminal</label>
                        <select id="id_device" name="id_device" class="form-control" onchange="anadir_filtro(this);">
                            <option value="">Cualquiera...</option>

                            <?php foreach($terminales as $term)
                            {
                                echo '<option value="'.$term->id_device.'">'.$term->device.'</option>';
                            }
                            ?>
                        </select>

                        <div id="multifiltro_id_device" class="multifiltro">
                            <input name="id_device_next" id="id_device_next" type="hidden" value="0">
                            <div id="multi_id_device">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="territory">Territorio: </label>
                        <select name="territory" id="territory" class="form-control input-sm" onchange="anadir_filtro(this);">
                            <option value="">Cualquiera...</option>
                            <?php
                            foreach($territorios as $territorio)
                            {

                                echo '<option value="'.$territorio->id_territory.'" >'.$territorio->territory.'</option>';
                            }
                            ?>
                        </select>

                        <div id="multifiltro_territory" class="multifiltro">
                            <input name="territory_next" id="territory_next" type="hidden" value="0">
                            <div id="multi_territory">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="brand_device">Fabricante: </label>
                        <select name="brand_device" id="brand_device" class="form-control input-sm" onchange="anadir_filtro(this);">
                            <option value="">Cualquiera...</option>
                            <?php
                            foreach($fabricantes as $fabricante)
                            {
                                echo '<option value="'.$fabricante->id_brand_device.'" >'.$fabricante->brand.'</option>';
                            }
                            ?>
                        </select>

                        <div id="multifiltro_brand_device" class="multifiltro">
                            <input name="brand_device_next" id="brand_device_next" type="hidden" value="0">
                            <div id="multi_brand_device">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>

                    </div>

                    <?php /*<div class="form-group">
                        <input type="submit" onclick="enviar_form_ajax('#form_ajax'); return false;">
                    </div>*/?>

                </div>


            </form>

        </div>
    </div>
</div>