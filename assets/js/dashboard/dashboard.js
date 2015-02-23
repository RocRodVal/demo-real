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

});


function createDataTable(){
    $("#table_incidencias_dashboard").dataTable();
}