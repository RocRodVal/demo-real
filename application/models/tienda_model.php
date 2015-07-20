<?php

class Tienda_model extends CI_Model {

	public function __construct()	{
		$this->load->database();
	}

	
	public function get_stock() {
	
		$query = $this->db->select('brand_device.brand, device.device, COUNT(devices_pds.id_device) AS unidades_pds, (ROUND((COUNT(devices_pds.id_device)*0.05))+2) AS stock_necesario,
									(
									SELECT  COUNT(*)
									FROM    devices_almacen
									WHERE (devices_almacen.id_device = devices_pds.id_device) AND (devices_almacen.status = "En stock")
									) AS deposito_almacen,
									(
									SELECT  COUNT(*)
									FROM    devices_almacen
									WHERE (devices_almacen.id_device = devices_pds.id_device) AND (devices_almacen.status = "En stock")
									) -
									(ROUND((COUNT(devices_pds.id_device)*0.05))+2) AS balance')
										->join('device','devices_pds.id_device = device.id_device')
										->join('brand_device','device.brand_device = brand_device.id_brand_device')
										->where('devices_pds.status','Alta')
										->group_by('devices_pds.id_device')
										->order_by('brand_device.brand', 'ASC')
										->order_by('device.device', 'ASC')
										->get('devices_pds');
	
		return $query->result();
	}	
	
	public function alta_dispositivos_almacen_update($data)
	{
		$this->db->insert('devices_almacen',$data);
		$id=$this->db->insert_id();


        // Insertar operación de baja en el histórico
        $elemento = array(
            'id_material_incidencia' => NULL,
            'id_devices_almacen' => NULL,
            'id_devices_almacen_new' => $id,
            'id_alarm' => NULL,
            'id_device'=>$data["id_device"],
            'id_incidencia' => NULL,
            'id_cliente' => NULL,
            'fecha' => $data["alta"],
            'cantidad' => (-1), // En negativo porque luego la función lo multiplica por -1
            'procesado' => 1
        );
        $this->alta_historico_IO($elemento,NULL);

	}	
	
	public function alta_agente($data)
	{
		$this->db->insert('agent',$data);
		$id=$this->db->insert_id();



	}	
	
	
	public function baja_dispositivos_almacen_update($id_device,$owner,$units)
    {


    $contar = $this->db->query("SELECT COUNT(id_device) as contador FROM devices_almacen WHERE id_device=$id_device AND owner='$owner' AND status = 1")->result();


        if(is_array($contar) && count($contar) > 0){
            $contar = $contar[0];
        }



    if($contar->contador > 0) {

        $units = ($units > $contar->contador) ? $contar->contador : $units;

        $this->db->select("id_devices_almacen,id_device");
        $this->db->where("id_device",$id_device);
        $this->db->where('owner', $owner);
        $this->db->where('status', 1);
        $this->db->limit($units);
        $dispositivos_a_borrar = $this->db->get("devices_almacen")->result();
        $total_baja =  $this->db->affected_rows();


        $cont = 0;
        // Recorremos los dispositivos a borrar.
        foreach($dispositivos_a_borrar as $dispositivo_baja){
            $id_device = $dispositivo_baja->id_device;
            $id_devices_almacen = $dispositivo_baja->id_devices_almacen;

            // Borrado lógico del dispositivo.
            $this->db->set('status', 4, FALSE);
            $this->db->where('id_devices_almacen', $id_devices_almacen);
            $this->db->update('devices_almacen');

            // Insertar operación de baja en el histórico
            $data = array(
                'id_material_incidencia' => NULL,
                'id_devices_almacen' => NULL,
                'id_devices_almacen_new' => $id_devices_almacen,
                'id_alarm' => NULL,
                'id_device'=>$id_device,
                'id_incidencia' => NULL,
                'id_cliente' => NULL,
                'fecha' => date('Y-m-d H:i:s'),
                'cantidad' => (1), // En positivo porque luego la función lo pasará a negativo
                'procesado' => 1
            );
            $this->alta_historico_IO($data,NULL);
            $cont++;
        }




        return $total_baja;

    }else{
        return -1;
    }


}



	
	public function borrar_alarmas($id_alarm,$units)
	{
		$this->db->set('units','units - '.$units, FALSE);
		$this->db->where('id_alarm',$id_alarm);
	
		$this->db->update('alarm');
	}	
	
	public function borrar_agente($sfid)
	{
		$this->db->where('sfid', $sfid);
		$this->db->delete('agent');
	}	
	
	public function borrar_dispositivos($sfid)
	{
		$sql = "DELETE devices_pds FROM devices_pds
				INNER JOIN pds ON pds.id_pds = devices_pds.id_pds
				WHERE pds.reference IN ('$sfid')";
		
		$this->db->query($sql);		
	}

	public function borrar_muebles($sfid)
	{
		$sql = "DELETE displays_pds FROM displays_pds
				INNER JOIN pds ON pds.id_pds = displays_pds.id_pds
				WHERE pds.reference IN ('$sfid')";
		
		$this->db->query($sql);	
	}

	public function alta_masiva_dispositivos($sfid)
	{
		$sql = "INSERT INTO devices_pds (client_type_pds,id_pds,id_displays_pds,id_display,position,id_device,IMEI,mac,serial,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status)
				SELECT pds.client_pds,pds.id_pds,displays_pds.id_displays_pds,displays_pds.id_display,devices_display.position,devices_display.id_device,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Alta'
				FROM pds,displays_pds,devices_display
				WHERE pds.id_pds=displays_pds.id_pds
				AND displays_pds.id_display=devices_display.id_display
				AND pds.reference = '$sfid'";
	
		$this->db->query($sql);
	}
	
	public function alta_masiva_muebles($sfid)
	{
		$sql = "INSERT INTO displays_pds (client_type_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status)
				SELECT pds.client_pds, pds.type_pds, pds.id_pds, pds.panelado_pds, displays_panelado.id_display, displays_panelado.position, '', 'Alta'
				FROM pds, displays_panelado
				WHERE pds.panelado_pds=displays_panelado.id_panelado
				AND pds.reference = '$sfid'";
	
		$this->db->query($sql);
	}	
	
	public function borrar_pds($sfid)
	{
		$this->db->where('reference', $sfid);
		$this->db->delete('pds');
	}	
	

	public function cerrar_pds($sfid)
	{
		$this->db->set('status','Baja');
		/**$this->db->set('reference','X-'.$sfid);**/
        $this->db->set('reference',''.$sfid.'-'.time());
		$this->db->where('reference', $sfid);
		$this->db->update('pds');
	}	
	
	public function get_stock_cruzado() {
	
		$query = $this->db->select('device.id_device, brand_device.brand, device.device,
									(
										SELECT COUNT(*)
										FROM devices_pds
								        WHERE (devices_pds.id_device = device.id_device) AND
										(devices_pds.status = "Alta")
								    ) AS unidades_pds,
									(SELECT  COUNT(*)
										FROM devices_almacen
										WHERE (devices_almacen.id_device = device.id_device) AND
										(devices_almacen.status = "En stock")
									) AS unidades_almacen')
										->join('brand_device','device.brand_device = brand_device.id_brand_device')
										->order_by('brand_device.brand', 'ASC')
										->order_by('device.device', 'ASC')
										->get('device');
	
		return $query->result();
	}

    /*
     * Generar CSV con el stock cruzado (Balance de activos).
     */
    public function get_stock_cruzado_csv() {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        $query = $this->db->select('device.id_device, brand_device.brand, device.device,
									(
										SELECT COUNT(*)
										FROM devices_pds
								        WHERE (devices_pds.id_device = device.id_device) AND
										(devices_pds.status = "Alta")
								    ) AS unidades_pds,
									(SELECT  COUNT(*)
										FROM devices_almacen
										WHERE (devices_almacen.id_device = device.id_device) AND
										(devices_almacen.status = "En stock")
									) AS unidades_almacen,

                                    ROUND((SELECT  COUNT(*)
										FROM devices_almacen
										WHERE (devices_almacen.id_device = device.id_device) AND
										(devices_almacen.status = "En stock")
									)
									-
									(SELECT COUNT(*)
										FROM devices_pds
								        WHERE (devices_pds.id_device = device.id_device) AND
										(devices_pds.status = "Alta"))* 0.05 -2 ) as Balance

                                    ')

            ->join('brand_device','device.brand_device = brand_device.id_brand_device')
            ->order_by('brand_device.brand', 'ASC')
            ->order_by('device.device', 'ASC')
            ->get('device');




        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Demo_Real-Balance_Activos.csv', $data);


    }


    public function get_cdm_alarmas() {
	
		$query = $this->db->select('brand_alarm.brand, alarm.alarm, COUNT(*) as incidencias')
		->join('alarm','alarm.id_alarm = material_incidencias.id_alarm')
		->join('brand_alarm','alarm.brand_alarm = brand_alarm.id_brand_alarm')
		->group_by('alarm.alarm')
		->order_by('brand_alarm.brand', 'ASC')
		->order_by('alarm.alarm', 'ASC')
		->get('material_incidencias');
	
		return $query->result();
	}
	
	
	public function get_cdm_dispositivos() {
	
		$query = $this->db->select('brand_device.brand, device.device, COUNT(*) as incidencias')
										->join('devices_almacen','devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen')
										->join('device','devices_almacen.id_device = device.id_device')
										->join('brand_device','device.brand_device = brand_device.id_brand_device')
										->group_by('device.device')
										->order_by('brand_device.brand', 'ASC')
										->order_by('device.device', 'ASC')
										->get('material_incidencias');
	
		return $query->result();
	}	
	

	
	public function search_pds($id) {


		if($id != FALSE) {
			$query = $this->db->select('pds.*,type_pds.pds,panelado.panelado,territory.territory')
				   ->join('type_pds','pds.type_pds = type_pds.id_type_pds')
				   ->join('panelado','pds.panelado_pds = panelado.id_panelado')
				   ->join('territory','pds.territory = territory.id_territory')
				   ->like('pds.reference',$id)
			       ->get('pds');

			return $query->result();
		}
		else {
			return FALSE;
		}
	}	

	
	public function search_dispositivo($id) {
		if($id != FALSE) {
			$query = $this->db->select('devices_almacen.*')
			//->where('devices_almacen.status','En stock')
			->where("(devices_almacen.IMEI LIKE '%{$id}%' OR devices_almacen.mac LIKE '%{$id}%' OR devices_almacen.serial LIKE '%{$id}%' OR devices_almacen.barcode LIKE '%{$id}%')")
			->get('devices_almacen');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function search_dispositivo_id($id) {
		if($id != FALSE) {
			$query = $this->db->select('devices_almacen.*')
			->where('devices_almacen.id_devices_almacen',$id)
			->get('devices_almacen');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_displays_panelado($id) {
		if($id != FALSE) {
			$query = $this->db->select('pds.id_pds,pds.panelado_pds,displays_panelado.*,display.*')
			->join('displays_panelado', 'pds.panelado_pds = displays_panelado.id_panelado')
			->join('display','displays_panelado.id_display = display.id_display')
			->where('pds.id_pds', $id)
			->order_by('position')
			->get('pds');
				
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	public function get_panelados_maestros() {
		$query = $this->db->select('id_panelado,panelado,panelado_abx')
			   ->order_by('panelado_abx')
			   ->get('panelado');
	
		return $query->result();
	}

    public function get_panelado_maestro($id) {
        $query = $this->db->select('id_panelado,panelado,panelado_abx')
            ->where("id_panelado",$id)
            ->order_by('panelado_abx')
            ->get('panelado')->result();

        if(is_array($query) && count($query)>0){
            return $query[0];
        }else{
            return NULL;
        }

    }

    public function get_displays_panelado_maestros($id_panelado) {
		if($id_panelado != FALSE) {
			$query = $this->db->select('displays_panelado.*,display.*')
			->join('display','displays_panelado.id_display = display.id_display')
			->where('displays_panelado.id_panelado', $id_panelado)
			->order_by('position')
			->get('displays_panelado');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	/* Funciones específicas para muebles y dispositivos tienda basados en SFID */
	
	public function get_displays_pds($id) {
		if($id != FALSE) {
			$query = $this->db->select('displays_pds.*,display.*')
			->join('display','displays_pds.id_display = display.id_display')
			->where('displays_pds.id_pds', $id)
			->order_by('position')
			->get('displays_pds');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	public function material_retorno() {
		$query = $this->db->select('devices_pds.id_devices_pds, pds.reference AS SFID, incidencias.id_incidencia AS incidencia, device.device as dispositivo, devices_pds.status AS estado')
		->join('pds','devices_pds.id_pds = pds.id_pds')
		->join('incidencias','devices_pds.id_devices_pds = incidencias.id_devices_pds')
		->join('device','devices_pds.id_device = device.id_device	')
		->where('devices_pds.status', 'Incidencia')
		->order_by('incidencias.id_incidencia')
		->get('devices_pds');
	
		return $query->result();
	}	
	
	
	public function facturacion_estado($fecha_inicio,$fecha_fin,$instalador = NULL,$dueno=NULL) {

        $query = $this->db->select('facturacion.fecha, pds.reference AS SFID, type_pds.pds, facturacion.id_intervencion AS visita, COUNT(facturacion.id_incidencia) AS incidencias, contact.contact AS instalador, client.client as dueno, SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros')
		->join('pds','facturacion.id_pds = pds.id_pds')
		->join('type_pds','pds.type_pds = type_pds.id_type_pds')
		->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds')
		->join('display','displays_pds.id_display = display.id_display')
		->join('intervenciones','facturacion.id_intervencion = intervenciones.id_intervencion', 'left')
		->join('contact','intervenciones.id_operador = contact.id_contact', 'left')
        ->join('client','display.client_display= client.id_client', 'left')
		->where('facturacion.fecha >=',$fecha_inicio)
		->where('facturacion.fecha <=',$fecha_fin);

        if(!is_null($instalador) && !empty($instalador)){
            $query = $this->db->where('intervenciones.id_operador',$instalador);
        }
        if(!is_null($dueno) && !empty($dueno)){
            $query = $this->db->where('display.client_display',$dueno);
        }
		$query = $this->db->group_by('facturacion.id_intervencion')
		->order_by('facturacion.fecha')
		->get('facturacion');
		
		return $query->result();
	}	

	
	function facturacion_estado_csv($fecha_inicio,$fecha_fin,$instalador = NULL,$dueno=NULL)
	{
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		
		$query = $this->db->select('facturacion.fecha, MONTH(facturacion.fecha) as mes, pds.reference AS SFID, type_pds.pds, facturacion.id_intervencion AS visita, COUNT(facturacion.id_incidencia) AS incidencias, contact.contact AS instalador, client.client AS dueno, SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros')
		->join('pds','facturacion.id_pds = pds.id_pds')
		->join('type_pds','pds.type_pds = type_pds.id_type_pds')
		->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds')
		->join('display','displays_pds.id_display = display.id_display')
		->join('intervenciones','facturacion.id_intervencion = intervenciones.id_intervencion', 'left')
		->join('contact','intervenciones.id_operador = contact.id_contact', 'left')
        ->join('client','display.client_display= client.id_client', 'left')
		->where('facturacion.fecha >=',$fecha_inicio)
		->where('facturacion.fecha <=',$fecha_fin);

        if(!is_null($instalador) && !empty($instalador)){
            $query = $this->db->where('intervenciones.id_operador',$instalador);
        }
        if(!is_null($dueno) && !empty($dueno)){
            $query = $this->db->where('display.client_display',$dueno);
        }

        $query = $this->db->group_by('facturacion.id_intervencion')
		->order_by('facturacion.fecha')
		->get('facturacion');
		
		$delimiter = ",";
		$newline = "\r\n";
		$data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
		force_download('Demo_Real-Facturacion.csv', $data);
	}	
	
	
	public function get_display_pds($id) {
			if($id != FALSE) {
			$query = $this->db->select('displays_pds.id_displays_pds, displays_pds.description, display.*')
			->join('display','displays_pds.id_display = display.id_display')
			->where('displays_pds.id_displays_pds', $id)
			->get('displays_pds');

			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	/* Fin funciones específicas para muebles y dispositivos tienda basados en SFID */	
	
	public function get_inventario_panelado($id) {
		if($id != FALSE) {		
			$query = $this->db->select('displays_panelado.*,display.*')
			->join('display','displays_panelado.id_display = display.id_display')
			->where('displays_panelado.id_panelado', $id)
			->order_by('position')
			->get('displays_panelado');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	
	public function get_panelados() {
		$query = $this->db->select('*')
		->order_by('panelado_abx')
		->get('panelado');
	
		return $query->result();
	}	

	
	public function get_displays() {
		$query = $this->db->select('*')
		->order_by('display')
		->get('display');
	
		return $query->result();
	}


    public function get_displays_demoreal() {
        $query = $this->db->query('
                    SELECT *
                    FROM display d
                    WHERE (
                      SELECT COUNT(id_devices_pds) FROM devices_pds p
                      WHERE p.id_display = d.id_display
                      AND p.status = "Alta"
                    ) >= 1 ORDER BY display');


        return $query->result();
    }

    public function get_devices_demoreal() {
        $query = $this->db->query('
                    SELECT *
                    FROM device d
                    WHERE (
                      SELECT COUNT(id_devices_pds) FROM devices_pds p
                      WHERE p.id_device = d.id_device
                      AND p.status = "Alta"
                    ) >= 1 ORDER BY device');


        return $query->result();
    }

    public function get_panelados_maestros_demoreal($tipo=NULL) {

            $sql = 'SELECT * FROM panelado
                         WHERE 1= 1 '
                         ;
                        if(!is_null($tipo)) {
                            $sql .= ' AND type_pds = ' . $tipo;
                            $sql .=  ' ORDER BY panelado ASC' ;
                        }else{

                            $sql .= 'AND id_panelado IN(
                                 SELECT DISTINCT(panel.id_panelado)
                                FROM display d
                                INNER JOIN displays_pds panel ON d.id_display = panel.id_display
                                WHERE (
                                  SELECT COUNT(id_devices_pds) FROM devices_pds p
                                  WHERE p.id_display = d.id_display
                                  AND p.status = "Alta"
                                ) >= 1  ORDER BY panelado ASC);';

                        }


        $query = $this->db->query($sql);

        return $query->result();
    }

    public function get_devices_pds($id) {

		if($id != FALSE) {
			$query = $this->db->select('devices_pds.*,device.*, COUNT(devices_pds.id_device) AS unidades')
			->join('device','devices_pds.id_device = device.id_device')
			->where('devices_pds.id_pds',$id)
			->where('devices_pds.status','Alta')
			->group_by('devices_pds.id_device')
			->order_by('device')
			->get('devices_pds');
				
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_devices_total() {

			$query = $this->db->select('devices_pds.*,device.*, COUNT(devices_pds.id_device) AS unidades')
			->join('device','devices_pds.id_device = device.id_device')
			->where('devices_pds.status','Alta')
			->group_by('devices_pds.id_device')
			->order_by('device')
			->get('devices_pds');
	
			return $query->result();
	}	
	
	
	public function get_displays_total() {
	
		$query = $this->db->select('displays_pds.*,display.*, COUNT(displays_pds.id_display) AS unidades')
		->join('display','displays_pds.id_display = display.id_display')
		->where('displays_pds.status','Alta')
		->group_by('displays_pds.id_display')
		->order_by('display')
		->get('displays_pds');
	
		return $query->result();
	}	
	
	
	public function get_devices_almacen() {
	
		$query = $this->db->select('devices_almacen.*,device.*, COUNT(devices_almacen.id_device) AS unidades')
		->join('device','devices_almacen.id_device = device.id_device')
		->where('devices_almacen.status','En stock')
		->group_by('devices_almacen.id_device')
		->order_by('device')
		->get('devices_almacen');
	
		return $query->result();
	}
	
	
	public function get_devices() {
		$query = $this->db->select('device.id_device, device.device')
		->where('device.status','Alta')
		->order_by('device')
		->get('device');
	
		return $query->result();
	}
		
	
	public function get_material_dispositivos($id) {
	
		$query = $this->db->select('material_incidencias.id_material_incidencias AS id_material_incidencias,
		        devices_almacen.id_devices_almacen AS id_devices_almacen, device.device AS device, devices_almacen.barcode AS barcode, material_incidencias.cantidad AS cantidad')
		->join('devices_almacen','devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen')
		->join('device','devices_almacen.id_device = device.id_device')
		->where('material_incidencias.id_incidencia',$id)
		->where('material_incidencias.id_devices_almacen <>','')
		->order_by('device.device')
		->get('material_incidencias');
	
		return $query->result();
	}
	
	
	public function get_material_alarmas($id) {
	
		$query = $this->db->select('material_incidencias.id_material_incidencias AS id_material_incidencias,
		material_incidencias.id_alarm AS id_alarm, alarm.code AS code, alarm.alarm AS alarm,
		material_incidencias.cantidad AS cantidad, client.client as dueno')
		->join('alarm','material_incidencias.id_alarm = alarm.id_alarm')
            ->join('client','alarm.client_alarm= client.id_client')
		->where('material_incidencias.id_incidencia',$id)
		->where('material_incidencias.id_alarm <>','')
		->order_by('alarm.alarm')
		->get('material_incidencias');
	
		return $query->result();
	}	
	
	
	public function reservar_dispositivos($id,$status)
	{
		$this->db->set('status',$status, FALSE);
		$this->db->where('id_devices_almacen',$id);
		$this->db->update('devices_almacen');
	}
		
	
	public function update_dispositivos($id,$imei,$mac,$serial,$barcode)
	{
		$this->db->set('IMEI',$imei);
		$this->db->set('mac',$mac);
		$this->db->set('serial',$serial);
		$this->db->set('barcode',$barcode);
		$this->db->where('id_devices_almacen',$id);
		$this->db->update('devices_almacen');
	}	

	public function get_devices_almacen_reserva() {
	
		$query = $this->db->select('devices_almacen.*, device.*')
		->join('device','devices_almacen.id_device = device.id_device')
		->where('devices_almacen.status','En stock')
		->order_by('device.device')
		->order_by('devices_almacen.serial')
		->get('devices_almacen');

	
		return $query->result();
	}


    /**
     * Método que devuelve una lista con los Dueños, con estado Alta
     * @return mixed
     */
    public function get_duenos(){
        $query = $this->db->select('id_client,client')
            ->where('status','Alta')
            ->order_by('client')
            ->get('client');

        return $query->result();

    }
	
	public function get_alarms_almacen_reserva($dueno = NULL) {
	
		$query = $this->db->select('client.client,alarm.*, brand_alarm.brand, type_alarm.type')
		->join('client','alarm.client_alarm = client.id_client')
		->join('brand_alarm','alarm.brand_alarm = brand_alarm.id_brand_alarm')
		->join('type_alarm','alarm.type_alarm = type_alarm.id_type_alarm')
		->order_by('brand')
		->order_by('code')
		->order_by('alarm')
        ->where('alarm.status','Alta');

            if(! is_null($dueno)){
                $query->where('alarm.client_alarm',$dueno);
            }
		$query = $this->db->get('alarm');

	
		return $query->result();
	}

    public function get_alarms_almacen() {
	
		$query = $this->db->select('alarms_almacen.*,alarm.*, COUNT(alarms_almacen.id_alarm) AS unidades')
		->join('alarm','alarms_almacen.id_alarm = alarm.id_alarm')
		->where('alarms_almacen.status','En stock')
		->group_by('alarms_almacen.id_alarm')
		->order_by('alarm')
		->get('alarms_almacen');
	
		return $query->result();
	}	
	
	
	public function get_devices_display($id) {
		if($id != FALSE) {
			$query = $this->db->select('devices_display.*,device.*')
			->join('device','devices_display.id_device = device.id_device')
			->where('devices_display.id_display',$id)
			->order_by('position')
			->get('devices_display');
				
			return $query->result();
		}
		else {
			return FALSE;
		}
	}
	
	
	public function count_devices_display($id)
	{
		$conditions = array('id_display'=>$id,'status'=>'Alta');
		$this->db->where($conditions);
		$this->db->from('devices_display');
		$count = $this->db->count_all_results();
		return $count;
	}	
	
	
	public function get_pds($id) {
		if($id != FALSE) {
			$query = $this->db->select('pds.*,type_pds.pds as pds, province.province, territory.territory')
			->join('type_pds','pds.type_pds = type_pds.id_type_pds')
			->join('province','pds.province = province.id_province')
			->join('territory','pds.territory = territory.id_territory')
			->where('pds.id_pds',$id)
			->get('pds');

			/*$query = $this->db->query("
            SELECT pds.*,type_pds.pds as pds, province.province, territory.territory
                FROM pds
                LEFT JOIN  type_pds ON pds.type_pds = type_pds.id_type_pds
                LEFT JOIN  province ON pds.province = province.id_province
                LEFT JOIN  territory ON pds.territory = territory.id_territory
                WHERE pds.id_pds=$id");*/
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_display($id) {
		if($id != FALSE) {
			$query = $this->db->select('*')
			->where('id_display',$id)
			->get('display');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}


			
	public function get_device($id) {
		if($id != FALSE) {
			$query = $this->db->select('*')
			->where('id_device',$id)
			->get('device');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}
	
	
	public function get_id($reference) {
		if($reference != FALSE) {
			$query = $this->db->select('*')
			->where('reference',$reference)
			->get('pds');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
/*
	 *  Devuelve conjunto de registros de incidencias abiertas,
	 *  filtradas si procede, y el subconjunto limitado paginado si procede
	 *
	 * */
	public function get_incidencias($page = 1, $cfg_pagination = NULL,$campo_orden=NULL,$orden=NULL,$filtros=NULL,$buscador=NULL) {

        $this->db->select('incidencias.*,pds.reference as reference')
        ->join('pds','incidencias.id_pds = pds.id_pds');

        /*  ESTADOS ABIERTOS SAT: Nueva, Revisada, Instalador asignado, Material asignado, Comunicada
            ESTADOS CERRADOS SAT: Resuelta, Pendiente recogida, Cerrada, Cancelada */
        $this->db->where('(incidencias.status != "Resuelta" && incidencias.status != "Pendiente recogida"
                        && incidencias.status != "Cerrada" && incidencias.status != "Cancelada")');
        /*  ESTADOS ABIERTOS PDS: Alta realizada, En proceso, En visita
            ESTADOS CERRADOS PDS: Finalizada, Cancelada */
        $this->db->where('(incidencias.status_pds != "Finalizada" && incidencias.status_pds != "Cancelada")');

        // Montamos las cláusulas where filtro, según el array pasado como param.
        if (!is_null($filtros) && !empty($filtros)){
            foreach($filtros as $k=>$f) {
                $this->db->where('incidencias.'.$k, $f);
            }
        }

        if(! empty($buscador['buscar_incidencia']))
            $this->db->where('incidencias.id_incidencia',$buscador['buscar_incidencia']);

        if(! empty($buscador['buscar_sfid']))
            $this->db->where('pds.reference',$buscador['buscar_sfid']);

        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($orden) && !empty($orden)) {
            $s_orden = $campo_orden. " ".$orden;
            $this->db->order_by($s_orden);
        }else{
            $this->db->order_by('fecha DESC');
        }

        $query =   $this->db->get('incidencias',$cfg_pagination['per_page'], ($page-1) * $cfg_pagination['per_page']);
        //echo $this->db->last_query();

		return $query->result();
	}


/**
*  Devuelve conjunto de registros de incidencias abiertas, para generar CSV
*  filtradas si procede
*
* */
    public function get_incidencias_csv($campo_orden=NULL,$orden=NULL,$filtros=NULL,$buscador=NULL,$tipo="abiertas") {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        $acceso = $this->uri->segment(1);

        $sql = 'SELECT incidencias.id_incidencia,
                            pds.reference as `SFID`,
                            incidencias.fecha,
                            incidencias.fecha_cierre,



                            (CASE incidencias.alarm_display WHEN 1 THEN ( CONCAT("Mueble: ",
                                (CASE ISNULL(display.display) WHEN TRUE THEN "Retirado" ELSE display.display END)
                            )) ELSE (CONCAT("Dispositivo: ",
                                (CASE ISNULL(device.device) WHEN TRUE THEN "Retirado" ELSE device.device END)
                            )) END) as elemento,


                            incidencias.tipo_averia,

                            (CASE incidencias.fail_device WHEN 1 THEN ("Sí") ELSE ("No") END) AS `Fallo dispositivo`,
                            (CASE incidencias.alarm_display WHEN 1 THEN ("Sí") ELSE ("No") END) AS `Alarma mueble`,
                            (CASE incidencias.alarm_device WHEN 1 THEN ("Sí") ELSE ("No") END) AS `Alarma dispositivo`,
                            (CASE incidencias.alarm_garra WHEN 1 THEN ("Sí") ELSE ("No") END) AS `Alarma anclaje`,

                            REPLACE(REPLACE(incidencias.description_1,CHAR(10),CHAR(32)),CHAR(13),CHAR(32)),
                            REPLACE(REPLACE(incidencias.description_2,CHAR(10),CHAR(32)),CHAR(13),CHAR(32)),
                            REPLACE(REPLACE(incidencias.description_3,CHAR(10),CHAR(32)),CHAR(13),CHAR(32)),
                            incidencias.parte_pdf,
                            incidencias.denuncia,
                            incidencias.foto_url,
                            incidencias.foto_url_2,
                            incidencias.foto_url_3,
                            incidencias.contacto,
                            incidencias.phone,
                            incidencias.email,
                            incidencias.id_operador,
                            incidencias.intervencion,';

                if($acceso==="admin"){
                    $sql .= 'incidencias.status_pds AS `Estado SAT`,';
                }

        $sql .='incidencias.status  AS `Estado`

                FROM incidencias
                JOIN pds ON incidencias.id_pds = pds.id_pds
                LEFT JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
                LEFT JOIN display ON displays_pds.id_display = display.id_display
                LEFT JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
                LEFT JOIN device ON devices_pds.id_device = device.id_device
                LEFT JOIN type_device ON device.type_device = type_device.id_type_device
                WHERE 1 = 1';


        if($tipo==="abiertas") {

            $sql .=
                '
                AND (incidencias.status != "Resuelta" AND incidencias.status != "Pendiente recogida" AND incidencias.status != "Cerrada" AND incidencias.status != "Cancelada")
                AND (incidencias.status_pds != "Finalizada" && incidencias.status_pds != "Cancelada")
                ';
            $sTitleFilename = "Incidencias_abiertas";
        }else{
            $sql .=
                '
                AND (incidencias.status = "Resuelta" OR incidencias.status = "Pendiente recogida" OR incidencias.status = "Cerrada" OR incidencias.status = "Cancelada")
                AND (incidencias.status_pds = "Finalizada" OR incidencias.status_pds = "Cancelada")
                ';
            $sTitleFilename = "Incidencias_cerradas";
        }


        // Montamos las cláusulas where filtro, según el array pasado como param.
        $sFiltrosFilename = "-";


        if (!is_null($filtros) && !empty($filtros)){
            foreach($filtros as $k=>$f) {
                $sql  .= (' AND incidencias.'.$k.'="'.$f.'" ');
                $sFiltrosFilename .= ($f."-");
            }
        }

        if(! empty($buscador['buscar_incidencia'])) {
            $sql .= (' AND incidencias.id_incidencia = "'.$buscador['buscar_incidencia'].'"');
            $sFiltrosFilename .= ($buscador['buscar_incidencia']."-");
        }

        if(! empty($buscador['buscar_sfid'])) {
            $sql .= (' AND pds.reference ="'.$buscador['buscar_sfid'].'"');
            $sFiltrosFilename .= ($buscador['buscar_sfid']."-");
        }

        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($orden) && !empty($orden)) {
            $sql .=  ' ORDER BY '.$campo_orden. ' '.$orden;

        }else{
            $sql .=  ' ORDER BY fecha DESC';
        }


        $query = $this->db->query($sql);


        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Demo_Real-'.$sTitleFilename.$sFiltrosFilename.date("d-m-Y").'T'.date("H:i:s").'.csv', $data);


    }

    public function get_incidencias_quantity($filtros=NULL,$buscador=NULL) {
        $this->db->select('COUNT(incidencias.id_incidencia) AS cantidad')
            ->join('pds','incidencias.id_pds = pds.id_pds');

        /*  ESTADOS ABIERTOS SAT: Nueva, Revisada, Instalador asignado, Material asignado, Comunicada
            ESTADOS CERRADOS SAT: Resuelta, Pendiente recogida, Cerrada, Cancelada */
        $this->db->where('(incidencias.status != "Resuelta" && incidencias.status != "Pendiente recogida"
                        && incidencias.status != "Cerrada" && incidencias.status != "Cancelada")');
        /*  ESTADOS ABIERTOS PDS: Alta realizada, En proceso, En visita
            ESTADOS CERRADOS PDS: Finalizada, Cancelada */
        $this->db->where('(incidencias.status_pds != "Finalizada" && incidencias.status_pds != "Cancelada")');

        // Montamos las cláusulas where filtro, según el array pasado como param.
        if (!is_null($filtros) && !empty($filtros)){
            foreach($filtros as $k=>$f) {
                $this->db->where('incidencias.'.$k, $f);
            }
        }

        if(! empty($buscador['buscar_incidencia']))
            $this->db->where('incidencias.id_incidencia',$buscador['buscar_incidencia']);

        if(! empty($buscador['buscar_sfid']))
            $this->db->where('pds.reference',$buscador['buscar_sfid']);

        $query =  $this->db->get('incidencias')->result();


        if(is_array($query) && count($query)>0){
            return $query[0]->cantidad;
        }else{
            return NULL;
        }


    }
	public function get_incidencias_pds($id) {
		$query = $this->db->select('incidencias.*,pds.reference as reference')
		->join('pds','incidencias.id_pds = pds.id_pds')
		->where('incidencias.id_pds',$id)
		->where('incidencias.status != "Cancelada"')
		->order_by('fecha ASC')
		->get('incidencias');
	
		return $query->result();
	}	
	
	
/**
     * Obtiene las filas de las incidencias finalizadas, aplicando filtro y buscador si procede.
     * @param int $page
     * @param null $cfg_pagination
     * @param null $filtro_finalizadas
     * @param null $buscador
     * @return mixed
     */
    public function get_incidencias_cerradas($page = 1, $cfg_pagination = NULL,$filtros_finalizadas=NULL,$buscador=NULL,$campo_orden=NULL,$orden=NULL)
    {
        $this->db->select('incidencias.*,pds.reference as reference')
            ->join('pds', 'incidencias.id_pds = pds.id_pds');


        if(! empty($buscador['buscar_incidencia']))
            $this->db->where('incidencias.id_incidencia',$buscador['buscar_incidencia']);

        if(! empty($buscador['buscar_sfid']))
            $this->db->where('pds.reference',$buscador['buscar_sfid']);

        /*  ESTADOS ABIERTOS SAT: Nueva, Revisada, Instalador asignado, Material asignado, Comunicada
            ESTADOS CERRADOS SAT: Resuelta, Pendiente recogida, Cerrada, Cancelada */
        $this->db->where('(incidencias.status = "Resuelta" || incidencias.status = "Pendiente recogida"
                        || incidencias.status = "Cerrada" || incidencias.status = "Cancelada")');
        /*  ESTADOS ABIERTOS PDS: Alta realizada, En proceso, En visita
            ESTADOS CERRADOS PDS: Finalizada, Cancelada */
        $this->db->where('(incidencias.status_pds = "Finalizada" || incidencias.status_pds = "Cancelada")');

        // Montamos las cláusulas where filtro, según el array pasado como param.
        if (!is_null($filtros_finalizadas) && !empty($filtros_finalizadas)){
            foreach($filtros_finalizadas as $k=>$f) {
                $this->db->where('incidencias.'.$k, $f);
            }
        }

        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($orden) && !empty($orden)) {
            $s_orden = $campo_orden. " ".$orden;
            $this->db->order_by($s_orden);
        }else{
            $this->db->order_by('fecha DESC');
        }



        $this->db->order_by('fecha ASC');


        $query = $this->db->get('incidencias',$cfg_pagination['per_page'], ($page-1) * $cfg_pagination['per_page']);
        //echo $this->db->last_query();
        return $query->result();
    }

    /**
     * Devuelve la cantidad de líneas de incidencias finalizadas, teniendo en cuenta filtro y buscador.
     * Útil para el paginador
     *
     * @param null $filtro_finalizadas
     * @param null $buscador
     * @return mixed
     */
    public function get_incidencias_cerradas_quantity($filtros_finalizadas=NULL,$buscador=NULL)
    {
        $this->db->select('COUNT(incidencias.id_incidencia) AS cantidad')
            ->join('pds', 'incidencias.id_pds = pds.id_pds');


        if(! empty($buscador['buscar_incidencia']))
            $this->db->where('incidencias.id_incidencia',$buscador['buscar_incidencia']);

        if(! empty($buscador['buscar_sfid']))
            $this->db->where('pds.reference',$buscador['buscar_sfid']);

        /*  ESTADOS ABIERTOS SAT: Nueva, Revisada, Instalador asignado, Material asignado, Comunicada
            ESTADOS CERRADOS SAT: Resuelta, Pendiente recogida, Cerrada, Cancelada */
        $this->db->where('(incidencias.status = "Resuelta" || incidencias.status = "Pendiente recogida"
                        || incidencias.status = "Cerrada" || incidencias.status = "Cancelada")');
        /*  ESTADOS ABIERTOS PDS: Alta realizada, En proceso, En visita
            ESTADOS CERRADOS PDS: Finalizada, Cancelada */
        $this->db->where('(incidencias.status_pds = "Finalizada" || incidencias.status_pds = "Cancelada")');

        // Montamos las cláusulas where filtro, según el array pasado como param.
        if (!is_null($filtros_finalizadas) && !empty($filtros_finalizadas)){
            foreach($filtros_finalizadas as $k=>$f) {
                $this->db->where('incidencias.'.$k, $f);
            }
        }

        $query = $this->db->get('incidencias')->result();

        if(is_array($query) && count($query)>0){
            return $query[0]->cantidad;
        }else{
            return NULL;
        }


    }


    /**
     * Devuelve un array fila correspondiente a una incidencia de id pasado como param.
     * @param $id
     * @return bool
     */
	public function get_incidencia($id) {
		if($id != FALSE) {
			$query = $this->db->select('incidencias.*')
			->where('incidencias.id_incidencia',$id)
			->get('incidencias');


			return $query->row_array();
		}
		else {
			return FALSE;
		}


	}
	public function historico_fecha($id,$status) {
		if($id != FALSE) {
			$query = $this->db->select('historico.fecha')
			->where('historico.id_incidencia',$id)
			->where('historico.status',$status)
			->get('historico');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function incidencia_update($id,$status_pds,$status)
	{
		$this->db->set('status_pds', $status_pds, FALSE);
		$this->db->set('status', $status, FALSE);
		$this->db->where('id_incidencia',$id);
		$this->db->update('incidencias');
	}	

	public function incidencia_update_cierre($id,$fecha_cierre)
	{
		$this->db->set('fecha_cierre', $fecha_cierre);
		$this->db->where('id_incidencia',$id);
		$this->db->update('incidencias');
	}	
	
	
	public function comentario_incidencia_update($id,$texto)
	{
		$this->db->set('description_2', $texto);
		$this->db->where('id_incidencia',$id);
		$this->db->update('incidencias');
	}	
	
	public function comentario_incidencia_instalador_update($id,$texto)
	{
		$this->db->set('description_3', $texto);
		$this->db->where('id_incidencia',$id);
		$this->db->update('incidencias');
	}
		
	
	public function incidencia_update_device_pds($id_devices_pds,$status)
	{
		$this->db->set('status', $status, FALSE);
		$this->db->where('id_devices_pds',$id_devices_pds);
		$this->db->update('devices_pds');
	}
		
	
	public function get_alarms_display($id) {
		if($id != FALSE) {
			$query = $this->db->select('alarms_display_pds.*,alarm.*')
			->join('alarm','alarms_display_pds.id_alarm = alarm.id_alarm')
			->where('alarms_display_pds.id_displays_pds',$id)
			->get('alarms_display_pds');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}
	
	public function get_alarms_device($id) {
		if($id != FALSE) {
			$query = $this->db->select('alarms_device_pds.*,alarm.*')
			->join('alarm', 'alarms_device_pds.id_alarm = alarm.id_alarm')
			->where('alarms_device_pds.id_devices_pds',$id)
			->get('alarms_device_pds');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_all_displays($id) {
			$query = $this->db->select('*')
				   ->where('id_pds',$id)
				   ->get('displays_pds');
			
			return $query->num_rows();
	}	

	
	public function get_all_devices($id) {
			$query = $this->db->select('*')
				   ->where('id_pds',$id)
				   ->get('devices_pds');
				
			return $query->num_rows();
	}

	
	public function insert_incidencia($data)
	{
		$this->db->insert('incidencias',$data);
		$id=$this->db->insert_id();
		return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);
	}	
	
	
	public function historico($data)
	{
		$this->db->insert('historico',$data);
		$id=$this->db->insert_id();
	}	
	
	public function incidencia_update_material($data)
	{
		$this->db->insert('material_incidencias',$data);
		$id=$this->db->insert_id();

        $this->alta_historico_IO($data,$id);

	}


    /**
     * Inserción de la Salida (No procesada hasta que no sea definitivo el material de la intervención,
     * al cambiar de estado la incidencia).
     *
     */
    public function alta_historico_IO($data,$id){

        $tipo = (is_null($data['id_alarm'])) ? "device" : "alarm";


        if($tipo==="device")            // ES DE TIPO TERMINAL
        {

            /**BACKUP SQL BUENA
             * $q_dueno = $this->db->query("SELECT DISTINCT(client_display) as id_client FROM display
                                          JOIN displays_pds ON display.id_display = displays_pds.id_display
                                          JOIN incidencias ON displays_pds.id_displays_pds = incidencias.id_displays_pds
                                          JOIN material_incidencias ON incidencias.id_incidencia = material_incidencias.id_incidencia
                                          WHERE material_incidencias.id_devices_almacen=".$data['id_devices_almacen'])->result()[0]; **/
            if(!empty($data['id_devices_almacen'])) {
                $q_dueno = $this->db->query("SELECT DISTINCT(client_display) as id_client, device.id_device as id_device FROM display
                                          JOIN displays_pds ON display.id_display = displays_pds.id_display
                                          JOIN incidencias ON displays_pds.id_displays_pds = incidencias.id_displays_pds
                                          JOIN material_incidencias ON incidencias.id_incidencia = material_incidencias.id_incidencia
                                          JOIN devices_almacen ON devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen
                                          JOIN device ON devices_almacen.id_device = device.id_device
                                          WHERE material_incidencias.id_devices_almacen=" . $data['id_devices_almacen'])->result();
                if(is_array($q_dueno) && count($q_dueno) > 0){
                    $q_dueno = $q_dueno[0];
                }

                $id_device = $q_dueno->id_device;
                $dueno = $q_dueno->id_client;
                $procesado = 0;
                $id_devices_almacen = $data['id_devices_almacen'];

            }else{
                $id_device = $data["id_device"];
                $dueno = NULL;
                $procesado = $data["procesado"];
                $id_devices_almacen = $data['id_devices_almacen_new'];
            }

            $elemento = array(
                'id_material_incidencia' => $id,
                'id_alarm' => NULL,
                'id_device'=>$id_device,
                'id_devices_almacen' => $id_devices_almacen,
                'id_incidencia' => $data['id_incidencia'],
                'id_client' => $dueno,
                'fecha' => $data['fecha'],
                'unidades' => ($data['cantidad'] * (-1)),
                'procesado' => $procesado
            );


            $this->db->insert('historico_IO',$elemento);

        }
        elseif($tipo==="alarm")         // ES DE TIPO ALARMA
        {
            $q_dueno = $this->db->query("SELECT client_alarm as id_client FROM alarm WHERE id_alarm=" . $data['id_alarm'])->result();

            if(is_array($q_dueno) && count($q_dueno) > 0){
                $q_dueno = $q_dueno[0];
            }
            $elemento = array(
                'id_material_incidencia' => $id,
                'id_alarm' => $data['id_alarm'],
                'id_device'=>NULL,
                'id_devices_almacen' => NULL,
                'id_incidencia' => $data['id_incidencia'],
                'id_client' => $q_dueno->id_client,
                'fecha' => $data['fecha'],
                'unidades' => ($data['cantidad'] * (-1)),
                'procesado' => 0
            );


            $this->db->insert('historico_IO',$elemento);
        }



    }

    /**
 * Método que da de baja unba alarma de la tabla de historico_IO, porque se ha desasignado de una incidencia.
 *
 * @param $id_material
 */
    public function baja_historico_IO($id_material=NULL)
    {
        if(! is_null($id_material))
        {
            $this->db->where('id_material_incidencia',$id_material);
            $this->db->delete('historico_IO');
        }

    }


    /**
     * Método que da por procesadas  las alarma de la tabla de historico_IO, porque se han
     * desasignado definitivamente a una incidencia, y ya deben aparecer en el histórico.
     *
     * @param $id_material
     */
    public function procesar_historico_incidencia($id_incidencia=NULL)
    {
        if(! is_null($id_incidencia))
        {
            $historico_IO['procesado'] = 1;

            $this->db->where('id_incidencia',$id_incidencia);
            $this->db->update('historico_IO',$historico_IO);
        }

    }

	public function facturacion($data)
	{
		$this->db->insert('facturacion',$data);
		$id=$this->db->insert_id();
	}
		
	
	public function incidencia_update_historico_sfid($data)
	{
		$this->db->insert('historico_sfid',$data);
		$id=$this->db->insert_id();
	}
		
	public function incidencia_update_sfid($sfid_old,$sfid_new)
	{
		$this->db->set('sfid',$sfid_new);
		$this->db->where('sfid',$sfid_old);
		$this->db->update('agent');
		
		$this->db->set('reference',$sfid_new);
		$this->db->where('reference',$sfid_old);
		$this->db->update('pds');		
	}	
	
	public function get_alarms_incidencia($id) {
		if($id != FALSE) {
			$query = $this->db->select('COUNT(id_alarm) as alarmas')
			->where('material_incidencias.id_incidencia',$id)
			->get('material_incidencias');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_devices_incidencia($id) {
		if($id != FALSE) {
			$query = $this->db->select('COUNT(id_devices_almacen) as dispositivos')
			->where('material_incidencias.id_incidencia',$id)
			->get('material_incidencias');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	public function login($data)
	{
		$sfid     = $data['sfid'];
		$password = $data['password'];
	
		$this->db->select('agent_id,sfid,password,type');
		$this->db->from('agent');
		$this->db->where('sfid',$sfid);
		$this->db->where('password',$password);
		$this->db->limit(1);
		$query=$this->db->get();
	
		if($query->num_rows()==1)
		{
			$row = $query->row();
			$data = array(
					'agent_id'  => $row->agent_id,
					'sfid'      => $row->sfid,
					'type'      => $row->type,
					'logged_in' => TRUE
			);
			$this->session->set_userdata($data);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}	
	
}

?>