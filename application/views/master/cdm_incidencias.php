		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <?php
            /*<div class="row">
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
            </div> */?>
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
                                foreach($tabla_1 as $clave=>$valor)
                                {
                                    echo "<td>".$valor->total_incidencias."</td>";
                                }
                                ?>
                                <td class="total"><?=$total_incidencias_total?></td>
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
                                <td  class="total"><?=$total_media?></td>
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
                                        foreach($incidencias_estado["Finalizada"] as $key=>$valor)
                                        {
                                            echo '<td>'.$valor.'</td>';
                                            $total += $valor;
                                        }
                                        echo '<td class="total">'.$total.'</td>';
                                    ?>
                                </tr>
                                <?php /*<tr>
                                    <th>Canceladas</th>
                                    <?php
                                    $total = 0;
                                    foreach($incidencias_estado["Cancelada"] as $key=>$valor)
                                    {
                                        echo '<td>'.$valor.'</td>';
                                        $total += $valor;
                                    }
                                    echo '<td class="total">'.$total.'</td>';
                                    ?>
                                </tr>*/?>


                                <tr>
                                    <th>En visita</th>
                                    <?php
                                    $total = 0;
                                    foreach($incidencias_estado["En visita"] as $key=>$valor)
                                    {
                                        echo '<td>'.$valor.'</td>';
                                        $total += $valor;
                                    }
                                    echo '<td class="total">'.$total.'</td>';
                                    ?>
                                </tr>
                                <tr>
                                    <th>En proceso</th>
                                    <?php
                                    $total = 0;
                                    foreach($incidencias_estado["En proceso"] as $key=>$valor)
                                    {
                                        echo '<td>'.$valor.'</td>';
                                        $total += $valor;
                                    }
                                    echo '<td class="total">'.$total.'</td>';
                                    ?>
                                </tr>
                                <tr>
                                    <th>Alta realizada</th>
                                    <?php
                                    $total = 0;
                                    foreach($incidencias_estado["Alta realizada"] as $key=>$valor)
                                    {
                                        echo '<td>'.$valor.'</td>';
                                        $total += $valor;
                                    }
                                    echo '<td class="total">'.$total.'</td>';
                                    ?>
                                </tr>

                            <tr class="separador"><?=$separador_td?></tr>

                                <tr>
                                    <th>Finalizadas &lt; 72h</th>
                                    <?php
                                    $total = 0;
                                    foreach($menos_72 as $key=>$valor)
                                    {
                                        echo '<td>'.$valor->cantidad.'</td>';
                                        $total += $valor->cantidad;
                                    }
                                    echo '<td class="total">'.$total.'</td>';
                                    ?>
                                </tr>

                                <tr>
                                    <th>Finalizadas &gt; 72h</th>
                                    <?php
                                    $total = 0;
                                    foreach($mas_72 as $key=>$valor)
                                    {
                                        echo '<td>'.$valor->cantidad.'</td>';
                                        $total += $valor->cantidad;
                                    }
                                    echo '<td class="total">'.$total.'</td>';
                                    ?>
                                </tr>

                            <tr class="separador"><?=$separador_td?></tr>

                            <tr>
                                <th>Intervenciones</th>
                                <?php
                                $total = 0;
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

                        </tbody>

                    </table>

                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
