/**
 * Created by dani on 13/2/15.
 */


$(document).ready(function(){
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    if(controller=="alta_pedido") {

        $('input[id="cantidades"]').keydown(checkUserData);
        $('input[name="contacto"]').keydown(checkUserData);
        $('input[name="phone"]').keydown(checkUserData);


        $('input[id="cantidades"]').change(checkUserData);
        $('input[name="contacto"]').change(checkUserData);
        $('input[name="phone"]').change(checkUserData);

        checkUserData();
    }
});


function checkUserData(){
    value= $('input[name="contacto"]').val().length>0 &&
            $('input[name="phone"]').val().length>0;
    $cantidades=$('input[id="cantidades"]');
    $todasCero=true;
    div = document.getElementById("alerta");

    //if(value==true) {
        for (var i=0; i<$cantidades.length; i++) {
                if (($cantidades[i].value==0) && ($todasCero)) {
                div.style.visibility = "visible";
                $todasCero=true;
            }
            else {
                div.style.visibility = "hidden";
                $todasCero=false;
            }
        }
   // }
    if ($todasCero) {value=false;}
    $('input[name="submit"]').attr('disabled',!value);
}

