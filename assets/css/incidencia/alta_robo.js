/**
 * Created by dani on 12/2/15.
 */
$(document).ready(function() {
    $("#test-upload").fileinput({
        'showPreview' : false,
        'allowedFileExtensions' : ['jpg', 'png','gif'],
        'elErrorContainer': '#errorBlock'
    });
    /*
     $("#test-upload").on('fileloaded', function(event, file, previewId, index) {
     alert('i = ' + index + ', id = ' + previewId + ', file = ' + file.name);
     });
     */
});