/**
 * Created by dbourgon on 26/10/2015.
 */

//const { exit } = require("node:process");

    function subselect_informe_pdv(select,id_select_origen,id_select_destino,secundario_escogido,site_url,callback) {
        //alert(secundario_escogido);
        cargar_subselect(id_select_origen,id_select_destino,secundario_escogido,site_url,callback);
        anadir_filtro(select);
    }
   
    // Cargar por ajax los subtipos asociados a un tipo.
    function cargar_subselect(id_select_origen,id_select_destino,secundario_escogido,site_url,callback) {

        var id_principal = $("#"+id_select_origen).val();
        var id_secundario = $("#"+id_select_destino).val();
        var enfocar = false;
        //alert(secundario_escogido);
        if(secundario_escogido != false || secundario_escogido != ''){
            id_secundario = secundario_escogido;            
            enfocar = true;
        }

        //alert(callback);
        //alert(site_url+id_principal+'/'+id_secundario);
        
        if (id_principal != "") {
                $.post(site_url + id_principal+'/'+id_secundario,
                {},
                function (data) {
                    //alert(id_select_destino);
                    $("#"+id_select_destino).html(data).promise().done(function(){                                  
                        if(enfocar) $(this).addClass("focused");
                        callback(); // Cargamos el callback, cuando ya ha cargado el html
                    });
                });
        }


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
// Cargar por ajax los devices de almacen de un tipo determinado
function cargar_subselectAlmacen(id_select_origen,id_select_destino,site_url,callback) {

    var id_principal = $("#"+id_select_origen).val();

    if (id_principal != "") {
        $.post(site_url + id_principal,
            {},
            function (data) {
                //alert(id_select_destino);
                $("#"+id_select_destino).html(data).promise().done(function(){
                    //if(enfocar) $(this).addClass("focused");
                    callback(); // Cargamos el callback, cuando ya ha cargado el html
                });
            });
    }


}
    
    
    
    





