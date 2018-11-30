<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 09/12/2015
 * Time: 09:15
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
                <form action="<?=site_url($controlador.'/tiendas_fabricante');?>" method="post" class="form-inline filtros form-mini" id="form">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="id_fabricante">Fabricante</label>
                            <select id="id_fabricante" name="id_fabricante" class="form-control" onchange="enviar_form('form');">
                                <option value="">Escoge el fabricante...</option>

                                <?php foreach($fabricantes as $fabricante)
                                {
                                    $selected = ($fabricante->id == $id_fabricante) ? ' selected = "selected" ' : '';
                                    echo '<option value="'.$fabricante->id.'" '.$selected.'>'.$fabricante->fabricante.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
   
    
        <div class="row tiendas-tipo tiendas-fab">
            <div class="col-lg-12">
                
                <?php 

                if(empty($resultado->segmentos)) { ?>
                
                <?php } else { ?>
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered ">
                    <tr>
                        <th>Tipo de tienda</th>
                        <th>Tipología</th>
                        <th>Mueble</th>
                        <th class="pdvs">Nº tiendas / Mueble</th>
                    </tr>
                    <?php  

                    $elementos=0;
                    $aux=null;
                    foreach($resultado->segmentos as $segmento){
                        /*agregado para que salgan los segmentos DHO NEXT y DHO NEXT PLUS juntos*/
                        if ($segmento->segmento=='DHO NEXT PLUS') {
                            continue;
                        }
                        if ($segmento->id_segmento == 2) {
                           // echo $segmento->id_segmento." ".$elementos;
                            $elementos++;
                            $aux = ($resultado->segmentos[$elementos]);
                                //echo $aux->segmento;
                        }

                        $tipologias = $segmento->tipologias;
                        $firstSeg = TRUE;
                        $rowSeg = count($tipologias);
                        /*agregado para que salgan los segmentos DHO NEXT y DHO NEXT PLUS juntos*/
                        $t=0;
                        foreach ($tipologias as $tipo){
                            $muebles = $tipo->muebles;
                            $firstTipo = TRUE;
                            $rowTipo = count($muebles);
                            /*agregado para que salgan los segmentos DHO NEXT y DHO NEXT PLUS juntos*/
                            $m=0;
                            foreach ($muebles as $mueble) {
                                ?>
                            <tr>
                                <td class="segmento"><strong><?= ($firstSeg) ? $segmento->segmento : ""; ?></strong></td>
                                <td class="tipologia"><strong><?= ($firstTipo) ? $tipo->tipologia : ""; ?></strong></td>
                                <td class="planograma"><a href="#" class="launch_planograma"><?= $mueble->display; ?></a>

                                    <div class="planograma">

                                        <table width="100%" height="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="img-mueble">
                                                    <?php if (!empty($mueble->imagen)) {
                                                        echo '<img src="' . site_url('application/uploads/' . $mueble->imagen . '') . '" width="100">';
                                                    } ?>
                                                </td>
                                                <td class="devices" height="100%">
                                                    <p><strong><?= $mueble->display ?></strong></p>
                                                    <?php foreach ($mueble->planograma as $dev) {
                                                        echo '<p>' . $dev->device . '</p>';
                                                    } ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    </td>
                                    <?php
                                    /*agregado para que salgan los segmentos DHO NEXT y DHO NEXT PLUS juntos*/
                                    if (!empty($aux)) {
                                        //echo "ENTRA SIEMPRE ".$elementos;
                                       // print_r($aux->tipologias[$t]->muebles[$m]->num_pds); echo "<br>";
                                        //$tipos=$aux->tipologias[$tipo];
                                        if(!empty($aux->tipologias[$t]->muebles[$m]->num_pds))
                                            $mueble->num_pds+=$aux->tipologias[$t]->muebles[$m]->num_pds;
                                       /* else
                                            echo "esta vario ".$mueble->num_pds;*/

                                    }

                                    ?>
                                    <td class="pdvs"><?= $mueble->num_pds ?></td>
                                    </tr>

                                    <?php $firstSeg = FALSE;
                                    $firstTipo = FALSE;
                                    $m++;
                            }
                            ?>

                            <tr class="separador-tipo">
                                <td height="1">&nbsp;</td>
                                <th colspan="3" height="1">&nbsp;</th>
                            </tr>

                            <?php
                            $t++;
                        }
                        ?>

                        <tr class="separador">
                            <?php
                            /*agregado para que salgan los segmentos DHO NEXT y DHO NEXT PLUS juntos*/
                            if (!empty($aux)) {
                                $segmento->num_pds+=$aux->num_pds;
                            }
                            ?>
                            <td class="total" colspan="4"><strong>Total tiendas / tipo: <?= $segmento->num_pds ?></strong></td>
                        </tr>

                        <?php
                        /*agregado para que salgan los segmentos DHO NEXT y DHO NEXT PLUS juntos*/
                        $elementos++;
                        if ($elementos>(count($resultado->segmentos)-1)) {
                            break;
                        }

                    }
                    ?>
                </table>
            <?php } ?>
              
            </div>
        </div>
    