/**
 * Created by dani on 13/2/15.
 */


$(document).ready(function(){
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    if(controller=="alta_incidencia_dispositivo" || controller == "alta_incidencia_mueble_alarma") {
        $('input[name="tipo_averia"]').change(showDenuncia);
        $('input[name="tipo_averia"]').change(checkUserData);
        $('textarea[name="description_1"]').keypress(checkUserData);
        $('#denuncia').change(checkUserData);
        /*
        if(controller == "alta_incidencia_dispositivo")
            $('textarea').attr('disabled',true);
        $('input[type="checkbox"]').attr('disabled',true);
        */
        $('input[name="email"]').keydown(checkUserData);
        $('input[name="contacto"]').keydown(checkUserData);
        $('input[name="phone"]').keydown(checkUserData);
        $('input[type="checkbox"]').change(checkUserData);

        $('input[name="email"]').change(checkUserData);
        $('input[name="contacto"]').change(checkUserData);
        $('input[name="phone"]').change(checkUserData);
        $('textarea[name="description_1"]').change(checkUserData);
        checkUserData();
    }
});


function showDenuncia(){
    console.log($("#"+this.id));
    if($("#"+this.id).attr('value')==1){
        $("#denuncia").show('slow');
    }
    else{
        $("#denuncia").hide('slow');
        //$('textarea').attr('disabled',false);
        //$('input[type="checkbox"]').attr('disabled',false);
    }
}

function checkDenuncia(){
    console.log($('.file-caption-name')[0]);
    disable=$('.file-caption-name')[0].innerHTML=='';
    //$('input[name="submit"]').attr('disabled',disable);
    if(disable==FALSE)
    	$('input[name="device"]').prop('checked',TRUE);
    return disable;
    //$('textarea').attr('disabled',disable);
    //$('input[type="checkbox"]').attr('disabled',disable);

}

function checkUserData(){
    value= $('input[name="contacto"]').val().length>0 &&
            $('input[name="phone"]').val().length>0 &&
            $('#description_1').val().length>=10;
    if(value==true) {
        if (controller == "alta_incidencia_dispositivo") {
            check = false;

            for (var i = 0; i < $('input[type="checkbox"]').length; i++) {
                console.log()
                if ($('input[type="checkbox"]')[i].checked) {
                    check = true;
                    break;
                }
            }

            if (check == true) {
                if ($('input[name="tipo_averia"]:checked').length == 0)
                    value = false;
                else {
                    if ($('input[name="tipo_averia"]:checked')[0].value == 1) {
                        value = !checkDenuncia();
                    }
                }
            }
            else{
                value=false;
            }
        }
    }
    console.log("DISABLED->"+!value);
    $('input[name="submit"]').attr('disabled',!value);
}

