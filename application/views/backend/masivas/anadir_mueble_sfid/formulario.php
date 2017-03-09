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
                    <p>Introduce los SFIDs a los que quieres añadir el mueble, separados por saltos de línea y selecciona el mueble, y la posición.</p>
                </div>
            </div>

<div class="row">
    <div class="col-lg-12">
        <div class="row buscador">
            <form id="form_ajax" action="<?=site_url($controlador.'/anadir_mueble_sfid');?>" method="post" class="form-inline filtros form-mini">
                <div id="deshabilitador"></div>
                <input type="hidden" name="anadir_mueble" value="si">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="sfids">SFIDs</label>
                        <textarea name="sfids" id="sfids" cols="60" rows="10"><?=$sfids?></textarea>

                    </div>


                    <div class="form-group">
                        <label for="id_display">Añadir...</label>
                        <select id="id_display" name="id_display" class="form-control">
                            <option value>Escoge un mueble...</option>
                            <?php foreach($muebles as $key=>$value)
                            { ?>

                                <option value="<?=$value->id_display?>"
                                    <?=($id_display == $value->id_display)? ' selected="selected" ' : '' ?> ><?=$value->display?></option>

                            <?php } ?>
                        </select>

                    </div>


                    <div class="form-group">
                        <label for="position">Posición...</label>
                        <input type="text" id="position" name="position" class="form-control">

                    </div>








                    <div class="form-group">
                        <input type="submit" onclick="return validar_anadir_mueble_sfid(); " id="submit_button" class="form-control input-sm" value="Añadir mueble">
                    </div>

                </div>


            </form>

        </div>
    </div>
</div>