		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <form action="<?=site_url("master/cdm_incidencias/")?>" method="post">
                    <div class="form-group">
                        <label for="tipo_tienda">
                            Tipo de tienda:
                        </label>
                        <select name="tipo_tienda" id ="tipo_tienda">
                            <option value="">Cualquier tipo</option>
                            <?php foreach($tipos_tienda as $tipo){
                                $selected = ($tipo->id_tipo === $tipo_tienda) ? ' selected="selected" ' : '';
                                echo '<option value="'.$tipo->id_tipo.'" '.$selected.'>'.$tipo->tipo.'</option>';
                            }?>
                        </select>

                        <label for="estado_incidencia">
                            Estado de la incidencia:
                        </label>
                        <select name="estado_incidencia" id ="estado_incidencia">
                            <option value="">Cualquier tipo</option>
                            <?php foreach($estados_incidencia as $estado){
                                $selected = ($estado->status_pds === $estado_incidencia) ? ' selected="selected" ' : '';
                                echo '<option value="'.$estado->status_pds.'" '.$selected.'>'.$estado->status_pds.'</option>';
                            }?>
                        </select>


                        <input type="hidden" name="filtrar_tipo" value="si">
                        <input type="submit" value="Enviar">
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $content ?>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
