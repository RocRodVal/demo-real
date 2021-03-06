<?php
/**
 * Created by PhpStorm.
 * User: dani
 * Date: 12/3/15
 * Time: 15:58
 */
?>
<style type="text/css">
    .zoom{
        /* Aumentamos la anchura y altura durante 2 segundos */
        transition: width 2s, height 2s, transform 2s;
        -moz-transition: width 2s, height 2s, -moz-transform 2s;
        -webkit-transition: width 2s, height 2s, -webkit-transform 2s;
        -o-transition: width 2s, height 2s,-o-transform 2s;

    }
    .zoom:active{
        position: relative;
        z-index: 1;
        /* tranformamos el elemento al pasar el mouse por encima al doble de
        su tamaño con scale(2). */
        transform : scale(3,2);
        -moz-transform : scale(3,2); /* Firefox */
        -webkit-transform : scale(3,2); /* Chrome - Safari */
        -o-transform : scale(3,2); /* Opera */
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <h2>Chat offline</h2>
            <?php
            if (empty($chats)) {
                echo '<p>No tiene mensajes en el chat.</p>';
            } else {
            ?>
            <div class="chat_offline">
                <?php
                foreach ($chats as $chat) {
                    ?>
                    <div class="row chat">
                        <?php if ($chat->agent === 'altabox') {
                            ?>
                            <div class="col-lg-12">
                                <div class="media">
                                    <!--
                                    <div class="media-left">
                                            <img class="media-object" data-src="holder.js/64x64" alt="SAT"
                                                 src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PGRlZnMvPjxyZWN0IHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjEzLjQ2MDkzNzUiIHk9IjMyIiBzdHlsZT0iZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQ7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+NjR4NjQ8L3RleHQ+PC9nPjwvc3ZnPg=="
                                                 data-holder-rendered="true" style="width: 64px; height: 64px;">
                                    </div>
                                    -->
                                    <div class="media-body">
                                        <div class="col-lg-9  sat">
                                            <p style="padding-top:10px;">
                                                <?php echo $chat->texto; ?>
                                            </p>

                                            <?php
                                            if ($chat->foto <> '') {
                                                ?>
                                                <p><img class="zoom"  src="<?= site_url('uploads/chats/' . $chat->foto) ?>" width="60%" /></p>
                                            <?php
                                            }
                                            ?>
                                            <small class="date">
                                                <?php echo $chat->fecha; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else {
                            ?>
                            <div class="col-lg-12">
                                <div class="media">
                                    <!--
                                    <div class="media-right">
                                            <img class="media-object" data-src="holder.js/64x64" alt="64x64"
                                                 src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PGRlZnMvPjxyZWN0IHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjEzLjQ2MDkzNzUiIHk9IjMyIiBzdHlsZT0iZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQ7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+NjR4NjQ8L3RleHQ+PC9nPjwvc3ZnPg=="
                                                 data-holder-rendered="true" style="width: 64px; height: 64px;">
                                    </div>
                                    -->
                                    <div class="media-body">
                                        <div class="col-lg-9  sfid">
                                            <p style="padding-top:10px;">
                                                <?php echo $chat->texto; ?>
                                            </p>

                                            <?php
                                            if ($chat->foto <> '') {
                                                ?>
                                                <p><img class="zoom"  src="<?= site_url('uploads/chats/' . $chat->foto) ?>" width="60%"/></p>
                                            <?php
                                            }
                                            ?>
                                            <small class="date">
                                                <?php echo $chat->fecha; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php
                }
                ?> </div> <?php
            }
            ?>
        </div>
        <form action="<?= site_url('admin/insert_chat/'.$id_pds_url.'/'.$id_inc_url) ?>" method="post"
              class="content_auto form_login" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-10">
                    <div class="form-group">
                        <div class="row">
                            <!--<label>Añadir comentarios</label>-->
                            <div class="col-lg-11">
                                <textarea class="form-control" rows="1" name="texto_chat" id="texto_chat"></textarea>
                            </div>
                                <input id="foto" type="file" multiple=false name="userfile" accept=".gif,.jpg,.png,.jpeg">
                        </div>
                    </div>
                    <!--
                    <div class="form-group">
                       <label>Adjuntar imagen
                            <small>(JPG, PNG, GIF)</small>
                        </label>
                        <input id="foto" type="file" multiple=false name="userfile">
                    </div>
                    -->
                </div>
                <section id="chat">
                <div class="col-lg-2">
                    <div class="form-group">
                        <input type="submit" value="Enviar" name="submit" class="btn btn-success enviar"/>
                    </div>
                </div>
                </section>
            </div>
		</form>            
    </div>
</div>
<script src="<?php echo base_url(); ?>assets/js/incidencia/view_incidencia.js" type="text/javascript"></script>