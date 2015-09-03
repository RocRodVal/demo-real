<?php

class Incidencia_model extends CI_Model {

	public function __construct()	{
		$this->load->database();
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
	

	
	
	public function get_pds($id) {
		if($id != FALSE) {
			$query = $this->db->select('pds.*,territory.territory')
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
	
	
	public function get_incidencias() {
			$query = $this->db->select('incidencias.*,pds.reference as reference')
			        ->join('pds','incidencias.id_pds = pds.id_pds')
				    ->order_by('fecha ASC')
			        ->get('incidencias');
	
			return $query->result();
	}	
	
	
	public function get_incidencias_pds($id) {
		$query = $this->db->select('incidencias.*,pds.reference as reference')
		->join('pds','incidencias.id_pds = pds.id_pds')
	
		->where('incidencias.id_pds',$id)
		->order_by('fecha ASC')
		->get('incidencias');
	
		return $query->result();
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



    /*
	 *  Devuelve conjunto de registros de incidencias abiertas,
	 *  filtradas si procede, y el subconjunto limitado paginado si procede
	 *
	 * */
    public function get_estado_incidencias($page = 1, $cfg_pagination = NULL,$array_orden= NULL,$filtros=NULL, $tipo="abiertas") {

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
     *  Devuelve conjunto de registros de incidencias abiertas, para generar CSV
     *  filtradas si procede
     *
     * */
    public function get_estado_incidencias_csv($array_orden = NULL,$filtros=NULL,$tipo="abiertas") {
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

                            device.brand_device as fabr,
                            territory.territory as `Territorio`,
                            ';


        $sql .= ' (SELECT brand_device.brand from brand_device  WHERE id_brand_device = fabr
                            ) as `Fabricante` ,';

        $sql .= 'incidencias.tipo_averia,';


        /*                    (CASE incidencias.fail_device WHEN 1 THEN ("Sí") ELSE ("No") END) AS `Fallo dispositivo`,
                            (CASE incidencias.alarm_display WHEN 1 THEN ("Sí") ELSE ("No") END) AS `Alarma mueble`,
                            (CASE incidencias.alarm_device WHEN 1 THEN ("Sí") ELSE ("No") END) AS `Alarma dispositivo`,
                            (CASE incidencias.alarm_garra WHEN 1 THEN ("Sí") ELSE ("No") END) AS `Alarma anclaje`,*/

        $sql .='
                           REPLACE(REPLACE(incidencias.description_1,CHAR(10),CHAR(32)),CHAR(13),CHAR(32)) as description_1,
                            REPLACE(REPLACE(incidencias.description_2,CHAR(10),CHAR(32)),CHAR(13),CHAR(32))  as description_2,
                            REPLACE(REPLACE(incidencias.description_3,CHAR(10),CHAR(32)),CHAR(13),CHAR(32))  as description_3,
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
            $sql .= 'incidencias.last_updated AS `Última modificación`, ';
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
                LEFT JOIN territory ON territory.id_territory=pds.territory
                WHERE 1 = 1';





        if($tipo==="abiertas")  $sTitleFilename = "Incidencias_abiertas";
        else  $sTitleFilename = "Incidencias_cerradas";

        $sql  .= ' && '.$this->get_condition_tipo_incidencia($tipo);

        // Montamos las cláusulas where filtro, según el array pasado como param.
        $sFiltrosFilename = "-";


        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["status"]) && !empty($filtros["status"]))                 $sql .= (' AND incidencias.status ="' .$filtros['status']. '"');
        if(isset($filtros["status_pds"]) && !empty($filtros["status_pds"]))         $sql .= (' AND incidencias.status_pds ="'.$filtros['status_pds'].'"');
        if(isset($filtros["id_incidencia"]) && !empty($filtros["id_incidencia"]))   $sql .= (' AND id_incidencia ='.$filtros['id_incidencia']);
        if(isset($filtros["territory"]) && !empty($filtros["territory"]))           $sql .= (' AND pds.territory = '.$filtros['territory']);
        if(isset($filtros["brand_device"]) && !empty($filtros["brand_device"]))
        {
            $sql .= (' AND incidencias.fail_device=1');
            $sql .= (' AND device.brand_device='.$filtros['brand_device']);
        }
        if(isset($filtros["reference"]) && !empty($filtros["reference"])) $sql .=(' AND reference= '.$filtros['reference']);

        $campo_orden = $orden = NULL;
        if(count($array_orden) > 0) {
            foreach ($array_orden as $key=>$value){
                $campo_orden = $key;
                $orden = $value;
            }
        }
        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($orden) && !empty($orden)) {
            $s_orden = $campo_orden. " ".$orden;
            $sql .= " ORDER BY ".($s_orden);
        }else{
            $sql .= " ORDER BY fecha DESC";
        }

        $query = $this->db->query($sql);


        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Demo_Real-'.$sTitleFilename.$sFiltrosFilename.date("d-m-Y").'T'.date("H:i:s").'.csv', $data);


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

    public function get_estado_incidencias_quantity($filtros=NULL, $tipo="abiertas") {

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




    function resetear_incidencia($id_inc) 
    {

        $o_pds = $this->db->query("SELECT id_pds FROM incidencias WHERE id_incidencia = '$id_inc' ")->row_array();

        $id_pds = (isset($o_pds["id_pds"])) ? $o_pds["id_pds"] : NULL;

        $data["id_pds"] = $id_pds;
        $data["id_inc"] = $id_inc;


        // Ya tenemos el ID....
        // - Comprobamos que exista la incidencia y no esté finalizada
        // - Reseteamos el status y status_pds
        // - Eliminamos del histórico de estados las entradas...
        // - Eliminamos la intervención
        // - Eliminamos el material asignado.

        $sql_check = ' SELECT id_incidencia FROM incidencias WHERE status NOT IN ("Cerrada","Cancelada","Resuelta") AND status_pds != "Finalizada" AND id_incidencia = "'.$id_inc.'" ';
        $query = $this->db->query($sql_check);
        $check = $query->row_array();


        if(empty($check))
        {
            // La incidencia no existe, o no es reseteable...
            return false;
        }
        else {
            // La incidencia exsite, seguimos con el proceso de reseteo...
            // Reseteamos estado de la incidencia
            $sql_status = 'UPDATE incidencias SET fecha_cierre = NULL, status="Nueva", status_pds = "Alta realizada" WHERE id_incidencia="' . $id_inc . '"';
            $query = $this->db->query($sql_status);

            // Sacar el ID de intervención asignada a la incidencia...
            $sql_intervencion = 'SELECT id_intervencion FROM intervenciones_incidencias WHERE id_incidencia = "' . $id_inc . '"';
            $query = $this->db->query($sql_intervencion);
            $resultado_intervencion = $query->row_array();
            if (!empty($resultado_intervencion)) {

                // Ahora borramos la relación de la incidencia con la intervención...
                $id_intervencion = $resultado_intervencion["id_intervencion"];
                $sql_int_inc = 'DELETE FROM intervenciones_incidencias WHERE id_incidencia = "' . $id_inc . '"';
                $this->db->query($sql_int_inc);

                // Si no hay otras incidencias relacionadas con la misma intervención, borramos la intervención...
                $sql_int_inc = 'SELECT id_incidencia, id_intervencion FROM intervenciones_incidencias WHERE id_intervencion = "' . $id_intervencion . '"';
                $query = $this->db->query($sql_int_inc);
                $resultados = $query->result();

                if (empty($resultados)) {
                    $this->db->query('DELETE FROM intervenciones WHERE id_intervencion ="' . $id_intervencion . '"');
                }


                // Eliminamos de la facturación la intervención...
                $this->db->query('DELETE FROM facturacion WHERE id_intervencion = "' . $id_intervencion . '"');

            }


            // Borramos el histórico de estados
            $this->db->query('DELETE FROM historico WHERE id_incidencia = "' . $id_inc . '"');

            // Borramos el material asignado
            $this->desasignar_material($id_inc);

            return true;
        }


    }



    function desasignar_material($id_inc,$tipo_dispositivo = "todo",$id_pds = NULL,$id_material_incidencia = NULL)
    {
        // TERMINAL
        if($tipo_dispositivo==="device" || $tipo_dispositivo==="todo")
        {
            $material_dispositivos = $this->tienda_model->get_material_dispositivos($id_inc);
            if (!empty($material_dispositivos)) {
                foreach ($material_dispositivos as $material) {

                    if($material->id_material_incidencias == $id_material_incidencia || $tipo_dispositivo==="todo")
                    {
                        // Poner el dispositivo de nuevo "En stock"
                        $id_devices_almacen = $material->id_devices_almacen;
                        $sql = "UPDATE devices_almacen SET status = 'En stock' WHERE id_devices_almacen = '" . $id_devices_almacen . "'";
                        $this->db->query($sql);

                        // Desvincular el dispositivo del material de la incidencia.
                        $id_material_incidencias = $material->id_material_incidencias;
                        $sql = "DELETE FROM material_incidencias WHERE id_material_incidencias = '$id_material_incidencias' ";
                        $this->db->query($sql);

                        // Borrar del histórico de dispositivo (diario almacen)
                        $this->tienda_model->baja_historico_io($id_material_incidencias);
                    }
                }
            }
        }

        if($tipo_dispositivo==="alarm" || $tipo_dispositivo==="todo")
        {
            $material_alarmas = $this->tienda_model->get_material_alarmas($id_inc);
            if (!empty($material_alarmas)) {
                foreach ($material_alarmas as $alarma) {
                    if($alarma->id_material_incidencias == $id_material_incidencia || $tipo_dispositivo==="todo")
                    {
                        // Incrementar la cantidad (stock a devolver), en la tabla ALARM, campo units.
                        $sql = "UPDATE alarm SET units = units +" . $alarma->cantidad . " WHERE id_alarm='" . $alarma->id_alarm . "'";
                        $this->db->query($sql);

                        // Borrar la alarma vinculada a material_incidencias
                        $id_material_incidencias = $alarma->id_material_incidencias;
                        $sql = "DELETE FROM material_incidencias WHERE id_material_incidencias = '$id_material_incidencias' ";
                        $this->db->query($sql);

                        // Borrar del histórico de alarmas (diario almacen)
                        $this->tienda_model->baja_historico_io($id_material_incidencias);
                    }
                }
            }
        }

    }
	
}

?>