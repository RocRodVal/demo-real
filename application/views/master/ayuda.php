<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 25/2/15
 * Time: 8:51
 */
?>

<!-- Chang URLs to wherever Video.js files will be hosted -->
<link href="<?= site_url('assets/css/plugins/video/video-js.css') ?>" rel="stylesheet">
<!-- video.js must be in the <head> for older IEs to work. -->
<script src="<?php echo base_url(); ?>assets/js/plugins/video/video.js"></script>

<!-- Unless using the CDN hosted version, update the URL to the Flash SWF -->
<script>
    videojs.options.flash.swf = "<?php echo base_url(); ?>assets/js/plugins/video/video-js.swf";
</script>
<!-- #page-wrapper -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h3>Ver mis incidencias</h3>
            <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" height='300px' width="90%"
                   data-setup="{}">
                <source src="<?php echo base_url(); ?>videos/ver_incidencias.mp4" type='video/mp4'/>
                1
            </video>
        </div>
        <div class="col-lg-6">
            <h3>Crear nueva avería</h3>
            <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" height='300px' width="90%"
                   data-setup="{}">
                <source src="<?php echo base_url(); ?>videos/nueva_averia.mp4" type='video/mp4'/>
            </video>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <h3>Crear nuevo robo</h3>
            <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" height='300px' width="90%"
                   data-setup="{}">
                <source src="<?php echo base_url(); ?>videos/nuevo_robo.mp4" type='video/mp4'/>
            </video>
        </div>
        <div class="col-lg-6">
            <h3>Crear nueva incidencia de mueble</h3>
            <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" height='300px' width="90%"
                   data-setup="{}">
                <source src="<?php echo base_url(); ?>videos/nueva_incidencia_mueble.mp4" type='video/mp4'/>
            </video>
        </div>
    </div>
</div>