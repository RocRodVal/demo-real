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
                //print_r($resultado);
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
                    
                    foreach($resultado->segmentos as $segmento){                          
                        $tipologias = $segmento->tipologias;
                        $firstSeg = TRUE;
                        $rowSeg = count($tipologias);
                        foreach($tipologias as $tipo){ 
                            $muebles = $tipo->muebles;
                            $firstTipo = TRUE;
                            $rowTipo = count($muebles);
                            foreach($muebles as $mueble) { ?>
                            <tr>
                                <td class="segmento"><strong><?=($firstSeg) ? $segmento->segmento : ""; ?></strong></td>                               
                                <td class="tipologia"><strong><?=($firstTipo) ? $tipo->tipologia  : ""; ?></strong></td>
                                <td class="planograma"><a href="#" class="launch_planograma"><?=$mueble->display; ?></a>
                                    <div class="planograma">
                                        
                                        <table width="100%" height="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="img-mueble">                                          
                                                    <?php if(!empty($mueble->imagen)){
                                                        echo '<img src="'.site_url('application/uploads/'.$mueble->imagen.'').'" width="100">';                                           
                                                    } ?>
                                                </td>    
                                            <td class="devices" height="100%">
                                                <p><strong><?=$mueble->display?></strong></p>
                                                <?php foreach($mueble->planograma as $dev) {
                                                    echo '<p>'.$dev->device.'</p>';
                                                }?>
                                                </td>
                                            </tr>
                                        </table>
                                        </div>
                                    </div>
                                </td>
                                <td class="pdvs"><?=$mueble->num_pds?></td>
                            </tr>  
                             
                        <?php $firstSeg = FALSE;
                                $firstTipo = FALSE;
                            }  
                            ?>
                            
                            <tr class="separador-tipo">
                                <td height="1">&nbsp;</td>
                                <th colspan="3" height="1">&nbsp;</th>
                             </tr>

                            <?php
                        }
                        ?>
                              
                            <tr class="separador">                                
                                <td class="total" colspan="4"><strong>Total tiendas / tipo:  <?=$segmento->num_pds?></strong></td>
                            </tr>        
                            
                    <?php } ?>
                    
                    
                </table>
                <?php } ?>
              
            </div>
        </div>
    