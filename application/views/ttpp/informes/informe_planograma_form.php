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
                <form action="<?=site_url($controlador.'/informe_planogramas');?>" method="post" class="form-inline filtros form-mini">
                <div class="col-sm-12">

                    <div class="form-group">
                        <label for="mueble">Mueble</label>
                        <select id="mueble_plano" name="mueble_plano" class="form-control"><option value="">Cualquiera...</option>

                            <?php foreach($muebles as $display)
                            {
                                $selected = ($display->id_display== $mueble_plano) ? ' selected = "selected" ' : '';
                                echo '<option value="'.$display->id_display.'" '.$selected.'>'.$display->display.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sfid">SFID</label>
                        <input id="sfid_plano" name="sfid_plano" value="<?=$sfid_plano?>" placeholder="Cualquiera..." class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="generar_informe" value="si">
                        <button type="submit" id="submit_button" class="form-control input-sm">Generar</button>
                    </div>

                    <div class="form-group">
                            <?php   if($generado_planograma===TRUE)    {?>
                                <a href="<?=base_url($controlador."/informe_planogramas/reset")?>" class="reiniciar_busqueda form-control input-sm"> Resetear informe</a>
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
