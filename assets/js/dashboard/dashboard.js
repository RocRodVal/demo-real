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

    $("table.table-hover tr td").on("mouseover",function(){
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


        });
    });

    $(".tiendas-fab table.table tr td table tr td").on("mouseover",function(){
       $(this).css("background","transparent");
       $(".tiendas-fab table.table tr td table ").css("background","transparent");
    });
    $(".tiendas-fab table.table tr td table tr td").on("mouseout",function(){
        $(this).css("background","transparent");
        $(".tiendas-fab table.table tr td table ").css("background","transparent");
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

    /** Click en aviso de mensajes de chat para ordenar la tabla por el chat **/
    $("a.mensajes_nuevos").on("click",function(){
        var campoOrdenar = $(this).attr("data-rel");
        var ordenCampo = $(this).attr("data-order");
        var idFormOrdenar = $(this).attr("data-order-form");


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
 * Aplica la clase CSS apropiada para mostrar el icono adecuado, en cuanto a la ordenaci??n por ese campo.
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
 *  A??adir filtro al multifiltro para un campo
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
        // A??adimos el campo
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

function anadir_filtroTexto(selector)
{


    // Identificar la capa del "Multifiltro" correspondiente a este campo
    var id_selector = $(selector).attr("id");
    var valor_escogido = $(selector).val();
    var texto_escogido = $(selector).val();

    var capa_wrapper_multifiltro = $("#multifiltro_"+id_selector);
    var capa_multifiltro = $("#multi_"+id_selector);
    var siguiente = $("#"+id_selector+"_next").val();


    var existe = false;

    var elementoNoDefinido = $("#multi_"+id_selector+" div.linea input[value="+valor_escogido+"]").val();
    if(elementoNoDefinido!=undefined) existe = true;


    // Si no existe...
    if(!existe && valor_escogido != '') {
        // A??adimos el campo
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
    // Eliminar l??nea filtro campo
    $("#"+id_selector+"_linea_"+indice).remove();
    // Eliminar indice de la cadena de ??ndices

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



/*
*   OPERAR INCIDENCIA: AFECTA A...
*/

function update_incidencia_afecta()
{
    var id_form = $("#afecta_a");

        $.ajax({
            type: 'POST',
            url: $(id_form).attr("action"),
            data: $(id_form).serialize(),
            // Mostramos un mensaje con la respuesta de PHP
            success: function (data) {
                var mensaje = $(id_form).find("span.result");
                var delay = 1500;

                $(mensaje).html(data);
                $(mensaje).fadeIn(500);

                setTimeout(function(){
                    $(mensaje).fadeOut(300);
                    location.reload();
                },delay);
            },
            error: function (error) {
                console.error(error.responseText);
            }
        });

}




function comprobar_stock(selector,id_input)
{
    var option = $(selector).find("option:selected");
    var stock = $(option).attr("data-stock");
    var input = $("#"+id_input);

    if(stock <= 0)
    {
        $(input).addClass('error');
        $(input).val("Stock insuficiente: "+stock);
        $(input).attr('disabled',true);
        $(input).attr('readonly',true);
    }
    else
    {
        $(input).removeClass('error');
        $(input).val(1);
        $(input).attr('disabled',false);
        $(input).attr('readonly',false);
    }
}

function comprobar_stock_final(selector,id_input)
{
    //var option = $(selector).find("option:selected");

    var stock = $('select[name="'+selector+'"] option:selected').attr('data-stock');
    var input = $("#"+id_input);

    if(stock - input.val()<0){
        $(input).addClass('error');
        $(input).val("Stock insuficiente: "+stock);
        $(input).attr('disabled',true);
        $(input).attr('readonly',true);
    }

}


$(document).ready(function(){
    // Autosubmitar el filtro de dashboard, al escoger un valor de los SELECTS
    $("form.autosubmit select").on("change",function(){
       $(this).parents('form.autosubmit').submit();
    });
    enfocar_campos();
});



function enfocar_campos()
{
    // Marcar los campos seleccionados del filtro de dashboard: SELECTS
    $("form.filtros select,form.filtros input").each(function(){
        var valor = $(this).find("option:selected").val();
        if(valor!='' && valor != undefined)     $(this).addClass("focused");
                                    else        $(this).removeClass("focused");
    });
    // Marcar los campos seleccionados del filtro de dashboard: INPUTS
    $("form.filtros input").each(function(){
        var valor = $(this).val();
        if(valor!='' && valor != undefined)     $(this).addClass("focused");
                                    else        $(this).removeClass("focused");
    });
}



/*
jQuery(document).on("xcrudbeforerequest", function(event, container) {
    if (container) {
        jQuery(container).find("select").select2("destroy");
    } else {
        jQuery(".xcrud").find("select").select2("destroy");
    }


});
jQuery(document).on("ready xcrudafterrequest", function(event, container) {
    if (container) {
        jQuery(container).find("select").select2();
    } else {
        jQuery(".xcrud").find("select").select2();
    }

});
jQuery(document).on("xcrudbeforedepend", function(event, container, data) {
    jQuery(container).find('select[name="' + data.name + '"]').select2("destroy");

    console.log('beforedepend');
    console.log(data);
});
jQuery(document).on("xcrudafterdepend", function(event, container, data) {
    jQuery(container).find('select[name="' + data.name + '"]').select2();
    alert('afterdepend');
    console.log(data);
});*/



/**
 NUEVA CATEGORIZACION
 **/

function load_select(url,select_destino,select_actual,id_selected)
{

    var escogido = $(select_actual).val();
    var form = $(select_actual).closest('form');

    if(id_selected != undefined && id_selected != '')
    {
        escogido = id_selected;
    }
    $.ajax({
        type: 'POST',
        url: (url+'/'+escogido),
        data: $(form).serialize(),
        // Mostramos un mensaje con la respuesta de PHP
        success: function (data) {
            //alert( $('#'+select_destino).attr("onchange"));
            $('#'+select_destino).html(data);
        },
        error: function (error) {
            console.error(error.responseText);
        }
    });

}


function load_input(url,select_destino,select_actual,id_selected)
{

    var escogido = $(select_actual).val();
    var form = $(select_actual).closest('form');

    if(id_selected != undefined && id_selected != '')
    {
        escogido = id_selected;
    }

    $.ajax({
        type: 'POST',
        url: (url+'/'+escogido),
        data: $(form).serialize(),
        // Mostramos un mensaje con la respuesta de PHP
        success: function (data) {
            //alert( $('#'+select_destino).attr("onchange"));
            $('#'+input_destino).val(data);
        },
        error: function (error) {
            console.error(error.responseText);
        }
    });

}

function insert(url,objeto,vuelta)
{


    if(
        ($("#id_tipo").find("option:selected").val()=='') ||
        ($("#id_subtipo").find("option:selected").val()=='') ||
        ($("#id_segmento").find("option:selected").val()=='')  ||
        ($("#id_tipologia").find("option:selected").val()=='')
    )
    {
        return false;
    }
    else {

        var form = $(objeto).closest('form');


        $.ajax({
            type: 'POST',
            url: (url),
            data: $(form).serialize(),
            // Mostramos un mensaje con la respuesta de PHP
            success: function (data) {
                $("#id_tipo").val('');
                $("#id_subtipo").val('');
                $("#id_segmento").val('');
                $("#id_tipologia").val('');

                if(data=='error') alert('Ya existe la categor??a');
                else    window.location.replace(vuelta);
            },
            error: function (error) {
                console.error(error.responseText);
            }
        });
    }
}



/**
 ** VALIDAR A??ADIR MUEBLE A SFIDs
 **/

function validar_anadir_mueble_sfid()
{
    var textareaVal = $("#sfids").val();
    var muebleVal = $("#id_display option:selected").val();

    if(textareaVal == '')        $("#sfids").addClass("error");
    else        $("#sfids").removeClass("error");

    if(muebleVal == '') $("#id_display").addClass("error");
    else  $("#id_display").removeClass("error");


    return (textareaVal != '' && muebleVal != 0);

}




$(document).ready(function(){
    $("a.launch_planograma").click(function(){
        //$(this).parent().parent().parent().parent().find("div.planograma").hide();
        var pulsacion = this;
        $(pulsacion).toggleClass("selected");  
        
        $(".tiendas-fab").find("div.planograma").fadeOut(function(){
             
            
        });
           
           $(pulsacion).parent().find("div.planograma").toggle();
           
                
                
                      
        
        
        
        return false;
    });
});