/**
 * Created by dani on 12/2/15.
 */
$(document).ready(function () {
    pathname = window.location.href;
    controller = pathname.split("/")[5];
    if(controller=="dashboard_pds") {
        $('[data-toggle="tooltip"]').tooltipster({
            contentAsHTML: true,
            position: "top"
        });
    }
});


