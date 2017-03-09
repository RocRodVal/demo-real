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
                <form action="<?=site_url($controlador.'/informe_sistemas_seguridad');?>" method="post" class="form-inline filtros form-mini" id="form">
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
                            <label for="tipo">Origen</label>
                            <select id="tipo" name="tipo" class="form-control" >
                                <option value="">Escoge tipo...</option>
                                <option value="incidencias" <?=($tipo == 'incidencias' ? ' selected="selected" ' : '')?>>Incidencias</option>
                                <option value="pedidos" <?=($tipo == 'pedidos' ? ' selected="selected" ' : '')?>>Pedidos</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="generar_informe" value="si">
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
            <p><a href="<?=base_url().$controlador?>/exportar_sistemas_seguridad/<?=$anio?>/<?=$tipo?>" class="btn exportar"><i class="glyphicon glyphicon-file"></i> Exportar Excel</a></p>
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
                        <th>Dueño</th>
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
                            echo "<th>" . $valor['dueno'] . "</th>";
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
      <?php } else {
          $cadena="";
          if (!empty($tipo)) {
              $cadena=" de ".$tipo;
              if (!empty($anio)) {
                  $cadena.=" para el año ".$anio;
              }
          }
          ?>
                        <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> No hay datos <?=$cadena?></p>
                        <?php
                    }?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->