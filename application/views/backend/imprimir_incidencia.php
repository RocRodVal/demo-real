<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Parte intervención</title>
	<style>
		body {font: 10px;}
		h1 {font: 16px;}
		h2 {font: 14px;}
		h3 {font: 12px;}
	</style>
</head>

<body>

<table width="100%">
<tr>
	<td width="20%"><img src="<?=site_url('assets/images/logo-focus_white.png')?>" width="200" height="24" /></td>
	<td width="80%" align="right">
		<h1 style="font-family: 'Helvetica'"><?php echo $title ?></h1>
		<p style="font-family: 'Helvetica'">
			<strong>Número de intervención:</strong> <?php echo $incidencia['intervencion']; ?><br />
			<strong>Número de incidencia:</strong> <?php echo $id_inc_url ?><br />            		
			<strong>Fecha:</strong> <?php echo date_format(date_create($historico_fecha_comunicada), 'd/m/Y'); ?>
		</p>
		<!--
		<p style="font-family: 'Helvetica'">
			<strong>Empresa instaladora</strong> <br />
		</p>
		-->
	</td>
</tr>
</table>
   	            	
<p style="font-family: 'Helvetica'">
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

<h3 style="font-family: 'Helvetica'">OBSERVACIONES PARA RESOLUCIÓN</h3>
<p><strong>Nota para los ténicos:</strong> acudid a la intervención con <strong>teclado</strong> y <strong>ratón</strong>, a fin de poder solucionar ciertas incidencias que requieren el uso de los mismos.</p>

<p style="font-family: 'Helvetica'">
<strong>Mueble:</strong> <?php echo $incidencia['display']['display'] ?><br/>
<strong>Dispositivo:</strong> <?php echo $incidencia['device']['brand_name']." / ".$incidencia['device']['device'] ?><br />
<strong>Comentarios para el instalador:</strong><br />
<?php echo $incidencia['description_3'] ?>
</p>

<hr />    

<p style="font-family: 'Helvetica'">
<strong>Contacto:</strong> <?php echo $incidencia['contacto'].' Tel. '.$incidencia['phone'] ?><br/>
<strong>Comentario tienda:</strong><br />
<?php echo $incidencia['description_1'] ?>
</p>

<h3 style="font-family: 'Helvetica'">MATERIAL ENVIADO PARA RESOLUCIÓN DE INCIDENCIA</h3>

<?php
if (!empty($material_dispositivos))
{
?>
<table style="font-family: 'Helvetica'" width="1200">
<thead>
<tr>
<th width="150" align="left">Unidades</th>
<th align="left">Dispositivo</th>
</tr>
</thead>
<tbody>
<?php
foreach ($material_dispositivos as $material_dispositivos_item)
{
?>
<tr>
<td align="left"><?php echo $material_dispositivos_item->cantidad ?></td>
<td align="left"><?php echo $material_dispositivos_item->device ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<?php 
}
?>

<?php
if (!empty($material_alarmas))
{
?>
<table style="font-family: 'Helvetica'" width="1200">
<thead>
<tr>
<th width="150" align="left">Unidades</th>
<th align="left">Alarma</th>
</tr>
</thead>
<tbody>
<?php
foreach ($material_alarmas as $material_alarmas_item)
{
?>
<tr>
<td align="left"><?php echo $material_alarmas_item->cantidad ?></td>
<td align="left"><?php echo $material_alarmas_item->alarm ?> (<?php echo $material_alarmas_item->code ?>)</td>		                
</tr>
<?php
}
?>
</tbody>
</table>  
<?php 
}
?>

<hr/>

<h3 style="font-family: 'Helvetica'">MATERIAL A DEVOLVER UNA VEZ RESUELTO</h3>

<p style="font-family: 'Helvetica'">
(Sólo sistema de seguridad en buen estado o terminales funcionen o no). Indicar con X lo que se devuelve.
</p>
<?php
if (!empty($material_dispositivos))
{
?>
<table style="font-family: 'Helvetica'" width="100%">
<thead>
<tr>
<th width="5%" align="left">Check</th>
<th width="5%" align="left">Unidades</th>
<th width="40%" align="left">Dispositivo</th>
<th width="5%" align="left">Check</th>
<th width="5%" align="left">Unidades</th>
<th width="40%" align="left">Dispositivo</th>
</tr>
</thead>
<tbody>
<?php
foreach ($material_dispositivos as $material_dispositivos_item)
{
?>
<tr>
<td align="left">[___]</td>
<td align="left"><?php echo $material_dispositivos_item->cantidad ?></td>
<td align="left"><?php echo $material_dispositivos_item->device ?></td>
<td align="left">[___]</td>
<td align="left">____</td>
<td align="left">____________________________________</td>	      
</tr>
<?php
}
?>
</tbody>
</table>
<?php 
}
?>

<br clear="all" />

<?php
if (!empty($material_alarmas))
{
?>
<table style="font-family: 'Helvetica'" width="100%">
<thead>
<tr>
<th width="5%" align="left">Check</th>
<th width="5%" align="left">Unidades</th>
<th width="40%" align="left">Alarma</th>
<th width="5%" align="left">Check</th>
<th width="5%" align="left">Unidades</th>
<th width="40%" align="left">Alarma</th>
</tr>
</thead>
<tbody>
<?php
foreach ($material_alarmas as $material_alarmas_item)
{
?>
<tr>
<td align="left">[___]</td>
<td align="left"><?php echo $material_alarmas_item->cantidad ?></td>
<td align="left"><?php echo $material_alarmas_item->alarm ?> (<?php echo $material_alarmas_item->code ?>)</td>
<td align="left">[___]</td>
<td align="left">____</td>
<td align="left">____________________________________</td>	                
</tr>
<?php
}
?>
</tbody>
</table>  
<?php 
}
?>

<br clear="all" />

<h3 style="font-family: 'Helvetica'">CHECKLIST A REALIZAR EN TIENDA</h3>

<p style="font-family: 'Helvetica'">
_____ Resuelta incidencia número <?php echo $id_inc_url ?><br />
_____ Revisadas que las pantallas funcionan correctamente. En caso de que no sea así ponerse en contacto con el teléfono __________
</p>

<br clear="all" />
<br />  
<br />
<br />
<br />
<br />

<table style="font-family: 'Helvetica'" width="100%">
<tr>
<td width="50%" align="left">
FDO. Instalador<br />
<strong>Fecha instalación:</strong> ___ / ___ / ___ (DD/MM/AA)
</td>
<td width="50%" align="left">FDO. Cliente (Nombre, firma y sello)</td>
</tr>
</table>    

<p style="font-family: 'Helvetica', font: 12px;">
<em>* En caso de ser necesarias observaciones indicar por la parte posterior.</em>
</p>


</body>
</html>