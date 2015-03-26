/*
Muebles por PdS
*/
INSERT INTO displays_pds (client_type_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status)
SELECT pds.client_pds, pds.type_pds, pds.id_pds, pds.panelado_pds, displays_panelado.id_display, displays_panelado.position, '', 'Alta'
FROM pds, displays_panelado
WHERE pds.panelado_pds=displays_panelado.id_panelado;

/*
Devices por PdS
*/
INSERT INTO devices_pds (client_type_pds,id_pds,id_displays_pds,id_display,position,id_device,IMEI,mac,serial,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status)
SELECT pds.client_pds,pds.id_pds,displays_pds.id_displays_pds,displays_pds.id_display,devices_display.position,devices_display.id_device,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Alta'
FROM pds,displays_pds,devices_display
WHERE pds.id_pds=displays_pds.id_pds
AND displays_pds.id_display=devices_display.id_display;

/*
Consulta SFIDs en grupo
*/
SELECT pds.id_pds, pds.reference, type_pds.pds, panelado.panelado, pds.commercial, pds.address, pds.zip, pds.city 
FROM pds
JOIN type_pds ON pds.type_pds = type_pds.id_type_pds
JOIN panelado ON pds.panelado_pds = panelado.id_panelado
WHERE reference IN (19990272,19440334,26360017,36000077,36000072,48810015,49440274,49990159,49440270,49990141,49990153,49990164,49440267,49440134,59440338,59990180,59990179,59990178,59990207,56000152);

/*
Insertar muebles por SFID
*/
INSERT INTO displays_pds (client_type_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status)
SELECT pds.client_pds, pds.type_pds, pds.id_pds, pds.panelado_pds, displays_panelado.id_display, displays_panelado.position, '', 'Alta'
FROM pds, displays_panelado
WHERE pds.panelado_pds=displays_panelado.id_panelado;

/*
Insertar dispositivos por SFID
*/
INSERT INTO devices_pds (client_type_pds,id_pds,id_displays_pds,id_display,position,id_device,IMEI,mac,serial,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status)
SELECT pds.client_pds,pds.id_pds,displays_pds.id_displays_pds,displays_pds.id_display,devices_display.position,devices_display.id_device,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Alta'
FROM pds,displays_pds,devices_display
WHERE pds.id_pds=displays_pds.id_pds
AND displays_pds.id_display=devices_display.id_display;

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
SELECT material_incidencias.*, alarm.alarm
FROM material_incidencias
JOIN alarm ON alarm.id_alarm = material_incidencias.id_alarm;