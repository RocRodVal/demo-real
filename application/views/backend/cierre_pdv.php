		<!-- #page-wrapper -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>Este proceso es irreversible. Se procederá al borrado de los dispositivos, muebles y punto de servicio de la app y se guardará en el histórico de cierres. También se elimina el acceso a la misma.</p>
                    <ul>
                        <li>Antes de realizar la operación se requiere que las incidencias abiertas asociadas al PdV sean cerradas.</li>
                        <li>Puedes exportar el listado de dispositivos de la tienda desde el enlace que se mostrará una vez indiques un SFID.</li>
                    </ul>
                    <p>&nbsp;</p>
                </div>
            </div>            
            <div class="row">
                <div class="col-lg-6">
                	<form action="<?=site_url('admin/cierre_pdv');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <label>SFID</label>
                            <input class="form-control" placeholder="SFID" name="sfid" id="sfid" value="<?=($baja_sfid!='') ? $baja_sfid : ''?>">
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php if(!is_null($error)) {

                        echo (!$error)
                            ? '<p class="message success"><i class="glyphicon glyphicon-error"></i>'
                            : '<p class="message error"><i class="glyphicon glyphicon-error"></i>';


                        switch($error)
                        {
                            case FALSE:
                                echo "El SFID <strong>$baja_sfid</strong> se ha dado de baja correctamente.";
                                break;

                            case "incidencias":
                                echo "Debes cerrar las incidencias abiertas del SFID <strong>$baja_sfid</strong> antes de poder darlo de baja";
                                break;

                            default: break;
                        }

                        echo '</p>';
                    } ?>
                </div>
            </div>
            <?php 
            if (isset($_POST['sfid']))
            {

            ?>

            <?php if(isset($baja_sfid) && !empty($baja_sfid)) { ?>
                <div class="row">
                    <div class="col-lg-12">
                        <p>&nbsp;</p>
                        <p class="message warning"><i class="glyphicon glyphicon-warning-sign"></i> Se va a proceder al borrado de dispositivos, muebles y punto de servicio de la app.</p>
                        <p class="message"><a href="<?=site_url('admin/get_inventarios_sfid/'.$baja_sfid.'/baja/'.$id_pds)?>"><i class="fa fa-file-pdf-o"></i>  Descargar informe de baja</a></p>
                        <p>&nbsp;</p>
                    </div>
                </div>
            <?php } ?>

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
                                            <th>Subtipo</th>
                                            <th>Segmento</th>
                                            <th>Tipología</th>
                                            <th>Nombre comercial</th>
                                            <th>Territorio</th>
                                            <th>Operaciones</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php 
   										foreach($tiendas as $tienda)
    									{
    									?>
    									<form action="<?=site_url('admin/update_cierre_pdv');?>" method="post" class="form-inline form-sfid">
    									<input type="hidden" name="id_pds" id="id_pds" value="<?php echo $tienda->id_pds ?>">
    									<input type="hidden" name="reference" id="reference" value="<?php echo $tienda->reference ?>">
    									<tr>
    										<td><?php echo $tienda->id_pds ?></td>
    										<td><?php echo $tienda->reference ?></a></td>
    										<td><?php echo $tienda->tipo ?></td>
                                            <td><?php echo $tienda->subtipo ?></td>
    										<td><?php echo $tienda->segmento ?></td>
                                            <td><?php echo $tienda->tipologia ?></td>
    										<td><?php echo $tienda->commercial ?></td>
    										<td><?php echo $tienda->territory ?></td>
    										<td><button type="submit" class="btn btn-default">Revisar y cerrar</button></td>
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

