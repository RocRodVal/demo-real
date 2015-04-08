<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Parte</title>
</head>

<body>
<div id="wrapper">
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title ?></h1>
            	<p>
            		<strong>Número de incidencia:</strong> <?php echo $id_inc_url ?><br />
            		<strong>Número de intervención:</strong> #<?php echo $incidencia['intervencion']; ?><br />
            		<strong>Fecha:</strong> <?php echo date_format(date_create($historico_fecha_comunicada), 'd/m/Y'); ?>
            	</p>
            	
            	<p>
            		<strong>Empresa instaladora:</strong> <br />
            	</p>
            	            	
            	<div class="data_tienda">
            		<p>
            			<strong>SFID:</strong> <?php echo $reference ?><br />
            			<?php echo $commercial ?><br />
                    	<?php echo $address ?><br />
                    	<?php echo $zip ?> -  <?php echo $city ?> (<?php echo $province ?>)<br />
                    	<?php 
                    	if ($phone_pds <>'')
                    	{	
                    	?>
                    	Tel. <?php echo $phone_pds ?>
                    	<?php 
                    	}
                    	?>
                    </p>	
                </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
        	<h3>OBSERVACIONES PARA RESOLUCIÓN</h3>
        	<p>
        		<strong>Mueble:</strong> <?php echo $incidencia['display']['display'] ?><br/>
                <strong>Dispositivo:</strong> <?php echo $incidencia['device']['brand_name']." / ".$incidencia['device']['device'] ?><br />
                <strong>Comentarios para el instalador:</strong><br />
        		<?php echo $incidencia['description_3'] ?>
                <br clear="all" />
                <hr />    
                <strong>Contacto:</strong> <?php echo $incidencia['contacto'].' Tel. '.$incidencia['phone'] ?><br/>
                <strong>Comentario tienda:</strong><br />
                <?php echo $incidencia['description_1'] ?>
        	</p>
        	<h3>MATERIAL ENVIADO PARA RESOLUCIÓN DE INCIDENCIA</h3>
        	<p>
            <?php
		    if (empty($material_dispositivos)) {
		    	echo '<p>No hay dipositivos asociados.</p>';
		    } else {
		    ?>
		    <div class="table-responsive">
		    	<table class="table table-striped table-bordered table-hover" id="table_incidencias_dashboard">
		        	<thead>
		        	<tr>
	             		<th width="60%">Dispositivo</th>
		                <th width="10%">Unidades</th>
		            </tr>
		            </thead>
		            <tbody>
		            <?php
		            foreach ($material_dispositivos as $material_dispositivos_item) {
		            ?>
		            <tr>
		                <td><?php echo $material_dispositivos_item->device ?></td>
		                <td><?php echo $material_dispositivos_item->cantidad ?></td>
		            </tr>
		            <?php
		            }
		            ?>
		            </tbody>
		        </table>
		    </div>
            <?php
            }
		    if (empty($material_alarmas)) {
		    	echo '<p>No hay alarmas asociadas.</p>';
		    } else {
		    ?>
		    <div class="table-responsive">
		    	<table class="table table-striped table-bordered table-hover" id="table_incidencias_dashboard">
		        	<thead>
		        	<tr>
	             		<th width="60%">Alarma</th>
		                <th width="10%">Unidades</th>
		            </tr>
		            </thead>
		            <tbody>
		            <?php
		            foreach ($material_alarmas as $material_alarmas_item) {
		            ?>
		            <tr>
		                <td><?php echo $material_alarmas_item->alarm ?> (<?php echo $material_alarmas_item->code ?>)</td>
		                <td><?php echo $material_alarmas_item->cantidad ?></td>
		            </tr>
		            <?php
		            }
		            ?>
		            </tbody>
		        </table>
		    </div>		    
		    <?php 
		    }
		    ?>
        </div>
    </div>
</div>
</div>
</body>
</html>