/**
 * Created by dani on 12/2/15.
 */
$(document).ready(function() {
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    if(controller=="alta_incidencia_robo") {
        $("#test-upload").fileinput({
            'showPreview': false,
            'allowedFileExtensions': ['jpg', 'png', 'gif', 'pdf'],
            'elErrorContainer': '#errorBlock'
        });
    }
    /*
     $("#test-upload").on('fileloaded', function(event, file, previewId, index) {
     alert('i = ' + index + ', id = ' + previewId + ', file = ' + file.name);
     });
     */
});