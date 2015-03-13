/**
 * Created by dani on 13/3/15.
 */
$(document).ready(function(){
   init();
});

function init(){
    $("#foto").fileinput({
        showCaption: false,
        browseIcon: '<i class="fa fa-camera"></i>',
        browseLabel: '',
        showRemove:false,
        previewFileType: "image"

    });
}