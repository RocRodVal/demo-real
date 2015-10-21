<?php

class Tienda_model extends CI_Model {

	public function __construct()	{
		$this->load->database();
        $this->load->model("incidencia_model");
        $this->load->model("intervencion_model");

	}

	
	public function get_stock() {
	
		$query = $this->db->query('
		SELECT temporal.id_device, brand_device.brand, temporal.device, unidades_pds,
		(CASE WHEN unidades_pds = 0 THEN 0 ELSE CEIL(unidades_pds * 0.05 + 2) END) as stock_necesario,
		deposito_almacen,
		(deposito_almacen - (CASE WHEN unidades_pds = 0 THEN 0 ELSE CEIL(unidades_pds * 0.05 + 2) END)) as balance
		FROM (
		        SELECT device.id_device, device.brand_device, device.device,
                    (
                        SELECT COUNT(*)
                        FROM devices_pds
                        WHERE (devices_pds.id_device = device.id_device) AND
                        (devices_pds.status = "Alta")
                    )
                    as unidades_pds,

                    (
                        SELECT  COUNT(*)
                        FROM devices_almacen
                        WHERE (devices_almacen.id_device = device.id_device) AND
                        (devices_almacen.status = "En stock")
                    )
                    as deposito_almacen FROM device
                ) as temporal

        JOIN brand_device ON temporal.brand_device = brand_device.id_brand_device
        WHERE unidades_pds > 0 OR deposito_almacen > 0
        ORDER BY brand_device.brand ASC, temporal.device ASC ');
	
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
        $this->alta_historico_io($elemento,NULL);

	}	
	
	public function alta_agente($data)
	{
		$this->db->insert('agent',$data);
		$id=$this->db->insert_id();



	}	
	
	
	public function baja_dispositivos_almacen_update($id_device,$owner,$units)
    {


    $contar = $this->db->query("SELECT COUNT(id_device) as contador FROM devices_almacen WHERE id_device=$id_device AND owner='$owner' AND status = 1")->row();




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
            $this->alta_historico_io($data,NULL);
            $cont++;
        }




        return $total_baja;

    }else{
        return -1;
    }


}

    /**
     * Método que devuelve el operador (instalador) consultado por ID
     * @param $id_operador
     * @return null
     */
    public function get_operador($id_operador)
    {
        $elem = NULL;

        if(!is_null($id_operador) && $id_operador > 0)
        {
            $query = $this->db->select("*")
                    ->where("id_contact",$id_operador)
                    ->where("type_profile_contact",1)
                    ->get("contact");

                    $elem = $query->row();
        }
        return $elem;
    }
    /**
     * Método que devuelve el dueño consultado por ID
     * @param $id_dueno
     * @return null
     */
    public function get_dueno($id_dueno)
    {
        $elem = NULL;

        if(!is_null($id_dueno) && $id_dueno > 0)
        {
            $query = $this->db->select("*")
                ->where("id_client",$id_dueno)
                ->where("status","Alta")
                ->where("facturable",1)
                ->get("client");

            $elem = $query->row();
        }
        return $elem;
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
	
		$query = $this->db->query('

		SELECT temporal.id_device, brand_device.brand, temporal.device, unidades_pds,
		(CASE WHEN unidades_pds = 0 THEN 0 ELSE CEIL(unidades_pds * 0.05 + 2) END) as stock_necesario,
		unidades_almacen,
		(unidades_almacen - (CASE WHEN unidades_pds = 0 THEN 0 ELSE CEIL(unidades_pds * 0.05 + 2) END)) as balance
		FROM (
		        SELECT device.id_device, device.brand_device, device.device,
                    (
                        SELECT COUNT(*)
                        FROM devices_pds
                        WHERE (devices_pds.id_device = device.id_device) AND
                        (devices_pds.status = "Alta")
                    )
                    as unidades_pds,

                    (
                        SELECT  COUNT(*)
                        FROM devices_almacen
                        WHERE (devices_almacen.id_device = device.id_device) AND
                        (devices_almacen.status = "En stock")
                    )
                    as unidades_almacen FROM device
                ) as temporal

        JOIN brand_device ON temporal.brand_device = brand_device.id_brand_device
        WHERE unidades_pds > 0 OR unidades_almacen > 0
        ORDER BY brand_device.brand ASC, temporal.device ASC ');




		return $query->result();
	}

    /*
     * Generar CSV con el stock cruzado (Balance de activos).
     */
    public function get_stock_cruzado_csv() {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        $query = $this->db->query('

		SELECT temporal.id_device, brand_device.brand, temporal.device, unidades_pds,
		(CASE WHEN unidades_pds = 0 THEN 0 ELSE CEIL(unidades_pds * 0.05 + 2) END) as stock_necesario,
		unidades_almacen,
		(unidades_almacen - (CASE WHEN unidades_pds = 0 THEN 0 ELSE CEIL(unidades_pds * 0.05 + 2) END)) as balance
		FROM (
		        SELECT device.id_device, device.brand_device, device.device,
                    (
                        SELECT COUNT(*)
                        FROM devices_pds
                        WHERE (devices_pds.id_device = device.id_device) AND
                        (devices_pds.status = "Alta")
                    )
                    as unidades_pds,

                    (
                        SELECT  COUNT(*)
                        FROM devices_almacen
                        WHERE (devices_almacen.id_device = device.id_device) AND
                        (devices_almacen.status = "En stock")
                    )
                    as unidades_almacen FROM device
                ) as temporal

        JOIN brand_device ON temporal.brand_device = brand_device.id_brand_device
        WHERE unidades_pds > 0 OR unidades_almacen > 0
        ORDER BY brand_device.brand ASC, temporal.device ASC ');



        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Demo_Real-Balance_Activos.csv', $data);


    }




    /*
    * Generar Exportacion de datos con el stock cruzado (NUEVA FUNCION).
    */
    public function exportar_stock_cruzado($formato="csv") {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');


        $resultados = $this->get_stock_cruzado();

        $arr_titulos = array('Id dispositivo','Fabricante','Dispositivo','Ud. pds','Stock necesario','Uds. Almacén','Balance');
        $excluir = array();
        $datos = preparar_array_exportar($resultados,$arr_titulos,$excluir);
        exportar_fichero($formato,$datos,"Balance_Dispositivos__".date("d-m-Y"));


    }



    public function get_cdm_alarmas() {
	
		$query = $this->db->select('brand_alarm.brand, alarm.alarm, COUNT(*) as incidencias')
		->join('alarm','alarm.id_alarm = material_incidencias.id_alarm')
		->join('brand_alarm','alarm.brand_alarm = brand_alarm.id_brand_alarm')
		->group_by('alarm.alarm')
            ->order_by('incidencias','DESC')
		->order_by('brand_alarm.brand', 'ASC')
		->order_by('alarm.alarm', 'ASC')
		->get('material_incidencias');
	
		return $query->result();
	}


    public function get_balance_alarmas() {


        $meses_funcionamiento = $this->db->query("SELECT PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'), DATE_FORMAT(MIN(fecha),'%Y%m')) AS meses FROM incidencias")->row();
        $meses = $meses_funcionamiento->meses;


        $query = $this->db->query('SELECT brand_alarm.brand as brand, alarm.alarm as alarm, alarm.picture_url as imagen, COUNT(*) as incidencias,
                                      CEIL (COUNT(*) / '.$meses.') as punto_pedido,
                                      units  as unidades_almacen,
                                      units - CEIL (COUNT(*) / '.$meses.') as balance
                                      FROM material_incidencias

                                      JOIN alarm ON alarm.id_alarm = material_incidencias.id_alarm
                                      JOIN brand_alarm ON alarm.brand_alarm = brand_alarm.id_brand_alarm
                                        GROUP BY alarm.alarm
                                        ORDER BY incidencias DESC, brand DESC, alarm DESC'
									);


        return $query->result();
    }

    /*
     * Generar CSV con el stock cruzado (Balance de activos).
     */
    public function exportar_balance_alarmas($formato="csv") {

        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');

        $resultados = $this->get_balance_alarmas();

        $arr_titulos = array('Brand','Alarm','Incidencias','Punto pedido','Uds. almacén','Balance');
        $excluir = array('picture_url','imagen');
        $datos = preparar_array_exportar($resultados,$arr_titulos,$excluir);


        exportar_fichero($formato,$datos,"Balance_Sistemas_Seguridad__".date("d-m-Y"));


    }
	
	
	public function get_cdm_dispositivos()
    {
	
		$query = $this->db->select('brand_device.brand, device.device, COUNT(*) as incidencias')
										->join('devices_almacen','devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen')
										->join('device','devices_almacen.id_device = device.id_device')
										->join('brand_device','device.brand_device = brand_device.id_brand_device')
										->group_by('device.device')
                                        ->order_by('incidencias','DESC')
										->order_by('brand_device.brand', 'ASC')
										->order_by('device.device', 'ASC')
										->get('material_incidencias');
	
		return $query->result();
	}	
	

	public function get_territorios(){
        $query = $this->db->select('territory.*')
                 ->order_by('id_territory','asc')
                ->get('territory');
        return $query->result();
    }

    public function get_fabricantes(){
        $query = $this->db->select('brand_device.*')
            ->order_by('brand','asc')
            ->get('brand_device');
        return $query->result();
    }

    public function get_muebles(){
        $query = $this->db->select('display.*')
            ->where('status','Alta')
            ->order_by('display','asc')
            ->get('display');
        return $query->result();
    }

    public function get_muebles_OLD(){
        $query = $this->db->select('display.*')
            ->where('status','Alta')
            ->order_by('display','asc')
            ->get('display');
        return $query->result();
    }


    public function get_terminales(){
        $query = $this->db->select('device.*')
            ->where('status','Alta')
            ->order_by('device','asc')
            ->get('device');
        return $query->result();
    }



    public function search_pds($id) {


		if($id != FALSE) {
			$query = $this->db->select('pds.*,pds_tipo.titulo as tipo,pds_subtipo.titulo as subtipo,pds_segmento.titulo as segmento,pds_tipologia.titulo as tipologia,territory.territory')
				    ->join('pds_tipo','pds.id_tipo= pds_tipo.id')
                    ->join('pds_subtipo','pds.id_subtipo= pds_subtipo.id')
                    ->join('pds_segmento','pds.id_segmento= pds_segmento.id')
                    ->join('pds_tipologia','pds.id_tipologia= pds_tipologia.id')

				   ->join('territory','pds.territory = territory.id_territory')
				   ->like('pds.reference',$id)
			       ->get('pds');

			return $query->result();
		}
		else {
			return FALSE;
		}
	}

    public function search_pds_OLD($id) {


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
            ->get('panelado')->row();


            return $query;


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




	
	function exportar_facturacion($formato="csv",$fecha_inicio,$fecha_fin,$instalador = NULL,$dueno=NULL)
	{
		$this->load->dbutil();
		$this->load->helper('file');
        $this->load->helper('csv');

		$this->load->helper('download');
        $this->load->model(array("contact_model","client_model"));



        $query = $this->db->select('facturacion.fecha, MONTH(facturacion.fecha) as mes, pds.reference AS SFID, type_pds.pds,
		facturacion.id_intervencion AS visita, COUNT(facturacion.id_incidencia) AS incidencias,
		contact.contact AS instalador, client.client AS dueno, SUM(facturacion.units_device) AS dispositivos,
		SUM(facturacion.units_alarma) AS otros')
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


        $arr_titulos = array('Fecha','Mes','SFID','Tipo tienda','Intervención','Nº Incidencias','Instalador',
            'Dueño','Dispositivos','Otros');
        $excluir = array();

        $data = preparar_array_exportar($query->result(),$arr_titulos,$excluir);

        //print_r($this->db->last_query());

        // GENERAR NOMBRE DE FICHERO


        $filename["dueno"] = (!is_null($dueno)) ? $this->client_model->getById($dueno)->getName() : NULL;                   // Campo a sanear

        $filename["instalador"]  = (!is_null($instalador)) ? $this->contact_model->getById($instalador)->getName() : NULL;  // Campo a sanear



        foreach($filename as $key=>$f_name)
        {
            $filename[$key] = str_sanitize($f_name);
        }
        $filename["fecha_inicio"] = (!is_null($fecha_inicio)) ? date("d-m-Y",strtotime($fecha_inicio)) : NULL;
        $filename["fecha_fin"] = (!is_null($fecha_fin)) ? date("d-m-Y",strtotime($fecha_fin)) : NULL;

        $str_filename = implode("___",$filename);

        exportar_fichero($formato,$data,'Facturacion-'.$str_filename);

	}



    public function facturacion_estado_intervencion($fecha_inicio,$fecha_fin,$instalador = NULL,$dueno=NULL) {

        $query = $this->db->select('
                    incidencias.fecha_cierre as fecha,
                    facturacion.id_incidencia as id_incidencia,
                    incidencias.status_pds,
                    pds.reference AS SFID,
                    type_pds.pds,
                    facturacion.id_intervencion AS visita,
                    contact.contact AS instalador,
                    client.client as dueno,
                            SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros')

            ->join('intervenciones','facturacion.id_intervencion = intervenciones.id_intervencion')
            ->join('intervenciones_incidencias','intervenciones.id_intervencion = intervenciones_incidencias.id_intervencion')
            ->join('incidencias','incidencias.id_incidencia = intervenciones_incidencias.id_incidencia')


            ->join('pds','facturacion.id_pds = pds.id_pds')
            ->join('type_pds','pds.type_pds = type_pds.id_type_pds')
            ->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds')
            ->join('display','displays_pds.id_display = display.id_display')
            ->join('contact','intervenciones.id_operador = contact.id_contact', 'left')
            ->join('client','display.client_display= client.id_client', 'left')

            ->where('incidencias.fecha_cierre >=',$fecha_inicio)
            ->where('incidencias.fecha_cierre <=',$fecha_fin)
            ;

        if(!is_null($instalador) && !empty($instalador)){
            $query = $this->db->where('intervenciones.id_operador',$instalador);
        }
        if(!is_null($dueno) && !empty($dueno)){
            $query = $this->db->where('display.client_display',$dueno);
        }
        $query = $this->db->group_by('facturacion.id_facturacion');

            $query = $this->db->order_by('incidencias.fecha_cierre,facturacion.id_intervencion')
            ->get('facturacion');


        $resultado = $query->result();

        // Guardamos en un array, los elementos facturables, que lo son porque todas sus incidencias asignadas han
        // sido cerradas o resueltas.
        //
        $facturacion = array();

        //echo "PRE".count($resultado)." - ";
        /* Recorro las intervenciones-incidencia así, si una intervencion tiene finalizadas todas sus incidencias, lo dejamos en el array. */
        foreach($resultado as $intervencion)
        {
            //print_r($intervencion);
            if($intervencion->status_pds == "Finalizada")
            {
                if(array_key_exists($intervencion->visita,$facturacion)){
                    //echo "Ya existe\n".$intervencion->visita;
                }
                $facturacion[$intervencion->visita] = $intervencion;
            }
            else
            {
                $facturacion[$intervencion->visita] = $intervencion;
                // Si existe la clave, eliminamos la intervención pq aún no es facturable por proveedor...
                if(array_key_exists($intervencion->visita,$facturacion))
                {
                    unset($facturacion[$intervencion->visita]);
                }
            }

        }

        /*
        $sql_borrar = 'DROP TABLE IF EXISTS incidencias_intervencion';
        $this->db->query($sql_borrar);
        $cond_fecha = "";

        if(!is_null($fecha_inicio)) $cond_fecha .= (' AND inc.`fecha_cierre` >= "'.$fecha_inicio.'" ');
        if(!is_null($fecha_fin)) $cond_fecha .= (' AND inc.`fecha_cierre` <= "'.$fecha_fin.'" ');



        $sql_crear = '
            CREATE temporary table IF NOT EXISTS incidencias_intervencion
            AS (
                    SELECT interv.`id_intervencion` as id_intervencion,
                    (SELECT COUNT(incidencias.id_incidencia)
                        FROM incidencias
                        JOIN intervenciones_incidencias ON incidencias.id_incidencia = intervenciones_incidencias.id_incidencia
                        WHERE id_intervencion = interv.`id_intervencion`) -
                    (SELECT COUNT(incidencias.id_incidencia)
                        FROM incidencias
                        JOIN intervenciones_incidencias ON incidencias.id_incidencia = intervenciones_incidencias.id_incidencia
                        WHERE id_intervencion = interv.`id_intervencion` AND `incidencias`.status_pds = "Finalizada") as incidencias_abiertas,
                    MAX(inc.fecha_cierre) as fecha_cierre
                FROM    `facturacion` fact,
                        `intervenciones_incidencias` interv,
                        `incidencias` inc

                WHERE
                    fact.id_intervencion = interv.id_intervencion
                    AND inc.id_incidencia = interv.id_incidencia
                    '.$cond_fecha.'
            );';
           */

      // echo "POST".count($facturacion)." - ";
        return $facturacion;



    }


    function exportar_intervenciones_facturacion($formato="csv",$fecha_inicio,$fecha_fin,$instalador = NULL,$dueno=NULL)
    {
        $this->load->dbutil();
        $this->load->helper(array('file','csv'));
        $this->load->helper('download');

        $this->load->model(array("contact_model","client_model"));


        // REALIZAMOS LA CONSULTA A LA BD
        $query = $this->db->select('
                    incidencias.fecha_cierre as fecha,
                    MONTH(incidencias.fecha_cierre) as mes,
                    facturacion.id_intervencion AS visita,
                    incidencias.status_pds,
                    pds.reference AS SFID,
                    type_pds.pds,
                    contact.contact AS instalador,
                    client.client as dueno,
                            SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros')

            ->join('intervenciones','facturacion.id_intervencion = intervenciones.id_intervencion')
            ->join('intervenciones_incidencias','intervenciones.id_intervencion = intervenciones_incidencias.id_intervencion')
            ->join('incidencias','incidencias.id_incidencia = intervenciones_incidencias.id_incidencia')


            ->join('pds','facturacion.id_pds = pds.id_pds')
            ->join('type_pds','pds.type_pds = type_pds.id_type_pds')
            ->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds')
            ->join('display','displays_pds.id_display = display.id_display')
            ->join('contact','intervenciones.id_operador = contact.id_contact', 'left')
            ->join('client','display.client_display= client.id_client', 'left')

            ->where('incidencias.fecha_cierre >=',$fecha_inicio)
            ->where('incidencias.fecha_cierre <=',$fecha_fin)
        ;
        if(!is_null($instalador) && !empty($instalador)){
            $query = $this->db->where('intervenciones.id_operador',$instalador);
        }
        if(!is_null($dueno) && !empty($dueno)){
            $query = $this->db->where('display.client_display',$dueno);
        }
        $query = $this->db->group_by('facturacion.id_facturacion');
        $query = $this->db->order_by('incidencias.fecha_cierre,facturacion.id_intervencion')
            ->get('facturacion');
        $resultado = $query->result();

        // DEFINO LAS CABECERAS DEL LISTADO A EXPORTAR
        $facturacion = array(
            array('Fecha','Mes','Intervencion','Estado','SFID','Tipo tienda','Instalador','Dueño','Dispositivos','Alarmas')
        );



        /* Recorro las intervenciones-incidencia así, si una intervencion tiene finalizadas todas sus incidencias, lo dejamos en el array.
        Y en caso contrario lo omitimos del informe. */
        foreach($resultado as $intervencion)
        {
            $intervencion->fecha = date("d/m/Y",strtotime(($intervencion->fecha)));  // Formato ES para la fecha
            if($intervencion->status_pds == "Finalizada")   // Si la incidencia actual
            {
                if(array_key_exists($intervencion->visita,$facturacion)){
                    //echo "Ya existe\n".$intervencion->visita;
                }
                $facturacion[$intervencion->visita] = (array)$intervencion;
            }
            else
            {
                $facturacion[$intervencion->visita] = (array)$intervencion;
                // Si existe la clave, eliminamos la intervención pq aún no es facturable por proveedor...
                if(array_key_exists($intervencion->visita,$facturacion))
                {
                    unset($facturacion[$intervencion->visita]);
                }
            }
        }

        // GENERAR NOMBRE DE FICHERO
        $filename["instalador"]  = (!is_null($instalador)) ? $this->contact_model->getById($instalador)->getName() : NULL;  // Campo a sanear
        $filename["dueno"] = (!is_null($dueno)) ? $this->client_model->getById($dueno)->getName() : NULL;                   // Campo a sanear
        foreach($filename as $key=>$f_name)
        {
            $filename[$key] = str_sanitize($f_name);
        }
        $filename["from"] = date("d-m-Y",strtotime($fecha_inicio));     // Campo no saneable
        $filename["to"] = date("d-m-Y",strtotime($fecha_fin));          // Campo no saneable
        $f_nombre = implode("__",$filename);

        // EXPORTAR EL RESULTADO
        exportar_fichero($formato,$facturacion,$f_nombre);

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


    public function get_tipos_incidencia()
    {
        $query = $this->db->select("id_type_incidencia,title")
                ->order_by('title ASC')
                ->get('type_incidencia');

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

    public function get_sfid($sfid) {
        if($sfid != FALSE) {
            $query = $this->db->select('pds.*, province.province, territory.territory')
                ->join('type_pds','pds.type_pds = type_pds.id_type_pds')
                ->join('province','pds.province = province.id_province')
                ->join('territory','pds.territory = territory.id_territory')
                ->like('pds.reference',$sfid,'none')
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
	public function get_incidencias($page = 1, $cfg_pagination = NULL,$array_orden= NULL,$filtros=NULL, $tipo="abiertas") {

        $this->db->select('incidencias.*,pds.reference as reference, device.brand_device as fabricante,
                            territory.territory as territory,
                       (SELECT brand_device.brand from brand_device  WHERE id_brand_device = fabricante
                            ) as brand')
        ->join('pds','incidencias.id_pds = pds.id_pds','left')
        ->join('devices_pds','incidencias.id_devices_pds=devices_pds.id_devices_pds','left')
        ->join('device','devices_pds.id_device=device.id_device','left')
            ->join('territory','territory.id_territory=pds.territory','left')
       ;



        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["status"]) && !empty($filtros["status"])) $this->db->where('incidencias.status',$filtros['status']);
        if(isset($filtros["status_pds"]) && !empty($filtros["status_pds"])) $this->db->where('incidencias.status_pds',$filtros['status_pds']);
        if(isset($filtros["id_incidencia"]) && !empty($filtros["id_incidencia"])) $this->db->where('id_incidencia',$filtros['id_incidencia']);
        if(isset($filtros["territory"]) && !empty($filtros["territory"])) $this->db->where('pds.territory',$filtros['territory']);
        if(isset($filtros["brand_device"]) && !empty($filtros["brand_device"])) {
            $this->db->where('incidencias.fail_device','1');
            $this->db->where('device.brand_device',$filtros['brand_device']);
        }
        if(isset($filtros["reference"]) && !empty($filtros["reference"])) $this->db->where('reference',$filtros['reference']);


        /* Obtenemos la condición por tipo de incidencia */
        $this->db->where($this->get_condition_tipo_incidencia($tipo));

        $campo_orden = $orden = NULL;
        if(count($array_orden) > 0) {
            foreach ($array_orden as $key=>$value){
                $campo_orden = $key;
                $orden = $value;
            }
        }
        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($orden) && !empty($orden)) {
            $s_orden = $campo_orden. " ".$orden;
            $this->db->order_by($s_orden);
        }else{
            $this->db->order_by('fecha DESC');
        }

        $query =   $this->db->get('incidencias',$cfg_pagination['per_page'], ($page-1) * $cfg_pagination['per_page']);
       // echo $this->db->last_query();

		return $query->result();
	}




    /**
     * Función que devuelve la condición a usar en un where para determinar si la incidencia/s son abiertas o cerradas
     * según los estados status y status_pds
     * @param string $tipo
     * @return mixed
     */
    public function get_condition_tipo_incidencia($tipo = "abiertas"){

        /*  ESTADOS ABIERTOS SAT: Nueva, Revisada, Instalador asignado, Material asignado, Comunicada
           ESTADOS CERRADOS SAT: Resuelta, Pendiente recogida, Cerrada, Cancelada */

        /*  ESTADOS ABIERTOS PDS: Alta realizada, En proceso, En visita
            ESTADOS CERRADOS PDS: Finalizada, Cancelada */

        if($tipo === "abiertas" )
        {
            $cond = '((incidencias.status != "Resuelta" && incidencias.status != "Pendiente recogida" && incidencias.status != "Cerrada" && incidencias.status != "Cancelada")
                        &&  (incidencias.status_pds != "Finalizada" && incidencias.status_pds != "Cancelada"))';
        }
        else
        {
            $cond = '((incidencias.status = "Resuelta" || incidencias.status = "Pendiente recogida" || incidencias.status = "Cerrada" || incidencias.status = "Cancelada")
                    && (incidencias.status_pds = "Finalizada" || incidencias.status_pds = "Cancelada")) ';
        }

        return $cond;
    }

    public function get_incidencias_quantity($filtros=NULL, $tipo="abiertas") {

        $this->db->select('COUNT(incidencias.id_incidencia) AS cantidad')
            ->join('pds','incidencias.id_pds = pds.id_pds');

        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["status"]) && !empty($filtros["status"])) $this->db->where('incidencias.status',$filtros['status']);
        if(isset($filtros["status_pds"]) && !empty($filtros["status_pds"])) $this->db->where('incidencias.status_pds',$filtros['status_pds']);
        if(isset($filtros["id_incidencia"]) && !empty($filtros["id_incidencia"])) $this->db->where('id_incidencia',$filtros['id_incidencia']);
        if(isset($filtros["territory"]) && !empty($filtros["territory"])) $this->db->where('pds.territory',$filtros['territory']);
        if(isset($filtros["brand_device"]) && !empty($filtros["brand_device"])) {
            $this->db->join('devices_pds','incidencias.id_devices_pds = devices_pds.id_devices_pds');
            $this->db->join('device','devices_pds.id_device=device.id_device');
            $this->db->where('incidencias.fail_device','1');
            $this->db->where('device.brand_device',$filtros['brand_device']);
        }
        if(isset($filtros["reference"]) && !empty($filtros["reference"])) $this->db->where('reference',$filtros['reference']);


        /**
         * Determinado el tipo por parámetro añadir distinción de tipo: abiertas o cerradas.
         */

        $this->db->where($this->get_condition_tipo_incidencia($tipo));

        //echo $this->db->last_query();
        /* Obtener el resultado */
        $query =  $this->db->get('incidencias')->row();

        //echo $this->db->last_query();
        return $query->cantidad;



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
        $ahora = date("Y-m-d H:i:s");

		$this->db->set('status_pds', $status_pds, FALSE);
		$this->db->set('status', $status, FALSE);
        $this->db->set('last_updated', "'$ahora'", FALSE);
		$this->db->where('id_incidencia',$id);
		$this->db->update('incidencias');
	}	

	public function incidencia_update_cierre($id,$fecha_cierre)
	{
        $ahora = date("Y-m-d H:i:s");
		$this->db->set('fecha_cierre', $fecha_cierre);
        $this->db->set('last_updated', "'$ahora'", FALSE);
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
		
	
	public function incidencia_update_device_pds($id_devices_pds,$status,$id_incidencia = NULL)
	{
		$this->db->set('status', $status, FALSE);
		$this->db->where('id_devices_pds',$id_devices_pds);
		$this->db->update('devices_pds');

        /**
         * Cambiar last_updated
         */
        if(!is_null($id_incidencia)){
            $ahora = date("Y-m-d H:i:s");
            $this->db->set('last_updated', "'$ahora'", FALSE);
            $this->db->where('id_incidencia',$id_incidencia);
            $this->db->update('incidencias');
        }


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

        $this->alta_historico_io($data,$id);

	}


    /**
     * Inserción de la Salida (No procesada hasta que no sea definitivo el material de la intervención,
     * al cambiar de estado la incidencia).
     *
     */
    public function alta_historico_io($data,$id){

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
                                          WHERE material_incidencias.id_devices_almacen=" . $data['id_devices_almacen'])->row();



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


            $this->db->insert('historico_io',$elemento);

        }
        elseif($tipo==="alarm")         // ES DE TIPO ALARMA
        {
            $q_dueno = $this->db->query("SELECT client_alarm as id_client FROM alarm WHERE id_alarm=" . $data['id_alarm'])->row();


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


            $this->db->insert('historico_io',$elemento);
        }



    }

    /**
 * Método que da de baja unba alarma de la tabla de historico_io, porque se ha desasignado de una incidencia.
 *
 * @param $id_material
 */
    public function baja_historico_io($id_material=NULL)
    {
        if(! is_null($id_material))
        {
            $this->db->where('id_material_incidencia',$id_material);
            $this->db->delete('historico_io');
        }

    }


    /**
     * Método que da por procesadas  las alarma de la tabla de historico_io, porque se han
     * desasignado definitivamente a una incidencia, y ya deben aparecer en el histórico.
     *
     * @param $id_material
     */
    public function procesar_historico_incidencia($id_incidencia=NULL)
    {
        if(! is_null($id_incidencia))
        {
            $historico_io['procesado'] = 1;

            $this->db->where('id_incidencia',$id_incidencia);
            $this->db->update('historico_io',$historico_io);
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



    /**
     * Insertar mueble SFID
     */
    function anadir_mueble_sfid($display,$pds,$position = 0)
    {

        // Si no están vacíos los objetos MUEBLE y PDS
        if(!empty($display) && !empty($pds))
        {
            $id_pds = $pds["id_pds"];
            $id_display = $display["id_display"];
            $client_type_pds = 1;

            $SQL = " INSERT INTO displays_pds (client_type_pds, id_pds, id_display, position, status)
                                        VALUES(".$client_type_pds.",".$id_pds.",".$id_display.",".$position.",'Alta'); ";

            $this->db->query($SQL);
            $id_displays_pds = $this->db->insert_id();

            // Buscar el planograma genérico del mueble
            $planograma = $this->get_devices_display($id_display);

            if(!empty($planograma))
            {
                foreach ($planograma as $terminal)
                {
                    $position = $terminal->position;
                    $id_device = $terminal->id_device;
                    $SQL = " INSERT INTO devices_pds(client_type_pds,id_pds, id_displays_pds, id_display, position, id_device, status)
                                            VALUES(".$client_type_pds.",".$id_pds.",".$id_displays_pds.",".$id_display.",".$position.",".$id_device.",'Alta'); ";
                    $this->db->query($SQL);
                }
            }

        }


    }


}

?>