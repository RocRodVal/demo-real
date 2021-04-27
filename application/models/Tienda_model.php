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
            'procesado' => 1,
            'status'    =>'En stock'
        );
        $this->alta_historico_io($elemento,NULL);

	}	
	
	public function alta_agente($data)
	{
		$this->db->insert('agent',$data);
		$id=$this->db->insert_id();
        return $id;
	}

    /*
     * Actualiza el estado de los terminales. En el caso de que nos envien IMEIs de éstos se cambiara el estado a los
     * dispositivos que tengan ese IMEI
     */
    public function baja_dispositivos_almacen_update($id_device,$units,$status_inc,$status_dest,$imeis=NULL)
    {

        $join="";
        $agregarIMEI=false;
        $array_imeis=array();
        if(!empty($imeis)) {

            $array_imeis=explode("\n",$imeis);
            foreach ($array_imeis as $key => $value){
                $array_imeis[$key]="'".trim($value." ")."'";
            }

        }
        $error=false;
        switch ($status_inc){
            case 1: {
                $statusI="En stock";
                if (!empty($array_imeis)) {
                    $encontrados=$this->search_dispositivos($statusI,$array_imeis);

                    if ($encontrados==count($array_imeis)) {
                        $agregarIMEI=true;
                    } else{
                        if($encontrados==0){
                            $agregarIMEI=false;
                        }else {
                            $error=true;
                        }
                    }
                }else {
                    $agregarIMEI=false;
                }
                if($agregarIMEI && (!empty($array_imeis))) {
                    $condicionImei = " AND IMEI IN (" . implode(",", $array_imeis) . ")";;
                }else {
                   // if (empty($array_imeis)) {
                        $condicionImei = " AND IMEI IS NULL";
                    /*}else {
                        $condicionImei = " AND IMEI IN (" . implode(",", $array_imeis) . ")";
                    }*/
                }
                $condicion=" AND status='En stock' ".$condicionImei;

                break;
            }
            case 2:{
                $statusI="Reservado";
                if (!empty($array_imeis)) {
                    $encontrados=$this->search_dispositivos($statusI,$array_imeis);

                    if ($encontrados==count($array_imeis)) {
                        $agregarIMEI=true;
                    } else{
                        if($encontrados==0){
                            $agregarIMEI=false;
                        }else {
                            $error=true;
                        }
                    }
                }else {
                    $agregarIMEI=false;
                }
                if ($agregarIMEI && !empty($array_imeis)) {
                    $condicionImei=" AND IMEI IN (".implode(",",$array_imeis).")";
                }else {
                    $condicionImei=" AND IMEI IS NULL";
                }

                $condicion=" AND material_incidencias.id_devices_almacen IS NULL AND status='Reservado' ".$condicionImei;
                $join="LEFT JOIN material_incidencias ON material_incidencias.id_devices_almacen=devices_almacen.id_devices_almacen ";

                break;
            }
            case 3:{
                $statusI="Televenta";
                if (!empty($array_imeis)) {
                    $encontrados=$this->search_dispositivos($statusI,$array_imeis);

                    if ($encontrados==count($array_imeis)) {
                        $agregarIMEI=true;
                    } else{
                        if($encontrados==0){
                            $agregarIMEI=false;
                        }else {
                            $error=true;
                        }
                    }
                }else {
                    $agregarIMEI=false;
                }
                if ($agregarIMEI && !empty($array_imeis)) {
                    $condicionImei=" AND IMEI IN (".implode(",",$array_imeis).")";
                }else {
                    $condicionImei=" AND IMEI IS NULL";
                }

                $condicion=" AND status='Televenta' ".$condicionImei;
                //$join="LEFT JOIN material_incidencias ON material_incidencias.id_devices_almacen=devices_almacen.id_devices_almacen ";

                break;
            }
            case 4: {
                $statusI="Transito";
                if (!empty($array_imeis)) {
                    $encontrados=$this->search_dispositivos($statusI,$array_imeis);

                    if ($encontrados==count($array_imeis)) {
                        $agregarIMEI=true;
                    } else{
                        if($encontrados==0){
                            $agregarIMEI=false;
                        }else {
                            $error=true;
                        }
                    }
                }else {
                    $agregarIMEI=false;
                }
                if ($agregarIMEI && !empty($array_imeis)) {
                    $condicionImei=" AND IMEI IN (".implode(",",$array_imeis).")";
                }else {
                    $condicionImei=" AND IMEI IS NULL";
                }
                $condicion=" AND material_incidencias.id_devices_almacen IS NULL AND status='Transito' AND devices_almacen.id_incidencia IS NULL ".$condicionImei;
                $join="LEFT JOIN material_incidencias ON material_incidencias.id_devices_almacen=devices_almacen.id_devices_almacen ";

                break;
            }
            case 5: {
                $statusI="RMA";
                if (!empty($array_imeis)) {
                    $encontrados=$this->search_dispositivos($statusI,$array_imeis);

                    if ($encontrados==count($array_imeis)) {
                        $agregarIMEI=true;
                    } else{
                        if($encontrados==0){
                            $agregarIMEI=false;
                        }else {
                            $error=true;
                        }
                    }
                }else {
                    $agregarIMEI=false;
                }
                if ($agregarIMEI && !empty($array_imeis)) {
                    $condicionImei=" AND IMEI IN (".implode(",",$array_imeis).")";
                }else {
                    $condicionImei=" AND IMEI IS NULL";
                }
                $condicion=" AND material_incidencias.id_devices_almacen IS NULL AND status='RMA' ".$condicionImei;
                $join="LEFT JOIN material_incidencias ON material_incidencias.id_devices_almacen=devices_almacen.id_devices_almacen ";

                break;
            }
            /* case 5: {
                 $condicion=" AND status='Baja'";
                 $statusI="Baja";
                 break;
             }*/
            default: $condicion="";
        }
        $cantidad=-1;
        switch ($status_dest){
            case 1: {
                $statusD="En stock";
                $cantidad=1;
                break;
            }
            case 2:{
                $statusD="Reservado";
                break;
            }
            case 3:{
                $statusD="Televenta";
                break;
            }
            case 4: {
                $statusD="Transito";
                break;
            }
            case 5: {
                $statusD="Baja";
                break;
            }
            case 6: {
                $statusD="RMA";
                break;
            }

        }

        if (!$error) {

            //$sql = "SELECT COUNT(id_device) as contador FROM devices_almacen " . $join . " WHERE id_device=$id_device AND status='".$statusI."'".$condicionImei;
            $sql = "SELECT count(distinct devices_almacen.id_devices_almacen) as contador FROM devices_almacen " . $join .
                " WHERE id_device=$id_device AND status='".$statusI."'".$condicionImei;
            $contar = $this->db->query($sql)->row();

            if ($contar->contador > 0) {
                if(empty($units)) {
                    $units = $contar->contador;
                }
                else {
                    $units = (($units > $contar->contador) ? $contar->contador : $units);
                }

                $sql = "SELECT devices_almacen.id_devices_almacen,devices_almacen.id_device FROM devices_almacen " . $join . " 
                WHERE id_device=$id_device " . $condicion . " LIMIT " . $units;

                $dispositivos_a_borrar = $this->db->query($sql)->result();

                $cont = 0;

                if($units==count($array_imeis) || (count($array_imeis)==0)) {
                    if(!empty($dispositivos_a_borrar)) {
                        // Recorremos los dispositivos a borrar.
                        foreach ($dispositivos_a_borrar as $dispositivo_baja) {

                            $id_device = $dispositivo_baja->id_device;
                            $id_devices_almacen = $dispositivo_baja->id_devices_almacen;

                            if ($agregarIMEI) {
                                $this->db->set('IMEI', trim($array_imeis[$cont], "'"));
                            }

                            // actualizado el estado del dispositivo.
                            $this->db->set('status', $statusD);
                            $this->db->where('id_devices_almacen', $id_devices_almacen);
                            $this->db->update('devices_almacen');

                            //   echo $this->db->last_query()."<br>";
                            // Insertar operación de baja en el histórico
                            $data = array(
                                'id_devices_almacen' => $id_devices_almacen,
                                'id_device' => $id_device,
                                'fecha' => date('Y-m-d H:i:s'),
                                'unidades' => $cantidad, // En positivo porque luego la función lo pasará a negativo
                                'procesado' => 1,
                                'status' => $statusD
                            );
                            $this->db->insert("historico_io", $data);
                            //echo $this->db->last_query()."<br>";
                            $cont++;
                        }

                        return $cont;
                    }else {
                        return -1;
                    }
                }else {
                    return -2;
                }
            } else {
                return -1;
            }

        }else
        { return -1;}
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

    public function borrar_dispositivos($id_pds)
    {
        /*buscamos los dispositivos de la tienda*/
        $sql = "SELECT * FROM devices_pds WHERE status ='Alta'
				AND id_pds ='".$id_pds."'";
        $resultado = $this->db->query($sql)->result();

        foreach ($resultado as $device) {
            /*Insertar en el historico de tienda el estado del dispositivo*/
            $elemento = array(
                'id_devices_pds' => $device->id_devices_pds,
                'fecha' => date('Y-m-d H:i:s'),
                'status' => 'Baja',
                'motivo' => CIERRE_TIENDA
            );
           // print_r($elemento); echo "<br>";
            $this->insert_historico_devicesPDS($elemento);
        }

        $sql = "UPDATE devices_pds SET status ='Baja' WHERE id_pds ='".$id_pds."'";
        $this->db->query($sql);
    }

    /*Recibe el elemento a insertar en la tabla historico_devicesPDS*/
    public function insert_historico_devicesPDS($elemento){
        $this->db->insert('historico_devicesPDS', $elemento);
    }

    /*Realiza el borrado logico de los muebles de una tienda*/
	public function borrar_muebles($id_pds)
	{
		$sql = "UPDATE displays_pds SET status ='Baja'
				WHERE id_pds ='".$id_pds."'";

		$this->db->query($sql);
	}


	/*Da de baja la tienda indicada*/
    public function cerrar_pds($sfid, $id_pds)
    {
        $this->db->set('status','Baja');
        /**$this->db->set('reference','X-'.$sfid);**/
        $this->db->set('reference',$sfid. "-" .time());
        $this->db->where('id_pds', $id_pds);
        $this->db->update('pds');
    }


	/*
	 * Calculo del stock para el cuadro del balance
	 */
    public function get_stock_cruzado($array_filtros=NULL,$page = 1, $cfg_pagination = NULL) {

        //Preparamos la consulta con los filtros de la busqueda en el caso de que se haya seleccionado alguno
        $where='';
        if(isset($array_filtros["id_modelo"]) && !empty($array_filtros["id_modelo"])) {
            $where .= ' AND temporal.id_device = ' . $array_filtros['id_modelo'];
        }
        if(isset($array_filtros["id_marca"]) && !empty($array_filtros["id_marca"])) {
            $where .= ' AND brand_device.id_brand_device = ' . $array_filtros['id_marca'];
        }

        $limit='';
        if(!empty($cfg_pagination)) {
            if ($page==1) {
                $limit=" LIMIT ".$page  *$cfg_pagination['per_page'];
            } else {
                if($page>1){
                    $limit=" LIMIT ".($page-1) *$cfg_pagination['per_page'].",".  $cfg_pagination['per_page'];
                }
            }
        }

        $this->db->query(" DROP VIEW IF EXISTS robados;");
        /*Creación de una vista que guarda los datos de los terminales robados y que no han llegado al almacen*/
        $this->db->query("CREATE VIEW robados AS 
                        SELECT devices_pds.id_device,COUNT(*) as suma 
                        FROM devices_pds 
                        INNER JOIN incidencias ON incidencias.id_devices_pds=devices_pds.id_devices_pds 
                        WHERE (devices_pds.status = \"Baja\" AND incidencias.tipo_averia=\"Robo\" and 
                        incidencias.status_pds=\"Finalizada\" and incidencias.id_incidencia NOT IN 
                        (select id_incidencia from devices_almacen where id_incidencia IS NOT NULL))
                        GROUP BY devices_pds.id_device");
        /*
         *  OR
                        (devices_pds.status = "RMA" AND incidencias.tipo_averia="Robo" and
                        incidencias.status_pds="Finalizada" and incidencias.id_incidencia NOT IN
                        (select id_incidencia from devices_almacen where id_incidencia IS NOT NULL))
         */

        $query = $this->db->query('
		SELECT temporal.id_device, brand_device.brand, temporal.device, unidades_pds,
		(unidades_transito+unidades_tienda_transito) as unidades_transito, unidades_reservado,
		unidades_rma,unidades_almacen, (case when unidades_robadas is NULL then 0 else unidades_robadas end) as unidades_robadas,
		unidades_televenta,
		(case when unidades_robadas is NULL then (unidades_pds + unidades_tienda_transito+ unidades_transito + unidades_reservado + unidades_rma 
            + unidades_almacen+unidades_televenta) 
            else (unidades_pds + unidades_transito + unidades_reservado + unidades_tienda_transito+ unidades_rma + 
            unidades_almacen+unidades_robadas+unidades_televenta) end) as total,
		(CASE WHEN unidades_pds = 0 THEN 0 ELSE CEIL(unidades_pds * 0.05 + 2) END) as stock_necesario,
		(unidades_almacen - (CASE WHEN unidades_pds = 0 THEN 0 ELSE CEIL(unidades_pds * 0.05 + 2) END)) as balance,
		temporal.status
		FROM (
		        SELECT device.id_device, device.brand_device, device.device, device.status,
                    (
                        SELECT COUNT(*)
                        FROM devices_pds
                        WHERE (devices_pds.id_device = device.id_device) AND
                        (devices_pds.status = "Alta" || devices_pds.status = "Incidencia" )
                    )
                    as unidades_pds,

                    (
                        SELECT  COUNT(*)
                        FROM devices_almacen
                        WHERE (devices_almacen.id_device = device.id_device) AND
                        (devices_almacen.status = "En stock")
                    )
                    as unidades_almacen,
                    ( SELECT COUNT(*)
                    FROM devices_almacen
                    WHERE (devices_almacen.id_device = device.id_device) AND (devices_almacen.status = "Transito") 
                    )
                    as unidades_transito,
                    ( SELECT COUNT(*)
                    FROM devices_pds
                    WHERE devices_pds.id_device = device.id_device AND devices_pds.status = "RMA"
                    )
                    as unidades_tienda_transito,
                    ( SELECT COUNT(*)
                    FROM devices_almacen
                    WHERE (devices_almacen.id_device = device.id_device) AND (devices_almacen.status = "Reservado") 
                    )
                    as unidades_reservado,
                    ( SELECT COUNT(*)
                    FROM devices_almacen
                    WHERE (devices_almacen.id_device = device.id_device) AND (devices_almacen.status = "RMA") 
                    )
                    as unidades_rma,
                    ( SELECT suma FROM robados  
                      WHERE robados.id_device = device.id_device 
                      ) 
                    as unidades_robadas ,
                      ( SELECT COUNT(*)
                        FROM devices_almacen
                        WHERE (devices_almacen.id_device = device.id_device) AND (devices_almacen.status = "Televenta") 
                    )
                    as unidades_televenta

                    FROM device
                ) as temporal

        JOIN brand_device ON temporal.brand_device = brand_device.id_brand_device
        WHERE temporal.status = "Alta" ' .$where. ' ORDER BY brand_device.brand ASC, temporal.device ASC '.$limit);

//echo $this->db->last_query(); exit;

        return $query->result();
    }
    /*
    * Generar Exportacion de datos con el stock cruzado (NUEVA FUNCION).
    */
    public function exportar_stock_cruzado($formato="csv",$controler="admin",$array_filtros=NULL) {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');

        $resultados = $this->get_stock_cruzado($array_filtros);
        $arr_titulos = array('Id dispositivo', 'Fabricante', 'Dispositivo', 'Uds. tienda', 'Uds. Transito',
            'Uds. Reservadas','Uds. Almacén RMA','Uds. Almacén','Uds. Robadas','Televenta','Total', 'Stock necesario', 'Balance');
        $excluir = array('status','unidades_tienda_transito');

        $datos = preparar_array_exportar($resultados,$arr_titulos,$excluir);
        exportar_fichero($formato,$datos,"Balance_Dispositivos__".date("d-m-Y"));
    }

    /*
   * Generar Exportacion de datos con el stock cruzado (NUEVA FUNCION).
   */
    public function exportar_cdm_dispositivos($formato="csv") {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');

        $resultados = $this->get_cdm_dispositivos();

        $arr_titulos = array('Marca','Modelo','Incidencias');
        $excluir = array('status');
        $datos = preparar_array_exportar($resultados,$arr_titulos,$excluir);
        exportar_fichero($formato,$datos,"Incidencias_Dispositivos__".date("d-m-Y"));
    }

    /*
    * Generar Exportacion de dispositivos en almacén
    */
    public function exportar_dispositivos_almacen($formato="csv") {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');

        $resultados = $this->tienda_model->get_devices_almacen_exportar();

        $arr_titulos = array('Dispositivo','Unidades');
        $excluir = array();

        $datos = preparar_array_exportar($resultados,$arr_titulos,$excluir);
        exportar_fichero($formato,$datos,"Dispositivos_Almacen__".date("d-m-Y"));


    }

    /*
    * Generar Exportacion de alarmas en almacén
    */
    public function exportar_alarmas_almacen($formato="csv") {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');

        $resultados = $this->tienda_model->get_alarms_almacen_reserva_exportar();

        $arr_titulos = array('Marca', 'Alarma', 'Unidades');
        $excluir = array();

        $datos = preparar_array_exportar($resultados,$arr_titulos,$excluir);
        exportar_fichero($formato,$datos,"Balance_Alarmas__".date("d-m-Y"));
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


    public function get_terminales(){
        $query = $this->db->select('device.*')
            ->where('status','Alta')
            ->order_by('device','asc')
            ->get('device');
        return $query->result();
    }



    public function search_pds($id,$status=NULL) {


		if($id != FALSE) {
			$query = $this->db->select('pds.*,pds_tipo.titulo as tipo,pds_subtipo.titulo as subtipo,pds_segmento.titulo as segmento,pds_tipologia.titulo as tipologia,territory.territory')
				    ->join('pds_tipo','pds.id_tipo= pds_tipo.id')
                    ->join('pds_subtipo','pds.id_subtipo= pds_subtipo.id')
                    ->join('pds_segmento','pds.id_segmento= pds_segmento.id')
                    ->join('pds_tipologia','pds.id_tipologia= pds_tipologia.id')

				   ->join('territory','pds.territory = territory.id_territory')
				   ->like('pds.reference',$id);

                    if(!is_null($status))
                    {
                        $query = $this->db->where('status',$status);
                    }
            $query = $this->db->get('pds');



			return $query->result();
		}
		else {
			return FALSE;
		}
	}


    public function search_id_pds($sfid) {

        $query = $this->db->select('id_pds')
            ->where('pds.reference',$sfid);
        $query = $this->db->get('pds');

        $resultado = $query->row();

        if(!empty($resultado)) {
            return $resultado->id_pds;
        }else{
            return NULL;
        }

    }

    /*Busca un dispositivo por IMEI/SN/MAC/BARCODE en el almacen*/
    public function search_dispositivo($codigo) {
		if($codigo != FALSE) {
			$query = $this->db->select('devices_almacen.*')
			//->where('devices_almacen.status','En stock')
			->where("(devices_almacen.IMEI LIKE '%{$codigo}%' OR devices_almacen.mac LIKE '%{$codigo}%' 
			OR devices_almacen.serial LIKE '%{$codigo}%' OR devices_almacen.barcode LIKE '%{$codigo}%')")
			->get('devices_almacen');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

    /*Busca un dispositivo por id en el almacen*/
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

    /*
     * Busca los IMEIs de los dispositivos que se le pasan como parametro. devuelve el numero de elementos encontrados
     */
    public function search_dispositivos($statusI,$array=null) {
        if(!empty($array)) {
            $i=0;
            $continuar=true;
            while ( $continuar && $i<count($array)) {
                $imei=trim($array[$i],"'");
                $query = $this->db->select('devices_almacen.*')
                    //->where('devices_almacen.status','En stock')
                    ->where("(devices_almacen.IMEI LIKE '$imei' OR devices_almacen.mac LIKE '$imei'
                     OR devices_almacen.serial LIKE '$imei' OR devices_almacen.barcode LIKE '$imei')")
                    ->where("devices_almacen.status",$statusI)
                    ->get('devices_almacen');
                //echo $this->db->last_query();exit;
                $row=$query->row();
                if (!empty($row)) {
                    $continuar=true;
                    $i++;
                }else {
                    $continuar=false;
                }
            }

            return $i;
        }
        else {
            return -1;
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
        /*cuando se cuentan las incidencias de una intervención estas deben tener id_incidencia diferente*/
        $result = array();

        if($this->input->post()) {
            $query = $this->db->select('facturacion.fecha, 
                                        pds.reference AS SFID, 
                                        pds_tipo.titulo as tipo, 
                                        pds_subtipo.titulo as subtipo,
			                            pds_segmento.titulo as segmento,  
			                            pds_tipologia.titulo as tipologia , 
			                            facturacion.id_intervencion AS visita, 
			                            COUNT(distinct(facturacion.id_incidencia)) AS incidencias, 
			                            contact.contact AS instalador, 
			                            client.client as dueno, 
			                            SUM(facturacion.units_device) AS dispositivos, 
			                            SUM(facturacion.units_alarma) AS otros')
                ->join('pds', 'facturacion.id_pds = pds.id_pds')
                ->join('pds_tipo', 'pds.id_tipo = pds_tipo.id', 'left')
                ->join('pds_subtipo', 'pds.id_subtipo = pds_subtipo.id', 'left')
                ->join('pds_segmento', 'pds.id_segmento = pds_segmento.id', 'left')
                ->join('pds_tipologia', 'pds.id_tipologia= pds_tipologia.id', 'left')
                ->join('displays_pds', 'facturacion.id_displays_pds = displays_pds.id_displays_pds')
                ->join('display', 'displays_pds.id_display = display.id_display')
                ->join('intervenciones', 'facturacion.id_intervencion = intervenciones.id_intervencion', 'left')
                ->join('contact', 'intervenciones.id_operador = contact.id_contact', 'left')
                ->join('client', 'display.client_display= client.id_client', 'left')
                ->where('facturacion.fecha >=', $fecha_inicio)
                ->where('facturacion.fecha <=', $fecha_fin);


            if (!is_null($instalador) && !empty($instalador)) {
                $query = $this->db->where('intervenciones.id_operador', $instalador);
            }
            if (!is_null($dueno) && !empty($dueno)) {
                $query = $this->db->where('display.client_display', $dueno);
            }
            $query = $this->db->group_by('facturacion.id_intervencion')
                ->order_by('facturacion.fecha')
                ->get('facturacion');

            $result = $query->result();

        }
		return $result
            ;
	}


	function exportar_facturacion($formato="csv",$fecha_inicio,$fecha_fin,$instalador = NULL,$dueno=NULL)
	{
		$this->load->dbutil();
		$this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('my_string');

		$this->load->helper('download');
        $this->load->model(array("contact_model","client_model"));



        $query = $this->db->select('facturacion.fecha,
                                    MONTH(facturacion.fecha) as mes,
                                    pds.reference AS SFID,
                                    pds_tipo.titulo as tipo,
                                    pds_subtipo.titulo as subtipo,
                                    pds_segmento.titulo as segmento,
                                    pds_tipologia.titulo as tipologia ,
                                    facturacion.id_intervencion AS visita,
                                    COUNT(distinct(facturacion.id_incidencia)) AS incidencias,
                                    contact.contact AS instalador,
                                    client.client AS dueno,
                                    SUM(facturacion.units_device) AS dispositivos,
                                    SUM(facturacion.units_alarma) AS otros')
                            ->join('pds','facturacion.id_pds = pds.id_pds')
                            ->join('pds_tipo','pds.id_tipo = pds_tipo.id','left')
                            ->join('pds_subtipo','pds.id_subtipo = pds_subtipo.id','left')
                            ->join('pds_segmento','pds.id_segmento = pds_segmento.id','left')
                            ->join('pds_tipologia','pds.id_tipologia= pds_tipologia.id','left')
                            ->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds')
                            ->join('display','displays_pds.id_display = display.id_display')
                            ->join('intervenciones','facturacion.id_intervencion = intervenciones.id_intervencion', 'left')
                            ->join('contact','intervenciones.id_operador = contact.id_contact', 'left')
                            ->join('client','display.client_display= client.id_client', 'left')
                            ->where('facturacion.fecha >=',urldecode($fecha_inicio))
                            ->where('facturacion.fecha <=',urldecode($fecha_fin));


        if(!is_null($instalador) && !empty($instalador)){
            $query = $this->db->where('intervenciones.id_operador',$instalador);
        }
        if(!is_null($dueno) && !empty($dueno)){
            $query = $this->db->where('display.client_display',$dueno);
        }

        $query = $this->db->group_by('facturacion.id_intervencion')
		->order_by('facturacion.fecha')
		->get('facturacion');


        $arr_titulos = array('Fecha','Mes','SFID','Tipo tienda','Subtipo tienda','Segmento tienda','Tipología tienda','Intervención','Nº Incidencias','Instalador',
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

	    $result = array();


	    if($this->input->post()) {
            $query = $this->db->select('
                    incidencias.fecha_cierre as fecha,
                    incidencias.status_pds,
                    pds.reference AS SFID,
                    facturacion.id_incidencia as id_incidencia,
                    pds_tipo.titulo as tipo,
                    pds_subtipo.titulo as subtipo,
			        pds_segmento.titulo as segmento,
			        pds_tipologia.titulo as tipologia,
			        pds.city as poblacion,
			        province.province as provincia,
			        incidencias.fecha_cierre as cierre,
                    facturacion.id_intervencion AS visita,
                    contact.contact AS instalador,
                    client.client as dueno,
                            SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros')
                ->join('intervenciones', 'facturacion.id_intervencion = intervenciones.id_intervencion')
                ->join('intervenciones_incidencias', 'intervenciones.id_intervencion = intervenciones_incidencias.id_intervencion')
                ->join('incidencias', 'incidencias.id_incidencia = intervenciones_incidencias.id_incidencia')
                ->join('pds', 'facturacion.id_pds = pds.id_pds')
                ->join('pds_tipo', 'pds.id_tipo = pds_tipo.id', 'left')
                ->join('pds_subtipo', 'pds.id_subtipo = pds_subtipo.id', 'left')
                ->join('pds_segmento', 'pds.id_segmento = pds_segmento.id', 'left')
                ->join('pds_tipologia', 'pds.id_tipologia= pds_tipologia.id', 'left')
                ->join('province', 'pds.province= province.id_province', 'left')
                ->join('displays_pds', 'facturacion.id_displays_pds = displays_pds.id_displays_pds')
                ->join('display', 'displays_pds.id_display = display.id_display')
                ->join('contact', 'intervenciones.id_operador = contact.id_contact', 'left')
                ->join('client', 'display.client_display= client.id_client', 'left')
                ->where('incidencias.fecha_cierre >=', $fecha_inicio)
                ->where('incidencias.fecha_cierre <=', $fecha_fin);

            if (!is_null($instalador) && !empty($instalador)) {
                $query = $this->db->where('intervenciones.id_operador', $instalador);
            }
            if (!is_null($dueno) && !empty($dueno)) {
                $query = $this->db->where('display.client_display', $dueno);
            }
            $query = $this->db->group_by('facturacion.id_facturacion');

            $query = $this->db->order_by('incidencias.fecha_cierre,facturacion.id_intervencion')
                ->get('facturacion');


            $resultado = $query->result();
            //echo $this->db->last_query(); exit;
            // Guardamos en un array, los elementos facturables, que lo son porque todas sus incidencias asignadas han
            // sido cerradas o resueltas.
            //
            $facturacion = array();

            //echo "PRE".count($resultado)." - ";
            /* Recorro las intervenciones-incidencia así, si una intervencion tiene finalizadas todas sus incidencias, lo dejamos en el array. */

            foreach ($resultado as $intervencion) {


                if (!isset($intervencion->incidencias)) {
                    if (array_key_exists($intervencion->visita, $facturacion)) {
                        $intervencion->incidencias = $facturacion[$intervencion->visita]->incidencias;
                    } else {
                        $intervencion->incidencias = array();
                    }

                }
                if ($intervencion->status_pds == "Finalizada") {
                    if (!in_array($intervencion->id_incidencia, $intervencion->incidencias)) {
                        $intervencion->incidencias[] = $intervencion->id_incidencia;
                    }
                    $facturacion[$intervencion->visita] = $intervencion;

                } else {
                    $facturacion[$intervencion->visita] = $intervencion;
                    // Si existe la clave, eliminamos la intervención pq aún no es facturable por proveedor...
                    if (array_key_exists($intervencion->visita, $facturacion)) {
                        unset($facturacion[$intervencion->visita]);
                    }
                }

            }

            $result = $facturacion;
        }
        return $result;
    }

    function exportar_intervenciones_facturacion($formato="csv",$fecha_inicio,$fecha_fin,$instalador = NULL,$dueno=NULL)
    {
        $this->load->dbutil();
        $this->load->helper(array('file','csv'));
        $this->load->helper('download');
        $this->load->helper('my_string');

        $this->load->model(array("contact_model","client_model"));


        // REALIZAMOS LA CONSULTA A LA BD
        $query = $this->db->select('
                    incidencias.fecha_cierre as fecha,
                    facturacion.id_intervencion AS visita,
                    facturacion.id_incidencia as id_incidencia,
                    incidencias.status_pds,
                    pds.reference AS SFID,
                    pds_tipo.titulo as tipo,
                    pds_subtipo.titulo as subtipo,
			        pds_segmento.titulo as segmento,
			        pds_tipologia.titulo as tipologia,
			        pds.city as poblacion,
			        province.province as provincia,
                    contact.contact AS instalador,
                    incidencias.fecha_cierre as cierre,
                    client.client as dueno,
                            SUM(facturacion.units_device) AS dispositivos, SUM(facturacion.units_alarma) AS otros, precio')

            ->join('intervenciones','facturacion.id_intervencion = intervenciones.id_intervencion')
            ->join('intervenciones_incidencias','intervenciones.id_intervencion = intervenciones_incidencias.id_intervencion')
            ->join('incidencias','incidencias.id_incidencia = intervenciones_incidencias.id_incidencia')
            ->join('pds','facturacion.id_pds = pds.id_pds')
            ->join('pds_tipo','pds.id_tipo = pds_tipo.id','left')
            ->join('pds_subtipo','pds.id_subtipo = pds_subtipo.id','left')
            ->join('pds_segmento','pds.id_segmento = pds_segmento.id','left')
            ->join('pds_tipologia','pds.id_tipologia= pds_tipologia.id','left')
            ->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds')
            ->join('province','province.id_province = pds.province')
            ->join('display','displays_pds.id_display = display.id_display')
            ->join('contact','intervenciones.id_operador = contact.id_contact', 'left')
            ->join('client','display.client_display= client.id_client', 'left')

            ->where('incidencias.fecha_cierre >=',urldecode($fecha_inicio))
            ->where('incidencias.fecha_cierre <=',urldecode($fecha_fin))
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
//echo $this->db->last_query(); exit;
        $titulos=array('Fecha','Intervencion','Incidencias','SFID','Tipo tienda','Subtipo tienda','Segmento tienda',
            'Tipología tienda','Población','Provincia','Instalador','Dueño','Dispositivos','Alarmas','Cierre','Precio');
        $excluir=array('status_pds','id_incidencia');

        // DEFINO LAS CABECERAS DEL LISTADO A EXPORTAR
        $facturacion = array();

        //$indice=0;
        //$incidencias=array();

        /* Recorro las intervenciones-incidencia así, si una intervencion tiene finalizadas todas sus incidencias, lo dejamos en el array.
        Y en caso contrario lo omitimos del informe.*/
        /* En el campo incidencias se guarda un array con todas las incidencias resueltas en una intervencion*/
        foreach($resultado as $intervencion)
        {
            $intervencion->fecha = date("d/m/Y",strtotime(($intervencion->fecha)));  // Formato ES para la fecha
            $intervencion->cierre= date("d/m/Y",strtotime(($intervencion->cierre)));  // Formato ES para la fecha de cierre
            if (!isset($intervencion->incidencias)) {
                if(array_key_exists($intervencion->visita,$facturacion)) {
                    $intervencion->incidencias=$facturacion[$intervencion->visita]->incidencias;
                }
                else {
                    $intervencion->incidencias=array();
                }

            }
            if($intervencion->status_pds == "Finalizada")   // Si la incidencia actual
            {
                if (!in_array( $intervencion->id_incidencia,$intervencion->incidencias)) {
                    $intervencion->incidencias[] = $intervencion->id_incidencia;
                }
                $facturacion[$intervencion->visita] = $intervencion;
            }
            else
            {
                $facturacion[$intervencion->visita] = $intervencion;
                // Si existe la clave, eliminamos la intervención pq aún no es facturable por proveedor...
                if(array_key_exists($intervencion->visita,$facturacion))
                {
                    //$indice=0;
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


        //Preparación del campo incidencias y el orden del contenido a mostrar
        $campos=array('fecha','visita','incidencias','SFID','tipo','subtipo','segmento','tipologia','poblacion',
            'provincia','instalador','dueno','dispositivos','otros','cierre','precio');
        $aux=array();
        $indice=0;
        foreach ($facturacion as $item_facturacion) {
            if(count($item_facturacion->incidencias) > 0){
                $item_facturacion->incidencias= implode(" - ",$item_facturacion->incidencias);
            }
            for ($i=0;$i<count($campos);$i++) {
                $aux[$indice][$campos[$i]] = $item_facturacion->$campos[$i];
            }
            $indice++;
        }

        $facturacion=preparar_array_exportar($aux,$titulos,$excluir);

        // EXPORTAR EL RESULTADO
        exportar_fichero($formato,$facturacion,$f_nombre);

    }

    public function facturacion_fabricanteM($fecha_inicio,$fecha_fin,$fabricante=NULL) {

        $result = array();
        if($this->input->post()) {
            $query = $this->db->select('facturacion.id_incidencia as incidencia,
                                    facturacion.id_intervencion as intervencion,
                                    facturacion.fecha, pds.reference AS SFID,
                                    pds.commercial as nombre,
                                    pds.address as direccion,
                                    pds.city as ciudad,
			                        client.client as fabricante,
			                        display.display as mueble,
			                        incidencias.description_1 as descripcion,
			                        solucion_incidencia.title as solucion,
			                        incidencias.fecha_cierre as cierre,
			                        display.display as mueble,
                                    (SELECT SUM(cantidad) FROM material_incidencias
                                    JOIN incidencias inc ON material_incidencias.id_incidencia = inc.id_incidencia
                                    JOIN pds  ON pds.id_pds = inc.id_pds
                                    WHERE facturacion.id_incidencia = material_incidencias.id_incidencia
                                    AND id_devices_almacen is not null) as dispositivos')
                ->join('pds', 'facturacion.id_pds = pds.id_pds', 'left')
                ->join('displays_pds', 'facturacion.id_displays_pds = displays_pds.id_displays_pds', 'left')
                ->join('display', 'displays_pds.id_display = display.id_display', 'left')
                ->join('incidencias', 'facturacion.id_incidencia = incidencias.id_incidencia', 'left')
                ->join('client', 'display.client_display= client.id_client', 'left')
                ->join('solucion_incidencia', 'solucion_incidencia.id_solucion_incidencia = incidencias.id_solucion_incidencia', 'left')
                ->join('intervenciones', 'facturacion.id_intervencion = intervenciones.id_intervencion', 'left')
                ->where('facturacion.fecha >=', $fecha_inicio)
                ->where('facturacion.fecha <=', $fecha_fin)
                ->where('client.facturable', '1')//Que sea facturable
                ->where('client.type_profile_client', '2'); //Tipo de cliente fabricante

            if (!is_null($fabricante) && !empty($fabricante)) {
                $query = $this->db->where('client.id_client', $fabricante);

            }

            $query = $this->db->order_by('facturacion.fecha')
                ->order_by('client.client')
                ->order_by('facturacion.id_intervencion,facturacion.id_incidencia')
                ->get('facturacion');
            $result = $query->result();
        }
        return $result;
    }


    function exportar_facturacion_fabricanteM($formato="csv",$fecha_inicio,$fecha_fin,$fabricante=NULL)
    {
        $this->load->dbutil();
        $this->load->helper(array('file','csv'));
        $this->load->helper('download');
        $this->load->helper('my_string');
        $this->load->model(array("contact_model","client_model"));

        // REALIZAMOS LA CONSULTA A LA BD
        $query = $this->db->select('facturacion.id_incidencia as incidencia,
                                    facturacion.id_intervencion as intervencion,
                                    facturacion.fecha as fecha,
                                    pds.reference AS SFID,
                                    pds.commercial as nombre,
                                    pds.address as direccion,
                                    pds.city as ciudad,
                                    client.client as fabricante,
                                    display.display as mueble,
                                    incidencias.description_1 as descripcion,
                                    solucion_incidencia.title as solucion,
                                    incidencias.fecha_cierre as cierre,
                                    display.display as mueble,
                                    (SELECT SUM(cantidad) FROM material_incidencias
                                    JOIN incidencias inc ON material_incidencias.id_incidencia = inc.id_incidencia
                                    JOIN pds  ON pds.id_pds = inc.id_pds
                                    WHERE facturacion.id_incidencia = material_incidencias.id_incidencia
                                    AND id_devices_almacen is not null) as dispositivos')
                        ->join('pds','facturacion.id_pds = pds.id_pds','left')
                        ->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds','left')
                        ->join('display','displays_pds.id_display = display.id_display','left')
                        ->join('incidencias','facturacion.id_incidencia = incidencias.id_incidencia','left')
                        ->join('client','display.client_display= client.id_client','left')
                        ->join('solucion_incidencia','solucion_incidencia.id_solucion_incidencia = incidencias.id_solucion_incidencia','left')
                        ->join('intervenciones','facturacion.id_intervencion = intervenciones.id_intervencion', 'left')
                        ->where('facturacion.fecha >=',urldecode($fecha_inicio))
                        ->where('facturacion.fecha <=',urldecode($fecha_fin))
                        ->where('client.facturable','1') //Que sea facturable
                        ->where('client.type_profile_client','2'); //Tipo de cliente fabricante


        if(!is_null($fabricante) && !empty($fabricante)){
            $query = $this->db->where('client.id_client',$fabricante);

        }
        $query = $this->db->order_by('facturacion.fecha')
            ->order_by('client.client')
            ->order_by('facturacion.id_intervencion,facturacion.id_incidencia')
            ->get('facturacion');

        $resultado = $query->result();

        $titulos=array('Intervención','Incidencia','Fecha','SFID','Nombre','Direccion','Ciudad','Mueble','Fabricante',
            'Nº terminales','Descripcion de tienda del error','Solucion','Cierre');
        $excluir=array();


        // GENERAR NOMBRE DE FICHERO
        $filename["fabricante"] = (!is_null($fabricante) && $fabricante!=0) ? $this->client_model->getById($fabricante)->getName() : "Todos";
        foreach($filename as $key=>$f_name)
        {
            $filename[$key] = str_sanitize($f_name);
        }
        $filename["from"] = date("d-m-Y",strtotime($fecha_inicio));     // Campo no saneable
        $filename["to"] = date("d-m-Y",strtotime($fecha_fin));          // Campo no saneable
        $f_nombre = implode("__",$filename);


        //Preparación del orden del contenido a mostrar
        $campos=array('intervencion','incidencia','fecha','SFID','nombre','direccion','ciudad','mueble','fabricante','dispositivos','descripcion','solucion','cierre');
        $aux=array();
        $indice=0;

        foreach ($resultado as $item) {
            $item->fecha= date("d/m/Y",strtotime(($item->fecha)));  // Formato ES para la fecha
            $item->cierre= date("d/m/Y",strtotime(($item->cierre)));  // Formato ES para la fecha de cierre
            if (empty($item->dispositivos)) {$item->dispositivos=0;}
            for ($i=0;$i<count($campos);$i++) {
                $aux[$indice][$campos[$i]] = $item->$campos[$i];
            }
            $indice++;
        }


        $facturacion=preparar_array_exportar($aux,$titulos,$excluir);

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
                    WHERE positions > 0 AND status="Alta" ORDER BY display');


        return $query->result();
    }

    public function get_mueblesdisplay_demoreal() {


        $query = $this->db->query('
                    SELECT *
                    FROM mueble_display md
                    WHERE positions > 0 AND status="Alta" ORDER BY name');


        return $query->result();
    }

    public function get_devices_demoreal() {
        $query = $this->db->query('
                    SELECT *
                    FROM device d
                    WHERE status= "Alta" AND (
                      SELECT COUNT(id_devices_pds) FROM devices_pds p
                      WHERE p.id_device = d.id_device
                      AND p.status = "Alta"
                    ) >= 1 ORDER BY device');


        return $query->result();
    }

    public function get_supervisores()
    {
        $query = $this->db->select('*')
                ->order_by('titulo','asc')
                ->get('pds_supervisor')
                ;

        return $query->result();
    }

    public function get_provincias()
    {
        $query = $this->db->select('id_province as id,province as titulo')
            ->order_by('titulo','asc')
            ->get('province')
        ;

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
	
	public function get_devices_total() {

			$query = $this->db->select('devices_pds.*,device.*, COUNT(devices_pds.id_device) AS unidades')
			->join('device','devices_pds.id_device = device.id_device')
			->where('devices_pds.status','Alta')
                ->or_where('devices_pds.status','Incidencia')
			->group_by('devices_pds.id_device')
			->order_by('device')
			->get('devices_pds');
	//echo $this->db->last_query();    exit;
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
        ->where('device.status','Alta')
		->group_by('devices_almacen.id_device')
		->order_by('device')
		->get('devices_almacen');

		return $query->result();
	}

	/*
	 *Nos devuelve los dispositivos del almacen que hay de un determinado tipo
	 */
    public function get_devicesAlmacen_Tipo($id_device) {

        $query = $this->db->select('devices_almacen.id_devices_almacen,devices_almacen.IMEI,device.device')
            ->join('device','devices_almacen.id_device = device.id_device')
            ->where('devices_almacen.status','En stock')
            ->where('device.status','Alta')
            ->where('device.id_device',$id_device)
            ->order_by('device')
            ->get('devices_almacen');
//echo $this->db->last_query(); exit;
        return $query->result();
    }

    /* dispositivos que no han llegado al almacen tras la resolucion de la incidencia bien sea porque se asignaron y no se usaron
    o porque genero la incidencia y no se ha enviado a almacen*/
    public function get_devices_recogida($page = 1, $cfg_pagination = NULL) {

        $limit='';
        if(!empty($cfg_pagination)) {
            if ($page==1) {
                $limit=" LIMIT ".$page  *$cfg_pagination['per_page'];
            } else {
                if($page>1){
                    $limit=" LIMIT ".($page-1) *$cfg_pagination['per_page'].",".  $cfg_pagination['per_page'];
                }
            }
        }
        /* Terminales que asignaron a la incidencia pero no se usaron*/
        $sql="SELECT m.id_incidencia,i.id_pds, d.* ,'Se asigno pero no se uso' as descripcion
              FROM material_incidencias m
              INNER JOIN devices_almacen a ON a.id_devices_almacen=m.id_devices_almacen AND a.status='Transito'
              INNER JOIN incidencias i ON i.id_incidencia=m.id_incidencia AND i.status='Pendiente recogida'
              INNER JOIN device d ON d.id_device=a.id_device
              UNION
              SELECT i.id_incidencia,i.id_pds,d.*,'genero la incidencia' as descripcion
              FROM devices_almacen a
              INNER JOIN incidencias i ON i.id_incidencia = a.id_incidencia AND a.status='Transito' AND i.status='Pendiente recogida'
              INNER JOIN device d ON d.id_device=a.id_device ".$limit;

        return $this->db->query($sql)->result();
    }

    /* total dispositivos que no han llegado al almacen tras la resolucion de la incidencia bien sea porque se asignaron y no se usaron
    o porque genero la incidencia y no se ha enviado a almacen*/
    public function get_recogidas_quantity($array_filtros=NULL) {
        /* Terminales que asignaron a la incidencia pero no se usaron*/
        $sql="SELECT m.id_incidencia,i.id_pds, d.* ,'Se asigno pero no se uso' as descripcion
              FROM material_incidencias m
              INNER JOIN devices_almacen a ON a.id_devices_almacen=m.id_devices_almacen AND a.status='Transito'
              INNER JOIN incidencias i ON i.id_incidencia=m.id_incidencia AND i.status='Pendiente recogida'
              INNER JOIN device d ON d.id_device=a.id_device
              UNION
              SELECT i.id_incidencia,i.id_pds,d.*,'genero la incidencia' as descripcion
              FROM devices_almacen a
              INNER JOIN incidencias i ON i.id_incidencia = a.id_incidencia AND a.status='Transito' AND i.status='Pendiente recogida'
              INNER JOIN device d ON d.id_device=a.id_device";

        return $this->db->query($sql)->num_rows();
    }

    /* dispositivos que no han llegado al almacen tras la resolucion de la incidencia bien sea porque se asignaron y no se usaron
   o porque genero la incidencia y no se ha enviado a almacen*/
    public function get_devices_recogida_exportar($page = 1, $cfg_pagination = NULL) {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('my_string');

        $this->load->helper('download');
        $this->load->model(array("contact_model","client_model"));

        $limit='';
        if(!empty($cfg_pagination)) {
            if ($page==1) {
                $limit=" LIMIT ".$page  *$cfg_pagination['per_page'];
            } else {
                if($page>1){
                    $limit=" LIMIT ".($page-1) *$cfg_pagination['per_page'].",".  $cfg_pagination['per_page'];
                }
            }
        }
        /* Terminales que asignaron a la incidencia pero no se usaron*/
        $sql="SELECT m.id_incidencia,p.reference, p.commercial as nombre,p.city as ciudad,pr.province as provincia,
              c.contact as instalador,
              d.device as dispositivo ,
              CASE
                WHEN a.IMEI = '' THEN ' '
                WHEN a.IMEI is null THEN ' '
                ELSE a.IMEI
              END IMEI,
              'Se asigno pero no se uso' as descripcion
              FROM material_incidencias m
              INNER JOIN devices_almacen a ON a.id_devices_almacen=m.id_devices_almacen AND a.status='Transito'
              INNER JOIN incidencias i ON i.id_incidencia=m.id_incidencia AND i.status='Pendiente recogida'
              INNER JOIN intervenciones_incidencias ii ON ii.id_incidencia = i.id_incidencia
              INNER JOIN intervenciones inter on inter.id_intervencion = ii.id_intervencion
              INNER JOIN contact c ON c.id_contact = inter.id_operador
              INNER JOIN device d ON d.id_device=a.id_device
              INNER JOIN pds p ON p.id_pds = i.id_pds
              INNER JOIN province pr ON pr.id_province = p.province
              UNION
              SELECT i.id_incidencia,p.reference,p.commercial as nombre,p.city as ciudad,pr.province as provincia,
              c.contact as instalador,
              d.device as dispositivo,
              CASE
                WHEN a.IMEI = '' THEN ' '
                WHEN a.IMEI is null THEN ' '
                ELSE a.IMEI
              END IMEI,
              'genero la incidencia' as descripcion
              FROM devices_almacen a
              INNER JOIN incidencias i ON i.id_incidencia = a.id_incidencia AND a.status='Transito' AND i.status='Pendiente recogida'
               INNER JOIN intervenciones_incidencias ii ON ii.id_incidencia = i.id_incidencia
              INNER JOIN intervenciones inter on inter.id_intervencion = ii.id_intervencion
              INNER JOIN contact c ON c.id_contact = inter.id_operador
              INNER JOIN pds p ON p.id_pds = i.id_pds
               INNER JOIN province pr ON pr.id_province = p.province
              INNER JOIN device d ON d.id_device=a.id_device ".$limit;

        $result = $this->db->query($sql)->result();

        $arr_titulos = array('id_incidencia','SFID','Nombre tienda','Ciudad','Provincia','Instalador','Dispositivo','IMEI','Causa');
        $excluir = array();

        $data = preparar_array_exportar($result,$arr_titulos,$excluir);

        //print_r($this->db->last_query());

        // GENERAR NOMBRE DE FICHERO
        //$filename["dueno"] = "duenio";                   // Campo a sanear
        $filename["instalador"]  = "instalador";  // Campo a sanear



        foreach($filename as $key=>$f_name)
        {
            $filename[$key] = str_sanitize($f_name);
        }


        $str_filename = implode("___",$filename);

        exportar_fichero("xlsx",$data,'Dispositivos_PendientesRecogida_'.$str_filename."_".date('Y-m-d'));

    }
    public function get_devices_almacen_exportar() {

        $query = $this->db->select('device.device, COUNT(devices_almacen.id_device) AS unidades')
            ->join('device','devices_almacen.id_device = device.id_device')
            ->where('devices_almacen.status','En stock')
           // ->where('device.status','Alta')
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
        //echo $this->db->last_query(); exit;
		return $query->result();
	}


	/*
	 * Cantidad de terminales que están dados de alta
	 */
    public function get_devices_quantity($array_filtros=NULL) {
       // $where='';
        if(isset($array_filtros["id_modelo"]) && !empty($array_filtros["id_modelo"])) {
            //$where .= ' AND device.id_device = ' . $array_filtros['id_modelo'];
            $this->db->where('device.id_device',$array_filtros['id_modelo']);
        }
        if(isset($array_filtros["id_marca"]) && !empty($array_filtros["id_marca"])) {

            $this->db->join('brand_device','brand_device.id_brand_device=device.brand_device')
            ->where('brand_device.id_brand_device',$array_filtros['id_marca']);

            //$where .= ' AND brand_device.id_brand_device = ' . $array_filtros['id_marca'];
        }
        $query = $this->db->select('count(*) as cantidad')
        ->where('device.status','Alta')
            ->order_by('device')
            ->get('device');
        //return $query->cantidad;
        //echo $this->db->last_query(); exit;
        return $query->row()->cantidad;
    }
	
	public function get_material_dispositivos($incidencia,$almacen=true) {

	    if(!$almacen) {
	        $sql ="SELECT material_incidencias.id_material_incidencias AS id_material_incidencias,
		        device.id_device AS id_devices_almacen, device.device AS device, '' AS imei, material_incidencias.cantidad AS cantidad 
		        FROM material_incidencias 
		        INNER JOIN device ON device.id_device = material_incidencias.id_devices_almacen 
		        WHERE material_incidencias.id_incidencia =". $incidencia['id_incidencia']."
		         AND material_incidencias.id_devices_almacen != ''
		         ORDER BY device.device";
            /*$query = $this->db->select('material_incidencias.id_material_incidencias AS id_material_incidencias,
		        device.id_device AS id_devices_almacen, device.device AS device, "" AS imei, material_incidencias.cantidad AS cantidad')
                ->join('device', 'device.id_device = material_incidencias.id_devices_almacen')
                ->join('devices_almacen', 'devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen')
                ->where('material_incidencias.id_incidencia', $incidencia['id_incidencia'])
                ->where('material_incidencias.id_devices_almacen <>', '')
                ->order_by('device.device')
                ->get('material_incidencias');*/
        }else{
            $sql = " SELECT material_incidencias.id_material_incidencias AS id_material_incidencias,
		        devices_almacen.id_devices_almacen AS id_devices_almacen, device.device AS device, devices_almacen.imei AS imei, material_incidencias.cantidad AS cantidad
		         FROM material_incidencias 
		         INNER JOIN devices_almacen ON devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen 
		         INNER JOIN device ON devices_almacen.id_device = device.id_device
		         WHERE material_incidencias.id_incidencia = ".$incidencia['id_incidencia'];
            if($incidencia['status_pds']!='Finalizada'){
                $sql.= " AND devices_almacen.status != 'Baja'";
            }else{
                $sql.=" AND devices_almacen.id_incidencia is not null";
            }
            $sql.=" ORDER BY device.device";
            //echo $sql; exit;
            /*$query = $this->db->select('material_incidencias.id_material_incidencias AS id_material_incidencias,
		        devices_almacen.id_devices_almacen AS id_devices_almacen, device.device AS device, devices_almacen.imei AS imei, material_incidencias.cantidad AS cantidad')
                ->join('devices_almacen', 'devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen')
                ->join('device', 'devices_almacen.id_device = device.id_device')
                ->where('material_incidencias.id_incidencia', $incidencia['id_incidencia'])
                ->where('material_incidencias.id_devices_almacen <>', '')
                ->where('devices_almacen.status<>', 'Baja')
                ->order_by('device.device')
                ->get('material_incidencias');*/
        }
        //echo $sql;exit;
	//echo $this->db->last_query();
		return $this->db->query($sql)->result();
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

       //echo $this->db->last_query();

		return $query->result();
	}


    public function get_tipos_incidencia($status=null)
    {
        if ($status != null){
            if ($status == 'Alta') {
                $query = $this->db->select("id_type_incidencia,title")
                    ->where('status', $status)
                    ->order_by('title ASC')
                    ->get('type_incidencia');

            }
        }else {
            $query = $this->db->select("id_type_incidencia,title")
                //->where('status',$status)
                ->order_by('title ASC')
                ->get('type_incidencia');
        }
        return $query->result();
    }

    public function get_type_incidencia($id_inc)
    {
        $query = $this->db->select("type_incidencia.id_type_incidencia,type_incidencia.title as title")
            ->join('incidencias','incidencias.id_type_incidencia = type_incidencia.id_type_incidencia')
            ->where('incidencias.id_incidencia',$id_inc)
            ->get('type_incidencia');
//echo $this->db->last_query();exit;
        return $query->row_array();
    }

    public function get_soluciones_incidencia()
    {
        $query = $this->db->select("*")
            ->order_by('title ASC')
            ->get('solucion_incidencia');

        return $query->result();
    }
	
	public function reservar_dispositivos($id,$status,$id_incidencia)
	{
		$this->db->set('status',$status, FALSE)
                ->set('id_incidencia',$id_incidencia)
		        ->where('id_devices_almacen',$id)
		        ->update('devices_almacen');

	}
		
	
	public function update_dispositivos($id,$imei,$mac,$serial,$barcode)
	{
		$this->db->set('IMEI',$imei)
		        ->set('mac',$mac)
		        ->set('serial',$serial)
		        ->set('barcode',$barcode)
                ->set('id_incidencia',null)
		        ->where('id_devices_almacen',$id)
		        ->update('devices_almacen');
	}	

	public function get_devices_almacen_reserva() {
	
		$query = $this->db->select('devices_almacen.*, device.*')
		->join('device','devices_almacen.id_device = device.id_device')
		->where('devices_almacen.status','En stock')
		->order_by('device.device')
		->order_by('devices_almacen.imei desc')
		->get('devices_almacen');

		return $query->result();
	}

    public function get_typedevices_almacen_reserva(){
        $sql = "SELECT distinct device.id_device, device.device FROM devices_almacen INNER JOIN device ON 
                devices_almacen.id_device=device.id_device WHERE devices_almacen.status='En stock'
                ORDER BY device.device";

        return $this->db->query($sql)->result();
    }

	/*Funcion que nos devuelve todos los dispositivos de almacen aunque no hay stock*/
    public function get_devices_almacen_all() {

        $query = $this->db->select('devices_almacen.*, device.*')
            ->join('device','devices_almacen.id_device = device.id_device')
            ->or_where('devices_almacen.status','En stock')
            ->or_where('devices_almacen.status','En stock')
            ->order_by('device.device')
            ->order_by('devices_almacen.imei desc')
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

        //echo $this->db->last_query(); exit;
		return $query->result();
	}

    public function get_alarms_almacen_reserva_exportar($dueno = NULL) {

        $query = $this->db->select('brand_alarm.brand, alarm.alarm,  alarm.units')
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
                ->where('devices_display.status','Alta')
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
			$query = $this->db->select('pds.*,pds_tipo.titulo as tipo, pds_subtipo.titulo as subtipo,
			pds_segmento.titulo as segmento,  pds_tipologia.titulo as tipologia, province.province, territory.territory')
			//->join('type_pds','pds.type_pds = type_pds.id_type_pds')
			->join('province','pds.province = province.id_province')
			->join('territory','pds.territory = territory.id_territory')
            ->join('pds_tipo','pds.id_tipo = pds_tipo.id','left')
            ->join('pds_subtipo','pds.id_subtipo = pds_subtipo.id','left')
            ->join('pds_segmento','pds.id_segmento = pds_segmento.id','left')
            ->join('pds_tipologia','pds.id_tipologia= pds_tipologia.id','left')
			->where('pds.id_pds',$id)
			->get('pds');

	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}

    public function get_sfid($sfid,$tipo_resultado="array") {
        if($sfid != FALSE) {
            $query = $this->db->select('pds.*, province.province, territory.territory')
                //->join('type_pds','pds.type_pds = type_pds.id_type_pds')
                ->join('province','pds.province = province.id_province')
                ->join('territory','pds.territory = territory.id_territory')
                ->like('pds.reference',$sfid,'none')
                ->get('pds');
//echo $this->db->last_query(); exit;

            if($tipo_resultado=="array") return $query->row_array();
            else return $query->row();
        }
        else {
            return FALSE;
        }
    }


    public function get_display($id,$tipo_objeto="array") {
		if($id != FALSE) {
			$query = $this->db->select('*')
			->where('id_display',$id)
			->get('display');

            return ($tipo_objeto == "array") ? $query->row_array() : $query->row();

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

    /**
     * Función que devuelve la condición a usar en un where para determinar si la incidencia/s son abiertas o cerradas
     * según los estados status y status_pds
     * @param string $tipo
     * @return mixed
     */
    public function get_condition_tipo_incidencia($tipo = "abiertas"){

        /*  ESTADOS ABIERTOS SAT: Nueva, Revisada, Instalador asignado, Material asignado, Comunicada
           ESTADOS CERRADOS SAT: Resuelta, Sustituido, SustituidoRMA, Pendiente recogida, Cerrada, Cancelada */

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

    /**
     * Devuelve un array fila correspondiente a una incidencia de id pasado como param.
     * @param $id
     * @return bool
     */
	public function get_incidencia($id) {
		if($id != FALSE) {
            $sql = "SELECT *, status_pds+0 as status_pdsNum, status+0 as statusNum FROM incidencias 
                        WHERE id_incidencia=$id";
            return $this->db->query($sql)->row_array();
		}
		else {
			return FALSE;
		}


	}

    public function historico_fecha($id,$status,$tabla=null) {
        if($id != FALSE) {
            if($tabla=="pedidos") {
                $query = $this->db->select('pedidos_historico.fecha')
                    ->where('pedidos_historico.id_pedido', $id)
                    ->where('pedidos_historico.status', $status)
                    ->get('pedidos_historico');
            }else {
                $query = $this->db->select('historico.fecha')
                    ->where('historico.id_incidencia', $id)
                    ->where('historico.status', $status)
                    ->get('historico');
            }
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

    public function incidencia_update_device_pds($id_devices_pds,$status,&$error,$id_incidencia = NULL)
    {

        $ahora = date("Y-m-d H:i:s");
        /*
         * Cuando se resuelve la incidencia
         *
         *
         */
        //echo "Estado ".$status;exit;
        switch ($status) {

            case ($status == 2): /*Cuando se revisa la incidenica*/
                $query = $this->db->select('*')
                    ->where('id_devices_pds', $id_devices_pds)
                    ->get('devices_pds');
                $device_pds = $query->row();

                if(isset($device_pds) && $device_pds->status !='Baja' && $device_pds->status !='Incidencia') {
                    $this->db->set('status', $status, FALSE);
                    $this->db->where('id_devices_pds', $id_devices_pds);
                    $this->db->update('devices_pds');

                    /*Insertamos que el dispositivo esta en incidencia*/
                    $elemento = array(
                        'id_devices_pds' => $id_devices_pds,
                        'fecha' => date('Y-m-d H:i:s'),
                        'status' => 'Incidencia',
                        'motivo' => INCIDENCIA_ALTA
                    );
                    $this->insert_historico_devicesPDS($elemento);
                }

                break;

            /*Cuando se cancela la incidencia*/
            case ($status == 3):
                /* obtengo los datos de la posicion que ha generado la incidencia */
                $query = $this->db->select('*')
                    ->where('id_devices_pds', $id_devices_pds)
                    ->get('devices_pds');
                $device_pds = $query->row();
                if($device_pds->status =='Incidencia') {
                    /*La posicion que genera la incidencia pasa a estar en Alta*/
                    $sql = "UPDATE devices_pds SET status='Alta' WHERE id_devices_pds =" . $id_devices_pds;
                    $this->db->query($sql);

                    /*Insertamos el cambio de estado en el historico*/
                    $elemento = array(
                        'id_devices_pds' => $device_pds->id_devices_pds,
                        'fecha' => date('Y-m-d H:i:s'),
                        'status' => 'Alta',
                        'motivo' => INCIDENCIA_CANCELADA
                    );
                    $this->insert_historico_devicesPDS($elemento);

                }
                break;
            /* se resuelve la incidencia*/
            case ($status == 6):
                $condicion = "";
                if (!empty($id_devices_pds)) {
                    $condicion = "AND incidencias.id_devices_pds=$id_devices_pds ";
                }
                $sql = "SELECT devices_pds.status,incidencias.tipo_averia FROM devices_pds 
                        JOIN incidencias ON incidencias.id_devices_pds=devices_pds.id_devices_pds WHERE  
                        incidencias.id_incidencia=$id_incidencia $condicion";
                $resultado = $this->db->query($sql)->row_array();

                if (!empty($resultado)) {
                    if ($resultado['status'] == "Incidencia") {
                        /* poner de Alta la posicion que origino la incidencia*/
                        $sql = "UPDATE devices_pds SET status='Alta' WHERE id_devices_pds =" . $id_devices_pds;
                        $this->db->query($sql);
                        /*Insertamos el cambio de estado en el historico*/
                        $elemento = array(
                            'id_devices_pds' => $id_devices_pds,
                            'fecha' => date('Y-m-d H:i:s'),
                            'status' => 'Alta',
                            'motivo' => INCIDENCIA_RESUELTA
                        );
                        $this->insert_historico_devicesPDS($elemento);
                    } else {
                        if ($resultado['tipo_averia']=='Robo') {
                            $sql = "SELECT status FROM historico WHERE id_incidencia=" . $id_incidencia . " AND status='Sustituido'";
                            $historico = $this->db->query($sql)->row();
                            if (!empty($historico))  {
                                $sql = "UPDATE devices_pds SET status='Baja' WHERE id_devices_pds =" . $id_devices_pds;
                                $this->db->query($sql);
                            }

                            /*Insertamos el cambio de estado en el historico*/
                            $elemento = array(
                                'id_devices_pds' => $id_devices_pds,
                                'fecha' => date('Y-m-d H:i:s'),
                                'status' => 'Baja',
                                'motivo' => INCIDENCIA_RESUELTA
                            );
                            $this->insert_historico_devicesPDS($elemento);
                        }
                    }


                    /*insertar en almacen el dispositivo que origino la incidencia en estado en transito*/
                    /* Guardamos los datos de la posicion que genero la incidencia y el devices_pds al que afecta la incidencia se da de baja */
                    if (!empty($id_devices_pds)) {
                        $query = $this->db->select('*')
                            ->where('id_devices_pds', $id_devices_pds)
                            ->get('devices_pds');
                        $device_pds = $query->row();

                        $query= $this->db->select('*')
                            ->where('id_incidencia', $id_incidencia)
                            ->get('incidencias');
                        $incidencia=$query->row();

                        /*Obtener el historico de la incidencia para saber si hay que dejar la posicion original en el mismo estado
                                                o ponerla de baja*/
                        $sql ="SELECT status FROM historico WHERE id_incidencia=".$id_incidencia." AND (status='Sustituido' OR status='SustituidoRMA')";
                        $historico=$this->db->query($sql)->row();

                        if (!empty($historico)) {
                            if ((($device_pds->status == 'RMA') && ($incidencia->tipo_averia != 'Robo')) ||
                                ($incidencia->tipo_averia == 'Robo') && ($historico->status == 'SustituidoRMA')
                            ) {
                                $data = array(
                                    'id_device' => $device_pds->id_device,
                                    'alta' => $ahora,
                                    'IMEI' => $device_pds->IMEI,
                                    'mac' => $device_pds->mac,
                                    'serial' => $device_pds->serial,
                                    'barcode' => $device_pds->barcode,
                                    'status' => 4, //Se inserta en almacen en transito
                                    'owner' => "ET",
                                    'id_incidencia' => $id_incidencia
                                );
                                $this->db->insert('devices_almacen', $data);

                                /*Insertar en el historico del almacen el estado del dispositivo*/
                                $elemento = array(
                                    'id_material_incidencia' => NULL,
                                    'id_devices_almacen' => $this->db->insert_id(),
                                    'id_alarm' => NULL,
                                    'id_device' => $device_pds->id_device,
                                    'id_incidencia' => $id_incidencia,
                                    'id_client' => NULL,
                                    'fecha' => date('Y-m-d H:i:s'),
                                    'unidades' => 1,
                                    'procesado' => 1,
                                    'status' => 'Transito'
                                );
                                //$this->db->insert('historico_io', $elemento);
                                $this->alta_historicoIo($elemento);

                                /*La posicion que genera la incidencia pasa a estar en Baja*/
                                $sql = "UPDATE devices_pds SET status='Baja' WHERE id_devices_pds =" . $id_devices_pds;
                                $this->db->query($sql);

                                /*Insertamos el cambio de estado en el historico*/
                                $elemento = array(
                                    'id_devices_pds' => $id_devices_pds,
                                    'fecha' => date('Y-m-d H:i:s'),
                                    'status' => 'Baja',
                                    'motivo' => INCIDENCIA_RESUELTA
                                );
                                $this->insert_historico_devicesPDS($elemento);
                            }

                        }
                    }
                }


                break;

            /* Cuando se pulsa el boton de recogida de material se debe hacer una entrada en el almacen para el
            dispositvo que origino la incidencia si se sustituyo por el enviado para solucionarla*/
            case ($status == 7):
                /*insertar en almacen el dispositivo que origino la incidencia en estado en transito*/
                /* Guardamos los datos de la posicion que genero la incidencia y el devices_pds al que afecta la incidencia se da de baja */
                if (!empty($id_devices_pds)) {
                    $query = $this->db->select('*')
                        ->where('id_devices_pds', $id_devices_pds)
                        ->get('devices_pds');
                    $device_pds = $query->row();

                    $query= $this->db->select('*')
                            ->where('id_incidencia', $id_incidencia)
                            ->get('incidencias');
                    $incidencia=$query->row();

                    /*Obtener el historico de la incidencia para saber si hay que dejar la posicion original en el mismo estado
                                            o ponerla de baja*/
                    $sql ="SELECT status FROM historico WHERE id_incidencia=".$id_incidencia." AND (status='Sustituido' OR status='SustituidoRMA')";
                    $historico=$this->db->query($sql)->row();

                    if (!empty($historico)) {
                        if ((($device_pds->status == 'RMA') && ($incidencia->tipo_averia != 'Robo')) ||
                            ($incidencia->tipo_averia == 'Robo') && ($historico->status == 'SustituidoRMA')
                        ) {

                            $sql = "SELECT * FROM devices_almacen WHERE IMEI = '$device_pds->IMEI' and status!='Baja' 
                                     AND id_incidencia=$id_incidencia";

                            $result = $this->db->query($sql)->result();
                            if(empty($result)) {
                                $data = array(
                                    'id_device' => $device_pds->id_device,
                                    'alta' => $ahora,
                                    'IMEI' => $device_pds->IMEI,
                                    'mac' => $device_pds->mac,
                                    'serial' => $device_pds->serial,
                                    'barcode' => $device_pds->barcode,
                                    'status' => 4, //Se inserta en almacen en transito
                                    'owner' => "ET",
                                    'id_incidencia' => $id_incidencia
                                );

                                $this->db->insert('devices_almacen', $data);

                                /*Insertar en el historico del almacen el estado del dispositivo*/
                                $elemento = array(
                                    'id_material_incidencia' => NULL,
                                    'id_devices_almacen' => $this->db->insert_id(),
                                    'id_alarm' => NULL,
                                    'id_device' => $device_pds->id_device,
                                    'id_incidencia' => $id_incidencia,
                                    'id_client' => NULL,
                                    'fecha' => date('Y-m-d H:i:s'),
                                    'unidades' => 1,
                                    'procesado' => 1,
                                    'status' => 'Transito'
                                );
                                //$this->db->insert('historico_io', $elemento);
                                $this->alta_historicoIo($elemento);
                            }
                            /*La posicion que genera la incidencia pasa a estar en Baja*/
                            $sql = "UPDATE devices_pds SET status='Baja' WHERE id_devices_pds =" . $id_devices_pds;
                            $this->db->query($sql);

                            /*Insertamos el cambio de estado en el historico*/
                            $elemento = array(
                                'id_devices_pds' => $id_devices_pds,
                                'fecha' => date('Y-m-d H:i:s'),
                                'status' => 'Baja',
                                'motivo' => INCIDENCIA_RESUELTA
                            );
                            $this->insert_historico_devicesPDS($elemento);
                        }

                    }
                }

                break;

            /*Cuando se hace el cierre forzoso de la incidencia*/
            case ($status == 8):

                /* obtengo los datos de la posicion que ha generado la incidencia */
                $query = $this->db->select('*')
                    ->where('id_devices_pds', $id_devices_pds)
                    ->get('devices_pds');
                $device_pds = $query->row();

                /*El material que se haya asignado ya este en transito o reservado pasara a Stock*/
                $sql = "SELECT * FROM material_incidencias m
                        INNER JOIN devices_almacen d ON d.id_devices_almacen=m.id_devices_almacen
                        WHERE m.id_incidencia=$id_incidencia AND m.id_devices_almacen IS NOT NULL";
                $material = $this->db->query($sql)->row();

                if (!empty($material)) {
                    $sql = "UPDATE devices_almacen SET status='En stock' WHERE  (status='Transito' || status='Reservado') AND id_devices_almacen=$material->id_devices_almacen";
                    $this->db->query($sql);

                    /*Insertar en el historico del almacen el estado del dispositivo*/
                    $elemento = array(
                        'id_material_incidencia' => $material->id_material_incidencias,
                        'id_devices_almacen'    =>  $material->id_devices_almacen,
                        'id_device'=>$material->id_device,
                        'id_incidencia' => $id_incidencia,
                        'fecha' => date('Y-m-d H:i:s'),
                        'unidades' => 1,
                        'procesado' => 1,
                        'status'    => 'En stock'
                    );
                    //$this->db->insert('historico_io',$elemento);
                    $this->alta_historicoIo($elemento);
                }
                if($device_pds->status == 'Incidencia') {
                    /*La posicion que genera la incidencia pasa a estar en Alta*/
                    $sql = "UPDATE devices_pds SET status='Alta' WHERE id_devices_pds =" . $id_devices_pds;
                    $this->db->query($sql);

                    /*Insertamos el cambio de estado en el historico*/
                    $elemento = array(
                        'id_devices_pds' => $id_devices_pds,
                        'fecha' => date('Y-m-d H:i:s'),
                        'status' => 'Alta',
                        'motivo' => INCIDENCIA_CIERREFORZOSO
                    );
                    $this->insert_historico_devicesPDS($elemento);
                }

                break;

            /*Ya se ha recogido el material y se cierra la incidencia*/
            case ($status == 9):
                /*insertar en almacen el dispositivo que origino la incidencia en estado en transito*/
                /* Guardamos los datos de la incidencia y el devices_pds al que afecta la incidencia se da de baja */
                $query = $this->db->select('*')
                    ->where('id_devices_pds', $id_devices_pds)
                    ->get('devices_pds');
                $device_pds = $query->row();

                $status='En stock';
                $id_devices_almacen=NULL;
                $id_device=NULL;
                $id_material_incidencia=NULL;


                //$result = null;
                if (!empty($device_pds)) {
                    $query = $this->db->select('*')
                        ->where('id_incidencia', $id_incidencia)
                        ->get('incidencias');
                    $incidencia = $query->row();

                    $sql = "SELECT * FROM devices_almacen WHERE status='Transito' AND id_device=$device_pds->id_device AND id_incidencia=$id_incidencia";
                    $result = $this->db->query($sql)->row();
                    //print_r($result);exit;
                    if (!empty($result)) {
                        if (!empty($device_pds) && $incidencia->tipo_averia != 'Robo') {

                            $id_devices_almacen = $result->id_devices_almacen;
                            $id_device = $result->id_device;
                            $sql = "UPDATE devices_almacen SET status='En stock' WHERE  id_devices_almacen=$result->id_devices_almacen";
                            $this->db->query($sql);
                        } else {
                            if (!empty($device_pds) && $incidencia->tipo_averia == 'Robo') {
                                $status = 'RMA';
                                $id_devices_almacen = $result->id_devices_almacen;
                                $id_device = $result->id_device;
                                $sql = "UPDATE devices_almacen SET status='RMA' WHERE  id_devices_almacen=$result->id_devices_almacen";
                                $this->db->query($sql);
                            }
                        }
                    }else {
                            $sql = "SELECT * FROM material_incidencias m
                                    INNER JOIN devices_almacen d ON d.id_devices_almacen=m.id_devices_almacen
                                    WHERE m.id_incidencia=$id_incidencia";
                            $material = $this->db->query($sql)->row();

                            if (!empty($material)) {
                                $id_devices_almacen = $material->id_devices_almacen;
                                $id_device = $material->id_device;
                                $id_material_incidencia = $material->id_material_incidencias;

                                $sql = "UPDATE devices_almacen SET status='En stock' WHERE  status='Transito' AND id_devices_almacen=$material->id_devices_almacen";
                                $this->db->query($sql);
                            }
                        }
                }else {//Si la incidencia no es de un dispositivo pero si tiene terminales asignados
                    $query = $this->db->select('*')
                        ->where('id_incidencia', $id_incidencia)
                        ->get('incidencias');
                    $incidencia = $query->row();

                    $sql = "SELECT * FROM devices_almacen WHERE status='Transito' AND id_incidencia=$id_incidencia";
                    $result = $this->db->query($sql)->row();

                    if (!empty($result)) {
                        if ($incidencia->tipo_averia != 'Robo') {
                            $id_devices_almacen = $result->id_devices_almacen;
                            $id_device = $result->id_device;
                            $sql = "UPDATE devices_almacen SET status='En stock' WHERE  id_devices_almacen=$result->id_devices_almacen";
                            $this->db->query($sql);
                        } else {
                            if ($incidencia->tipo_averia == 'Robo') {
                                $status = 'RMA';
                                $id_devices_almacen = $result->id_devices_almacen;
                                $id_device = $result->id_device;
                                $sql = "UPDATE devices_almacen SET status='RMA' WHERE  id_devices_almacen=$result->id_devices_almacen";
                                $this->db->query($sql);
                            }
                        }
                    }
                }

                /*Insertar en el historico del almacen el estado del dispositivo*/
                $elemento = array(
                    'id_material_incidencia' => $id_material_incidencia,
                    'id_devices_almacen' => $id_devices_almacen,
                    'id_alarm' => NULL,
                    'id_device'=>$id_device,
                    'id_incidencia' => $id_incidencia,
                    'id_client' => NULL,
                    'fecha' => date('Y-m-d H:i:s'),
                    'unidades' => 1,
                    'procesado' => 1,
                    'status'    => $status
                );
                //$this->db->insert('historico_io',$elemento);
                $this->alta_historicoIo($elemento);

                break;

            /* se sustituyen los terminales implicados en la incidencia*/
            case ($status == 10):
                /* Guardamos los datos de la posicion que genero la incidencia y el devices_pds al que afecta la incidencia se da de baja */
                $query = $this->db->select('*')
                    ->where('id_devices_pds', $id_devices_pds)
                    ->get('devices_pds');
                $device_pds = $query->row();

                /** Guardamos los datos del dispositivo que se queda en la tienda y la entrada en devices_almacen que
                 * va a ser instalada para solucionar la incidencia se da de baja*/
                $sql = "SELECT id_devices_almacen,id_material_incidencias,cantidad FROM material_incidencias WHERE id_incidencia=$id_incidencia AND id_alarm IS NULL";
                $material=$this->db->query($sql);

                $encontrado = false;
                /*Si por lo que sea tenemos varios registros de material de incidencias tenemos que buscar el que realmente sea un dispositivo del almacen y no un modelo de dispositivo*/
                foreach ($material->result_array() as $material) {

                    $sql = "SELECT * FROM devices_almacen WHERE id_devices_almacen = " . $material["id_devices_almacen"] ." AND status='Transito'";
                    $device_almacen = $this->db->query($sql)->row();
                    if (!empty($device_almacen)) {

                        $encontrado = true;
                        //$sql = "SELECT * FROM devices_almacen WHERE id_devices_almacen = $device_almacen-> (SELECT id_devices_almacen FROM material_incidencias WHERE id_incidencia=$id_incidencia AND id_alarm IS NULL)";
                        //$device_almacen = $this->db->query($sql)->row();
                       // $result = $material->row();

                        /*Insertar en el historico del almacen el estado del dispositivo*/
                        $elemento = array(
                            'id_material_incidencia' => $material["id_material_incidencias"],
                            'id_alarm' => NULL,
                            'id_device' => $device_almacen->id_device,
                            'id_devices_almacen' => $device_almacen->id_devices_almacen,
                            'id_incidencia' => $id_incidencia,
                            'id_client' => NULL,
                            'fecha' => date('Y-m-d H:i:s'),
                            'unidades' => $material["cantidad"] * (-1),
                            'procesado' => 1,
                            'status' => 'Baja'
                        );
                        // $this->db->insert('historico_io',$elemento);
                        $this->alta_historicoIo($elemento);

                        $sql = "UPDATE devices_almacen SET status='Baja' WHERE id_devices_almacen =" . $device_almacen->id_devices_almacen;
                        $this->db->query($sql);


                        /* poner de baja la posicion que origino la incidencia*/
                        if ($device_pds->status != 'Baja') {
                            $sql = "UPDATE devices_pds SET status='RMA' WHERE id_devices_pds =" . $device_pds->id_devices_pds;
                            $this->db->query($sql);

                            /*Insertamos el cambio de estado en el historico*/
                            $elemento = array(
                                'id_devices_pds' => $device_pds->id_devices_pds,
                                'fecha' => date('Y-m-d H:i:s'),
                                'status' => 'RMA',
                                'motivo' => INCIDENCIA_TERMINALESSUSTITUIDOS
                            );
                            $this->insert_historico_devicesPDS($elemento);
                        }

                        /* Insertar el dispositivo instalado en la posición del mueble */
                        $data = array(
                            'client_type_pds' => $device_pds->client_type_pds,
                            'id_pds' => $device_pds->id_pds,
                            'id_displays_pds' => $device_pds->id_displays_pds,
                            'id_display' => $device_pds->id_display,
                            'alta' => $ahora,
                            'position' => $device_pds->position,
                            'id_device' => $device_almacen->id_device,
                            'IMEI' => $device_almacen->IMEI,
                            'mac' => $device_almacen->mac,
                            'serial' => $device_almacen->serial,
                            'barcode' => $device_almacen->barcode,
                            'status' => 1
                        );
                        $this->db->insert('devices_pds', $data);


                        /*Insertamos el alta del nuevo dispositivo en tienda*/
                        $elemento = array(
                            'id_devices_pds' => $this->db->insert_id(),
                            'fecha' => date('Y-m-d H:i:s'),
                            'status' => 'Alta',
                            'motivo' => INCIDENCIA_TERMINALESSUSTITUIDOS
                        );
                        $this->insert_historico_devicesPDS($elemento);
                    }
                }
                if(!$encontrado)
                    $error = "No se han podido sustituir los terminales";

                break;

            /* se sustituyen los terminales implicados en la incidencia siendo necesario enviar a almacen
                                el terminal que genero la incidencia como RMA*/
            case ($status == 11):
                /* Guardamos los datos de la posicion que genero la incidencia y el devices_pds al que afecta la incidencia se da de baja */
                $query = $this->db->select('*')
                    ->where('id_devices_pds', $id_devices_pds)
                    ->get('devices_pds');
                $device_pds = $query->row();

                /*
                 * Guardamos los datos del dispositivo que se queda en la tienda y la entrada en devices_almacen que va a ser instalada para solucionar la incidencia se da de baja
                 */
                $sql = "SELECT * FROM material_incidencias WHERE id_incidencia=$id_incidencia AND id_alarm IS NULL";
                $material=$this->db->query($sql);


                $encontrado = false;
                /*Si por lo que sea tenemos varios registros de material de incidencias tenemos que buscar el que realmente sea un dispositivo del almacen y no un modelo de dispositivo*/
                foreach ($material->result_array() as $material) {

                    $sql = "SELECT * FROM devices_almacen WHERE id_devices_almacen = " . $material["id_devices_almacen"] ." AND status='Transito'";
                    $device_almacen = $this->db->query($sql)->row();
                    if (!empty($device_almacen)) {

                        $encontrado = true;
                        //if ($material->num_rows() == 1) {

                        // $sql = "SELECT * FROM devices_almacen WHERE id_devices_almacen = (SELECT id_devices_almacen FROM material_incidencias WHERE id_incidencia=$id_incidencia AND id_alarm IS NULL)";
                        //$device_almacen = $this->db->query($sql)->row();
                        //$result=$material->row();

                        /*Insertar en el historico del almacen el estado del dispositivo*/
                        $elemento = array(
                            'id_material_incidencia' => $material["id_material_incidencias"],
                            'id_alarm' => NULL,
                            'id_device' => $device_almacen->id_device,
                            'id_devices_almacen' => $device_almacen->id_devices_almacen,
                            'id_incidencia' => $id_incidencia,
                            'id_client' => NULL,
                            'fecha' => date('Y-m-d H:i:s'),
                            'unidades' => $material["cantidad"] * (-1),
                            'procesado' => 1,
                            'status' => 'Baja'
                        );
                        //$this->db->insert('historico_io',$elemento);
                        $this->alta_historicoIo($elemento);

                        $sql = "UPDATE devices_almacen SET status='Baja' WHERE id_devices_almacen =" . $device_almacen->id_devices_almacen;
                        $this->db->query($sql);
                        /* poner como RMA la posicion que origino la incidencia*/
                        $sql = "UPDATE devices_pds SET status='RMA' WHERE id_devices_pds =" . $device_pds->id_devices_pds;
                        $this->db->query($sql);

                        /* Insertar el dispositivo instalado en la posición del mueble */
                        $data = array(
                            'client_type_pds' => $device_pds->client_type_pds,
                            'id_pds' => $device_pds->id_pds,
                            'id_displays_pds' => $device_pds->id_displays_pds,
                            'id_display' => $device_pds->id_display,
                            'alta' => $ahora,
                            'position' => $device_pds->position,
                            'id_device' => $device_almacen->id_device,
                            'IMEI' => $device_almacen->IMEI,
                            'mac' => $device_almacen->mac,
                            'serial' => $device_almacen->serial,
                            'barcode' => $device_almacen->barcode,
                            'status' => 1
                        );
                        $this->db->insert('devices_pds', $data);
                    }
                }
                if(!$encontrado)
                    $error = "No se han podido sustituir los terminales";
                break;

            default:

                $this->db->set('status', 4, FALSE);
                $this->db->where('id_devices_pds', $id_devices_pds);
                $this->db->update('devices_pds');
                break;

        }
        /**
         * Cambiar last_updated
         */
        if(!is_null($id_incidencia)){
            $this->db->set('last_updated', "'$ahora'", FALSE);
            $this->db->where('id_incidencia',$id_incidencia);
            $this->db->update('incidencias');
        }

    }


	public function historico($data)
	{
		$this->db->insert('historico',$data);
		$id=$this->db->insert_id();
	}	
	
	public function incidencia_update_material($data,$almacen)
	{
		$this->db->insert('material_incidencias',$data);
		$id=$this->db->insert_id();
        if($almacen)
            $this->alta_historico_io($data,$id);

	}

    /**
     * Inserción de la Salida (No procesada hasta que no sea definitivo el material de la intervención,
     * al cambiar de estado la incidencia).
     */
    public function alta_historico_io($data,$id){

        $tipo = (is_null($data['id_alarm'])) ? "device" : "alarm";

        if($tipo==="device")            // ES DE TIPO TERMINAL
        {
            if(!empty($data['id_devices_almacen'])) {
                $q_dueno = $this->db->query("SELECT DISTINCT(client_display) as id_client, device.id_device as id_device,devices_almacen.status FROM display
                                          JOIN displays_pds ON display.id_display = displays_pds.id_display
                                          JOIN incidencias ON displays_pds.id_displays_pds = incidencias.id_displays_pds
                                          JOIN material_incidencias ON incidencias.id_incidencia = material_incidencias.id_incidencia
                                          JOIN devices_almacen ON devices_almacen.id_devices_almacen = material_incidencias.id_devices_almacen
                                          JOIN device ON devices_almacen.id_device = device.id_device
                                          WHERE material_incidencias.id_devices_almacen=" . $data['id_devices_almacen'])->row();
                $id_device = $q_dueno->id_device;
                $dueno = $q_dueno->id_client;
                $procesado = 0;
                $status=$q_dueno->status;
                $id_devices_almacen = $data['id_devices_almacen'];

            }else{
                $id_device = $data["id_device"];
                $dueno = NULL;
                $procesado = $data["procesado"];
                $status=$data['status'];
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
                'procesado' => $procesado,
                'status'    => $status
            );

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



        }
       // $this->db->insert('historico_io',$elemento);
        $this->alta_historicoIo($elemento);

    }

    /** Método que da de baja unba alarma de la tabla de historico_io, porque se ha desasignado de una incidencia.
    * @param $id_material*/
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
     * @param $id_material
     */
    public function procesar_historico_incidencia($id_incidencia=NULL)
    {
        if(! is_null($id_incidencia))
        {
            $historico_io['procesado'] = 1;
            $historico_io['status'] = "Transito";

            $this->db->where('id_incidencia',$id_incidencia);
            $this->db->update('historico_io',$historico_io);
        }
    }

    public function facturacion($data)
	{
        //$id_intervencion = isset($data["id_intervencion"]) ? $data["id_intervencion"] : NULL;
        $id_incidencia = isset($data["id_incidencia"]) ? $data["id_incidencia"] : NULL;

       $query = $this->db->select("count(id_facturacion) as facturado")->where("id_incidencia",$id_incidencia)->get("facturacion")->result();
        $res = array_shift($query);
       if($res->facturado == 0)
       {
           $this->db->insert('facturacion', $data);
           $id = $this->db->insert_id();
       }
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
	

    /**
     * Insertar mueble SFID
     */
    function anadir_mueble_sfid($display,$pds,$tipo="noApertura",$position = NULL,$id_tipo_alarmado=NULL)
    {
        $id_displays_pds=-1;
        // Si no están vacíos los objetos MUEBLE y PDS
        if(!empty($display) && !empty($pds))
        {
            $id_pds = $pds->id_pds;
            $id_display = $display->id_display;
            $client_type_pds = 1;


            if(is_null($position))
            {
                // Si no recibimos posición donde insertar (pos=NULL) lo ponemos al final del panelado
                $query = $this->db->query(" SELECT max(position) as ultimo FROM displays_pds WHERE id_pds = " . $id_pds);
                $result = $query->row();

                if(!empty($result->ultimo) && $result->ultimo > 0)
                {
                    $position = $result->ultimo + 1;
                }else{
                    $position = 1;
                }

            }
            else {

                // SACAMOS TODOS LOS MUEBLES DESDE LA POS a INSERTAR EN ADELANTE, EN ESA TIENDA, Y LOS MOVEMOS UNA POSICION ADELANTE
                $SQL = " SELECT id_displays_pds as ocupado FROM displays_pds WHERE id_pds = " . $id_pds . " AND position =" . $position;
                $query = $this->db->query($SQL);
                $result = $query->row();

                if (!empty($result->ocupado)) {

                    $SQL = "    UPDATE displays_pds
                        SET position = (position + 1)
                        WHERE id_pds = " . $id_pds . " AND position >= " . $position;

                    $this->db->query($SQL);

                }
            }

            $SQL = " INSERT INTO displays_pds (client_type_pds, id_pds, id_display, id_tipo_alarmado,position, status)
                                        VALUES(".$client_type_pds.",".$id_pds.",".$id_display.",".(empty($id_tipo_alarmado) ? 'NULL' : $id_tipo_alarmado).",".$position.",'Alta'); ";
            
            $this->db->query($SQL);
            $id_displays_pds = $this->db->insert_id();

            // Buscar el planograma genérico del mueble
            $planograma = $this->get_devices_display($id_display);

            if(!empty($planograma))
            {
                foreach ($planograma as $terminal)
                {
                    $fecha = date('Y-m-d H:i:s');
                    $position = $terminal->position;
                    $id_device = $terminal->id_device;
                    $id_muebledisplay = null;
                    if($terminal->isDisplay)
                        $id_muebledisplay = $terminal->id_muebledisplay;
                    $SQL = " INSERT INTO devices_pds(client_type_pds,id_pds, id_displays_pds, id_display, alta,position, id_device,id_muebledisplay, status)
                                            VALUES(".$client_type_pds.",".$id_pds.",".$id_displays_pds.",".$id_display.",'".
                        $fecha."',".$position.",".$id_device.",".$id_muebledisplay.",'Alta'); ";
                    $this->db->query($SQL);

                    /*Insertar en el historico de tienda el estado del dispositivo*/
                    $elemento = array(
                        'id_devices_pds' => $this->db->insert_id(),
                        'fecha' => $fecha,
                        'status' => 'Alta',
                        'motivo' => APERTURA
                    );
                    if($tipo=="noApertura")
                        $elemento['motivo']=ADDMUEBLE;

                    $this->db->insert('historico_devicesPDS', $elemento);
                }
            }

        }

        return $id_displays_pds;

    }

    public function actualizar_devices_almacen($id_inc)
    {
        $sql="SELECT id_devices_almacen FROM material_incidencias WHERE id_incidencia=$id_inc AND id_devices_almacen IS NOT NULL";
        $material_incidencia=$this->db->query($sql)->result();
        if(!empty($material_incidencia)) {
            foreach ($material_incidencia as $m) {
                $sql = "SELECT * FROM devices_almacen WHERE status='Reservado' AND id_devices_almacen =".$m->id_devices_almacen;
                $device_almacen=$this->db->query($sql)->row();
                if(!empty($device_almacen)) {
                    $sql = "UPDATE devices_almacen SET status='Transito' WHERE id_devices_almacen =".$device_almacen->id_devices_almacen;
                    $this->db->query($sql);
                }
            }
        }
    }

    /*
	 * insertamos los datos de un dispositivo del almacen en el historico
	 */
    public function alta_historicoIo($elemento,$estado_anterior=NULL){
        if(!empty($estado_anterior)) {
            if ($estado_anterior != $elemento['status']) {
                $this->db->insert('historico_io', $elemento);
            }
        }
        else {
            $this->db->insert('historico_io', $elemento);
        }
    }

    /*insertamos un device en almacen*/
    public function alta_device_almacen($elemento){

        if (!empty($elemento)) {
            $this->db->insert('devices_almacen', $elemento);
            $id=$this->db->insert_id();
            return $id;
        }
    }

    /*Nos devuelve el listado de los alarmados que están en Alta*/
    public function get_alarmados(){
        $query = $this->db->select('tipo_alarmado.*')
            ->where('status','Alta')
            ->order_by('title','asc')
            ->get('tipo_alarmado');
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

    /**
     * Eliminar mueble SFID
     */
    function eliminar_mueble_sfid($display,$pds)
    {
        $resultado  = array() ;
        // Si no están vacíos los objetos MUEBLE y PDS
        if(!empty($display) && !empty($pds))
        {
            $id_pds = $pds->id_pds;
            $id_display = $display->id_display;

            //Obtenemos los datos del mueble en la tienda
            $SQL = " SELECT * FROM displays_pds WHERE id_pds = " . $id_pds ." AND id_display = ".$id_display." AND status='Alta'";
            $query = $this->db->query($SQL);
            $displays_pds = $query->row();

            if(!empty($displays_pds) ){
                $SQL = "SELECT * FROM devices_pds WHERE id_displays_pds = ".$displays_pds->id_displays_pds." AND status ='Alta'";
                $query = $this->db->query($SQL);
                $devices_pds = $query->result();

                if(!empty($devices_pds)){
                    /*Si el numero de elementos que están de alta es igual al numero de elementos del mueble entonces lo damos de baja*/
                    if($display->positions==count($devices_pds)) {
                        $SQL = "UPDATE devices_pds SET status='Baja' WHERE id_displays_pds=".$displays_pds->id_displays_pds.
                        " AND status='Alta'";
                        $this->db->query($SQL);

                        $SQL = "UPDATE displays_pds SET status='Baja' WHERE id_displays_pds=".$displays_pds->id_displays_pds;
                        $this->db->query($SQL);
                        $resultado =array ("resultado"=>1,"sfid"=>$pds->reference,"mueble" => $displays_pds->id_displays_pds,"dispositivos"=>count($devices_pds),
                            "mensaje" =>" Tiene ".count($devices_pds)." dispositivos");

                        foreach ($devices_pds as $device){
                            $elemento = array(
                                'id_devices_pds' => $device->id_devices_pds,
                                'fecha' => date('Y-m-d H:i:s'),
                                'status' => 'Baja',
                                'motivo' => REMOVEMUEBLE
                            );
                            // print_r($elemento); echo "<br>";
                            $this->insert_historico_devicesPDS($elemento);
                        }
                    }else {
                        $SQL = "SELECT * FROM devices_pds WHERE id_displays_pds = ".$displays_pds->id_displays_pds." AND (status ='RMA' OR status='Incidencia')";
                        //echo $SQL; exit;
                        $query = $this->db->query($SQL);
                        $devices_pdsRMA = $query->result();
                        if(!empty( $devices_pdsRMA)){
                            $resultado =array ("resultado"=>0,"sfid"=>$pds->reference,"mueble" => $displays_pds->id_displays_pds,"dispositivos"=>count($devices_pdsRMA),
                                "mensaje"=>" Tiene ".count($devices_pdsRMA)." posiciones con incidencia/RMA");
                        }else{
                            $SQL = "UPDATE devices_pds SET status='Baja' WHERE id_displays_pds=".$displays_pds->id_displays_pds.
                            " AND status='Alta'";
                            $this->db->query($SQL);
                            
                            $SQL = "UPDATE displays_pds SET status='Baja' WHERE id_displays_pds=".$displays_pds->id_displays_pds;
                            $this->db->query($SQL);

                            $resultado =array ("resultado"=>1,"sfid"=>$pds->reference,"mueble" => $displays_pds->id_displays_pds,"dispositivos"=>count($devices_pds),
                                "mensaje" =>" Tiene ".count($devices_pds)." dispositivos");
                            
                            foreach ($devices_pds as $device){
                                $elemento = array(
                                    'id_devices_pds' => $device->id_devices_pds,
                                    'fecha' => date('Y-m-d H:i:s'),
                                    'status' => 'Baja',
                                    'motivo' => REMOVEMUEBLE
                                );
                                // print_r($elemento); echo "<br>";
                                $this->insert_historico_devicesPDS($elemento);
                            }
                        }
                    }
                }else {
                    $SQL = "SELECT * FROM devices_pds WHERE id_displays_pds = ".$displays_pds->id_displays_pds." AND (status ='RMA' OR status='Incidencia')";
                    //echo $SQL; exit;
                    $query = $this->db->query($SQL);
                    $devices_pds_inc = $query->result();
                    if(count($devices_pds_inc)!=0)
                        $resultado =array ("resultado"=>0,"sfid"=>$pds->reference,"mueble" => $displays_pds->id_displays_pds,"dispositivos"=>count($devices_pds),
                            "mensaje"=>" Tiene ".count($devices_pds_inc)." posiciones con incidencia/RMA");
                    else
                        $resultado = array("resultado"=>0,"sfid" => $pds->reference, "mueble" => $displays_pds->id_displays_pds, "dispositivos" => count($devices_pds),
                            "mensaje"=>" Tiene ".count($devices_pds)." dispositivos");
                }
            }else {
                $resultado = array("resultado"=>0,"sfid" => $pds->reference, "mueble" => 0, "dispositivos" => 0,
                    "mensaje"=>" El mueble ya ha  sido dado de baja");
            }

        }
        return $resultado;

    }


    /*Actualiza la tienda pasada como parametro, usada principalmente para incorporar la fecha de apertura*/
    function update_pdv($pds){
        $sql = "UPDATE pds SET openingDate='$pds->openingDate' WHERE id_pds =$pds->id_pds";
        $this->db->query($sql);
    }
}
?>