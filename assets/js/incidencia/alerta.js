/**
 * Created by dani on 13/2/15.
 */
$(document).ready(function(){
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    /*
    if(controller=="alta_incidencia" ||
        controller=="alta_incidencia_robo" ||
        controller=="alta_incidencia_mueble" ||
        controller=="alta_incidencia_device") {
        $.growl("Si tu incidencia está relacionada con el mobiliario, debes gestionarla con CROMA. ¡Muchas gracias!", {
            type: "warning"
        });
    }
    */
    if(controller == "alta_incidencia"){
        data="Si tu incidencia está relacionada con el mobiliario, debes gestionarla con CROMA.<br/><br/> ¡Muchas gracias!";
        $("#msg_modal").html(data);
        $("#modal_alert").modal();
    }
});