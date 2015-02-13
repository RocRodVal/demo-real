/**
 * Created by dani on 12/2/15.
 */
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    createDataTable();
});


function createDataTable(){
    $("#table_incidencias_dashboard").dataTable();
}