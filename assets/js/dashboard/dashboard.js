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
    $("#table_incidencias_dashboard").dataTable( {
    stateSave: true,
	  "pageLength": 100
	} );
}

function avisoChat(){
    $(".chat_nuevo").each(function(key,elem){
            $(elem).addClass('pulse');
    });
    /*
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
    */
    var contador=0;
    setInterval(function(){
        $(".chat_nuevo").each(function(key,elem){
            if(contador%2!=0){
                $(elem).css('color','#f60');
            }
            else{
                $(elem).css('color','#000');
            }

        });
        contador++;
    }, 750);
}