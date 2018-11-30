<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-borde-lineal table-estado-incidencias">
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
                    <th>Total</th>
                </tr>
            </thead>
                <tbody>
                <?php
                /**
                 * Incidencias totales por dias operativos
                 */
                ?>
                    <tr>


                        <th>Total incidencias</th>
                        <?php
                        $mes_idx = $primer_mes;
                        foreach($tabla_1 as $valor)
                        {
                            echo "<td>";
                            echo ($valor > 0)
                                ? "<a href='".base_url('informe/exportar_cdm_incidencias/'.$anio.'/'.$mes_idx)."' >".$valor."</a>"
                                : $valor ;
                            echo "</td>";
                            $mes_idx++;
                        }
                        ?>
                        <td class="total"><?=(empty($total_incidencias_total)) ? 0 : $total_incidencias_total; ?></td>
                    </tr>

                    <tr>
                        <th>Días operativos</th>
                        <?php
                        foreach($dias_operativos as $key=>$valor)
                        {
                            echo "<td>".$valor."</td>";
                        }
                        ?>
                        <td  class="total"><?=$total_dias_operativos?></td>
                    </tr>

                    <tr>
                        <th>Media</th>
                        <?php
                        foreach($incidencias_dia as $key=>$valor)
                        {
                            echo "<td>".$valor."</td>";
                        }
                        ?>
                        <td  class="total"><?=(empty($total_media)) ? 0 : $total_media; ?></td>
                    </tr>

                    <tr class="separador"><?=$separador_td?></tr>

                    <?php
                    /**
                     * Incidencias por ESTADO
                     */
                    ?>
                        <tr>
                            <th>Finalizadas</th>
                            <?php
                                $total = 0;
                                $mes_idx = $primer_mes;
                                foreach($incidencias_estado["Finalizada"] as $key=>$valor)
                                {
                                    echo "<td>";
                                    echo ($valor > 0)
                                        ? "<a href='".base_url('informe/exportar_cdm_incidencias/'.$anio.'/'.$mes_idx.'/4')."' >".$valor."</a>"
                                        : $valor;

                                    $total += $valor;
                                    $mes_idx++;
                                    echo "</td>";
                                }
                                echo '<td class="total">'.$total.'</td>';
                            ?>
                        </tr>

                        <tr>
                            <th>En visita</th>
                            <?php
                            $total = 0;
                            $mes_idx = $primer_mes;
                            foreach($incidencias_estado["En visita"] as $key=>$valor)
                            {
                                echo "<td>";
                                echo ($valor > 0)
                                    ? "<a href='".base_url('informe/exportar_cdm_incidencias/'.$anio.'/'.$mes_idx.'/3')."' >".$valor."</a>"
                                    : $valor;

                                $total += $valor;
                                $mes_idx++;
                                echo "</td>";
                            }
                            echo '<td class="total">'.$total.'</td>';
                            ?>
                        </tr>
                        <tr>
                            <th>En proceso</th>
                            <?php
                            $total = 0;
                            $mes_idx = $primer_mes;
                            foreach($incidencias_estado["En proceso"] as $key=>$valor)
                            {
                                echo "<td>";
                                echo ($valor > 0)
                                    ? "<a href='".base_url('informe/exportar_cdm_incidencias/'.$anio.'/'.$mes_idx.'/2')."' >".$valor."</a>"
                                    : $valor;

                                $total += $valor;
                                $mes_idx++;
                                echo "</td>";
                            }
                            echo '<td class="total">'.$total.'</td>';
                            ?>
                        </tr>
                        <tr>
                            <th>Alta realizada</th>
                            <?php
                            $total = 0;
                            $mes_idx = $primer_mes;
                            foreach($incidencias_estado["Alta realizada"] as $key=>$valor)
                            {
                                echo "<td>";
                                echo ($valor > 0)
                                    ? "<a href='".base_url('informe/exportar_cdm_incidencias/'.$anio.'/'.$mes_idx.'/1')."' >".$valor."</a>"
                                    : $valor;

                                $total += $valor;
                                $mes_idx++;
                                echo "</td>";
                            }
                            echo '<td class="total">'.$total.'</td>';
                            ?>
                        </tr>

                    <tr class="separador"><?=$separador_td?></tr>
                        <?php
                            // FINALIZADAS y EN PROCESO <72H
                            $total_menos_72 = array();
                        ?>
                        <tr>
                            <th>Finalizadas &lt; 72h</th>
                            <?php
                            $total = 0;
                            $mes_idx = $primer_mes;
                            foreach($menos_72 as $key=>$valor)
                            {
                                echo "<td>";
                                echo ($valor->cantidad > 0)
                                    ? "<a href='".base_url('informe/exportar_cdm_incidencias_finalizadas/'.$anio.'/'.$mes_idx.'/1')."' >".$valor->cantidad."</a>"
                                    : $valor->cantidad;

                                $total += $valor->cantidad;

                                $total_menos_72[$mes_idx] = $valor->cantidad;   // Sumar al total < 72 las finalizadas <72

                                $mes_idx++;
                                echo "</td>";

                            }
                            echo '<td class="total">'.$total.'</td>';
                            ?>
                        </tr>
                        <tr>
                            <th>En proceso &lt; 72h</th>
                            <?php
                            $total = 0;
                            $mes_idx = $primer_mes;
                            foreach($proceso_menos_72 as $key=>$valor)
                            {
                                echo "<td>";
                                echo ($valor->cantidad > 0)
                                    ? "<a href='".base_url("informe/exportar_cdm_incidencias/$anio/$mes_idx/2/1")."' >".$valor->cantidad."</a>"
                                    : $valor->cantidad;

                                $total += $valor->cantidad;

                                $total_menos_72[$mes_idx] += $valor->cantidad;   // Sumar al total <72 las En proceso <72
                                $mes_idx++;
                                echo "</td>";
                            }
                            echo '<td class="total">'.$total.'</td>';
                            ?>
                        </tr>


                        <tr>
                            <th>TOTAL  &lt; 72h</th>
                            <?php
                                $total = 0;
                                $mes_idx = $primer_mes;

                                foreach($total_menos_72 as $cantidad)
                                {
                                    echo "<td>";
                                    /*echo ($valor->cantidad > 0)
                                        ? "<a href='".base_url("informe/exportar_cdm_incidencias/$anio/$mes_idx/2/1")."' >".$valor->cantidad."</a>"
                                        : $valor->cantidad;*/
                                    echo $cantidad;
                                    $total += $cantidad; // Total de la línea
                                    $mes_idx++;
                                    echo "</td>";
                                }
                                echo '<td class="total">'.$total.'</td>';
                            ?>
                        </tr>

                        <?php
                        // FINALIZADAS y EN PROCESO <72H
                        $total_mas_72 = array();
                        ?>
                        <tr>
                            <th>Finalizadas &gt; 72h</th>
                            <?php
                            $total = 0;
                            $mes_idx = $primer_mes;
                            foreach($mas_72 as $key=>$valor)
                            {
                                echo "<td>";
                                echo ($valor->cantidad > 0)
                                    ? "<a href='".base_url('informe/exportar_cdm_incidencias_finalizadas/'.$anio.'/'.$mes_idx.'/0')."' >".$valor->cantidad."</a>"
                                    : $valor->cantidad;

                                $total += $valor->cantidad;
                                $total_mas_72[$mes_idx] = $valor->cantidad;  // Sumar al total >72 las Finalidas >72

                                $mes_idx++;
                                echo "</td>";
                            }
                            echo '<td class="total">'.$total.'</td>';
                            ?>
                        </tr>

                <tr>
                    <th>En proceso &gt; 72h</th>
                    <?php
                    $total = 0;
                    $mes_idx = $primer_mes;
                    foreach($proceso_mas_72 as $key=>$valor)
                    {
                        echo "<td>";
                        echo ($valor->cantidad > 0)
                            ? "<a href='".base_url("informe/exportar_cdm_incidencias/$anio/$mes_idx/2/0")."' >".$valor->cantidad."</a>"
                            : $valor->cantidad;

                        $total += $valor->cantidad;

                        $total_mas_72[$mes_idx] += $valor->cantidad;  // Sumar al total >72 las En proceso >72
                        $mes_idx++;
                        echo "</td>";
                    }
                    echo '<td class="total">'.$total.'</td>';
                    ?>
                </tr>
                <tr>
                    <th>TOTAL  &gt; 72h</th>
                    <?php
                    $total = 0;
                    $mes_idx = $primer_mes;

                    foreach($total_mas_72 as $cantidad)
                    {
                        echo "<td>";
                        /*echo ($valor->cantidad > 0)
                            ? "<a href='".base_url("informe/exportar_cdm_incidencias/$anio/$mes_idx/2/1")."' >".$valor->cantidad."</a>"
                            : $valor->cantidad;*/
                        echo $cantidad;
                        $total += $cantidad; // Total de la línea
                        $mes_idx++;
                        echo "</td>";
                    }
                    echo '<td class="total">'.$total.'</td>';
                    ?>
                </tr>

                <tr class="separador"><?=$separador_td?></tr>

                <tr>
                    <th>Intervenciones</th>
                    <?php
                    $total = 0;
                    $mes_idx = $primer_mes;
                    foreach($intervenciones_anio as $key=>$valor)
                    {
                        echo '<td>'.$valor->cantidad.'</td>';
                        $total += $valor->cantidad;
                    }
                    echo '<td class="total">'.$total.'</td>';
                    ?>
                </tr>

                <tr>
                    <th>Alarmas</th>
                    <?php
                    $total = 0;
                    $mes_idx = $primer_mes;
                    foreach($alarmas_anio as $key=>$valor)
                    {
                        echo '<td>'.$valor->cantidad.'</td>';
                        $total += $valor->cantidad;
                    }
                    echo '<td class="total">'.$total.'</td>';
                    ?>
                </tr>

                <tr>
                    <th>Terminales</th>
                    <?php
                    $total = 0;
                    $mes_idx = $primer_mes;
                    foreach($terminales_anio as $key=>$valor)
                    {
                        echo '<td>'.$valor->cantidad.'</td>';
                        $total += $valor->cantidad;
                    }
                    echo '<td class="total">'.$total.'</td>';
                    ?>
                </tr>

                <tr>
                    <th>Incidencias resueltas</th>
                    <?php
                    $total = 0;
                    $mes_idx = $primer_mes;
                    foreach($incidencias_resueltas as $key=>$valor)
                    {
                        echo '<td>'.$valor->cantidad.'</td>';
                        $total += $valor->cantidad;
                    }
                    echo '<td class="total">'.$total.'</td>';
                    ?>
                </tr>
                <tr>
                    <th>Media <br> Incidencias/Intervención</th>
                    <?php

                    foreach($media_inc_int as $key=>$valor)
                    {
                        echo '<td>'.$valor->cantidad.'</td>';
                    }
                    echo '<td class="total">'.$total_media_inc_int.'</td>';
                    ?>
                </tr>

                <tr class="separador"><?=$separador_td?></tr>

                <tr>
                    <th>Incidencias por robo</th>
                    <?php /* INCIDENCIAS POR ROBO ABS */
                    foreach($incidencias_robo as $key=>$valor)
                    {
                        echo '<td>'.$valor->cantidad.'</td>';
                    }
                    echo '<td class="total">'.$total_inc_robo.'</td>';
                    ?>
                </tr>
                <tr>
                    <th>Tiendas robadas</th>
                    <?php /* TIENDAS implicadas en los robos */
                    foreach($incidencias_robo as $key=>$valor)
                    {
                        if(empty($valor->tiendas) )
                            $valor=0;
                        else
                             $valor=$valor->tiendas;

                        echo '<td>'.$valor.'</td>';
                    }
                    echo '<td class="total">'.$total_tiendas.'</td>';
                    ?>
                </tr>

                <tr>
                    <th>Ratio  de incidencias por robo</th>
                    <?php /* INCIDENCIAS POR ROBO ABS */
                    foreach($incidencias_robo as $key=>$valor)
                    {
                        $denom = ($valor->total==0) ? 1 : $valor->total;
                        echo '<td>'.number_format ($valor->cantidad / $denom * 100,2,",",".").' %</td>';
                    }
                    echo '<td class="total">'.number_format ($total_inc_robo/ $total_inc_tipo*100,2,",",".").' %</td>';
                    ?>
                </tr>

                <tr>
                    <th>Incidencias por avería</th>
                    <?php /* INCIDENCIAS POR ROBO/AVERIA */
                    foreach($incidencias_averia as $key=>$valor)
                    {
                        echo '<td>'.$valor->cantidad.'</td>';
                    }
                    echo '<td class="total">'.$total_inc_averia.'</td>';
                    ?>
                </tr>

                <tr>
                    <th>Ratio  de incidencias por avería</th>
                    <?php /* INCIDENCIAS POR ROBO ABS */
                    foreach($incidencias_averia as $key=>$valor)
                    {
                        $denom = ($valor->total==0) ? 1 : $valor->total;
                        echo '<td>'.number_format ($valor->cantidad / $denom * 100,2,",",".").' %</td>';
                    }
                    echo '<td class="total">'.number_format ($total_inc_averia/ $total_inc_tipo*100,2,",",".").' %</td>';
                    ?>
                </tr>

                <tr class="separador"><?=$separador_td?></tr>

                <tr>
                    <th>Terminales en tienda</th>
                    <?php /* TERMINALES EN ALMACEN */
                    foreach($terminalesTienda as $key=>$valor)
                         if($valor->cantidad==0)
                             echo '<td> --</td>';
                        else echo '<td>'. $valor->cantidad.'</td>';

                    ?>
                    <td class="total">&nbsp;</td>
                </tr>
                <tr>
                    <th>Terminales en almacén</th>
                    <?php /* TERMINALES EN ALMACEN */
                    foreach($terminalesAlmacen as $key=>$valor)
                        if($valor->cantidad==0)
                            echo '<td> --</td>';
                    else
                        echo '<td>'.$valor->cantidad.'</td>';
                    ?>
                    <td class="total">&nbsp;</td>
                </tr>
                </tbody>

            </table>

        </div>
    </div>
</div>
<!-- /#page-wrapper -->
