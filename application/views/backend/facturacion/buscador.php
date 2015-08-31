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
        <p>Seleccione un rango de fechas, dueño y/o instalador.</p>
        <form action="<?=base_url().$accion;?>" method="post" class="form-inline form-sfid">

            <div class="form-group">
                <label>Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo date('Y-m-01'); ?>">
            </div>

            <div class="form-group">
                <label>Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>Dueño</label>
                <select name="dueno" id="dueno">
                    <option value="">Todos</option>
                    <?php
                    foreach($select_duenos as $elem_dueno){
                        $option_selected = ($dueno == $elem_dueno->id_client) ? 'selected ="selected" ' : '';
                        echo '<option value="'.$elem_dueno->id_client.'" '.$option_selected.'>'.$elem_dueno->client.'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Instalador</label>

                <select name="instalador" id="instalador">
                    <option value="">Todos</option>
                    <?php
                    foreach($select_instaladores as $inst){
                        $option_selected = ($instalador == $inst->id_contact) ? 'selected ="selected" ' : '';
                        echo '<option value="'.$inst->id_contact.'" '.$option_selected.'>'.$inst->contact.'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-default">Buscar</button>
            </div>
        </form>
    </div>
</div>