/**
 * Created by dani on 13/2/15.
 */
$(document).ready(function(){
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    if(controller=="alta_incidencia_device") {
        $('input[name="tipo_averia"]').change(showAlarmaMueble);
        $('input[name="alarm_display"]').change(showAlarmaMovil);
        $('input[name="alarm_device"]').change(showDescription);
        $('textarea[name="description"]').blur(checkDescription);
        $('input[name="email"]').blur(checkUserData);
        $('input[name="contacto"]').blur(checkUserData);
        $('input[name="phone"]').blur(checkUserData);
    }
});


function showAlarmaMueble(){
    $("#alarmaDisplay").show();
}

function showAlarmaMovil(){
    $("#alarmaDevice").show();
}

function showDescription(){
    $("#description_textArea").show();
}

function checkDescription(){
    disable_value=$("#description").val().length<20;
    $('input[name="contacto"]').attr('disabled',disable_value);
    $('input[name="email"]').attr('disabled',disable_value);
    $('input[name="phone"]').attr('disabled',disable_value);
    $('input[name="submit"]').attr('disabled',disable_value);
}

function checkUserData(){
    value= $('input[name="contacto"]').val().length>0 &&
            /*$('input[name="phone"]').val().length>0 &&
            $('input[name="email"]').val().length>0 &&*/
            $('textarea[name="description"]').val().length>20;
    $('input[name="submit"]').attr('disabled',!value);

}