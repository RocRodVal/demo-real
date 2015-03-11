/**
 * Created by dani on 9/2/15.
 */
var pathname = window.location.href;
var base_url = pathname.split("intervencion")[0];
var url_intervencion = "intervencion/";
var intervencion_session = null;
var id_intervencion_session = 0;

$(document).ready(function () {

    pathname = window.location.href;
    controller = pathname.split("/")[4];
    if(controller=="intervencion") {
        initComponents();
        initData();
    }
});

function initComponents() {
    $("#btn_nueva_intervencion").click(function () {
        $("#modal_nueva_intervencion").modal();
    });

}

function initData() {
    getIntervenciones();
}

function getIntervenciones() {
    $.ajax({
        type: "POST",
        url: base_url + url_intervencion + "listar_intervenciones"
    }).done(function (msg) {
        json = JSON.parse(msg);
        console.log(json);
        intervenciones_array = new Array();
        $.each(json, function (key, intervencion) {
            actionsBtn = actionsIntervencion(intervencion.id_intervencion, intervencion.status);
            intervenciones_array.push(new Array(
                "<a onClick='viewIntervencion(" + intervencion.id_intervencion + ")'>#" + intervencion.id_intervencion + "</a>",
                intervencion.fecha,
                intervencion.pds.reference,
                intervencion.pds.address,
                intervencion.operador.contact,
                intervencion.incidencias.length,
                intervencion.status,
                actionsBtn
            ));

        });
        $('#table_intervenciones').dataTable({
            "data": intervenciones_array,
            "autoWidth": false,
            "bProcessing": true,
            "bDestroy": true
        });
        options = {
            onConfirm: cancelIntervencion,
            placement: "left"
        }

        $('[data-toggle="confirmation"]').confirmation(options);
        $('[data-toggle="tooltip"]').tooltip();
    }).error(function (msg) {

    });
}

function viewIntervencion(intervencion_id) {
    $.ajax({
        type: "POST",
        data: "intervencion_id=" + intervencion_id,
        url: base_url + url_intervencion + "getInfoIntervercion"
    }).done(function (msg) {
        intervencion = JSON.parse(msg);
        intervencion_session = intervencion;
        incidencias_array = new Array();
        $.each(intervencion.incidencias, function (key, incidencia) {
            actionsBtn = actionsIncidencia(incidencia.id_incidencia);
            incidencias_array.push(new Array(
                "<a onClick='viewIncidencia(" + incidencia.id_incidencia + ")'>#" + incidencia.id_incidencia + "</a>",
                incidencia.fecha,
                incidencia.pds.reference,
                incidencia.pds.address,
                incidencia.status,
                actionsBtn
            ));

        });
        $('#table_incidencias').dataTable({
            "data": incidencias_array,
            "autoWidth": false,
            "bProcessing": true,
            "bDestroy": true
        });
        $('[data-toggle="tooltip"]').tooltip();

        //añadimos los datos de la intervencion
        $("#ver_intervencion_id").html("#"+intervencion_id);
        $("#fecha_ver_intervencion").html(intervencion.fecha);
        $("#status_ver_intervencion").html(intervencion.status);
        $("#description_ver_intervencion").html(intervencion.description);
        //añadimos los datos del contacto-
        $("#nombre_contacto_ver_intervencion").html(intervencion.operador.contact);
        $("#telefono_contacto_ver_intervencion").html(intervencion.operador.phone);
        $("#email_contacto_ver_intervencion").html(intervencion.operador.email);
        //si el estado no es nueva, le deshabilitamos el botón de añadir incidencias
        $("#bnt_add_incidencia_to_intervencion").attr('disabled',intervencion.status!='Nueva');

        //mostramos el modal con la información obtenida
        $("#modal_ver_intervencion").modal();

    }).error(function (msg) {

    });
}

function actionsIntervencion(id_intervencion, status) {
    disableResolve = '';
    disableDocu = '';
    disableRemove = '';
    switch (status) {
        case 'Nueva':
            disableResolve = 'disabled';
            break;
        case 'Comunicada':
            disableDocu = 'disabled';
            break;
        case 'Cerrada':
            disableResolve = 'disabled';
            disableDocu = 'disabled';
            break;
        case 'Cancelada':
            disableResolve = 'disabled';
            disableDocu = 'disabled';
            disableRemove = 'disabled';
            break;
    }
    btnResolve = "<button data-toggle='tooltip' title='Resolver intervención' class='btn btnTable btn-success' " + disableResolve + " onClick='cerrarIntervencion(" + id_intervencion + ");'><i class='fa fa-check-square-o'></i></button>";
    btnDocu = "<button data-toggle='tooltip' title='Imprimir documentación' class='btn btnTable btn-default' " + disableDocu + " onClick='generateDoc(" + id_intervencion + ");'><i class='fa fa-files-o'></i></button>";
    btnDelete = "<button title='Cancelar intervención' class='delete btn btnTable btn-danger' " + disableRemove + "data-toggle='confirmation' onClick='setIntervencionSession(" + id_intervencion + ");'>" +
    "<i class='fa fa-trash'></i></button>"
    return btnResolve + btnDocu + btnDelete
}

function actionsIncidencia(id_incidencia) {
    btnDelete = "<div data-toggle='tooltip' title='Eliminar de la intervención' class='btn btn-danger btnTable' onClick='deleteIncidenciaIntervencion(" + id_incidencia + ");'><i class='fa fa-trash'></i></div>";
    return btnDelete
}

function viewIncidencia(incidencia_id) {
    intervencion = intervencion_session;
    $.each(intervencion.incidencias, function (key, incidencia) {
        if (incidencia.id_incidencia == incidencia_id) {
            showDataIncidencia(incidencia);
            return;
        }
    });
}

function showDataIncidencia(incidencia) {
    console.log(incidencia);
    $("#id_incidencia").html("#"+incidencia.id_incidencia);
    $("#fecha_alta_incidencia").html(incidencia.fecha);
    $("#estado_incidencia").html(incidencia.status);
    $("#comentario_incidencia").html(incidencia.description);
    $("#mueble_incidencia").html(incidencia.display);
    $("#telefono_incidencia").html(incidencia.device);
    $("#modal_ver_incidencia").modal();
}

function setIntervencionSession(intervencion) {
    id_intervencion_session = intervencion;
}

function deleteIncidenciaIntervencion(id_incidencia) {
    $.ajax({
        type: "POST",
        url: base_url + url_intervencion + "deleteIncidenciaIntervencion",
        data: "incidencia_id=" + id_incidencia + "&intervencion_id=" + id_intervencion_session
    }).done(function (msg) {
        json = JSON.parse(msg);
        if (json == true)
            refreshTablaIncidencias();
        else
            alert("Se ha producido un error");
        $('.delete').confirmation('hide');

    }).error(function (msg) {

    });
}

function cancelIntervencion() {
    $.ajax({
        type: "POST",
        url: base_url + url_intervencion + "cancelIntervencion",
        data: "intervencion_id=" + id_intervencion_session
    }).done(function (msg) {
        json = JSON.parse(msg);
        if (json == true)
            refreshTablaIncidencias();
        else
            alert("Se ha producido un error");
        $('.delete').confirmation('hide');

    }).error(function (msg) {

    });
}

function generateDoc(intervencion_id) {
    id_intervencion_session = intervencion_id;
    $.ajax({
        type: "POST",
        url: base_url + url_intervencion + "generateDocIntervencion",
        data: "intervencion_id=" + id_intervencion_session
    }).done(function (msg) {
        json = JSON.parse(msg);
        if (json == true)
            refreshTablaIntervencion();
        else
            alert("Se ha producido un error");
    }).error(function (msg) {

    });
}

function cerrarIntervencion(intervencion_id) {
    id_intervencion_session = intervencion_id;
    $.ajax({
        type: "POST",
        url: base_url + url_intervencion + "cerrarIntervencion",
        data: "intervencion_id=" + id_intervencion_session
    }).done(function (msg) {
        json = JSON.parse(msg);
        if (json == true)
            refreshTablaIncidencias();
        else
            alert("Se ha producido un error");
    }).error(function (msg) {

    });
}

function refreshTablaIntervencion() {
    getIntervenciones();
}

function refreshTablaIncidencias() {
    getIntervenciones();
    $.ajax({
        type: "POST",
        data: "intervencion_id=" + intervencion_session.id_intervencion,
        url: base_url + url_intervencion + "getInfoIntervercion"
    }).done(function (msg) {
        intervencion = JSON.parse(msg);
        console.log(intervencion);
        intervencion_session = intervencion;
        incidencias_array = new Array();
        $.each(intervencion.incidencias, function (key, incidencia) {
            actionsBtn = actionsIncidencia(incidencia.id_incidencia);
            incidencias_array.push(new Array(
                "<a onClick='viewIncidencia(" + incidencia.id_incidencia + ")'>#" + incidencia.id_incidencia + "</a>",
                incidencia.fecha,
                incidencia.pds.reference,
                incidencia.pds.address,
                "-------",
                incidencia.status,
                actionsBtn
            ));

        });
        $('#table_incidencias').dataTable({
            "data": incidencias_array,
            "autoWidth": false,
            "bProcessing": true,
            "bDestroy": true
        });
        $('[data-toggle="tooltip"]').tooltip();
    }).error(function (msg) {
    });
}


function showModaladdIncidenciasIntervencion() {
    id_pds = intervencion_session.pds.id_pds;
    $("#select_incidencias_add_intervencion").html('');
    $.ajax({
        type: "POST",
        url: base_url + url_intervencion + "getIncidenciasTiendaSinIntervencion",
        data: "id_pds=" + id_pds
    }).done(function (msg) {
        incidencias = JSON.parse(msg);
        console.log(incidencias);
        $.each(incidencias, function (key, incidencia) {
            $("#select_incidencias_add_intervencion").append(
                '<option value=' + incidencia.id_incidencia + '>#' + incidencia.id_incidencia + '-' +
                incidencia.tipo_averia + '</option>');
        });
        $("#modal_intervencion_add_incidencia").modal();
    }).error();
}

function addIncidenciasIntervencion() {
    incidencias = $("#select_incidencias_add_intervencion").val();
    //Tenemos en un array las incidencias a añadir a la intervención
    if (incidencias.length > 0) {
        jsonString = JSON.stringify(incidencias);
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                incidencias: jsonString,
                id_intervencion: intervencion_session.id_intervencion
                },
            url: base_url + url_intervencion + "addIncidenciasIntervencion"
        }).done(function (msg) {
            if(JSON.parse(msg)==true){
                refreshTablaIncidencias();
            }
            $("#modal_intervencion_add_incidencia").modal('hide');
        }).error();

    }

}