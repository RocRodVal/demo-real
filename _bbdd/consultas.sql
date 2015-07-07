/*
Incoming
*/

SELECT displays_pds.* 
FROM `displays_pds` 
JOIN pds ON displays_pds.id_pds = pds.id_pds 
WHERE pds.reference IN (XXX,YYY);

SELECT devices_pds.* 
FROM `devices_pds` 
JOIN pds ON devices_pds.id_pds = pds.id_pds 
WHERE pds.reference IN (XXX,YYY);

/*
Muebles por PdS
*/
INSERT INTO displays_pds (client_type_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status)
SELECT pds.client_pds, pds.type_pds, pds.id_pds, pds.panelado_pds, displays_panelado.id_display, displays_panelado.position, '', 'Alta'
FROM pds, displays_panelado
WHERE pds.panelado_pds=displays_panelado.id_panelado
AND pds.reference = SFID;


/*
Insertar un mueble en una posición
*/
INSERT INTO displays_pds (client_type_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status)
SELECT pds.clien
t_pds, pds.type_pds, pds.id_pds, pds.panelado_pds, XX, PP, '', 'Alta'
FROM pds
WHERE pds.reference IN (XXX,YYY);


/*
Borrar un mueble
*/
DELETE displays_pds FROM displays_pds
INNER JOIN pds ON pds.id_pds = displays_pds.id_pds
JOIN display ON display.id_display = displays_pds.id_display
WHERE pds.reference IN (XXX,YYY)
AND displays_pds.id_display IN (ZZ,WW);


/*
Devices por PdS
*/
INSERT INTO devices_pds (client_type_pds,id_pds,id_displays_pds,id_display,position,id_device,IMEI,mac,serial,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status)
SELECT pds.client_pds,pds.id_pds,displays_pds.id_displays_pds,displays_pds.id_display,devices_display.position,devices_display.id_device,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Alta'
FROM pds,displays_pds,devices_display
WHERE pds.id_pds=displays_pds.id_pds
AND displays_pds.id_display=devices_display.id_display
AND pds.reference = SFID;


/*
Insertar dispositivos en un mueble
*/
INSERT INTO devices_pds (client_type_pds,id_pds,id_displays_pds,id_display,position,id_device,IMEI,mac,serial,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status)
SELECT pds.client_pds,pds.id_pds,displays_pds.id_displays_pds,displays_pds.id_display,devices_display.position,devices_display.id_device,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Alta'
FROM pds
JOIN displays_pds ON displays_pds.id_pds = pds.id_pds
JOIN devices_display ON devices_display.id_display = displays_pds.id_display
WHERE ((pds.reference IN (XXX,YYY)) AND (displays_pds.id_display = XX));


/*
Borrar dispositivos en un mueble
*/
DELETE FROM devices_pds
WHERE devices_pds.id_displays_pds IN ( 
	SELECT displays_pds.id_displays_pds FROM displays_pds
	INNER JOIN pds ON pds.id_pds = displays_pds.id_pds
	JOIN display ON display.id_display = displays_pds.id_display
	WHERE pds.reference IN (XXX,YYY)
	AND displays_pds.id_display IN (ZZ,WW)
	ORDER BY pds.reference
);


/*
Update masiva en tienda
*/
UPDATE devices_pds
JOIN pds ON pds.id_pds = devices_pds.id_pds
SET alta= now(), id_device = XXX, devices_pds.status = "Alta"
WHERE
	(
    pds.reference IN (XXX,YYY) AND 
    ((devices_pds.id_display = XX) AND (devices_pds.id_device = XX) AND (devices_pds.position = XX)));

	/* UPDATE MASIVA EN TIENDAS, CON CAMPOS UNICOS **/
	
	/*
	*	1º
	*/
	
	/* Sacar primero los terminales en esas posiciones de mueble a actualizar, que tienen incidencia; cruzando con Incidencias para
	enviar los datos a Almacén y las resuelvan antes de continuar: */
	SELECT 
		pds.id_pds,	pds.reference,	id_display as Mueble,	alta as Fecha_Alta,	position,	id_device as Dispositivo,
		IMEI,	devices_pds.status,	incidencias.id_incidencia,	incidencias.status,	incidencias.status_pds as Status_SAT
	FROM devices_pds,pds, incidencias
	WHERE pds.id_pds = devices_pds.id_pds  
		ANd incidencias.id_devices_pds = devices_pds.id_devices_pds 
		AND id_display= _ID_DISPLAY_ 
		AND position = _POS_
		AND  devices_pds.status = 'Incidencia' 
		AND incidencias.status NOT IN('Cerrada','Cancelada','Resuelta');
	
	/*
	*	2º 
	*/
	/* Obtener los id_pds a actualizar, ya que el SFID no identifica unívocamente a un punto de venta. Consultar al historico: */
	--SELECT id_pds FROM pds WHERE reference IN('19993587','46000063','46000065','46000067','46000078','46000079','47000011','49050007','56000008','56000978','36000012','36000017','36000932','56000109','56000138','46000111','47000012','26000003','26000035','26000045','26000049','26000070','26000080','26000089','26000091','26000093','26000096','26000103','26000110','26000797','26360002','27000001','27000024','27000026','27000027','29090000','29993433','26000079','26000102','56000210','56000020','56000021','56000054','59991792','36000007','36000054','46000052','49447052','57140000','59442753','59440001','16000060','26000808','29990074','56000049','76000041','56000083','56000106','26000074','56000024','36000029','36000060','37000000','26000033','66000008','66000009','66000010','66000014','67000000','66000025','36000031','36000976','26000047','36000043','16000054','16000055','16000156','16000079','16000082','16000088','16000094','16000108','16000117','16000128','16000134','16690002','16690007','16690009','17000017','17000020','17000021','17010000','19440103','19990072','19990157','19992621','16000116','16000158','16000175','16000037','56000023','57120000','59445420','59449719','56000209','46000077','47000010','49990032','46000076','28250727','36000016','36000018','36000051','16000126','77000007','19446560','56000082','59080302','59447942','26000044','29440124','66000024','66000022','66000026','26000686','16000044','46000106','46000158','46000026','46000034','46000042','46000047','46000080','46000203','47000000','47000008','16000121','16000040','19448193','76000040','77000000','79440062','16000053','26000088','26000106','27000000');
	SELECT id_pds FROM pds WHERE reference IN('SFID1','SFID2'....);
	
	/*
	*	3º
	*	Obtener el terminal y estado que había en la posicióna actualizar
	*/

	SELECT d_pds.id_devices_pds, pds.reference as sfid, d_pds.id_pds, display.display,d_pds.id_display,device.device,d_pds.id_device, d_pds.alta, d_pds.position, d_pds.status as StatusPDS 
	FROM devices_pds as d_pds
		INNeR JOIN pds ON d_pds.id_pds = pds.id_pds
		INNER JOIN device ON device.id_device = d_pds.id_device
		INNER JOIN display ON display.id_display = d_pds.id_display
		WHERE d_pds.id_pds IN (	SELECT id_pds FROM pds WHERE reference IN('SFID1','SFID2'....))
		AND d_pds.id_display = _ID_DISPLAY_
		AND position =_POS_
	
	
	
	SELECT pds.id_pds,reference,sfid.sfid_old, sfid.sfid_new FROM pds 
LEFT JOIN historico_sfid sfid ON pds.id_pds = sfid.id_pds
WHERE reference IN('16000037','16000040','16000044','16000053','16000054','16000055','16000060','16000066','16000079','16000082','16000088','16000094','16000108','16000116','16000117','16000121','16000126','16000128','16000134','16000158','16000175','16690002','16690007','16690009','17000017','17000020','17000021','17010000','19440103','19446560','19448193','19990072','19990157','19992621','19993587','26000003','26000033','26000035','26000044','26000045','26000047','26000049','26000070','26000074','26000079','26000080','26000088','26000089','26000091','26000093','26000096','26000102','26000103','26000106','26000110','26000686','26000797','26000808','26360002','27000000','27000001','27000024','27000026','27000027','28250727','29090000','29440124','29990074','29993433','36000007','36000012','36000016','36000017','36000018','36000029','36000031','36000043','36000051','36000054','36000060','36000932','36000976','37000000','46000021','46000024','46000026','46000034','46000042','46000047','46000052','46000054','46000063','46000065','46000067','46000076','46000077','46000078','46000079','46000080','46000090','47000000','47000008','47000010','47000011','47000012','49050007','49447052','49990032','56000008','56000020','56000021','56000023','56000024','56000049','56000054','56000082','56000083','56000086','56000106','56000109','56000138','56000978','57120000','57140000','59080302','59440001','59442753','59445420','59447942','59449719','59990075','59991792','66000004','66000008','66000009','66000010','66000014','66000022','67000000','69990010','69990031','76000040','76000041','77000000','77000007','79440062')
OR pds.id_pds IN(
	SELECT sfid.id_pds
	FROM historico_sfid sfid
	INNER JOIN devices_pds ON sfid.id_pds = devices_pds.id_pds 
	WHERE id_display= 32 AND position = 3
			AND sfid.sfid_old IN('16000037','16000040','16000044','16000053','16000054','16000055','16000060','16000066','16000079','16000082','16000088','16000094','16000108','16000116','16000117','16000121','16000126','16000128','16000134','16000158','16000175','16690002','16690007','16690009','17000017','17000020','17000021','17010000','19440103','19446560','19448193','19990072','19990157','19992621','19993587','26000003','26000033','26000035','26000044','26000045','26000047','26000049','26000070','26000074','26000079','26000080','26000088','26000089','26000091','26000093','26000096','26000102','26000103','26000106','26000110','26000686','26000797','26000808','26360002','27000000','27000001','27000024','27000026','27000027','28250727','29090000','29440124','29990074','29993433','36000007','36000012','36000016','36000017','36000018','36000029','36000031','36000043','36000051','36000054','36000060','36000932','36000976','37000000','46000021','46000024','46000026','46000034','46000042','46000047','46000052','46000054','46000063','46000065','46000067','46000076','46000077','46000078','46000079','46000080','46000090','47000000','47000008','47000010','47000011','47000012','49050007','49447052','49990032','56000008','56000020','56000021','56000023','56000024','56000049','56000054','56000082','56000083','56000086','56000106','56000109','56000138','56000978','57120000','57140000','59080302','59440001','59442753','59445420','59447942','59449719','59990075','59991792','66000004','66000008','66000009','66000010','66000014','66000022','67000000','69990010','69990031','76000040','76000041','77000000','77000007','79440062')
			AND sfid.sfid_new NOT IN ('16000037','16000040','16000044','16000053','16000054','16000055','16000060','16000066','16000079','16000082','16000088','16000094','16000108','16000116','16000117','16000121','16000126','16000128','16000134','16000158','16000175','16690002','16690007','16690009','17000017','17000020','17000021','17010000','19440103','19446560','19448193','19990072','19990157','19992621','19993587','26000003','26000033','26000035','26000044','26000045','26000047','26000049','26000070','26000074','26000079','26000080','26000088','26000089','26000091','26000093','26000096','26000102','26000103','26000106','26000110','26000686','26000797','26000808','26360002','27000000','27000001','27000024','27000026','27000027','28250727','29090000','29440124','29990074','29993433','36000007','36000012','36000016','36000017','36000018','36000029','36000031','36000043','36000051','36000054','36000060','36000932','36000976','37000000','46000021','46000024','46000026','46000034','46000042','46000047','46000052','46000054','46000063','46000065','46000067','46000076','46000077','46000078','46000079','46000080','46000090','47000000','47000008','47000010','47000011','47000012','49050007','49447052','49990032','56000008','56000020','56000021','56000023','56000024','56000049','56000054','56000082','56000083','56000086','56000106','56000109','56000138','56000978','57120000','57140000','59080302','59440001','59442753','59445420','59447942','59449719','59990075','59991792','66000004','66000008','66000009','66000010','66000014','66000022','67000000','69990010','69990031','76000040','76000041','77000000','77000007','79440062')
);
	
		
	/* Sacar la info de los terminales "viejos" que están en la posición del mueble XX en la que vamos a ubicar el nuevo terminal Y*/
	/*Con el ID de mueble y posición podemos sacar qué hay actualmente en esa ubicación para todas las tiendas que tengan el mueble */
	SELECT * FROM devices_pds WHERE id_display= _ID_DISPLAY_ AND position = _POS_
	
	
	/* Sacar id_pds del SFID  -Opcional- **/ 
	SELECT id_pds FROM pds WHERE reference LIKE 'X-SFID-X';
	
	/* Sacar el ID del mueble genérico */
	SELECT id_display FROM display  WHERE display LIKE 'Y-NOMBRE-Y';	
	/* Sacar el ID del terminal genérico */
	SELECT id_device FROM device WHERE device LIKE 'Y-NOMBRE-Y';
	
	
	

    
/*
Borrado muebles tienda
*/
DELETE displays_pds FROM displays_pds
INNER JOIN pds ON pds.id_pds = displays_pds.id_pds
WHERE pds.reference IN (XXX,YYY);


/*
Borrado dispositivos tienda
*/
DELETE devices_pds FROM devices_pds
INNER JOIN pds ON pds.id_pds = devices_pds.id_pds
WHERE pds.reference IN (XXX,YYY);
    

/*
Alta dispositivos en almacén
*/
INSERT INTO `devices_almacen`
(`id_device`,`alta`,`IMEI`,`mac`,`serial`,`barcode`,
`id_color_device`,`id_complement_device`,`id_status_device`,`id_status_packaging_device`,
`picture_url_1`,`picture_url_2`,`picture_url_3`,
`description`,`owner`,`status`)
VALUES 
(XXX,now(),NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,"En stock"),
(XXX,now(),NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,"En stock"),
(XXX,now(),NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,"En stock"),
(XXX,now(),NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,"En stock"),
(XXX,now(),NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,"En stock");


/*
Baja dispositivos en almacén
*/
UPDATE `devices_almacen` 
SET `status`= "Enviado" WHERE (`status` = "En stock" AND `id_device` = XXX) LIMIT XXX;


/*
Consulta SFIDs en grupo
*/
SELECT pds.id_pds, pds.reference, type_pds.pds, panelado.panelado, pds.commercial, pds.address, pds.zip, pds.city 
FROM pds
JOIN type_pds ON pds.type_pds = type_pds.id_type_pds
JOIN panelado ON pds.panelado_pds = panelado.id_panelado
WHERE reference IN (XXX,YYY);


/*
Facturación
*/
SELECT facturacion.fecha, pds.reference AS SFID, type_pds.pds, COUNT(facturacion.id_incidencia) AS incidencias, contact.contact AS instalador, SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros
FROM facturacion
JOIN pds ON facturacion.id_pds = pds.id_pds
JOIN type_pds ON pds.type_pds = type_pds.id_type_pds
JOIN displays_pds ON facturacion.id_displays_pds = displays_pds.id_displays_pds
JOIN display ON displays_pds.id_display = display.id_display
LEFT JOIN intervenciones ON facturacion.id_intervencion = intervenciones.id_intervencion
LEFT JOIN contact ON intervenciones.id_operador = contact.id_contact
GROUP BY facturacion.id_intervencion
ORDER BY facturacion.fecha ASC;


/*
Reserva dispositivos desde lista materiales
*/
UPDATE devices_almacen
JOIN material_incidencias ON material_incidencias.id_devices_almacen = devices_almacen.id_devices_almacen
JOIN incidencias ON material_incidencias.id_incidencia = incidencias.id_incidencia
SET devices_almacen.status = 4
WHERE incidencias.status = 'Resuelta';


/*
Incidencias
*/
SELECT incidencias.*, pds.reference, device.device, display.display
FROM incidencias
JOIN device ON incidencias.id_devices_pds = device.id_device
JOIN display ON incidencias.id_displays_pds = display.id_display
JOIN pds ON incidencias.id_pds = pds.id_pds;


/*
Export incidencias
*/
SELECT 
	incidencias.id_incidencia,
	incidencias.fecha,
	pds.reference AS SFID,
	display.display AS mueble,
	device.device AS dispositivo,
	incidencias.tipo_averia,
	incidencias.fail_device,
	incidencias.alarm_display,
	incidencias.alarm_device,
	incidencias.alarm_garra,
	incidencias.description_1,
	incidencias.description_2,
	incidencias.parte_pdf,
	incidencias.denuncia,
	incidencias.foto_url,
	incidencias.foto_url_2,
	incidencias.foto_url_3,
	incidencias.contacto,
	incidencias.phone,
	incidencias.email,
	incidencias.id_operador,
	incidencias.intervencion,
	incidencias.status
FROM incidencias
JOIN pds ON incidencias.id_pds = pds.id_pds
JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
JOIN display ON displays_pds.id_display = display.id_display
LEFT JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
LEFT JOIN device ON devices_pds.id_device = device.id_device;


/*
Buscar duplicados
*/
SELECT reference, COUNT(*) as count
FROM pds
GROUP BY reference
HAVING COUNT(*) > 1;


/*
Seleccionar dispositivos incidencia
*/
SELECT material_incidencias.*, devices_almacen.serial, device.device
FROM material_incidencias
JOIN devices_almacen ON devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen
JOIN device ON devices_almacen.id_device = device.id_device;


/*
Seleccionar alarmas incidencia
*/
SELECT material_incidencias.*, brand_alarm.brand, alarm.alarm
FROM material_incidencias
JOIN alarm ON alarm.id_alarm = material_incidencias.id_alarm
JOIN brand_alarm ON alarm.brand_alarm = brand_alarm.id_brand_alarm;


/*
Seleccionar dispositivos almacen por incidencia
*/
SELECT material_incidencias.*, devices_almacen.serial, device.device
FROM material_incidencias
JOIN devices_almacen ON devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen
JOIN device ON devices_almacen.id_device = device.id_device
WHERE id_incidencia = INCIDENCIA;


/*
Seleccionar material incidencias
*/
SELECT material_incidencias.fecha, material_incidencias.id_incidencia, brand_alarm.brand, alarm.alarm, device.device, devices_almacen.serial
FROM material_incidencias
LEFT JOIN devices_almacen ON devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen
LEFT JOIN device ON devices_almacen.id_device = device.id_device
LEFT JOIN alarm ON alarm.id_alarm = material_incidencias.id_alarm
LEFT JOIN brand_alarm ON alarm.brand_alarm = brand_alarm.id_brand_alarm;


/*
Export incidencias para volcado CSV
*/
SELECT 
	incidencias.id_incidencia AS Incidencia,
	incidencias.fecha AS Fecha,
	pds.reference AS Referencia,
    pds.commercial AS "Nombre comercial",
    pds.address AS Dirección,
    pds.zip AS CP,
    pds.city AS Ciudad,
    province.province AS Provincia,
	display.display AS Mueble,
	device.device AS Dispositivo,
	incidencias.tipo_averia AS Tipo,
	incidencias.fail_device AS "Fallo dispositivo",
	incidencias.alarm_display "Alarma mueble",
	incidencias.alarm_device "Alarma dispositivo",
	incidencias.alarm_garra "Sistema de alarma",
	incidencias.description_1 AS "Comentarios",
	incidencias.description_2 AS "Comentarios SAT",
    incidencias.description_3 AS "Comentarios Instalador",
	incidencias.contacto,
	incidencias.phone AS "Teléfono",
    incidencias.status_pds AS "Estado tienda",
	incidencias.status AS "Estado SAT"
FROM incidencias
JOIN pds ON incidencias.id_pds = pds.id_pds
JOIN province ON pds.province = province.id_province
LEFT JOIN county ON pds.county = county.id_county
LEFT JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
JOIN display ON displays_pds.id_display = display.id_display
LEFT JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
LEFT JOIN device ON devices_pds.id_device = device.id_device;


/*
Inventario cruzado
*/
SELECT device.id_device, brand_device.brand, device.device,
	(
		SELECT COUNT(*)
		FROM devices_pds
        WHERE (devices_pds.id_device = device.id_device) AND
		(devices_pds.status = 'Alta')
    ) AS unidades_pds,
	(SELECT  COUNT(*)
		FROM devices_almacen
		WHERE (devices_almacen.id_device = device.id_device) AND
		(devices_almacen.status = 'En stock')
	) AS unidades_almacen
FROM device
JOIN brand_device ON device.brand_device = brand_device.id_brand_device;


/*
Proceso borrado PdV
*/
DELETE FROM `agent` WHERE `sfid` IN (XXX,YYY);


DELETE displays_pds FROM displays_pds
INNER JOIN pds ON pds.id_pds = displays_pds.id_pds
WHERE pds.reference IN (XXX,YYY);


DELETE devices_pds FROM devices_pds
INNER JOIN pds ON pds.id_pds = devices_pds.id_pds
WHERE pds.reference IN (XXX,YYY);


DELETE FROM `pds` WHERE `reference` IN (XXX,YYY);


/*
Proceso cambio panelado PdV
*/
UPDATE pds
SET type_pds = XX, panelado_pds = YY
WHERE ((type_pds = JJ) AND (panelado_pds = ZZ));


DELETE displays_pds FROM displays_pds
INNER JOIN pds ON pds.id_pds = displays_pds.id_pds
WHERE pds.reference IN (XXX,YYY);


DELETE devices_pds FROM devices_pds
INNER JOIN pds ON pds.id_pds = devices_pds.id_pds
WHERE pds.reference IN (XXX,YYY);


INSERT INTO displays_pds (client_type_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status)
SELECT pds.client_pds, pds.type_pds, pds.id_pds, pds.panelado_pds, displays_panelado.id_display, displays_panelado.position, '', 'Alta'
FROM pds, displays_panelado
WHERE pds.panelado_pds=displays_panelado.id_panelado
AND pds.reference IN (XXX,YYY);


INSERT INTO devices_pds (client_type_pds,id_pds,id_displays_pds,id_display,position,id_device,IMEI,mac,serial,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status)
SELECT pds.client_pds,pds.id_pds,displays_pds.id_displays_pds,displays_pds.id_display,devices_display.position,devices_display.id_device,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Alta'
FROM pds,displays_pds,devices_display
WHERE pds.id_pds=displays_pds.id_pds
AND displays_pds.id_display=devices_display.id_display
AND pds.reference IN (XXX,YYY);


/*
Trazabilidad dispositivo
*/

SELECT 
	devices_almacen.id_devices_almacen, 
	device.device, 
	devices_almacen.alta, 
	devices_almacen.IMEI, 
	devices_almacen.mac, 
	devices_almacen.serial, 
	devices_almacen.barcode, 
	devices_almacen.status
FROM orange.devices_almacen
JOIN device ON device.id_device = devices_almacen.id_device
WHERE devices_almacen.id_device = XX
ORDER BY devices_almacen.alta;


SELECT 
	material_incidencias.id_devices_almacen, 
	material_incidencias.cantidad,
	device.device,
	material_incidencias.fecha,
	material_incidencias.id_incidencia, 
	devices_almacen.IMEI,
	devices_almacen.mac,
	devices_almacen.serial,
	devices_almacen.barcode,
	devices_almacen.status 
FROM material_incidencias
JOIN devices_almacen ON devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen
JOIN device ON device.id_device = devices_almacen.id_device
WHERE device.id_device = XX;


/*
Varios acceso master
*/
SELECT
	YEAR(fecha) AS Year, 
	MONTH(fecha) AS Mes, 
	COUNT(*) AS Incidencias, 
	(
		SELECT
			COUNT(*) 
			FROM incidencias
			WHERE 
			(
				(status_pds = 'Finalizada' OR status_pds = 'Cancelada')
				AND (YEAR(fecha) = Year AND MONTH(fecha) = Mes)
			)
	) AS Cerradas
FROM incidencias
GROUP BY 
	YEAR(fecha),
	MONTH(fecha);

/*
Filtro 72 h. v1 
*/


SELECT
	YEAR(incidencias.fecha) AS Year, 
	MONTH(incidencias.fecha) AS Mes, 
	COUNT(*) AS Incidencias,
    (
		SELECT
			COUNT(*) 
			FROM historico
			WHERE
			(
			((historico.status_pds = 'Cancelada' AND historico.status = 'Cancelada') OR
			(historico.status_pds = 'Finalizada' AND historico.status = 'Resuelta')) AND
            (DATE_ADD(incidencias.fecha, INTERVAL 96 HOUR) >= historico.fecha) AND
            (YEAR(historico.fecha) = Year AND MONTH(historico.fecha) = Mes)
			)
    ) AS "- 72 h.",  
    (
		SELECT
			COUNT(*) 
			FROM historico
			WHERE
			(
			((historico.status_pds = 'Cancelada' AND historico.status = 'Cancelada') OR
			(historico.status_pds = 'Finalizada' AND historico.status = 'Resuelta')) AND
            (DATE_ADD(incidencias.fecha, INTERVAL 96 HOUR) <= historico.fecha) AND
            (YEAR(historico.fecha) = Year AND MONTH(historico.fecha) = Mes)
			)
    ) AS "+ 72 h.",           
	(
		SELECT
			COUNT(*) 
			FROM incidencias
			WHERE 
			(
				(incidencias.status_pds = 'Finalizada' OR incidencias.status_pds = 'Cancelada') AND
				(YEAR(incidencias.fecha) = Year AND MONTH(incidencias.fecha) = Mes)
			)
	) AS Cerradas
FROM incidencias
GROUP BY 
	YEAR(incidencias.fecha),
	MONTH(incidencias.fecha);
    
/*
Filtro 72 h. v2 
*/    

SELECT
	YEAR(incidencias.fecha) AS Year, 
	MONTH(incidencias.fecha) AS Mes, 
	COUNT(*) AS Incidencias,
    (
		SELECT
			COUNT(*) 
			FROM historico
			WHERE
			(
				(historico.status_pds = 'Finalizada' AND historico.status = 'Resuelta') AND
				(
					DATE_ADD(
					(
						SELECT 
							historico.fecha
							FROM historico
							WHERE
                            ((historico.status = 'Revisada') AND
							(historico.id_incidencia = incidencias.id_incidencia))
					), INTERVAL 72 HOUR) <= historico.fecha) AND
					(YEAR(historico.fecha) = Year AND MONTH(historico.fecha) = Mes)
			)
	) AS "PKI",           
	(
		SELECT
			COUNT(*) 
			FROM incidencias
			WHERE 
			(
				(incidencias.status = 'Cerrada') AND
				(YEAR(incidencias.fecha) = Year AND MONTH(incidencias.fecha) = Mes)
			)
	) AS Cerradas
FROM incidencias
WHERE incidencias.status != 'Cancelada'
GROUP BY 
	YEAR(incidencias.fecha),
	MONTH(incidencias.fecha);
    
/*
Filtro 72 h. v3 
*/    

    
### CREAR VISTA (BORRAR PRIMERO SI YA EXISTE, PARA ACTUALIZARLA) CON LOS DATOS QUE NECESITAN CÁLCULO DE TAL MANERA QUE EN LA 
###  QUERY PRINCIPAL No REQUIERA CALCULO.

DROP VIEW IF EXISTS view_historico_incidencia_revisada;
CREATE VIEW view_historico_incidencia_revisada AS 
(
	SELECT  historico.id_historico, DATE_ADD( historico.fecha, INTERVAL 72 HOUR) as fecha_inc, historico.fecha as fecha
	FROM historico, incidencias
	WHERE
    (
		(historico.status = 'Revisada') AND
		(historico.id_incidencia = incidencias.id_incidencia)
	)
);
    


SELECT	YEAR(incidencias.fecha) AS Year,  MONTH(incidencias.fecha) AS Mes, 	COUNT(id_incidencia) AS Incidencias,
	(
		SELECT COUNT(id_historico) FROM historico
		WHERE
			(
				(	(historico.status_pds = 'Cancelada' AND historico.status = 'Cancelada') OR (historico.status_pds = 'Finalizada' AND historico.status = 'Resuelta') ) 
                AND	(historico.id_historico IN ( SELECT 	id_historico FROM view_historico_incidencia_revisada WHERE fecha >= fecha_inc) 
                AND (YEAR(historico.fecha) = Year AND MONTH(historico.fecha) = Mes)
			)
	)
) as '<72h',           
	(
		SELECT
			COUNT(id_incidencia) 
			FROM incidencias
			WHERE 
			(
				(incidencias.status_pds = 'Finalizada' OR incidencias.status_pds = 'Cancelada') AND
				(YEAR(incidencias.fecha) = Year AND MONTH(incidencias.fecha) = Mes)
			)
	) AS Cerradas
    
FROM incidencias GROUP BY Year,	Mes;    