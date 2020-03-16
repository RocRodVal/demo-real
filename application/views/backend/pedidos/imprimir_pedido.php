<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Albaran pedido</title>
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
	<td width="20%"><img src="<?=site_url('assets/images/logo.png')?>" width="200" height="24" /></td>
	<td width="80%" align="right">
		<h1 style="font-family: 'Helvetica'"><?php echo $title ?></h1>
		<p style="font-family: 'Helvetica'">
			<strong>Número de Pedido:</strong> <?php echo $id_pedido_url ?><br />
			<strong>Fecha:</strong> <?php echo date_format(date_create($pedido->fecha), 'd/m/Y');?><br />

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

<h3 style="font-family: 'Helvetica'">CONTACTO</h3>
<?php /*<p><strong>Nota para los ténicos:</strong> acudid a la intervención con <strong>teclado</strong> y <strong>ratón</strong>, a fin de poder solucionar ciertas incidencias que requieren el uso de los mismos.</p>*/ ?>

<hr />    

<p style="font-family: 'Helvetica'">
<strong>Contacto:</strong> <?php echo $pedido->contacto.' Tel. '.$pedido->phone .' Email. '.$pedido->email ?><br/>

</p>
<br><br><br>
<h3 style="font-family: 'Helvetica'">MATERIAL ENVIADO</h3>
<hr/>
<?php
if (!empty($detallePedido))
{
?>
<table style="font-family: 'Helvetica'" width="1200">
<thead>
<tr>
	<th width="50" align="left">Código</th>
	<th width="150" align="left">Unidades</th>
	<th align="left">Alarma</th>
</tr>
</thead>
<tbody>
<?php
foreach ($detallePedido as $d)
{
?>
<tr>
	<td align="left"><?php echo $d->code ?></td>
	<td align="left"><?php echo $d->cantidad ?></td>
	<td align="left"><?php echo $d->alarm ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<?php 
}
?>

</tbody>
</table>  

<hr/>

</body>
</html>