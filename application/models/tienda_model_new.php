<?php

class Tienda_model_new extends CI_Model {


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
	}	
	
	
	public function baja_dispositivos_almacen_update($id_device,$owner,$units)
	{
		$this->db->set('status',4, FALSE);
		$this->db->where('id_device',$id_device);
		$this->db->where('owner',$owner);
		$this->db->where('status',1);
		$this->db->limit($units);
		
		$this->db->update('devices_almacen');		
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
	
	
	public function facturacion_estado($fecha_inicio,$fecha_fin) {
		$query = $this->db->select('facturacion.fecha, pds.reference AS SFID, type_pds.pds, facturacion.id_intervencion AS visita, display.display, SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros')
		->join('pds','facturacion.id_pds = pds.id_pds')
		->join('type_pds','pds.type_pds = type_pds.id_type_pds')
		->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds')
		->join('display','displays_pds.id_display = display.id_display')
		->where('facturacion.fecha >=',$fecha_inicio)
		->where('facturacion.fecha <=',$fecha_fin)	
		->group_by('facturacion.id_intervencion')
		->order_by('facturacion.fecha')
		->get('facturacion');
		
		return $query->result();
	}	

	
	function facturacion_estado_csv($fecha_inicio,$fecha_fin)
	{
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		
		$query = $this->db->select('facturacion.fecha, pds.reference AS SFID, type_pds.pds, facturacion.id_intervencion AS visita, display.display, SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros')
		->join('pds','facturacion.id_pds = pds.id_pds')
		->join('type_pds','pds.type_pds = type_pds.id_type_pds')
		->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds')
		->join('display','displays_pds.id_display = display.id_display')
		->where('facturacion.fecha >=',$fecha_inicio)
		->where('facturacion.fecha <=',$fecha_fin)		
		->group_by('facturacion.id_intervencion')
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
	
		$query = $this->db->select('device.device AS device, devices_almacen.barcode AS barcode, material_incidencias.cantidad AS cantidad')
		->join('devices_almacen','devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen')
		->join('device','devices_almacen.id_device = device.id_device')
		->where('material_incidencias.id_incidencia',$id)
		->where('material_incidencias.id_devices_almacen <>','')
		->order_by('device.device')
		->get('material_incidencias');
	
		return $query->result();
	}
	
	
	public function get_material_alarmas($id) {
	
		$query = $this->db->select('alarm.code AS code, alarm.alarm AS alarm, material_incidencias.cantidad AS cantidad')
		->join('alarm','material_incidencias.id_alarm = alarm.id_alarm')
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
		
	
	public function get_alarms_almacen_reserva() {
	
		$query = $this->db->select('client.client,alarm.*, brand_alarm.brand, type_alarm.type')
		->join('client','alarm.client_alarm = client.id_client')
		->join('brand_alarm','alarm.brand_alarm = brand_alarm.id_brand_alarm')
		->join('type_alarm','alarm.type_alarm = type_alarm.id_type_alarm')
		->where('alarm.status','Alta')
		->order_by('brand')
		->order_by('code')
		->order_by('alarm')
		->get('alarm');
	
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
			$query = $this->db->select('pds.*,type_pds.pds, province.province, territory.territory')
			->join('type_pds','pds.type_pds = type_pds.id_type_pds')
			->join('province','pds.province = province.id_province')
			->join('territory','pds.territory = territory.id_territory')
			->where('pds.id_pds',$id)
			->get('pds');
	
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
	
	
	public function get_incidencias($page = 1, $cfg_pagination = NULL,$campo_orden=NULL,$orden=NULL,$filtro=NULL,$buscador=NULL) {

        $this->db->select('incidencias.*,pds.reference as reference')
        ->join('pds','incidencias.id_pds = pds.id_pds');

        $this->db->where('(incidencias.status != "Cerrada" && incidencias.status != "Cancelada" && incidencias.status != "Resuelta")');

        if (!is_null($filtro) && !empty($filtro)){
            $this->db->where('incidencias.status', $filtro);
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

		return $query->result();
	}

    public function get_incidencias_quantity($filtro=NULL,$buscador=NULL) {
        $this->db->select('COUNT(incidencias.id_incidencia) AS cantidad')
            ->join('pds','incidencias.id_pds = pds.id_pds');

        $this->db->where('(incidencias.status != "Cerrada" && incidencias.status != "Cancelada" && incidencias.status != "Resuelta")');

        if (!is_null($filtro) && !empty($filtro)){
            $this->db->where('incidencias.status', $filtro);
        }

        if(! empty($buscador['buscar_incidencia']))
            $this->db->where('incidencias.id_incidencia',$buscador['buscar_incidencia']);

        if(! empty($buscador['buscar_sfid']))
            $this->db->where('pds.reference',$buscador['buscar_sfid']);

        $query =  $this->db->get('incidencias');


        return $query->result()[0]->cantidad;
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
    public function get_incidencias_finalizadas($page = 1, $cfg_pagination = NULL,$filtro_finalizadas=NULL,$buscador=NULL,$campo_orden=NULL,$orden=NULL)
    {
        $this->db->select('incidencias.*,pds.reference as reference')
            ->join('pds', 'incidencias.id_pds = pds.id_pds');


        if(! empty($buscador['buscar_incidencia']))
            $this->db->where('incidencias.id_incidencia',$buscador['buscar_incidencia']);

        if(! empty($buscador['buscar_sfid']))
            $this->db->where('pds.reference',$buscador['buscar_sfid']);

        $this->db->where('(incidencias.status = "Cerrada" || incidencias.status = "Cancelada" || incidencias.status = "Resuelta")');

        if (!is_null($filtro_finalizadas) && !empty($filtro_finalizadas)){
             $this->db->where('incidencias.status', $filtro_finalizadas);
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
    public function get_incidencias_finalizadas_quantity($filtro_finalizadas=NULL,$buscador=NULL)
    {
        $this->db->select('COUNT(incidencias.id_incidencia) AS cantidad')
            ->join('pds', 'incidencias.id_pds = pds.id_pds');


        if(! empty($buscador['buscar_incidencia']))
            $this->db->where('incidencias.id_incidencia',$buscador['buscar_incidencia']);

        if(! empty($buscador['buscar_sfid']))
            $this->db->where('pds.reference',$buscador['buscar_sfid']);

        $this->db->where('(incidencias.status = "Cerrada" || incidencias.status = "Cancelada" || incidencias.status = "Resuelta")');

        if (!is_null($filtro_finalizadas) && !empty($filtro_finalizadas)){
            $this->db->where('incidencias.status', $filtro_finalizadas);
        }

        $query = $this->db->get('incidencias');
        return $query->result()[0]->cantidad;
    }

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