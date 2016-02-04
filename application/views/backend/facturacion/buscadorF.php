<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 31/08/2015
 * Time: 17:41
 */
?>

<div class="row">
    <div class="col-lg-12">
        <?php
            if(isset($_POST['generar_facturacion'])){
                $fecha_ini = $_POST["fecha_inicio"];
                $fecha_fin = $_POST["fecha_fin"];
            }else{
                $fecha_ini = date('Y-m-01');
                $fecha_fin = date('Y-m-d');
            }
        ?>
        <p>Seleccione un rango de fechas, fabricante.</p>
        <form action="<?=base_url().$accion;?>" method="post" class="form-inline form-sfid form-mini">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Inicio</label>
                    <input class="form-control" type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_ini; ?>">
                </div>

                <div class="form-group">
                    <label>Fin</label>
                    <input class="form-control" type="date" name="fecha_fin" id="fecha_fin" value="<?php echo $fecha_fin; ?>">
                </div>

                <div class="form-group">
                    <label>Fabricante</label>
                    <select class="form-control" name="fabricante" id="fabricante">
                        <option value="">Todos</option>
                        <?php
                        foreach($select_fabricantes as $elem_fab){
                            $option_selected = ($fabricante == $elem_fab->id_client) ? 'selected ="selected" ' : '';
                            echo '<option value="'.$elem_fab->id_client.'" '.$option_selected.'>'.$elem_fab->client.'</option>';
                        }
                        ?>
                    </select>
                </div>
<!--
                <div class="form-group">
                    <label>Instalador</label>

                    <select class="form-control" name="instalador" id="instalador">
                        <option value="">Todos</option>
                        <?php /*
                        foreach($select_instaladores as $inst){
                            $option_selected = ($instalador == $inst->id_contact) ? 'selected ="selected" ' : '';
                            echo '<option value="'.$inst->id_contact.'" '.$option_selected.'>'.$inst->contact.'</option>';
                        }*/
                        ?>
                    </select>
                </div> -->

                <div class="form-group">
                    <input type="hidden" name="generar_facturacion" value="si">
                    <button type="submit" id="submit_button" class="form-control input-sm">Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>