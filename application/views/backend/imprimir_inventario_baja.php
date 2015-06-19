<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Inventario de dispositivos (Baja)</title>
	<style>
		body {font: 10px; font-family: 'Helvetica',arial, sans-serif}
		h1 {font: 16px;}
		h2 {font: 14px;}
		h3 {font: 12px;}
        table{ width:100%; font-family: 'Helvetica',arial, sans-serif; }
        table th{ background:#dc291e; color:#fff;text-align: center; padding:5px; border:none; border-collapse:collapse; }
        table td{ padding:5px; }
    </style>
</head>
<body>
<table width="100%">
<tr>
	<td width="20%"><img src="<?=site_url('assets/images/logo-focus_white.png')?>" width="200" height="24" /></td>
    <td width="80%" align="right"><h1><?=$title?></h1></td>
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
<h3 style="font-family: 'Helvetica'">INVENTARIO DE DISPOSITIVOS (BAJA)</h3>
<?=$content?>
</body>
</html>