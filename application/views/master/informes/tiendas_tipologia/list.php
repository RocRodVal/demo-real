<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 17/11/2015
 * Time: 16:41
 */

?>

<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
        </div>
    </div>

    <div class="row tiendas-tipo">
        <div class="col-lg-12">
            <?php



            foreach($tiendas_tipologia as $subtipo) {
                $tipologias = $subtipo->tipologias;

                $mostrar = FALSE;
                foreach($tipologias as $tipo) {
                    if(count($tipo->muebles) > 0 ) $mostrar = TRUE;
                }


                        if ($mostrar) {
                            ?>

                                <h3><?= $subtipo->subtipo ." <!--({$subtipo->id_subtipo})--> " ?>: <span><?=$subtipo->total ?></span></h3>




                            <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-sorting">
                                <tr>


                                    <th class="text principal" colspan="2">Total tiendas con DEMOS</th>
                                    <?php
                                    $total = 0;
                                    foreach ($tipologias as $tipologia) {
                                            ?>
                                            <th class="num primera-linea"><?php echo $tipologia->total; ?></th>
                                        <?php
                                    }
                                    ?>
                                    <th class="num primera-linea">&nbsp;</th>

                                </tr>
                                <tr>
                                    <th class="text">Tiendas por mueble</th>
                                    <th class="text">Mueble</th>
                                    <?php foreach ($tipologias as $tipologia) {
                                       ?>
                                        <th class="tipo"><?= $tipologia->titulo  ." <!--({$tipologia->id_tipologia})--> " ?></th>
                                    <?php  } ?>

                                    <?php /* ?><th class="tipo">Tipo</th> */?>
                                </tr>

                                <?php
                                $muebles_aux = array();
                                $tipologias_aux = $tipologias;
                                foreach ($tipologias as $tipologia) {
                                    $muebles = $tipologia->muebles;

                                    $tipologia->total_demos_aux = 0;


                                    foreach ($muebles as $mueble) {

                                        $elem = new StdClass();
                                        $elem->total = $mueble->total;
                                        $elem->id_display = $mueble->id_display;
                                        $elem->display = $mueble->display. " <!--({$mueble->id_display})--> ";
                                        $elem->tipo_mueble = $mueble->tipo_mueble;
                                        $elem->num_pds = $mueble->num_pds;
                                        $elem->num_pds_display = $mueble->num_pds_display;

                                        if (!m_object_search($muebles_aux, "id_display", $mueble->id_display)) {


                                            array_push($muebles_aux, $elem);
                                            $tipologia->total_demos_aux += $mueble->total_demos;

                                        }else{

                                        }
                                    }

                                    $tipologia->muebles_aux = $muebles_aux;
                                    ?>
                                <?php }




                                foreach ($muebles_aux as $display) {

                                    ?>
                                    <tr>
                                        <td><?php
                                            // Nº de muebles en todas las tiendas por tipologia
                                            //echo $display->num_pds . " <!-- (S={$subtipo->id_subtipo}, M={$display->id_display}) --> " ;

                                            // Nº de tiendas, por mueble en tipologia
                                            echo $display->num_pds_display;
                                            ?>
                                        </td>
                                        <th class="display"><?= $display->display ?></th>
                                        <?php
                                        // Aquí buscamos para el mueble actual, cuántas tiendas lo tienen, por tipología; si es que
                                        // la tipología lo tiene


                                        foreach ($tipologias as $tipologia) {
                                            $muebles = $tipologia->muebles;
                                            $busqueda = get_object_search($muebles,"id_display",$display->id_display);
                                            if(!is_null($busqueda)){
                                                echo '<td>'.$busqueda->positions.'</td>';

                                            }else{
                                                echo '<td>-</td>';
                                            }

                                        }
                                        ?>
                                        <?php /* <td><?=$display->tipo_mueble?></td> */ ?>
                                    </tr>

                                <?php  } ?>
                                <tr>
                                    <td></td>
                                    <th class="total">Total demos / tienda</th>
                                    <?php
                                        foreach($tipologias as $tipologia){
                                            $cont = 0;
                                          foreach($tipologia->muebles as $mueble){
                                             $cont += $mueble->positions;
                                          }

                                          echo ($cont > 0) ?  "<td><strong>$cont</strong></td>" : "<td><strong>0</strong></td>";

                                        }
                                    ?>
                                    <td></td>
                                </tr>
                            </table>


                        <?php }

            }?>
        </div>
    </div>