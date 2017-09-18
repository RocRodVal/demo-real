<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 24/07/2015
 * Time: 9:50
 */

?>
<!-- #page-wrapper -->
<div id="wrapper">
<?php
if (empty($mensaje)){
?>
		<div id="print-wrapper">


		            <h1 class="page-header"><?php echo $title." - ".$nombre_mueble ?></h1>


            <?php if(!empty($resultado)) { ?>
                <div >
                   <form id="form_ajax" action="<?=site_url($controlador.'/update_actualizacion_masiva/'.$id_mueble);?>" method="post" class="form-inline filtros form-mini">
                        <div class="form-group">
                            <table id="resultado">
                                <tr>
                                    <th>SFID</th>
                                    <?php
                                    //print_r($resultado);
                                    for($i=0;$i<$resultado["posiciones"];$i++){
                                    ?>

                                        <th>DEVICE</th>
                                        <th>IMEI</th>
                                        <th>POSICION</th>

                              <?php } ?>
                                </tr>
                                <?php
                                foreach($resultado as $clave => $elemento)
                                    if ($clave!="posiciones") {
                                        {
                                            ?>
                                            <tr>
                                                <td><input type="text" value="<?= $clave ?>" name="sfid[]"
                                                                   size="10" readonly></td>
                                                        <?php
                                                        //print_r($elemento);
                                                        foreach ($elemento as $e) {
                                                            foreach ($e as $valor) {
                                                                ?>
                                                                <td>
                                                                    <input type="text" value="<?= $valor["device"] ?>"
                                                                           name="device[][<?= $clave ?>]" size="35">
                                                                </td>
                                                                <td>
                                                                    <input type="text" value="<?= $valor["imei"] ?>"
                                                                           name="imei[][<?= $clave ?>]" size="20">
                                                                </td>
                                                                <td>
                                                                    <input type="text" value="<?= $valor["posicion"] ?>"
                                                                           name="posicion[][<?= $clave ?>]" size="2">
                                                                </td>

                                                                <?php
                                                            }
                                                        }
                                                        ?>


                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </table>

                                    </div>
<div>
                                    <input type="submit" value="Guardar" class="form-control">
</div>
                            </form>

                        </div>
                <?php  }
                else {
                    echo "No hay resultados";
                }?>

        </div>
<?php }
else { ?>
<div id="priwrapper">


    <h2 class="page-header"><?php echo $mensaje; ?></h2>



    <div class="col-lg-12">

        <p><a href="<?=base_url().'admin/actualizacion_masiva'?>" class="btn btn-warning" target><span class="glyphicon glyphicon-chevron-left"></span> Volver</a></p>

    </div>

        <a href="<?=site_url('admin/actualizacion_masiva')?>" class="btn btn-danger">Volver</a>
    <input type="submit" value="Volver" class="form-control left">
</div>
<?php }?>
</div>