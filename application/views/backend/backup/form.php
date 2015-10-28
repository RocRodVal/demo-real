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
            <form id="form_ajax" action="<?=site_url($controlador.'/informe_backup');?>" method="post" class="form-inline filtros form-mini">

                <input type="hidden" name="generar_backup" value="si">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="sfids">SFIDs</label>
                          <textarea name="sfids" id="sfids" cols="60" rows="10"></textarea>

                      </div>

                    <div class="form-group">
                        <label for="opcion">Tipo</label>
                        <select id="opcion" name="opcion" class="form-control">
                            <option value="planograma" >Planogramas</option>
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