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
                    <form id="form_ajax" action="<?=site_url($controlador.'/actualizacion_masiva');?>" method="post" class="form-inline filtros form-mini">

                        <input type="hidden" name="generar_actualizacion" value="si">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="sfids">SFIDs</label>
                                  <textarea name="sfids" id="sfids" cols="60" rows="10" ><?=(!empty($sfids)) ? $sfids : "" ?></textarea>

                              </div>

                            <div class="form-group">
                                <label for="mueble">Mueble</label>
                                <select id="mueble" name="mueble" class="form-control">
                                    <option value="">Escoge el mueble...</option>
                                    <?php foreach($muebles as $display)
                                    {
                                        $selected = ($display->id_display== $id_mueble) ? ' selected = "selected" ' : '';
                                        echo '<option value="'.$display->id_display.'" '.$selected.'>'.$display->display.'</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="form-group">
                                <input type="submit" value="Generar" class="form-control">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>