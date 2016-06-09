<?php
if (!empty($tipoC)) {
    if($mensajes_nuevosC > 1){ ?>
        <a href="../estado_incidencias/cerradas" data-rel="nuevos"  data-order="desc" data-order-form="form_orden_<?=$tipo?>" class="mensajes_nuevosC blink">Tienes <?=$mensajes_nuevosC?> mensajes nuevos de inc. <?=ucwords($tipoC)?></a>
        <?php
    }
    elseif($mensajes_nuevosC == 1)
    {?>
        <a href="../estado_incidencias/cerradas" data-rel="nuevos"  data-order="desc" data-order-form="form_orden_<?=$tipo?>" class="mensajes_nuevosC blink">Tienes <?=$mensajes_nuevosC?> mensaje nuevo de inc. <?=ucwords($tipoC)?></a>
    <?php }
}
else {
    if ($mensajes_nuevos > 1) { ?>
        <a href="#" data-rel="nuevos" data-order="desc" data-order-form="form_orden_<?= $tipo ?>"
           class="mensajes_nuevos blink">Tienes <?= $mensajes_nuevos ?> mensajes nuevos de inc.<?=ucwords($tipo)?> </a>
        <?php
    } elseif ($mensajes_nuevos == 1) {
        ?>
        <a href="#" data-rel="nuevos" data-order="desc" data-order-form="form_orden_<?= $tipo ?>"
           class="mensajes_nuevos blink">Tienes <?= $mensajes_nuevos ?> mensaje nuevo de inc. <?=ucwords($tipo)?></a>
    <?php }
}
?>
