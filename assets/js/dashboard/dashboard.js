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
    $("table.table tr td").on("mouseover",function(){
        $(this).parent().children().each(function(){
            if($.isNumeric($(this).text()) && $(this).text() < 0){
                $(this).css("background-color", "#aa0000");
                $(this).css("color", "#ffffff");
            }else {
                $(this).css("background-color", "#e9e9e9");
                $(this).css("color", "#000000");
            }
        });
    });
    $("table.table  tr td").on("mouseout",function(){
        $(this).parent().children().each(function(){

            if($.isNumeric($(this).text()) && $(this).text() < 0){
                $(this).css("background-color", "#ff0000");
                $(this).css("color", "#ffffff");
            }else {
                $(this).css("background-color", "#ffffff");
                $(this).css("color", "#000000");
            }

        });
    });

    /** Click en TH para ordenar **/
    $(".table-sorting th.sorting").on("click",function(){
        var campoOrdenar = $(this).attr("data-rel");
        var ordenCampo = $(this).attr("data-order");

        if(ordenCampo === '' || ordenCampo === 'desc'){ ordenCampo = 'asc'}
        else{ ordenCampo = 'desc'}

        var idFormOrdenar = $(this).parent().parent().parent().attr("data-order-form");
        $("#"+idFormOrdenar).find("input[name="+idFormOrdenar+"_campo]").val(campoOrdenar);
        $("#"+idFormOrdenar).find("input[name="+idFormOrdenar+"_orden]").val(ordenCampo);
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