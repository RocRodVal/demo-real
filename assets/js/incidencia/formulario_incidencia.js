/**
 * Created by dani on 13/2/15.
 */


$(document).ready(function(){
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    if(controller=="alta_incidencia_dispositivo") {
        $('input[name="tipo_averia"]').change(showDenuncia);
        $('textarea[name="description_2"]').blur(checkDescription);
        $('textarea').attr('disabled',true);
        $('#denuncia').change(checkDenuncia);
        $('input[type="checkbox"]').attr('disabled',true);
        $('input[name="email"]').blur(checkUserData);
        $('input[name="contacto"]').blur(checkUserData);
        $('input[name="phone"]').blur(checkUserData);
        $('input[type="checkbox"]').change(checkUserData)
        checkUserData();
    }
});


function showDenuncia(){
    console.log($("#"+this.id));
    if($("#"+this.id).attr('value')==1){
        $("#denuncia").show('slow');
        checkDenuncia();
    }
    else{
        $("#denuncia").hide('slow');
        $('textarea').attr('disabled',false);
        $('input[type="checkbox"]').attr('disabled',false);

    }
}

function checkDenuncia(){
    console.log($('.file-caption-name')[0]);
    disable=$('.file-caption-name')[0].innerHTML=='';
    $('textarea').attr('disabled',disable);
    $('input[type="checkbox"]').attr('disabled',disable);
    checkUserData();
}

function checkDescription(){
    disable_value=$("#description_1").val().length<20;
    $('input[name="contacto"]').attr('disabled',disable_value);
    $('input[name="phone"]').attr('disabled',disable_value);
    if(!disable_value)
        checkUserData();
}

function checkUserData(){
    value= $('input[name="contacto"]').val().length>0 &&
            $('input[name="phone"]').val().length>0 &&
            $('#description_1').val().length>20;
    check=false;
    for(var i=0;i<$('input[type="checkbox"]').length;i++)
    {
        console.log()
        if($('input[type="checkbox"]')[i].checked){
            check=true;
            break;
        }
    };
    if (check==false){
        value=false;
    }
    $('input[name="submit"]').attr('disabled',!value);

}

