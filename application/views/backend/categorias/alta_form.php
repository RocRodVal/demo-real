<?php
// Declaraciones y llamadas
$ci = & get_instance();
$ci->load->model('categoria_model');

$tipos = $ci->categoria_model->get_pds_tipos(); // Todos los tipos
$tipo = $ci->categoria_model->get_pds_tipo($this->result_row->id_tipo); // Tipo al editar


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
                <label class="control-label col-sm-3">Canal PDS*</label>
                <div class="col-sm-4">


                    <select name="id_tipo" id="id_tipo" onchange="load_select('<?=base_url('admin/categorias_subtipos')?>','id_subtipo',this)"
                            class="xcrud-input form-control form-control" data-required="1" data-type="select">
                        <option value="">-- Selecciona --</option>
                        <?php  foreach($tipos as $key=>$value)
                        {
                            echo '<option value="'.$value['id'].'">'.$value['titulo'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Tipología PDS*</label>
                <div class="col-sm-4">
                    <?php // MOSTRAR SUBTIPOS?>
                    <select name="id_subtipo" id="id_subtipo"
                            onchange="load_select('<?=base_url('admin/categorias_tipologias')?>','id_tipologia',this)" class="xcrud-input form-control form-control" data-required="1" data-type="select">
                        <option value="">-- Selecciona --</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Concepto PDS*</label>
                <div class="col-sm-4">
                    <?php // MOSTRAR SEGMENTOS
                    $segmentos = $ci->categoria_model->get_segmentos_pds(); ?>

                    <select name="id_segmento" id="id_segmento"  class="xcrud-input form-control form-control" data-required="1" data-type="select">
                        <option value="">-- Selecciona --</option>
                        <?php  foreach($segmentos as $key=>$value)
                        {
                            echo '<option value="'.$value['id'].'">'.$value['titulo'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-sm-3">Categorización PDS*</label>
                <div class="col-sm-4">
                    <?php // MOSTRAR TIPOLOGIAS ?>

                    <select name="id_tipologia" id="id_tipologia"  onchange="load_input('<?=base_url('admin/categorias_tipologias')?>','id_subtipo_tipologia',this);" class="xcrud-input form-control form-control" data-required="1" data-type="select">
                        <option value="">-- Selecciona --</option>
                    </select>
                    <input type="hidden" name="id_subtipo_tipologia" id="id_subtipo_tipologia" value="">
                </div>
            </div>


           </div>
    </div>


    <p>&nbsp;</p>

</form>