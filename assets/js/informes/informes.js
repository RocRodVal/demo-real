/**
 * Created by dbourgon on 26/10/2015.
 */

    // Cargar por ajax los subtipos asociados a un tipo.
    function cargar_subselect(id_select_origen,id_select_destino,secundario_escogido,site_url,callback) {

        var id_principal = $("#"+id_select_origen).val();
        var id_secundario = $("#"+id_select_destino).val();
        var enfocar = false;
        
        if(secundario_escogido != false || secundario_escogido != ''){
            id_secundario = secundario_escogido;            
            enfocar = true;
        }

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
    
    
    
    





