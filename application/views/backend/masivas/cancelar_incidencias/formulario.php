<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
            <p>Introduce los IDs de las incidencias que deseas cancelar, separados por saltos de línea.</p>
        </div>
    </div>

<div class="row">
    <div class="col-lg-12">
        <div class="row buscador">
            <form id="form_ajax" action="<?=site_url($controlador.'/cancelar_incidencias');?>" method="post" class="form-inline filtros form-mini">
                <div id="deshabilitador"></div>
                <input type="hidden" name="cancelar_incidencias" value="si">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="sfids">Incidencias</label>
                        <textarea name="incidencias" id="incidencias" cols="60" rows="10"><?=$incidencias?></textarea>

                    </div>


                    <div class="form-group">
                        <input type="submit" id="submit_button" class="form-control input-sm" value="Cancelar incidencias">
                    </div>

                </div>


            </form>

        </div>
    </div>
</div>