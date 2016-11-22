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
                        <form action="<?=site_url('master/informe_visual');?>" method="post" class="form-inline filtros form-mini">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="tipo_tienda_visual">Tipo de tienda</label>
                                    <select id="tipo_tienda_visual" name="tipo_tienda_visual" class="form-control" onchange="cargar_panelados(this);">
                                        <option value="">Escoge el tipo de tienda...</option>

                                        <?php foreach($tipos_tienda as $tipo)
                                        {
                                            $selected = ($tipo->id_type_pds == $tipo_tienda_visual) ? ' selected = "selected" ' : '';
                                            echo '<option value="'.$tipo->id_type_pds.'" '.$selected.'>'.$tipo->pds.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="panelado_visual_val" id="panelado_visual_val" value="<?=$panelado_visual?>">
                                    <label for="panelado_visual">Panelado</label>
                                    <select id="panelado_visual" name="panelado_visual" class="form-control"><option value="">Escoge un tipo de tienda...</option>

                                    </select>
                                </div>

                                <script>
                                    // Cargar por ajax los panelados vinculados al tipo_tienda escogido.
                                    function cargar_panelados() {

                                        var id_tipo_tienda = $("#tipo_tienda_visual").val();
                                        var id_panelado = $("#panelado_visual_val").val();
                                        if (id_tipo_tienda != "") {
                                            $.post('<?php echo site_url()?>informe/panelado_tienda/' + id_tipo_tienda+'/'+id_panelado,
                                                {},
                                                function (data) {
                                                    $('#panelado_visual').html(data);
                                                });
                                        }
                                    }
                                    $(document).ready(function(){

                                        if($("#tipo_tienda_visual").val()!= ""){ cargar_panelados();  }
                                    });

                                </script>


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
                                        <a href="<?=base_url("master/informe_visual/reset")?>" class="reiniciar_busqueda form-control input-sm"> Resetear informe</a>
                                    <?php } ?>
                                </div>

                            </div>


                        </form>
                    </div>
                </div>
            </div>





     <?php /**
         *    Este cierre debe ir cada uno de las vistas-resultado que puede generar este informe </div>
        <!-- /#page-wrapper --> */?>
