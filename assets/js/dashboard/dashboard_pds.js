/**
 * Created by dani on 12/2/15.
 */
$(document).ready(function () {
    pathname = window.location.href;
    dataURL = pathname.split("/");
    if(dataURL[4]=="tienda" && dataURL[5]=="dashboard") {
        $('[data-toggle="tooltip"]').tooltipster({
            contentAsHTML: true,
            position: "top"
        });
    }
});


