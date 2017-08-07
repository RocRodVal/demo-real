<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 24/07/2015
 * Time: 9:50
 */

?>
<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title." - ".$nombre_mueble ?></h1>
                </div>
            </div>

        <div class="row">
            <div class="col-lg-12">
            <?php if(!empty($resultado)) { ?>
                <div class="row buscador">
                   <form id="form_ajax" action="<?=site_url($controlador.'/update_actualizacion_masiva/'.$id_mueble);?>" method="post" class="form-inline filtros form-mini">
                        <div class="form-group">
                            <table id="resultao">
                                <tr>
                                    <th>SFID</th>
                                    <?php
                                    for($i=0;$i<count($resultado);$i++){
                                    ?>

                                        <th>DEVICE</th>
                                        <th>IMEI</th>
                                        <th>POSICION</th>

                              <?php } ?>
                                </tr>
                                            <?php
                                            foreach($resultado as $clave => $elemento)

                                            {?>

                                                <tr>
                                                    <td><input type="text" value="<?=$clave?>" name="sfid[]" size="10" readonly></td>
                                                    <?php
                                                        //print_r($elemento);
                                                    foreach ($elemento as $e){
                                                        foreach ($e as $valor){?>
                                                            <td>
                                                                <input type="text" value="<?=$valor["device"]?>" name="device[][<?=$clave?>]" size="35">
                                                            </td>
                                                            <td>
                                                                <input type="text" value="<?=$valor["imei"]?>" name="imei[][<?=$clave?>]" size="20">
                                                            </td>
                                                            <td>
                                                                <input type="text" value="<?=$valor["posicion"]?>" name="posicion[][<?=$clave?>]" size="2">
                                                            </td>

                                                        <?php
                                                            }
                                                        }
                                                        ?>



                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </table>

                                    </div>

                                <div class="col-sm-12">
                                   <div class="form-group">
                                        <input type="submit" value="Guardar" class="form-control">
                                    </div>
                                </div>
                            </form>

                        </div>
                <?php  }
                else {
                    echo "No hay resultados";
                }?>
            </div>
        </div>
