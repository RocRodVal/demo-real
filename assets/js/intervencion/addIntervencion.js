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
        $.each(json, function (key, intervencion) {
            $("#nueva_intervencion_select_intervencion").append(
                '<option value="' + intervencion.id_intervencion + '">#' +
                intervencion.id_intervencion + ' - ' + intervencion.operador.contact +
                ' - ' + intervencion.fecha + '</option>');
        });
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
        //si la incidencia se añade a una ya existente
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
        description=$("#nueva_intervencion_description").html();
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
            }
            else {
                alert(json);
            }
        }).error(function (msg) {
        });
    }

}