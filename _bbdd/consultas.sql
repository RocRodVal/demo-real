/*
Muebles por PdS
*/
INSERT INTO displays_pds (client_type_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status)
SELECT pds.client_pds, pds.type_pds, pds.id_pds, pds.panelado_pds, displays_panelado.id_display, displays_panelado.position, '', 'Alta'
FROM pds, displays_panelado
WHERE pds.panelado_pds=displays_panelado.id_panelado
AND pds.reference = SFID;

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
Consulta SFIDs en grupo
*/
SELECT pds.id_pds, pds.reference, type_pds.pds, panelado.panelado, pds.commercial, pds.address, pds.zip, pds.city 
FROM pds
JOIN type_pds ON pds.type_pds = type_pds.id_type_pds
JOIN panelado ON pds.panelado_pds = panelado.id_panelado
WHERE reference IN (19990272,19440334,26360017,36000077,36000072,48810015,49440274,49990159,49440270,49990141,49990153,49990164,49440267,49440134,59440338,59990180,59990179,59990178,59990207,56000152);

/*
Facturación
*/
SELECT facturacion.fecha, pds.reference AS SFID, type_pds.pds, facturacion.id_intervencion AS visita, display.display, SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros
FROM facturacion
JOIN pds ON facturacion.id_pds = pds.id_pds
JOIN type_pds ON pds.type_pds = type_pds.id_type_pds
JOIN displays_pds ON facturacion.id_displays_pds = displays_pds.id_displays_pds
JOIN display ON displays_pds.id_display = display.id_display
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
Varios acceso master
*/

SELECT 
  YEAR(fecha), 
  MONTH(fecha), 
  COUNT(*)
FROM incidencias
WHERE fail_device = 1
GROUP BY 
  YEAR(fecha),
  MONTH(fecha);

SELECT 
  YEAR(fecha) AS Year, 
  MONTH(fecha) AS Mes, 
  COUNT(*) AS Incidencias,
  (
  SELECT
	COUNT(*) 
	FROM incidencias
	JOIN historico ON incidencias.id_incidencia = historico.id_incidencia
    WHERE 
    (
    (historico.status_pds = 'Finalizada' OR historico.status_pds = 'Cancelada')
    AND (historico.fecha >= ((incidencias.fecha) + INTERVAL 72 HOUR))
    AND YEAR(historico.fecha) = Year
    AND MONTH(historico.fecha) = Mes
    )
  ) AS '+ 72h.',

  (
  SELECT
	COUNT(*) 
	FROM incidencias
	WHERE 
    (
    (status_pds = 'Finalizada' OR status_pds = 'Cancelada')
    AND YEAR(fecha) = Year
    AND MONTH(fecha) = Mes
    )
  ) AS Cerradas
FROM incidencias
GROUP BY 
  YEAR(fecha),
  MONTH(fecha); 