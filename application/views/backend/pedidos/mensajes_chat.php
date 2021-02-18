<?php
if (!empty($tipoC)) {
    if($mensajes_nuevosC > 1){ ?>
        <a href="../pedidos/finalizados?campo_orden=nuevos&orden_campo=desc" data-rel="nuevos"  data-order="desc" data-order-form="form_orden_<?=$tipoC?>" 
        class="mensajes_nuevosC blink">Tienes <?=$mensajes_nuevosC?> mensajes nuevos de pedidos <?=ucwords($tipoC)?></a>
        <?php
    }
    elseif($mensajes_nuevosC == 1)
        //?orden_campo=desc&campo_orden=nuevos
    {?>
        <a href="../pedidos/finalizados?campo_orden=nuevos&orden_campo=desc" data-rel="nuevos"  data-order="desc" data-order-form="form_orden_<?=$tipoC?>" 
        class="mensajes_nuevosC blink">Tienes <?=$mensajes_nuevosC?> mensaje nuevo del pedido. <?=ucwords($tipoC)?></a>
    <?php }
}
else {
    if ($mensajes_nuevos > 1) { ?>
        <a href="#" data-rel="nuevos" data-order="desc" data-order-form="form_orden"
           class="mensajes_nuevos blink">Tienes <?= $mensajes_nuevos ?> mensajes nuevos de pedidos.<?=ucwords($tipo)?> </a>
        <?php
    } elseif ($mensajes_nuevos == 1) {
        ?>
        <a href="#" data-rel="nuevos" data-order="desc" data-order-form="form_orden"
           class="mensajes_nuevos blink">Tienes <?= $mensajes_nuevos ?> mensaje nuevo del pedido. <?=ucwords($tipo)?></a>
    <?php }
}
?>
