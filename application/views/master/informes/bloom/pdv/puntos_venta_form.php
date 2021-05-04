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
            <form id="form_ajax" action="<?=site_url($controlador.'/resultado_pdv');?>" method="post" class="form-inline filtros form-mini">
                <div id="deshabilitador"></div>
                <input type="hidden" name="generar_informe" value="si">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="id_tipo">Canal</label>
                        <select id="id_tipo" name="id_tipo" class="form-control" onchange="anadir_filtro(this);"><option value="">Canal...</option>
                            <?php foreach($pds_tipos as $tipo)
                            {
                                echo '<option value="'.$tipo["id"].'">'.$tipo["titulo"].'</option>';
                            }
                            ?>
                        </select>
                        <div id="multifiltro_id_tipo" class="multifiltro">
                            <input name="id_tipo_next" id="id_tipo_next" type="hidden" value="0">
                            <div id="multi_id_tipo">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="id_subtipo">Tipología</label>
                        <select id="id_subtipo" name="id_subtipo" class="form-control" onchange="anadir_filtro(this);"><option value="">Tipología...</option>
                            <?php foreach($pds_subtipos as $subtipo)
                            {
                                echo '<option value="'.$subtipo["id"].'">'.$subtipo["titulo"].'</option>';
                            }
                            ?>
                        </select>
                        <div id="multifiltro_id_subtipo" class="multifiltro">
                            <input name="id_subtipo_next" id="id_subtipo_next" type="hidden" value="0">
                            <div id="multi_id_subtipo">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="id_segmento">Concepto</label>
                        <select id="id_segmento" name="id_segmento" class="form-control" onchange="anadir_filtro(this);"><option value="">Concepto...</option>
                            <?php foreach($pds_segmentos as $segmento)
                            {
                                echo '<option value="'.$segmento["id"].'">'.$segmento["titulo"].'</option>';
                            }
                            ?>
                        </select>
                        <div id="multifiltro_id_segmento" class="multifiltro">
                            <input name="id_segmento_next" id="id_segmento_next" type="hidden" value="0">
                            <div id="multi_id_segmento">
                                <?php /*<div class="linea">
                                    <label class="auto"></label> <input type="hidden" name="panelado_1" value="1">
                                    <a href="#" onclick="eliminar_filtro();"><i class="glyphicon glyphicon-remove"></i></a>
                                </div>*/?>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="id_tipologia">Categorización</label>
                        <select id="id_tipologia" name="id_tipologia" class="form-control" onchange="anadir_filtro(this);"><option value="">Categorización...</option>
                            <?php foreach($pds_tipologias as $tipologia)
                            {
                                echo '<option value="'.$tipologia["id"].'">'.$tipologia["titulo"].'</option>';
                            }
                            ?>
                        </select>
                        <div id="multifiltro_id_tipologia" class="multifiltro">
                            <input name="id_tipologia_next" id="id_tipologia_next" type="hidden" value="0">
                            <div id="multi_id_tipologia">
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
                        <label for="id_muebledisplay">Muebles display</label>
                        <select id="id_muebledisplay" name="id_muebledisplay" class="form-control" onchange="anadir_filtro(this);"><option value="">Cualquiera...</option>

                            <?php foreach($mueblesdisplay as $muebledisplay)
                            {
                                echo '<option value="'.$muebledisplay->id_muebledisplay.'">'.$muebledisplay->name.'</option>';
                            }
                            ?>
                        </select>

                        <div id="multifiltro_id_muebledisplay" class="multifiltro">
                            <input name="id_muebledisplay_next" id="id_muebledisplay_next" type="hidden" value="0">
                            <div id="multi_id_muebledisplay">
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

                <div class="col-sm-12">
                    <a href="<?=site_url($controlador.'/resultado_pdv/exportarT');?>">Exportar todos</a>
                </div>
            </form>


        </div>
    </div>
</div>