/**
 * Created by dani on 12/2/15.
 */

/**
 * Plugin SCROLL
 * @param target
 * @param options
 * @param callback
 * @returns {*}
 */
$.fn.scrollTo = function( target, options, callback ){
    if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
    var settings = $.extend({
        scrollTarget  : target,
        offsetTop     : 50,
        duration      : 500,
        easing        : 'swing'
    }, options);
    return this.each(function(){
        var scrollPane = $(this);
        var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
        var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
        scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
            if (typeof callback == 'function') { callback.call(this); }
        });
    });
}


$(document).ready(function () {
    pathname = window.location.href;
    dataURL = pathname.split("/");
    view = dataURL[5];
    controller = dataURL[4]
    if(view=="dashboard" && controller!="tienda") {
        $('[data-toggle="tooltip"]').tooltip();
        createDataTable();
    }
    if(view=="dashboard"){
        avisoChat();
    }

});

function createDataTable(){
   /* $("#table_incidencias_dashboard").dataTable( {
        stateSave: true,
        "pageLength": 100
    } );*/
}

function avisoChat(){
    $(".chat_nuevo").each(function(key,elem){
        $(elem).addClass('pulse');
    });
    /*
     setInterval(function(){
     $(".chat_nuevo").each(function(key,elem){
     if($(elem).hasClass('pulse')){
     $(elem).removeClass('pulse');
     }
     else{
     $(elem).addClass('pulse');
     }
     });
     }, 2000);
     */
    var contador=0;
    setInterval(function(){
        $(".chat_nuevo").each(function(key,elem){
            if(contador%2!=0){
                $(elem).css('color','#f60');
            }
            else{
                $(elem).css('color','#000');
            }

        });
        contador++;
    }, 750);
}




$(document).ready(function () {
    var colorCeldaOriginal = "#ffffff";



    /** Efecto HOVER  en columnas **/
    $(".table-sorting th.sorting").on("mouseover",function(){
        var col = $(this).parent().children().index($(this));

        var idTabla  = $(this).parent().parent().parent().attr("id");
        colorCeldaOriginal =  $("#"+idTabla+" tr").find("td").css("background-color");

        $("#"+idTabla+" tr").find("td:eq("+col+")").css("background-color","#e9e9e9");
    });
    $(".table-sorting th.sorting").on("mouseout",function(){
        var col = $(this).parent().children().index($(this));
        $(".table-sorting tr").find("td:eq("+col+")").css("background-color",colorCeldaOriginal);
    });

    /** Efecto HOVER  en filas **/
    var  colorTemp, bgTemp,i;

    $("table.table tr td").on("mouseover",function(){
        colorTemp= new Array();
        bgTemp = new Array();
        i=0;
        $(this).parent().children().each(function(){

                if($(this).hasClass("warning")) {
                    $(this).addClass("warning-hover");

                }else if($(this).hasClass("notice")){
                    $(this).addClass("notice-hover");

                }else {
                    colorTemp[i] = $(this).css('color');
                    bgTemp[i] = $(this).css('background-color');
                    $(this).css("background-color", "#e9e9e9");
                    $(this).css("color", "#000000");
                }
                i++;



            console.log($(this).attr("class"));

        });
    }).on("mouseout",function(){
        i=0;
        $(this).parent().children().each(function(){

            if($(this).hasClass("warning")) {
                $(this).removeClass("warning-hover");

            }else if($(this).hasClass("notice")){
                $(this).removeClass("notice-hover");

            }else {
                $(this).css("background-color", bgTemp[i]);
                $(this).css("color", colorTemp[i]);
            }
                i++;

            console.log($(this).attr("class"));
        });
    });

    /** Click en TH para ordenar **/
    $(".table-sorting th.sorting").on("click",function(){
        var campoOrdenar = $(this).attr("data-rel");
        var ordenCampo = $(this).attr("data-order");

        if(ordenCampo === '' || ordenCampo === 'desc'){ ordenCampo = 'asc'}
        else{ ordenCampo = 'desc'}

        var idFormOrdenar = $(this).parent().parent().parent().attr("data-order-form");
        //alert(idFormOrdenar);
        $("#"+idFormOrdenar).find("input[name="+idFormOrdenar+"_campo_orden]").val(campoOrdenar);
        $("#"+idFormOrdenar).find("input[name="+idFormOrdenar+"_orden_campo]").val(ordenCampo);
        $("#"+idFormOrdenar).find("input[name=form]").val(idFormOrdenar);
        $("#"+idFormOrdenar).submit();
    });


    /** Scroll entre elementos **/

    $("a.scrollTo").on("click",function(e){
        var destino = $(this).attr("href");
        $("body").scrollTo(destino,{duration:'slow', offsetTop : '50'});



    });


});

/**
 * Aplica la clase CSS apropiada para mostrar el icono adecuado, en cuanto a la ordenación por ese campo.
 * @param tabla
 * @param campo
 * @param orden
 */
function marcarOrdenacion(tabla,campo,orden) {
    $("#" + tabla + " th.sorting_asc").removeClass("sorting_asc");
    $("#" + tabla + " th.sorting_desc").removeClass("sorting_desc");
    $("#" + tabla + " th.sorting").attr("data-order","");
    $("#" + tabla + " th[data-rel='"+campo+"']").addClass("sorting_"+orden);
    $("#" + tabla + " th[data-rel='"+campo+"']").attr("data-order",orden);
}



/**
 *  Añadir filtro al multifiltro para un campo
 */

function anadir_filtro(selector)
{


    // Identificar la capa del "Multifiltro" correspondiente a este campo
    var id_selector = $(selector).attr("id");
    var valor_escogido = $(selector).val();
    var texto_escogido = $("#"+id_selector+" option:selected").html();

    var capa_wrapper_multifiltro = $("#multifiltro_"+id_selector);
    var capa_multifiltro = $("#multi_"+id_selector);
    var siguiente = $("#"+id_selector+"_next").val();


    var existe = false;

    var elementoNoDefinido = $("#multi_"+id_selector+" div.linea input[value="+valor_escogido+"]").val();
    if(elementoNoDefinido!=undefined) existe = true;


    // Si no existe...
    if(!existe && valor_escogido != '') {
        // Añadimos el campo
        var label = $('<label/>', {
            'for': id_selector+'_' + siguiente,
            'class':'auto'
        });
        $(label).text(texto_escogido);

        var input = $('<input/>', {
            'name' : id_selector+'_multi[]',
            'type': 'hidden',
            'value' : valor_escogido

        });

        var enlace_borrar = $('<a/>', {
            'href' : '#',
            'onclick' : 'eliminar_multifiltro("'+id_selector+'","'+siguiente+'");'
        });
        $(enlace_borrar).html('<i class="glyphicon glyphicon-remove"></i>');

        var lineaActual = $('<div/>', {
            'class': 'linea',
            'id': id_selector+'_linea_' + siguiente
        });

        $(lineaActual).append(label);
        $(lineaActual).append(input);
        $(lineaActual).append(enlace_borrar);

        $(capa_multifiltro).append(lineaActual);

        siguiente++;
        $("#"+id_selector+"_next").val(siguiente);


        var filtros = $("#multi_"+id_selector+" div.linea");
        if(filtros.length > 0){ $(capa_wrapper_multifiltro).fadeIn(); }
        $("#"+id_selector).val("");
        enviar_form_ajax('#form_ajax');
    }




}


function eliminar_multifiltro(id_selector,indice)
{
    var capa_wrapper_multifiltro = $("#multifiltro_"+id_selector);
    // Eliminar línea filtro campo
    $("#"+id_selector+"_linea_"+indice).remove();
    // Eliminar indice de la cadena de índices

    var filtros = $("#multi_"+id_selector+" div.linea");
    if(filtros.length == 0){ $(capa_wrapper_multifiltro).hide(); }

    $("#"+id_selector).val("");

    enviar_form_ajax('#form_ajax');

}


function enviar_form_ajax(id_form, action)
{
    // Ocultamos  capa de  resultados..
    $("#result").hide();
    // Se deshabilita el formulario..
    $(id_form).addClass("disabled");
    $("#deshabilitador").css("display","block");



        $.ajax({
            type: 'POST',

            url: $(id_form).attr('action'),
            data: $(id_form).serialize(),
            // Mostramos un mensaje con la respuesta de PHP
            success: function(data) {

                $('#result').html(data);
                $('#result').fadeIn();

                // Re-habilita el formulario..
                $(id_form).removeClass("disabled");
                $("#deshabilitador").css("display","none");

            },
            error:function(error){
                console.error(error.responseText);
            }
        });



}


var callback_enviar_form = function()
{

}



var confirmar_reset_incidencia = function(form_enviar, mensaje)
{
    var valor_inc = $("#id_inc").val();
    if(valor_inc == "") enviar_form(form_enviar);
    mensaje  = mensaje.replace( "##NUM_INC##",valor_inc);
  

    if(window.confirm(mensaje))
    {
        var action =  $("#"+form_enviar).attr("action") + '/' +valor_inc;
        $("#"+form_enviar).attr("action",action);
        return true;
    }
    else{
        return false;
    }
}

var enviar_form = function(id_form)
{
    $("#"+id_form).submit();
}