/**
 * Created by dani on 12/2/15.
 */
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
    $("#table_incidencias_dashboard").dataTable( {
    stateSave: true,
	  "pageLength": 100
	} );
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

    /** Efecto HOVER  en columnas **/
    $(".table-sorting th.sorting").on("mouseover",function(){
       var col = $(this).parent().children().index($(this));
        $(".table-sorting tr").find("td:eq("+col+")").css("background-color","#e9e9e9");
    });
    $(".table-sorting th.sorting").on("mouseout",function(){
        var col = $(this).parent().children().index($(this));
        $(".table-sorting tr").find("td:eq("+col+")").css("background-color","#fff");
    });

    /** Efecto HOVER  en filas **/
    $("table.table tr td").on("mouseover",function(){
        $(this).parent().children().css("background-color","#e9e9e9");
    });
    $("table.table  tr td").on("mouseout",function(){
        $(this).parent().children().css("background-color","#fff");
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


});


function marcarOrdenacion(tabla,campo,orden) {

    $("#" + tabla + " th.sorting_asc").removeClass("sorting_asc");


    $("#" + tabla + " th.sorting_desc").removeClass("sorting_desc");

    $("#" + tabla + " th.sorting").attr("data-order","");

    $("#" + tabla + " th[data-rel='"+campo+"']").addClass("sorting_"+orden);
    $("#" + tabla + " th[data-rel='"+campo+"']").attr("data-order",orden);

}