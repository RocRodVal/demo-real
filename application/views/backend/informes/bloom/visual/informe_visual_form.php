		<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
		        </div>
		    </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="row buscador">
                        <form action="<?=site_url($controlador.'/informe_visual');?>" method="post" class="form-inline filtros form-mini">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="id_tipo_visual">Tipo de tienda</label>
                                    <?php
                                        $url_ajax_subtipos = site_url().'informe/subtipos_tienda/';
                                    ?>
                                    <select id="id_tipo_visual" name="id_tipo_visual" class="form-control" onchange="cargar_subselect('id_tipo_visual','id_subtipo_visual',false,'<?=$url_ajax_subtipos?>');">
                                        <option value="">Escoge el tipo...</option>
                                        <?php
                                        foreach($tipos as $tipo)
                                        {
                                            $selected = ($tipo["id"] == $id_tipo_visual) ? ' selected = "selected" ' : '';
                                            echo '<option value="'.$tipo["id"].'" '.$selected.'>'.$tipo["titulo"].'</option>';
                                        }
                                        ?>
                                    </select>

                                </div>

                                <div class="form-group">

                                    <input type="hidden" name="id_subtipo_visual_val" id="id_subtipo_visual_val" value="<?php ?>">
                                    <label for="id_subtipo_visual">Subtipo</label>
                                    <?php
                                         $url_ajax_tipologias = base_url()."informe/tipologias_tienda/";
                                    ?>
                                    <select id="id_subtipo_visual" name="id_subtipo_visual" class="form-control"
                                            onchange="cargar_subselect('id_subtipo_visual','id_tipologia_visual','<?=$id_tipologia_visual?>','<?=$url_ajax_tipologias?>');">
                                            <option value="">Escoge un Tipo de tienda...</option>
                                    </select>


                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="id_segmento_visual_val" id="id_segmento_visual_val" value="<?php ?>">
                                    <label for="id_segmento_visual">Segmento</label>
                                    <select id="id_segmento_visual" name="id_segmento_visual" class="form-control">
                                        <option value="">Escoge el segmento...</option>
                                        <?php
                                        foreach($segmentos as $seg)
                                        {
                                            $selected = ($seg["id"] == $id_segmento_visual) ? ' selected = "selected" ' : '';
                                            echo '<option value="'.$seg["id"].'" '.$selected.'>'.$seg["titulo"].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="id_tipologia_visual_val" id="id_tipologia_visual_val" value="<?php ?>">
                                    <label for="id_tipologia_visual">Tipología</label>

                                        <select id="id_tipologia_visual" name="id_tipologia_visual" class="form-control"
                                            >
                                        <option value="">Escoge Tipo y subtipo de tienda...</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="sfid_visual">SFID</label>
                                    <input id="sfid_visual" name="sfid_visual" value="<?=$sfid_visual?>" placeholder="Cualquiera..." class="form-control">
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="generar_informe" value="si">
                                    <button type="submit" id="submit_button" class="form-control input-sm">Generar</button>
                                </div>

                                <div class="form-group">
                                    <?php   if($generado_visual===TRUE)    {?>
                                        <a href="<?=base_url("admin/informe_visual/reset")?>" class="reiniciar_busqueda"> <i class="glyphicon glyphicon-remove"></i> Resetear informe</a>
                                    <?php } ?>
                                </div>

                            </div>


                        </form>
                    </div>
                </div>
            </div>


            <?php

                echo "  Tipo: $id_tipo_visual <br>
                        Subtipo: $id_subtipo_visual <br>
                        Segmento: $id_segmento_visual <br>
                        Tipologí: $id_tipologia_visual <br>";
            ?>

            <script>
                // Cargar los selects con valor al recargar la pagina.
                $(document).ready(function(){
                    /// TIPO
                    if($("#id_tipo_visual").val()!= ""){
                        cargar_subselect("id_tipo_visual","id_subtipo_visual",'<?=$id_subtipo_visual?>',"<?=$url_ajax_subtipos?>",function(){

                            /// SUBTIPO
                            if($("#id_subtipo_visual").val()!= ""){
                                cargar_subselect("id_subtipo_visual","id_tipologia_visual",'<?=$id_tipologia_visual?>',"<?=$url_ajax_tipologias?>");
                            }

                        });



                    }


                });
            </script>


     <?php /**
         *    Este cierre debe ir cada uno de las vistas-resultado que puede generar este informe </div>
        <!-- /#page-wrapper --> */?>
