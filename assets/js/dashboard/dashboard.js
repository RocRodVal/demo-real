/**
 * Created by dani on 12/2/15.
 */
$(document).ready(function () {
    pathname = window.location.href;
    dataURL = pathname.split("/");
    controller = dataURL[5];
    if(controller=="dashboard") {
        $('[data-toggle="tooltip"]').tooltipster({
            contentAsHTML: true
        });
        createDataTable();
    }

});


function createDataTable(){
    $("#table_incidencias_dashboard").dataTable();
}