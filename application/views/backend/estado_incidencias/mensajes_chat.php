<?php if($mensajes_nuevos > 1){ ?>
    <a href="#" data-rel="nuevos"  data-order="desc" data-order-form="form_orden_<?=$tipo?>" class="mensajes_nuevos blink">Tienes <?=$mensajes_nuevos?> mensajes nuevos</a>
<?php
}
elseif($mensajes_nuevos == 1)
{?>
    <a href="#"   data-rel="nuevos"  data-order="desc" data-order-form="form_orden_<?=$tipo?>" class="mensajes_nuevos blink">Tienes <?=$mensajes_nuevos?> mensaje nuevo</a>
<?php }