		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?> <font color="red">[Beta]</font></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Antes de proceder al añadido automático de muebles y dispositivos se ha tener creado el PdS en la zona de maestros con todos los datos y panelado correcto.</p>
                    <p>En este periodo de pruebas se deben apuntar todas las altas de SFID y hacérmelas llegar por email.</p>
                    <p>&nbsp;</p>
                </div>
            </div>            
            <div class="row">
                <div class="col-lg-6">
                	<form action="<?=site_url('admin/apertura_pdv');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <label>SFID</label>
                            <input class="form-control" placeholder="SFID" name="sfid" id="sfid">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php if(isset($alta_sfid) && !empty($alta_sfid)) { ?>
                <div class="row">
                    <div class="col-lg-12">
                        <p>&nbsp;</p>
                        <p class="message success"><i class="glyphicon glyphicon-ok"></i> Se han añadido automáticamente, muebles y dispositivos para el PdS <?=$alta_sfid?></p>
                        <p class="message"><a href="<?=site_url('admin/get_inventarios_sfid/'.$alta_sfid.'/alta')?>"><i class="fa fa-file-pdf-o"></i> Descargar informe de alta</a></p>
                        <p>&nbsp;</p>
                    </div>
                </div>
            <? } ?>

            <?php 
            if (isset($_POST['sfid']))
            {	
            ?>
            <div class="row">
                <div class="col-lg-12">
 					<?php
                    if(empty($tiendas)){
                    	echo '<p>No hay resultados para esa cadena de búsqueda.</p>';
                    }
                    else
                    {					
 					?>
 					<div class="panel panel-default">
                        <div class="panel-body">
                        	<div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Ref.</th>
                                            <th>SFID</th>
                                            <th>Tipo</th>
                                            <th>Panelado</th>
                                            <th>Nombre comercial</th>
                                            <th>Zona</th>
                                            <th>Operaciones</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($tiendas as $tienda)
    									{
    									?>
    									<form action="<?=site_url('admin/update_apertura_pdv');?>" method="post" class="form-inline form-sfid">
    									<input type="hidden" name="id_pds" id="id_pds" value="<?php echo $tienda->id_pds ?>">
    									<input type="hidden" name="reference" id="reference" value="<?php echo $tienda->reference ?>">
    									<tr>
    										<td><?php echo $tienda->id_pds ?></td>
    										<td><?php echo $tienda->reference ?></a></td>
    										<td><?php echo $tienda->pds ?></td>
    										<td><?php echo $tienda->panelado ?></td>
    										<td><?php echo $tienda->commercial ?></td>
    										<td><?php echo $tienda->territory ?></td>
    										<td><button type="submit" class="btn btn-default">Añadir muebles y dispositivos</button></td>
    									</tr>
    									</form>
					    				<?php
					    				}
					    				?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php 
					}
                    ?>                    
            	</div>        
            </div>
            <?php 
            }
            ?>                
        </div>
        <!-- /#page-wrapper -->

        <?php $this->load->view('backend/intervenciones/ver_intervencion');?>

