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