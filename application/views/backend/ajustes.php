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
                <form action="<?=site_url($controlador.'/ajustes');?>" method="post" class="form-inline filtros form-mini" id="form">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="anio">Año</label>
                            <select id="anio" name="anio" class="form-control">
                                <option value="">Escoge año...</option>
                                <?php
                                for($i=2015;$i<=date("Y");$i++) {
                                    echo '<option value="'.$i.'" ' . ($anio == $i ? ' selected="selected" ' : '') . '>'.$i.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="generar_tabla" value="si">
                            <button type="submit" id="submit_button" class="form-control input-sm">Generar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            if (!empty($anio) && !empty($valor_resultado)) { ?>
            <div class="table-responsive">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-borde-lineal table-estado-incidencias">
                    <form action="<?=site_url('admin/update_ajustes_totales');?>" method="post" class="form-inline filtros form-mini">
                    <?php
                    $separador_td = "<td>&nbsp;</td><td>&nbsp;</td>";
                    foreach($meses_columna as $clave=>$valor) {
                        $separador_td .= "<td>&nbsp;</td>";
                    }
                    ?>
                    <thead>
                    <tr>
                        <th></th>
                        <?php
                        foreach($meses_columna as $clave=>$valor)
                        {
                            echo "<th>".$valor."</th>";
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Intervenciones</th>
                            <?php
                            if (!empty($valor_resultado)) {
                                foreach ($valor_resultado as $clave => $valor) {
                                    echo "<td><input size='5' type='text' name='intervenciones[]' value=".$valor['intervenciones']." ></td>";
                                }
                            }
                            ?>
                        </tr>
                        <tr>
                            <th>Terminales</th>
                            <?php
                            if (!empty($valor_resultado)) {
                                foreach ($valor_resultado as $clave => $valor) {
                                    echo "<td><input size='5' type='text' name='terminales[]' value=".$valor['terminales']." ></td>";
                                }
                            }
                            ?>
                        </tr>
                        <tr><td colspan="<?=count($meses_columna)?>"></td>
                           <td><input type="hidden" name="anio" value="<?=$anio?>"/>
                               <input type="submit" value="Guardar" id="submit_button" class="form-control input-sm"/></td></tr>
                    </tbody>
                  <?php }else { ?>
                    <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> <?=($mensaje!='')? $mensaje : 'No hay datos' ?></p>
                  <?php }?>
                    </form>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->