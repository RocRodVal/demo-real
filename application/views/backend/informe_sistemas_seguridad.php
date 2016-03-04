		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
          <!--  <div class="filtro">
                <form action="<?=site_url($controlador."/informe_sistemas_seguridad/")?>" method="post" class="filtros form-mini col-lg-12">
                    <div class="col-lg-1">
                        <label for="anio">Anio:</label>
                        <select name="anio" id ="anio" class="form-mini input-sm">
                            <option value="" <?=($anio=="" ? ' selected="selected" ':'')?>>Selecciona</option>
                            <?php
                                echo '<option value="2015" '. ($anio=="2015" ? ' selected="selected" ':'').'>2015</option>';
                                echo '<option value="2016"'. ($anio=="2016" ? ' selected="selected" ':'').'>2016</option>';
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-1">
                        <input type="hidden" name="filtrar_anio" value="si">
                        <input type="submit" value="Generar" class="form-control input-sm">
                    </div>
                </form>
            </div> -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="row buscador">
                        <form action="<?=site_url($controlador.'/informe_sistemas_seguridad');?>" method="post" class="form-inline filtros form-mini" id="form">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="anio">Año</label>
                                    <select id="anio" name="anio" class="form-control" onchange="enviar_form('form');">
                                        <option value="">Escoge año...</option>

                                        <?php
                                        //for($i=2015;$i<=getdate()['year'];$i++) {
                                        for($i=2015;$i<=date("Y");$i++) {
                                            echo '<option value="'.$i.'" ' . ($anio == $i ? ' selected="selected" ' : '') . '>'.$i.'</option>';
                                          //  echo '<option value="2016"' . ($anio == "2016" ? ' selected="selected" ' : '') . '>2016</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!--<div class="form-group">
                                    <input type="hidden" name="filtrar_anio" value="si">
                                    <input type="submit" value="Generar" class="form-control input-sm">
                                </div>-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?
                    if (!empty($anio)) { ?>
                        <p><a href="<?=base_url().$controlador?>/exportar_sistemas_seguridad/<?=$anio?>" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a></p>
                        <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-borde-lineal table-sistemas-seguridad">

                        <?php
                        $separador_td = "<td>&nbsp;</td><td>&nbsp;</td>";
                        foreach($meses_columna as $clave=>$valor) {
                            $separador_td .= "<td>&nbsp;</td>";
                        }
                        ?>
                        <thead>
                            <tr>
                                <th>Alarma</th>
                                <th>Código</th>
                                <th>Fabricante</th>
                                   <?php

                                   foreach($meses_columna as $clave=>$valor)
                                   {
                                        echo "<th>".$valor."</th>";
                                   }
                                   ?>

                            </tr>
                        </thead>
                            <tbody>
                                <?php

                                if (!empty($valor_resultado)) {
                                    foreach ($valor_resultado as $clave => $valor) {
                                        echo "<tr>";
                                        echo "<th>$clave</th>";
                                        echo "<th>" . $valor['code'] . "</th>";
                                        echo "<th>" . $valor['fabricante'] . "</th>";
                                        if(!is_null($primer_mes)) {
                                            for ($i = $primer_mes; $i <= $ultimo_mes; $i++) {
                                                echo "<td>$valor[$i]</td>";
                                            }
                                        }else {
                                            echo "<td>No hay datos</td>";
                                        }
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        <?php } ?>

                         </table>
                        </div>
                </div>

            </div>

        </div>
        <!-- /#page-wrapper -->
