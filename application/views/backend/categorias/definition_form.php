<?php
// Declaraciones y llamadas
$ci = & get_instance();
$ci->load->model('categoria_model');

$accion = "create";

$editar = $this->result_row;
if(!empty($editar) && !is_null($editar))
{
    $id_tipo = $editar['pds_categoria.id_tipo'];
    $id_subtipo = $editar['pds_categoria.id_subtipo'];
    $id_segmento = $editar['pds_categoria.id_segmento'];
    $id_tipologia = $editar['pds_categoria.id_tipologia'];

    $accion = "edit";
}

$tipos = $ci->categoria_model->get_pds_tipos(); // Todos los tipos


echo $id_tipo;



?>



<h2>Categoría PDS<small> </small>
    <span class="xcrud-toggle-show xcrud-toggle-up"><i class="glyphicon glyphicon-chevron-up"></i></span>
</h2>

<form action="" method="post" id="form_categorias_pds">
    <div class="xcrud-top-actions btn-group">
        <a href="#form_categorias_pds" onclick="insert('<?=base_url('admin/insert_categoria')?>',this,'<?=base_url('admin/categorias_pdv#form_categorias_pds')?>');"  class="btn btn-danger">Guardar</a><a href="javascript:;" data-task="list" class="btn btn-default xcrud-action">Volver</a>
    </div>

    <div class="xcrud-view">
        <div class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-3">Tipo PDS*</label>
                <div class="col-sm-4">


                    <select name="id_tipo" id="id_tipo" onchange="load_select('<?=base_url('admin/categorias_subtipos')?>','id_subtipo',this,'<?=$id_tipo?>')"
                            class="xcrud-input form-control form-control" data-required="1" data-type="select">
                        <option value="">-- Selecciona --</option>
                        <?php  foreach($tipos as $key=>$value)
                        {
                            if($accion == "edit" && $value["id"] == $id_tipo)
                            {
                                echo '<option value="'.$value['id'].'" selected="selected">'.$value['titulo'].'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$value['id'].'">'.$value['titulo'].'</option>';
                            }

                        }
                        ?>
                    </select>
                    <?php
                        if($accion=="edit")
                        {
                            echo '
                                <script>
                                    load_select("'.base_url('admin/categorias_subtipos').'","id_subtipo",$("#id_tipo"),"'.$id_tipo.'");
                                </script>
                            ';
                        }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Subtipo PDS*</label>
                <div class="col-sm-4">
                    <?php // MOSTRAR SUBTIPOS?>
                    <select name="id_subtipo" id="id_subtipo"
                            onchange="load_select('<?=base_url('admin/categorias_tipologias')?>','id_tipologia',this,'<?=$id_subtipo?>')" class="xcrud-input form-control form-control" data-required="1" data-type="select">
                        <option value="">-- Selecciona --</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Segmento PDS*</label>
                <div class="col-sm-4">
                    <?php // MOSTRAR SEGMENTOS
                    $segmentos = $ci->categoria_model->get_segmentos_pds(); ?>

                    <select name="id_segmento" id="id_segmento"  class="xcrud-input form-control form-control" data-required="1" data-type="select">
                        <option value="">-- Selecciona --</option>
                        <?php  foreach($segmentos as $key=>$value)
                        {
                            if($accion == "edit" && $value["id"] == $id_segmento)
                            {
                                echo '<option value="' . $value['id'] . '" selected="selected">' . $value['titulo'] . '</option>';
                            }
                            else
                            {
                                echo '<option value="' . $value['id'] . '">' . $value['titulo'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-sm-3">Tipología PDS*</label>
                <div class="col-sm-4">
                    <?php // MOSTRAR TIPOLOGIAS ?>

                    <select name="id_tipologia" id="id_tipologia"  onchange="load_select('<?=base_url('admin/categorias_tipologias')?>','id_tipologia',this);" class="xcrud-input form-control form-control" data-required="1" data-type="select">
                        <option value="">-- Selecciona --</option>
                    </select>
                    <input type="hidden" name="id_subtipo_tipologia" id="id_subtipo_tipologia" value="">
                </div>
            </div>


           </div>
    </div>


    <p>&nbsp;</p>

</form>