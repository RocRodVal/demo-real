CREATE TABLE `historico_io` (
  `id_historico_almacen` int(11) NOT NULL AUTO_INCREMENT,
  `id_devices_almacen` int(11) DEFAULT NULL COMMENT 'Id. del dispositivo del almacén afectado en el histórico por Entrada/Salida',
  `id_device` int(11) DEFAULT NULL COMMENT 'Id. del dispositivo del maestro afectado en el histórico por Entrada/Salida',
  `id_alarm` int(11) DEFAULT NULL COMMENT 'Id. de la alarma  afectada en el histórico por Entrada/Salida',
  `id_client` int(11) DEFAULT NULL COMMENT 'Dueño de la alarma',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de la operación de Entrada o Salida',
  `unidades` int(11) DEFAULT '0' COMMENT 'Unidades de la operación: si el valor es positivo, se trata de una entrada; y por contra, si es negativo se trata de una salida de alarma/s',
  `id_incidencia` int(11) DEFAULT NULL COMMENT 'Se usará cuando el control sea sobre el material asignado en incidencia.',
  `id_intervencion` int(11) DEFAULT NULL COMMENT 'Id. de la intervención correspondiente a la alarma asignada como material a la misma. Dato quizá redundante, pero tal como se monta la relación incidencia-intervención; nos facilitará la búsqueda por uno u otro valor en este histórico.',
  `procesado` tinyint(1) DEFAULT '1' COMMENT 'Se utilizará para marcar las entradas/salidas como definitivas. En el caso de alarmas asignadas para una intervención, además de indicar a qué incidencia pertenecen, hay que marcar como procesado=0, de tal manera que no saldrán en el histórico de mano. Ya que pueden editarse los materiales asignados hasta que la incidencia no pase al siguiente paso; donde ya no se puede editar el material asignado y entonces éstas pasarían a estado procesado=1, ya mostrándose en el histórico.',
  `id_material_incidencia` int(11) DEFAULT NULL COMMENT 'Id. del material asociado a la incidencia ya que no se controla si ya existe la misma al asociarlo a la intervención. Se requiere esto aquí por lo mismo.',
  PRIMARY KEY (`id_historico_almacen`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COMMENT='Tabla que guarda el histórico de Entrada/Salida de alarmas masivas. También de dispositivos y alarmas asignadas a incidencias/intervenciones (sólo Salida).';
