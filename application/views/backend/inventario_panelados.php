		<!-- #page-wrapper -->
		<div id="page-wrapper">
		    <div class="row">
		        <div class="col-lg-12">
		            <h1 class="page-header"><?php echo $title ?></h1>
		        </div>
		    </div>
            <div class="row">
                <div class="col-lg-12">
                	<form action="<?=site_url('admin/inventarios_panelados');?>" method="post" class="form-inline form-sfid">
                        <div class="form-group">
                            <select class="form-control" id="id_panelado" name="id_panelado">
					        <option value="">-- Tipo panelado --</option>
					        <?php foreach ($panelados as $panelado): ?>
					        <option value="<?=$panelado->id_panelado ?>"><?=$panelado->panelado_abx ?> (Orange: <?=$panelado->panelado ?>)</option>
					        <?php endforeach ?>
					        </select>
					        <button type="submit" class="btn btn-default">Buscar</button>  					        
                        </div>
                    </form>
                </div>
            </div>
            <?php 
            if (isset($_POST['id_panelado']))
            {	
            ?>		    
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
 					
 					<?php
                    if(empty($displays)){
                    	echo '<p>No tenemos resultados para sus datos.</p>';
                    }
                    else
                    {					
 					?> 
 					<div class="panel panel-default">
						<div class="panel-body">
							<ul>
							<?php
							foreach($displays as $display){
									?>
									<li><?php echo $display->position ?>. <a href="#"><?php echo $display->display ?></a></li>
								<?php
							}
							?>
							</ul>
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
