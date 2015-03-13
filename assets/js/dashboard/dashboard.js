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
    if(view=="dashboard"){
        avisoChat();
    }

});

function createDataTable(){
    $("#table_incidencias_dashboard").dataTable();
}

function avisoChat(){
    setInterval(function(){
        $(".chat_nuevo").each(function(key,elem){
            if($(elem).hasClass('pulse')){
                $(elem).removeClass('pulse');
            }
            else{
                $(elem).addClass('pulse');
            }
        });
    }, 2000);
}