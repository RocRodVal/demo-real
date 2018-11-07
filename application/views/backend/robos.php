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
                <form action="<?=site_url($controlador.'/robos');?>" method="post" class="form-inline filtros form-mini" id="form">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="anioI">Año</label>
                            <select id="anioI" name="anioI" class="form-control">
                                <option value="">Desde el año ...</option>
                                <?php
                                for($i=2015;$i<=date("Y");$i++) {
                                    echo '<option value="'.$i.'" ' . ($anioI == $i ? ' selected="selected" ' : '') . '>'.$i.'</option>';
                                }
                                ?>
                            </select>

                            <select id="anioF" name="anioF" class="form-control">
                                <option value="">hasta el año ...</option>
                                <?php
                                for($i=2015;$i<=date("Y");$i++) {
                                    echo '<option value="'.$i.'" ' . ($anioF == $i ? ' selected="selected" ' : '') . '>'.$i.'</option>';
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

        <?php
        if (!empty($anioI) && !empty($anioF) && !empty($valor_resultado)) { ?>
        <div class="col-lg-8">
            <?php $url = base_url()."admin/exportar_robos/".$anioI."/".$anioF; ?>
                <p><a href="<?=$url?>" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a></p>
                <div class="table-responsive">
                    <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-borde-lineal table-estado-incidencias">
                        <thead>
                        <tr>
                            <th></th>
                            <th> Robos </th>
                            <th> Tiendas mueble</th>
                            <th> Total demos</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                            foreach ($valor_resultado as $valor) {
                                echo " <tr>
                                       <td style=\"text-align: right; background-color: rgb(233, 233, 233); color: rgb(51, 51, 51);\" >$valor->display</td>
                                       <td>$valor->robos</td>
                                       <td>$valor->tiendasMueble</td>
                                       <td>$valor->totalDemos</td></tr>";
                            }
                        ?>
                            <tr hidden>
                                <td><input type="hidden" name="anioI" value="<?= $anioI ?>"/>
                                    <input type="hidden" name="anioF" value="<?= $anioF ?>"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
            <?php
        }else { ?>
            <div class="col-lg-12">
                <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> <?=($mensaje!='')? $mensaje : 'No hay datos' ?></p>
            </div>
        <?php }?>

    </div>
</div>
<!-- /#page-wrapper -->