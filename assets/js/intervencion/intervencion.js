/**
 * Created by dani on 9/2/15.
 */
var pathname = window.location.href;
var base_url = pathname.split("intervencion")[0];
var url_intervencion = "intervencion/";
var intervencion_session=null;

$(document).ready(function () {
    initComponents();
    initData();
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
        json=JSON.parse(msg);
        console.log(json);
        intervenciones_array = new Array();
        $.each(json,function(key,intervencion){
            actionsBtn=actionsIntervencion(intervencion.id_intervencion);
            intervenciones_array.push(new Array(
                "<a onClick='viewIntervencion("+intervencion.id_intervencion+")'>#"+intervencion.id_intervencion+"</a>",
                intervencion.fecha,
                intervencion.pds.reference,
                intervencion.pds.address,
                intervencion.operador.contact,
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

    }).error(function (msg) {

    });
}

function viewIntervencion(intervencion_id){
     $.ajax({
         type:"POST",
         data:"intervencion_id="+intervencion_id,
         url: base_url+url_intervencion+"getInfoIntervercion"
     }).done(function (msg) {
         intervencion = JSON.parse(msg);
         intervencion_session=intervencion;
         incidencias_array = new Array();
         $.each(intervencion.incidencias,function(key,incidencia){
             actionsBtn=actionsIncidencia(incidencia.id_incidencia);
             incidencias_array.push(new Array(
                 "<a onClick='viewIncidencia("+incidencia.id_incidencia+")'>#"+incidencia.id_incidencia+"</a>",
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
         //añadimos los datos de la intervencion
         $("#fecha_ver_intervencion").html(intervencion.fecha);
         $("#status_ver_intervencion").html(intervencion.status);
         $("#description_ver_intervencion").html(intervencion.description);
         //añadimos los datos del contacto-
         $("#nombre_contacto_ver_intervencion").html(intervencion.operador.contact);
         $("#telefono_contacto_ver_intervencion").html(intervencion.operador.phone);
         $("#email_contacto_ver_intervencion").html(intervencion.operador.email);
         //mostramos el modal con la información obtenida
         $("#modal_ver_intervencion").modal();
     }).error(function (msg) {

     });
}

function actionsIntervencion(id_intervencion){
    btnResolve="<div class='btn btn-success'><i class='fa fa-check-square-o'></i></div>";
    btnDelete="<div class='btn btn-danger'><i class='fa fa-trash'></i></div>";
    return btnResolve+btnDelete
}

function actionsIncidencia(id_incidencia){
    btnVer="<div class='btn btn-default'><i class='fa fa-eye'></i></div>";
    btnEdit="<div class='btn btn-success'><i class='fa fa-pencil-square-o'></i></div>";
    btnDelete="<div class='btn btn-danger'><i class='fa fa-trash'></i></div>";
    return btnVer+btnEdit+btnDelete
}

function viewIncidencia(incidencia_id){
    intervencion=intervencion_session;
    $.each(intervencion.incidencias,function(key,incidencia){
       if(incidencia.id_incidencia==incidencia_id){
           showDataIncidencia(incidencia);
           return;
       }
    });
}

function showDataIncidencia(incidencia){
    console.log(incidencia);
    $("#modal_ver_incidencia").modal();
}

function refreshTablaIncidencias() {

}