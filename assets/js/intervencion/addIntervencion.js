/**
 * Created by dani on 10/2/15.
 */
var pathname = window.location.href;
var base_url = pathname.split("admin")[0];
var url_intervencion = "intervencion/";
id_pds_session = null;
id_incidencia_session = null;

function showModalNewIntervencion(id_pds, id_incidencia) {
    $("#nueva_intervencion_description").html('');
    base_url = pathname.split("admin")[0];
    url_intervencion = "intervencion/";
    id_pds_session = id_pds;
    id_incidencia_session = id_incidencia;
    $("#nueva_intervencion_select_intervencion").html("<option value='0'>Selecciona una intervencion</option>");
    $("#nueva_intervencion_select_operador").html("<option value='0'>Selecciona un operador</option>")
    $.ajax({
        type: "POST",
        data: "id_pds=" + id_pds,
        url: base_url + url_intervencion + "getIntervencionesPDS"
    }).done(function (msg) {
        json = JSON.parse(msg);
        console.log(json);
        if (json.length>0) {
            $.each(json, function (key, intervencion) {
                $("#nueva_intervencion_select_intervencion").append(
                    '<option value="' + intervencion.id_intervencion + '">#' +
                    intervencion.id_intervencion + ' - ' + intervencion.operador.contact +
                    ' - ' + intervencion.fecha + '</option>');
            });
        }else {
            $("#nueva_intervencion_select_intervencion").attr('disabled',1);
        }
        $("#nueva_intervencion_select_intervencion").change(disableNewIntervencion);
        $.ajax({
            type: "POST",
            url: base_url + url_intervencion + "getOperadoresIntervencion"
        }).done(function (msg) {
            operadores = JSON.parse(msg);
            console.log(operadores);
            $.each(operadores, function (key, operador) {
                $("#nueva_intervencion_select_operador").append(
                    '<option value="' + operador.id_contact + '">' + operador.contact + '</option>');
            });
            $("#modal_nueva_intervencion").modal();
        }).error(function (msg) {
        });

    }).error(function (msg) {
    });

}

function disableNewIntervencion() {
    value_condition = $("#nueva_intervencion_select_intervencion").val() != 0;
    $("#nueva_intervencion_select_operador").attr('disabled', value_condition);
    $("#nueva_intervencion_description").attr('disabled', value_condition);
    if (value_condition) {
        $("#nueva_intervencion_select_operador").val(0);
    }


}

function saveIntervencion() {
    if ($("#nueva_intervencion_select_intervencion").val() != 0) {
        //si la incidencia se a??ade a una ya existente
        intervencion_id = $("#nueva_intervencion_select_intervencion").val();
        $.ajax({
            type: "POST",
            data: "intervencion_id=" + intervencion_id + "&incidencia_id=" + id_incidencia_session,
            url: base_url + url_intervencion + "addIncidenciaToIntervencion"
        }).done(function (msg) {
            json = JSON.parse(msg);
            if (json == true) {
                $("#modal_nueva_intervencion").modal('hide');
            }
            else if (json == false) {
            }
            else {
                alert(json);
            }
            setInterval(function(){
                location.reload();
            },800);

        }).error(function (msg) {
        });
    }
    else {
        //si es una nueva intervencion
        operador_id=$("#nueva_intervencion_select_operador").val();
        if(operador_id==0){
            alert('Seleccione un operador');
            return;
        }
        description=$("#nueva_intervencion_description").val();
        $.ajax({
            type: "POST",
            data:   "operador_id=" + operador_id +
                    "&incidencia_id=" + id_incidencia_session+
                    "&pds_id="+id_pds_session+
                    "&description="+description,
            url: base_url + url_intervencion + "createIntervencion"
        }).done(function (msg) {
            json = JSON.parse(msg);
            if (json == true) {
                $("#modal_nueva_intervencion").modal('hide');
            }
            else if (json == false) {
                alert(json);
            }
            else {
                alert(json);
            }
            setInterval(function(){
                location.reload();
            },800);
        }).error(function (msg) {
        });

    }
}

function showModalViewIntervencion(id_intervencion){
    var base_url = pathname.split("admin")[0];
    $.ajax({
        type: "POST",
        data: "intervencion_id=" + id_intervencion,
        url: base_url + url_intervencion + "getInfoIntervercion"
    }).done(function (msg) {
        intervencion = JSON.parse(msg);
        console.log(intervencion);
        intervencion_session = intervencion;
        incidencias_array = new Array();
        $.each(intervencion.incidencias, function (key, incidencia) {
            btnDelete = "<div class='btn btn-danger' disabled onClick='deleteIncidenciaIntervencion(" + incidencia.id_incidencia + ");'><i class='fa fa-trash'></i></div>";
            incidencias_array.push(new Array(
                "<a onClick='viewIncidencia_(" + incidencia.id_incidencia + ")'>" + incidencia.id_incidencia + "</a>",
                incidencia.fecha,
                incidencia.pds.reference,
                incidencia.pds.commercial,
                incidencia.status//,
                //btnDelete
            ));

        });
        $('#table_incidencias').dataTable({
            "data": incidencias_array,
            "autoWidth": false,
            "bProcessing": true,
            "bDestroy": true
        });
        //a??adimos los datos de la intervencion
        var contacto = intervencion.operador.contact;
        if (contacto.length>25) {
            contacto = contacto.substr(0,20)+"....";
        }
        $("#fecha_ver_intervencion").html(intervencion.fecha);
        $("#status_ver_intervencion").html(intervencion.status);
        $("#description_ver_intervencion").html(intervencion.description);
        //a??adimos los datos del contacto-
        $("#nombre_contacto_ver_intervencion").html(contacto);
        $("#telefono_contacto_ver_intervencion").html(intervencion.operador.phone);
        $("#email_contacto_ver_intervencion").html(intervencion.operador.email);
        //si el estado no es nueva, le deshabilitamos el bot??n de a??adir incidencias
        $("#bnt_add_incidencia_to_intervencion").attr('disabled',true);
        $("#modal_ver_intervencion").modal();
    }).error(function (msg) {
    });
}

function viewIncidencia_(incidencia_id) {
    intervencion = intervencion_session;
    $.each(intervencion.incidencias, function (key, incidencia) {
        if (incidencia.id_incidencia == incidencia_id) {
            showDataIncidencia_(incidencia);
            return;
        }
    });
}

function showDataIncidencia_(incidencia) {
    console.log(incidencia);
    $("#id_incidencia").html("#"+incidencia.id_incidencia);
    $("#fecha_alta_incidencia").html(incidencia.fecha);
    $("#estado_incidencia").html(incidencia.status);
    $("#comentario_incidencia").html(incidencia.description);
    $("#mueble_incidencia").html(incidencia.display);
    $("#telefono_incidencia").html(incidencia.device);
    $("#modal_ver_incidencia_").modal();
}

