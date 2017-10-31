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

$(document).ready(function (e) {
    $('#modal_alert').on('show.bs.modal', function(e) {
        data="Recuerda que puedes adjuntar el parte enviado por el técnico para el cierre de la incidencia";
        $(e.currentTarget).find('#msg_modal').html(data);
    });
});

/*function showModalAlert() {
    data="Recuerda que debes adjuntar el parte enviado por el técnico para el cierre de la incidencia";
    $("#msg_modal").html(data);
    $("#modal_alert").modal();
}*/
function enviarFormulario(){
    /* when the submit button in the modal is clicked, submit the form */
    //alert('submitting');
    $('#btnResolverHidden').click();
};