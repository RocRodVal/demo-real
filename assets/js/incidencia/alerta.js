/**
 * Created by dani on 13/2/15.
 */
$(document).ready(function(){
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    if(controller == "alta_incidencia"){
        data="Si tu incidencia es de mobiliario, tramítalo a través de tu mantenimiento.<br/><br/>" +
        "Si tu incidencia es de Smartphone, Tablet o Sistemas de seguridad continúa en este proceso.<br><br>" +
        "Si tu incidencia es de PLV, remítela a tu responsable de Trade territorial";
        $("#msg_modal").html(data);
        $("#modal_alert").modal();
    }
});