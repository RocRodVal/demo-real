/**
 * Created by dani on 9/2/15.
 */
var pathname = window.location.href;
var base_url = pathname.split("intervencion")[0];
var url_intervencion = "intervencion/";
var intervencion_session=null;
var id_intervencion_session=0;

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
            actionsBtn=actionsIntervencion(intervencion.id_intervencion,intervencion.status);
            intervenciones_array.push(new Array(
                "<a onClick='viewIntervencion("+intervencion.id_intervencion+")'>#"+intervencion.id_intervencion+"</a>",
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
        options ={
            onConfirm: cancelIntervencion,
            placement: "left"
        }
        $('[data-toggle="confirmation"]').confirmation(options);
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

function actionsIntervencion(id_intervencion,status){
    disableResolve = '';
    disableDocu = '';
    disableRemove = '';
    switch (status){
        case 'Nueva': disableResolve='disabled';break;
        case 'Comunicada': disableDocu='disabled';break;
        case 'Cerrada': disableResolve='disabled';disableDocu='disabled';break;
        case 'Cancelada':disableResolve='disabled';disableDocu='disabled';disableRemove='disabled';break;
    }
    btnResolve="<button class='btn btn-success' "+disableResolve+" onClick='cerrarIntervencion("+id_intervencion+");'><i class='fa fa-check-square-o'></i></button>";
    btnDocu="<button class='btn btn-default' "+disableDocu+" onClick='generateDoc("+id_intervencion+");'><i class='fa fa-files-o'></i></button>";
    btnDelete="<button class='delete btn btn-danger' "+disableRemove+ "data-toggle='confirmation' onClick='setIntervencionSession("+id_intervencion+");'>" +
                "<i class='fa fa-trash'></i></button>"
    return btnResolve+btnDocu+btnDelete
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

function setIntervencionSession(intervencion){
    id_intervencion_session=intervencion;
}

function cancelIntervencion(){
    $.ajax({
        type:"POST",
        url:base_url+url_intervencion+"cancelIntervencion",
        data:"intervencion_id="+id_intervencion_session
    }).done(function (msg) {
        json=JSON.parse(msg);
        if(json==true)
            refreshTablaIncidencias();
        else
            alert("Se ha producido un error");
        $('.delete').confirmation('hide');

    }).error(function (msg) {

    });
}

function generateDoc(intervencion_id){
    id_intervencion_session=intervencion_id;
    $.ajax({
        type:"POST",
        url:base_url+url_intervencion+"generateDocIntervencion",
        data:"intervencion_id="+id_intervencion_session
    }).done(function (msg) {
        json=JSON.parse(msg);
        if(json==true)
            refreshTablaIncidencias();
        else
            alert("Se ha producido un error");
    }).error(function (msg) {

    });
}

function cerrarIntervencion(intervencion_id){
    id_intervencion_session=intervencion_id;
    $.ajax({
        type:"POST",
        url:base_url+url_intervencion+"cerrarIntervencion",
        data:"intervencion_id="+id_intervencion_session
    }).done(function (msg) {
        json=JSON.parse(msg);
        if(json==true)
            refreshTablaIncidencias();
        else
            alert("Se ha producido un error");
    }).error(function (msg) {

    });
}

function refreshTablaIncidencias() {
    getIntervenciones();
}