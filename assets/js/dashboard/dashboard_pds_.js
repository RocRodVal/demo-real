/**
 * Created by dani on 12/2/15.
 */
$(document).ready(function(){
    // Radialize the colors
    Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    });
    initDonut();

});


function initDonut(){
    // Build the chart
    $('#donut_pds').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Estado de tus incidencias'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Número',
            data: [
                ['Nuevas',   45],
                ['Rechazdas',       26],
                {
                    name: 'Visita prevista',
                    y: 12,
                    sliced: true,
                    selected: true
                },
                ['Resueltas',    10]
            ]
        }]
    });
}