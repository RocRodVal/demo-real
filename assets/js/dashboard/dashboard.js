/**
 * Created by dani on 12/2/15.
 */
$(document).ready(function () {
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    if(controller=="dashboard") {
        $('[data-toggle="tooltip"]').tooltip();
        createDataTable();
    }
});


function createDataTable(){
    $("#table_incidencias_dashboard").dataTable();
}