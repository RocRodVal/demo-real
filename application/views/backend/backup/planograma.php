<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 20/10/2015
 * Time: 12:35
 */
?>
<div id="print-wrapper">
    <?php
    if (!empty($resultado)) {
    ?>
    <table cellpadding="0" cellspacing="0" border="1" class="backup">
        <tr>
            <th class="num">SFID</th>
            <th class="num">ID PDS</th>
            <th class="num">ID_DISPLAYS_PDS</th>
            <th class="num">POSITION</th>
            <th>DISPLAY</th>
            <th class="num">ID_DISPLAY</th>
            <th class="num">STATUS DISPLAY</th>
            <th class="num">ID_DEV_PDS</th>
            <th class="num">CLIENT</th>
            <th class="num">POSITION</th>
            <th class="num">ID_DEV</th>
            <th class="">DEVICE</th>
            <th class="num">IMEI</th>
            <th class="num">MAC</th>
            <th class="num">SERIAL</th>
            <th class="num">OWNER</th>
            <th class="num">STATUS</th>
        </tr>
        <?php
            foreach($resultado as $clave=>$mueble){ ?>
                <tr>
                    <td class="num"><?=$mueble->reference?></td>
                    <td class="num"><?=$mueble->id_pds?></td>
                    <td class="num"><?=$mueble->id_displays_pds?></td>
                    <td class="num pos"><?=$mueble->position?></td>
                    <td class="name"><?=$mueble->display?></td>
                    <td class="num"><?=$mueble->id_display?></td>
                    <td class="num"><?=$mueble->status?></td>
                    <td class="num"><?= $mueble->id_devices_pds ?></td>
                    <td class="num"><?= $mueble->client_type_pds ?></td>
                    <td class="num"><?= $mueble->position_device ?></td>
                    <td class="num"><?= $mueble->id_device ?></td>
                    <td class="num"><?= $mueble->device ?></td>
                    <td class="num"><?= $mueble->IMEI ?></td>
                    <td class="num"><?= $mueble->mac ?></td>
                    <td class="num"><?= $mueble->serial ?></td>
                    <td class="num"><?= $mueble->owner ?></td>
                    <td class="num"><?= $mueble->status ?></td>

                </tr>
        <?php }?>

    </table>
    <?php
    }
    else { ?>
        <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> No hay datos</p>
    <?php } ?>
</div>