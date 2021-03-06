<?php

class Incidencia_model extends CI_Model {


	public function __construct()	{
		$this->load->database();
        $this->load->model('chat_model');
        $this->load->model('tienda_model');
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
	
    /*
	 *  Devuelve conjunto de registros de incidencias abiertas,
	 *  filtradas si procede, y el subconjunto limitado paginado si procede
	 *
	 * */
    public function get_estado_incidencias($page = 1, $cfg_pagination = NULL,$array_orden= NULL,$filtros=NULL, $tipo="abiertas",$arr_agentes_excluidos=NULL) {

        if (empty($arr_agentes_excluidos)) {
           // echo "entra en el vario";
            $arr_agentes_excluidos = $this->chat_model->get_agentes_excluidos();
        }
        $agentes_excluidos = "";

        if(count($arr_agentes_excluidos)  > 0){
            foreach($arr_agentes_excluidos as $agente) $agentes_excluidos .= ("'".$agente."',");

        }

        $agentes_excluidos  = rtrim($agentes_excluidos,",");

        $this->db->select("incidencias.*,pds.reference as reference, pds_supervisor.titulo as supervisor, province.province as provincia, device.brand_device as fabricante,
                           territory.territory as territory,
                           (SELECT brand_device.brand from brand_device  WHERE id_brand_device = fabricante) as brand,
                           (SELECT COUNT(*)
                                FROM chat
                               
                                JOIN agent ON chat.agent = agent.sfid
                                WHERE chat.status = 'Nuevo'
                                AND incidencias.id_incidencia = chat.id_incidencia
                                AND agent.type NOT IN ($agentes_excluidos)) as nuevos,device.device, display.display",FALSE)

                 ->join('pds','incidencias.id_pds = pds.id_pds','left outer')
                 ->join('pds_supervisor','pds.id_supervisor= pds_supervisor.id','left')
                 ->join('province','pds.province= province.id_province','left')
                 ->join('displays_pds','incidencias.id_displays_pds= displays_pds.id_displays_pds','left outer')
                 ->join('display','displays_pds.id_display=display.id_display','left outer')
                 ->join('devices_pds','incidencias.id_devices_pds=devices_pds.id_devices_pds','left outer')
                 ->join('device','devices_pds.id_device=device.id_device','left outer')
                 ->join('territory','territory.id_territory=pds.territory','left outer')
                 ->join('brand_device','device.brand_device = brand_device.id_brand_device','left outer');

        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["status"])        && !empty($filtros["status"]))          $this->db->where('incidencias.status',$filtros['status']);
        if(isset($filtros["status_pds"])    && !empty($filtros["status_pds"]))      $this->db->where('incidencias.status_pds',$filtros['status_pds']);
        if(isset($filtros["id_incidencia"]) && !empty($filtros["id_incidencia"]))   $this->db->where('incidencias.id_incidencia',$filtros['id_incidencia']);
        if(isset($filtros["territory"])     && !empty($filtros["territory"]))       $this->db->where('pds.territory',$filtros['territory']);
        if(isset($filtros["brand_device"])  && !empty($filtros["brand_device"]))    $this->db->where('device.brand_device',$filtros['brand_device']);

        if(isset($filtros["id_display"])    && !empty($filtros["id_display"]))      $this->db->where('display.id_display',$filtros['id_display']);
        if(isset($filtros["id_device"])     && !empty($filtros["id_device"]))       $this->db->where('device.id_device',$filtros['id_device']);
        if(isset($filtros["id_supervisor"]) && !empty($filtros["id_supervisor"]))   $this->db->where('pds.id_supervisor',$filtros['id_supervisor']);
        if(isset($filtros["id_provincia"])  && !empty($filtros["id_provincia"]))    $this->db->where('province.id_province',$filtros['id_provincia']);

        if(isset($filtros["reference"])     && !empty($filtros["reference"]))       $this->db->where('reference',$filtros['reference']);
        if(isset($filtros["id_intervencion"])&& !empty($filtros["id_intervencion"])) {
            $this->db->join('intervenciones_incidencias','intervenciones_incidencias.id_incidencia= incidencias.id_incidencia','left');
            $this->db->where('intervenciones_incidencias.id_intervencion', $filtros['id_intervencion']);
        }

        if(isset($filtros["id_tipo"])       && !empty($filtros["id_tipo"]))         $this->db->where('pds.id_tipo',$filtros['id_tipo']);
        if(isset($filtros["id_subtipo"])    && !empty($filtros["id_subtipo"]))      $this->db->where('pds.id_subtipo',$filtros['id_subtipo']);
        if(isset($filtros["id_segmento"])   && !empty($filtros["id_segmento"]))     $this->db->where('pds.id_segmento',$filtros['id_segmento']);
        if(isset($filtros["id_tipologia"])  && !empty($filtros["id_tipologia"]))    $this->db->where('pds.id_tipologia',$filtros['id_tipologia']);
        if(isset($filtros["id_tipo_incidencia"])  && !empty($filtros["id_tipo_incidencia"])) {
            $this->db->join('type_incidencia','type_incidencia.id_type_incidencia=incidencias.id_type_incidencia','left outer');
            $this->db->where('incidencias.id_type_incidencia',$filtros['id_tipo_incidencia']);
        }



        /* Obtenemos la condici??n por tipo de incidencia */
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

        return $query->result();
    }


    /**
     *  Devuelve conjunto de registros de incidencias abiertas, para generar CSV
     *  filtradas si procede
     * */
    public function exportar_incidencias($array_orden = NULL,$filtros=NULL,$tipo="abiertas",$formato="csv",$porrazon=NULL,$conMaterial=NULL) {
        //$start = microtime(true);
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');

        $acceso = $this->uri->segment(1);

        // Array de t??tulos de campo para la exportaci??n XLS/CSV
        $arr_titulos = array('Id incidencia','SFID','Tipolog??a','Tipo','Direcci??n','Provincia','Fecha','Elemento','Territorio',
            'Fabricante','Mueble','Tipo alarmado','Terminal','Supervisor','Tipo aver??a','Tipo Robo',
            'Texto 1','Texto 2','Texto 3','Parte PDF','Denuncia','Foto 1','Foto 2','Foto 3','Contacto','Tel??fono','Email',
            'Id. Operador','Intervenci??n','Estado','??ltima modificaci??n','Estado Sat','Razon parada','Descripcion parada');
        $excluir = array('fecha_cierre','fabr','id_type_incidencia','id_tipo_robo',"tiempo_parada","meses","dias","horas","minutos","tiempo");

        if($conMaterial=="conMaterial") {
            $material=true;
        }else {
            $material=false;
        }
        if($material) {

            $arr_titulos = array('Id incidencia','SFID','Tipolog??a','Tipo','Direcci??n','Provincia','Fecha','Mueble','Terminal','Tipo aver??a',
                'Texto 1','Estado Sat','Descripcion parada','Tiempo parada','Tiempo','Semanas','Material Dispositivos','Material Alarmas');
            array_push($excluir,"elemento","Territorio","Fabricante","alarmado","supervisor","tipo_robo","description_2","description_3",
                "parte_pdf","denuncia","foto_url","foto_url_2","foto_cierre","contacto","phone","email","id_operador",
                "intervencion","id_tipo_robo","last_updated","Estado PDS","razon_Parada");
            $excluir = array_diff($excluir, array('tiempo_parada','tiempo'));
        }else {//ROBO

            if($porrazon=='robo') {
                $arr_titulos = array('Id incidencia', 'SFID', 'Tipolog??a', 'Tipo', 'Direcci??n', 'Provincia', 'Fecha', 'Mueble', 'Terminal', 'Tipo aver??a',
                    'Texto 1', 'Estado Sat', 'Unidades', 'Descripcion parada','Tipolog??a robo');
                array_push($excluir, "elemento", "Territorio", "Fabricante", "alarmado", "supervisor", "tipo_robo", "description_2", "description_3",
                    "parte_pdf", "denuncia", "foto_url", "foto_url_2", "foto_cierre", "contacto", "phone", "email", "id_operador",
                    "intervencion", "id_tipo_robo", "last_updated", "Estado PDS","tiempo");
            }
        }


        // ARRAY CON LOS DISTINTOS ACCESOS QUE NO COMPARTEN CAMPOS CON EL INFORME DE ACCESO GLOBAL ADMIN
        $array_accesos_excluidos = array("master","territorio","tienda","ttpp");
        if(in_array($acceso,$array_accesos_excluidos)){ // En master, excluimos de la exportaci??n los campos...
            // Array de t??tulos de campo para la exportaci??n XLS/CSV
            $arr_titulos = array('Id incidencia','SFID','Tipologia','Tipo','Direcci??n','Provincia','Fecha','Elemento','Territorio',
                'Fabricante','Mueble','Terminal','Supervisor','Tipo aver??a',
                'Texto 1','Denuncia','Foto 1','Foto 2','Foto 3','Contacto','Tel??fono','Email',
                'Id. Operador','Intervenci??n','Estado','Razon parada','Descripcion parada');


            array_push($excluir,'description_2');
            array_push($excluir,'description_3');
            array_push($excluir,'parte_pdf');
            array_push($excluir,'last_updated');
            array_push($excluir,'status_pds');
            array_push($excluir,'alarmado');
            array_push($excluir,'id_tipo_robo');
            array_push($excluir,'tipo_robo');

            if($conMaterial) {
                array_push($excluir, 'Material Dispositivos');
                array_push($excluir, 'Material Alarmas');
            }
        }

        $sql = 'SELECT incidencias.id_incidencia,
                        pds.reference as `SFID`,
                        concat(pds_tipo.titulo,"-",pds_subtipo.titulo,"-",pds_segmento.titulo,"-",pds_tipologia.titulo) as tipologia,
                        pds_tipo.abreviatura as tipo,
                        concat(address," - ",zip," ",city) as direccion,
                        province.province as provincia,
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

        $sql .= 'display.display as mueble,
                tipo_alarmado.title as alarmado,
                device.device as terminal,
                pds_supervisor.titulo as supervisor,
                ';
        $sql .= 'incidencias.tipo_averia,';
        $sql .= 'tipo_robo.title as tipo_robo,';
        $sql .='REPLACE(REPLACE(incidencias.description_1,CHAR(10),CHAR(32)),CHAR(13),CHAR(32)) as description_1,
                REPLACE(REPLACE(incidencias.description_2,CHAR(10),CHAR(32)),CHAR(13),CHAR(32))  as description_2,
                REPLACE(REPLACE(incidencias.description_3,CHAR(10),CHAR(32)),CHAR(13),CHAR(32))  as description_3,
                incidencias.parte_pdf,
                incidencias.denuncia,
                incidencias.foto_url,
                incidencias.foto_url_2,
                incidencias.foto_cierre,
                incidencias.contacto,
                incidencias.phone,
                incidencias.email,
                incidencias.id_operador,
                intervenciones_incidencias.id_intervencion as intervencion,
                type_incidencia.id_type_incidencia,
                tipo_robo.id as id_tipo_robo,';

        if($acceso=="admin"){
            $sql .= 'incidencias.status  AS `Estado SAT`,';
            $sql .= 'incidencias.last_updated, ';
            $sql .= 'incidencias.status_pds as `Estado PDS`,
                     type_incidencia.title as `razon_Parada`,
                     REPLACE(REPLACE(incidencias.descripcion_parada,CHAR(10),CHAR(32)),CHAR(13),CHAR(32)) as descripcion_parada';
        }else{
            $sql .= 'incidencias.status_pds as `Estado PDS`,
                    type_incidencia.title as `razon_Parada`';
        }
        if(!is_null($porrazon)) {
            $sql .= ', CONCAT(TIMESTAMPDIFF(DAY, historico.fecha, now()), \' dias \',
                    MOD(TIMESTAMPDIFF(HOUR, historico.fecha, now()), 24), \' horas \',
                    MOD(TIMESTAMPDIFF(MINUTE, historico.fecha, now()), 60), \' minutos \') as tiempo_parada';

            $sql .= ",TIMESTAMPDIFF(MONTH, historico.fecha, now()) as meses ,  
                    TIMESTAMPDIFF(DAY, historico.fecha, now()) as dias, 
                    MOD(TIMESTAMPDIFF(HOUR, historico.fecha, now()), 24) as  horas , 
                    MOD(TIMESTAMPDIFF(MINUTE, historico.fecha, now()), 60) as minutos,
                    (CASE  
	                   WHEN TIMESTAMPDIFF(DAY, historico.fecha, now())<3 THEN \"<72H\" 
                       WHEN TIMESTAMPDIFF(DAY, historico.fecha, now())>=3 and TIMESTAMPDIFF(DAY, historico.fecha, now())<=7 THEN \">72H <1 semana \" 
                       WHEN TIMESTAMPDIFF(DAY, historico.fecha, now())>7 AND TIMESTAMPDIFF(DAY, historico.fecha, now())<=31 THEN \">1 semana\"
                       WHEN TIMESTAMPDIFF(DAY, historico.fecha, now())>31 THEN \">1 mes\" 
                    END) as tiempo,";
        }
        $sql = rtrim($sql,",");

        $sql .='
                FROM incidencias
                LEFT OUTER JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
                LEFT JOIN tipo_alarmado ON tipo_alarmado.id= displays_pds.id_tipo_alarmado
                LEFT OUTER JOIN display ON displays_pds.id_display = display.id_display
                LEFT OUTER JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
                LEFT OUTER JOIN device ON devices_pds.id_device = device.id_device
                LEFT OUTER JOIN type_device ON device.type_device = type_device.id_type_device
                LEFT OUTER JOIN pds ON incidencias.id_pds = pds.id_pds
                LEFT OUTER JOIN territory ON territory.id_territory=pds.territory
                LEFT OUTER JOIN brand_device ON device.brand_device = brand_device.id_brand_device
                LEFT JOIN pds_supervisor ON pds.id_supervisor= pds_supervisor.id
                LEFT JOIN type_incidencia ON incidencias.id_type_incidencia = type_incidencia.id_type_incidencia
                LEFT JOIN province ON pds.province= province.id_province
                LEFT JOIN pds_segmento ON pds.id_segmento=pds_segmento.id
                LEFT JOIN pds_tipo ON pds.id_tipo=pds_tipo.id
                LEFT JOIN pds_subtipo ON pds.id_subtipo = pds_subtipo.id
                LEFT JOIN pds_tipologia ON pds.id_tipologia= pds_tipologia.id
                LEFT JOIN intervenciones_incidencias ON intervenciones_incidencias.id_incidencia = incidencias.id_incidencia
                LEFT JOIN tipo_robo ON tipo_robo.id=incidencias.id_tipo_robo';

        if(!is_null($porrazon))
            $sql .= ' INNER JOIN historico ON incidencias.id_incidencia = historico.id_incidencia';
        $sql.=' WHERE 1 = 1';

        if(!is_null($porrazon))
            $sql.=  ' AND historico.status="Revisada" ';

        if($tipo==="abiertas")  $sTitleFilename = "Incidencias_abiertas";
        else
            $sTitleFilename = "Incidencias_cerradas";

        if($porrazon=='robo')
            $sTitleFilename .= "_Robos";

        $sql  .= ' && '.$this->get_condition_tipo_incidencia($tipo,$porrazon);

        // Montamos las cl??usulas where filtro, seg??n el array pasado como param.
        $sFiltrosFilename = "-";


        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["status"]) && !empty($filtros["status"]))                 $sql .= (' AND incidencias.status ="' .$filtros['status']. '"');
        if(isset($filtros["status_pds"]) && !empty($filtros["status_pds"]))         $sql .= (' AND incidencias.status_pds ="'.$filtros['status_pds'].'"');
        if(isset($filtros["id_incidencia"]) && !empty($filtros["id_incidencia"]))   $sql .= (' AND incidencias.id_incidencia ='.$filtros['id_incidencia']);
        if(isset($filtros["territory"]) && !empty($filtros["territory"]))           $sql .= (' AND pds.territory = '.$filtros['territory']);
        if(isset($filtros["brand_device"]) && !empty($filtros["brand_device"]))
        {

            $sql .= (' AND device.brand_device='.$filtros['brand_device']);
        }
        if(isset($filtros["id_display"]) && !empty($filtros["id_display"])) {
            $sql .= (' AND display.id_display ="'.$filtros['id_display'].'" ');
        }
        if(isset($filtros["id_device"]) && !empty($filtros["id_device"])) {
            $sql .= (' AND device.id_device ="'.$filtros['id_device'].'" ');
        }
        if(isset($filtros["id_supervisor"]) && !empty($filtros["id_supervisor"])) {
            $sql .= (' AND pds.id_supervisor ="'.$filtros['id_supervisor'].'" ');
        }
        if(isset($filtros["id_tipo_incidencia"]) && !empty($filtros["id_tipo_incidencia"])) {
            $sql .= (' AND type_incidencia.id_type_incidencia ='.$filtros['id_tipo_incidencia']);
        }
        if(isset($filtros["id_provincia"]) && !empty($filtros["id_provincia"])) {
            $sql .= (' AND  province.id_province ="'.$filtros['id_provincia'].'" ');
        }

        if(isset($filtros["reference"]) && !empty($filtros["reference"])) $sql .=(' AND reference= '.$filtros['reference']);
        if(isset($filtros["id_intervencion"]) && !empty($filtros["id_intervencion"])) $sql .=(' AND intervenciones_incidencias.id_intervencion = '.$filtros['id_intervencion']);

        if(isset($filtros["id_tipo"]) && !empty($filtros["id_tipo"])) {
            $sql .= (' AND  pds.id_tipo ="'.$filtros['id_tipo'].'" ');
        }
        if(isset($filtros["id_subtipo"]) && !empty($filtros["id_subtipo"])) {
            $sql .= (' AND  pds.id_subtipo ="'.$filtros['id_subtipo'].'" ');
        }
        if(isset($filtros["id_segmento"]) && !empty($filtros["id_segmento"])) {
            $sql .= (' AND  pds.id_segmento ="'.$filtros['id_segmento'].'" ');
        }
        if(isset($filtros["id_tipologia"]) && !empty($filtros["id_tipologia"])) {
            $sql .= (' AND  pds.id_tipologia ="'.$filtros['id_tipologia'].'" ');
        }


        $campo_orden = $orden = NULL;
        if(count($array_orden) > 0) {
            foreach ($array_orden as $key=>$value){
                $campo_orden = $key;
                $orden = $value;
            }
        }
        if (!is_null($porrazon)) {
            if($porrazon=="robo"){
                $sql .= " ORDER BY tipo_robo.title ASC, fecha DESC ";
            }else {
                $sql .= " ORDER BY type_incidencia.title ASC, fecha DESC ";
            }
        } else {

            $sql .= " ORDER BY fecha DESC";
        }
        //echo $sql; exit;
        $query = $this->db->query($sql);

        $resultado=$query->result();

        if (is_null($porrazon)) {
            $datos = preparar_array_exportar($resultado, $arr_titulos, $excluir);
            exportar_fichero($formato,$datos,$sTitleFilename.$sFiltrosFilename.date("d-m-Y")."T".date("H:i:s")."_".date("d-m-Y"));
        }
        else {
            /*En el caso de querer exportar las incidencias de tipo robo agrupadas por tipo de robo*/
            if($porrazon=="robo"){
                $linea = 0;
                $anterior = 0;
                foreach ($resultado as $key => $campos) {
                    $incidencia = $this->tienda_model->get_incidencia($campos->id_incidencia);
                    $materialD = $this->tienda_model->get_material_dispositivos($incidencia, true);
                    $materialA = $this->tienda_model->get_material_alarmas($campos->id_incidencia);
                    if (is_null($campos->tipo_robo)) {
                        $titulo = "Robos sin tipo de robo";
                    } else {
                        $titulo = $campos->tipo_robo;
                    }

                    if ($key == 0) {
                        $anterior = $campos->id_tipo_robo;
                    }

                    if (($campos->id_tipo_robo == $anterior)) {
                        $contador = 0;
                        foreach ($campos as $campo => $valor) {
                            if ($key == 0) {
                                if (!in_array($campo, $excluir)) {
                                    if ($contador == 0) {
                                        $aux[$linea][$campo] = $titulo;
                                    } else $aux[$linea][$campo] = "";
                                    $aux[$linea + 1] = $arr_titulos;
                                    $aux[$linea + 2][$campo] = $valor;
                                    $lineas = 3;
                                }

                            } else {

                                if (!in_array($campo, $excluir))
                                    $aux[$linea][$campo] = $valor;
                                $lineas = 1;
                            }
                            $contador++;

                        }
                        if($key!=0)
                            $aux[$linea]["Tipologia robo"]=$titulo;
                        else $aux[$linea + 2]["Tipologia robo"] = $titulo;

                    } else {
                        $anterior = $campos->id_tipo_robo;
                        $contador = 0;
                        foreach ($campos as $campo => $valor) {

                            $aux[$linea][$campo] = "";
                            if (!in_array($campo, $excluir)) {
                                if ($contador == 0) {
                                    $aux[$linea + 1][$campo] = $titulo;
                                } else $aux[$linea][$campo] = "";
                                $aux[$linea + 2] = $arr_titulos;
                                $aux[$linea + 3][$campo] = $valor;
                                $lineas = 4;
                            }

                            $contador++;
                        }
                        if($key!=0)
                            $aux[$linea+3]["Tipologia robo"]=$titulo;
                        else $aux[$linea]["Tipologia robo"] = $titulo;
                    }
                    if ($material) {
                        $aux[$linea + ($lineas - 1)]['Material Dispositivos'] = '';
                        foreach ($materialD as $k => $m) {
                            $aux[$linea + ($lineas - 1)]['Material Dispositivos'] .= $m->device . "-" . $m->cantidad . ",";
                        }
                        $aux[$linea + ($lineas - 1)]['Material Alarmas'] = '';
                        foreach ($materialA as $k => $m) {

                            $aux[$linea + ($lineas - 1)]['Material Alarmas'] .= $m->code . " " . $m->alarm . "-" . $m->cantidad . ",";
                        }
                    }
                    $linea = $linea + $lineas;

                }
                /*En el caso de querer exportar las incidencias por razon de parada*/
            }else {
                $linea = 0;
                $anterior = 0;
                foreach ($resultado as $key => $campos) {
                    $incidencia = $this->tienda_model->get_incidencia($campos->id_incidencia);
                    if (is_null($campos->razon_Parada)) {
                        $materialD = $this->tienda_model->get_material_dispositivos($incidencia, true);
                        $materialA = $this->tienda_model->get_material_alarmas($campos->id_incidencia);
                        $titulo = "Incidencias sin razon de parada";
                    } else {
                        $titulo = $campos->razon_Parada;
                        $materialA = $this->tienda_model->get_material_alarmas($campos->id_incidencia);
                        if (strtolower($titulo) == strtolower(RAZON_PARADA)) {
                            $materialD = $this->tienda_model->get_material_dispositivos($incidencia, false);
                        } else {
                            $materialD = $this->tienda_model->get_material_dispositivos($incidencia, true);
                        }
                    }
                    $textoSemanas="";
                    if($campos->dias<=7)
                        $textoSemanas = "Menos de una semana";
                    else
                        if($campos->dias>7 && $campos->dias <=31)
                            $textoSemanas ="De una semana a un mes";
                        else
                            if($campos->dias>31)
                                $textoSemanas="Mas de un mes";

                    if ($key == 0) {
                        $anterior = $campos->id_type_incidencia;
                    }

                    if (($campos->id_type_incidencia == $anterior)) {
                        $contador = 0;
                        foreach ($campos as $campo => $valor) {
                            if ($key == 0) {
                                if (!in_array($campo, $excluir)) {
                                    if ($contador == 0) {
                                        $aux[$linea][$campo] = $titulo;
                                    } else $aux[$linea][$campo] = "";
                                    $aux[$linea + 1] = $arr_titulos;
                                    $aux[$linea + 2][$campo] = $valor;
                                    $lineas = 3;
                                }

                            } else {

                                if (!in_array($campo, $excluir))
                                    $aux[$linea][$campo] = $valor;
                                $lineas = 1;
                            }
                            $contador++;
                        }
                    } else {
                        $anterior = $campos->id_type_incidencia;
                        $contador = 0;
                        foreach ($campos as $campo => $valor) {

                            $aux[$linea][$campo] = "";
                            if (!in_array($campo, $excluir)) {
                                if ($contador == 0) {
                                    $aux[$linea + 1][$campo] = $titulo;
                                } else $aux[$linea][$campo] = "";
                                $aux[$linea + 2] = $arr_titulos;
                                $aux[$linea + 3][$campo] = $valor;
                                $lineas = 4;
                            }

                            $contador++;
                        }
                    }

                    if ($material) {
                        $aux[$linea + ($lineas - 1)]['Semanas']=$textoSemanas;
                        $aux[$linea + ($lineas - 1)]['Material Dispositivos'] = '';
                        foreach ($materialD as $k => $m) {
                            $aux[$linea + ($lineas - 1)]['Material Dispositivos'] .= $m->device . "-" . $m->cantidad . ",";
                        }
                        $aux[$linea + ($lineas - 1)]['Material Alarmas'] = '';
                        foreach ($materialA as $k => $m) {

                            $aux[$linea + ($lineas - 1)]['Material Alarmas'] .= $m->code . " " . $m->alarm . "-" . $m->cantidad . ",";
                        }
                    }
                    $linea = $linea + $lineas;

                }
            }

            //$datos = preparar_array_exportar($aux, $arr_titulos, $excluir);
            if(!empty($aux)) {
                exportar_fichero($formato, $aux, $sTitleFilename . $sFiltrosFilename . "_" . date("d-m-Y") . "T" . date("H:i:s"));
               // $time_elapsed = microtime(true) - $start;
                //echo $time_elapsed; exit;
            }

        }

    }

    /**
     * Funci??n que devuelve la condici??n a usar en un where para determinar si la incidencia/s son abiertas o cerradas
     * seg??n los estados status y status_pds
     * @param string $tipo
     * @return mixed
     */
    public function get_condition_tipo_incidencia($tipo = "abiertas",$averia='Incidencia'){

        if($tipo === "abiertas" )
        {
            $cond = '((incidencias.status != "Resuelta" && incidencias.status != "Pendiente recogida" && incidencias.status != "Cerrada" && incidencias.status != "Cancelada")
                        &&  (incidencias.status_pds != "Finalizada" && incidencias.status_pds != "Cancelada" ';
            if($averia=='robo') {
                $cond .= ' && incidencias.tipo_averia="Robo"';
            }
            $cond .='))';
        }
        else {
            $cond = '((incidencias.status = "Resuelta" || incidencias.status = "Pendiente recogida" || incidencias.status = "Cerrada" || incidencias.status = "Cancelada"
                    || incidencias.status = "Sustituido" || incidencias.status = "SustituidoRMA")
                    && (incidencias.status_pds = "Finalizada" || incidencias.status_pds = "Cancelada") ';
            if($averia=='robo')
                $cond .= ' && incidencias.tipo_averia="Robo"';
            $cond .=')';
        }

        return $cond;


    }

    public function get_estado_incidencias_quantity($filtros=NULL, $tipo="abiertas") {

        $bJoin['devices_pds'] = false;
        $bJoin['device'] = false;


        $this->db->select('COUNT(incidencias.id_incidencia) AS cantidad')
            ->join('pds','incidencias.id_pds = pds.id_pds','left outer')
            ->join('pds_supervisor','pds.id_supervisor= pds_supervisor.id','left')
            ->join('province','pds.province= province.id_province','left')
            ->join('displays_pds','incidencias.id_displays_pds= displays_pds.id_displays_pds','left outer')
            ->join('display','displays_pds.id_display=display.id_display','left outer')
            ->join('devices_pds','incidencias.id_devices_pds=devices_pds.id_devices_pds','left outer')
            ->join('device','devices_pds.id_device=device.id_device','left outer')
            ->join('territory','territory.id_territory=pds.territory','left outer')
            ->join('brand_device','device.brand_device = brand_device.id_brand_device','left outer');

        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["status"]) && !empty($filtros["status"])) $this->db->where('incidencias.status',$filtros['status']);
        if(isset($filtros["status_pds"]) && !empty($filtros["status_pds"])) $this->db->where('incidencias.status_pds',$filtros['status_pds']);
        if(isset($filtros["id_incidencia"]) && !empty($filtros["id_incidencia"])) $this->db->where('incidencias.id_incidencia',$filtros['id_incidencia']);
        if(isset($filtros["territory"]) && !empty($filtros["territory"])) $this->db->where('pds.territory',$filtros['territory']);
        if(isset($filtros["brand_device"]) && !empty($filtros["brand_device"])) {

            $this->db->where('device.brand_device',$filtros['brand_device']);
        }
        if(isset($filtros["id_display"]) && !empty($filtros["id_display"])) {
            $this->db->where('display.id_display',$filtros['id_display']);
        }

        if(isset($filtros["id_device"]) && !empty($filtros["id_device"])) {
            $this->db->where('device.id_device',$filtros['id_device']);
        }
        if(isset($filtros["brand_device"]) && !empty($filtros["brand_device"])) {
            $this->db->where('device.brand_device',$filtros['brand_device']);
        }
        if(isset($filtros["id_supervisor"]) && !empty($filtros["id_supervisor"])) {
            $this->db->where('pds.id_supervisor',$filtros['id_supervisor']);
        }
        if(isset($filtros["id_provincia"]) && !empty($filtros["id_provincia"])) {
            $this->db->where('province.id_province',$filtros['id_provincia']);
        }
        if(isset($filtros["id_tipo_incidencia"]) && !empty($filtros["id_tipo_incidencia"])) {
            $this->db->join('type_incidencia','type_incidencia.id_type_incidencia= incidencias.id_type_incidencia','left');
            $this->db->where('type_incidencia.id_type_incidencia',$filtros['id_tipo_incidencia']);
        }

        if(isset($filtros["reference"]) && !empty($filtros["reference"])) $this->db->where('reference',$filtros['reference']);
        if(isset($filtros["id_intervencion"]) && !empty($filtros["id_intervencion"]))  {
            $this->db->join('intervenciones_incidencias','intervenciones_incidencias.id_incidencia= incidencias.id_incidencia','left');
            $this->db->where('intervenciones_incidencias.id_intervencion',$filtros['id_intervencion']);
        }

        if(isset($filtros["id_tipo"]) && !empty($filtros["id_tipo"])) {
            $this->db->where('pds.id_tipo',$filtros['id_tipo']);
        }
        if(isset($filtros["id_subtipo"]) && !empty($filtros["id_subtipo"])) {
            $this->db->where('pds.id_subtipo',$filtros['id_subtipo']);
        }
        if(isset($filtros["id_segmento"]) && !empty($filtros["id_segmento"])) {
            $this->db->where('pds.id_segmento',$filtros['id_segmento']);
        }
        if(isset($filtros["id_tipologia"]) && !empty($filtros["id_tipologia"])) {
            $this->db->where('pds.id_tipologia',$filtros['id_tipologia']);
        }

        /**
         * Determinado el tipo por par??metro a??adir distinci??n de tipo: abiertas o cerradas.
         */
        $this->db->where($this->get_condition_tipo_incidencia($tipo));

        /* Obtener el resultado */
        $query =  $this->db->get('incidencias')->row();

       // echo $this->db->last_query();

        return $query->cantidad;


    }


    /*Se desaigna el material de una incidena*/
    function desasignar_material($id_inc,$tipo_dispositivo = "todo",$almacen=true,$id_pds = NULL,$id_material_incidencia = NULL)
    {

        // TERMINAL
        if($tipo_dispositivo==="device" || $tipo_dispositivo==="todo")
        {
            $incidencia = $this->tienda_model->get_incidencia($id_inc);

            $material_dispositivos = $this->tienda_model->get_material_dispositivos($incidencia,$almacen);

            if (!empty($material_dispositivos)) {

                foreach ($material_dispositivos as $material) {

                    if($material->id_material_incidencias == $id_material_incidencia || $tipo_dispositivo==="todo")
                    {
                        if($almacen) {
                            // Poner el dispositivo de nuevo "En stock"
                            $id_devices_almacen = $material->id_devices_almacen;
                            $sql = "UPDATE devices_almacen SET status = 'En stock', id_incidencia=NULL WHERE id_devices_almacen = '" . $id_devices_almacen . "'";
                            $this->db->query($sql);
                        }
                        // Desvincular el dispositivo del material de la incidencia.
                        $id_material_incidencias = $material->id_material_incidencias;
                        $sql = "DELETE FROM material_incidencias WHERE id_material_incidencias = '$id_material_incidencias' ";

                        $this->db->query($sql);

                        if($almacen)
                            // Borrar del hist??rico de dispositivo (diario almacen)
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
                        if($almacen) {
                            // Incrementar la cantidad (stock a devolver), en la tabla ALARM, campo units.
                            $sql = "UPDATE alarm SET units = units +" . $alarma->cantidad . " WHERE id_alarm='" . $alarma->id_alarm . "'";
                            $this->db->query($sql);
                        }

                        // Borrar la alarma vinculada a material_incidencias
                        $id_material_incidencias = $alarma->id_material_incidencias;
                        $sql = "DELETE FROM material_incidencias WHERE id_material_incidencias = '$id_material_incidencias' ";
                        $this->db->query($sql);

                        if($almacen)
                            // Borrar del hist??rico de alarmas (diario almacen)
                            $this->tienda_model->baja_historico_io($id_material_incidencias);
                    }
                }
            }
        }
    }

    /**
     * @param $id_incidencia
     * @param $averia
     */
    public function actualizar_averia($id_incidencia=NULL,$averia=NULL)
    {
        if(!is_null($id_incidencia))
        {
            $incidencia = $this->tienda_model->get_incidencia($id_incidencia);

            if(is_array($averia))
            {
                $update_fields = array();
                foreach($averia as $campo=>$valor)
                {
                    if($campo=="descripcion_parada" && !is_null($valor)){
                        $update_fields[]= ($campo.' = \''.$valor.'\'');
                    }else {
                        if(($campo=="id_type_incidencia") && (!is_null($averia['id_type_incidencia'])) && !is_null($incidencia[$campo])){
                            //echo "entra en el if ".$campo." - ".$valor;
                            $this->desasignar_material($id_incidencia,"todo",false);
                        }
                        $update_fields[]= ($campo.' = '.$valor);
                    }

                }

                $fields = implode(",",$update_fields);

                $sql = "UPDATE incidencias SET $fields WHERE id_incidencia = $id_incidencia";
                $this->db->query($sql);

                $sql ="SELECT dp.id_devices_pds FROM devices_pds dp INNER JOIN incidencias i ON i.id_incidencia=$id_incidencia 
                       WHERE dp.id_devices_pds = i.id_devices_pds and dp.status!='Incidencia' and i.status_pds!='Finalizada'
                       and i.status_pds!='Cancelada'";
                $row = $this->db->query($sql)->row();

                if(!empty($row)) {
                    $sql = "UPDATE devices_pds d SET d.status='Incidencia' WHERE d.id_devices_pds =" . $row->id_devices_pds;
                    $this->db->query($sql);
                }
                return true;
            }
        }
        return false;
    }


    /**
     * Devuelve los distintos estados de incidencia, pero que se est??n usando en alguna incidencia
     * @return mixed
     */
    public function get_estados_incidencia()
    {
        $query = $this->db->query("SELECT DISTINCT(status_pds) FROM incidencias ");
        return $query->result();
    }

    /**
     * Devuelve los estados por los que ha pasado una incidencia
     * @return mixed
     */
    public function get_estados($id_incidencia)
    {
        $query = $this->db->query("SELECT DISTINCT (status) FROM historico where id_incidencia=$id_incidencia 
                                    ORDER BY fecha");
        return $query->result_array();
    }

    public function get_titulos_estado()
    {
        $titulo_incidencias_estado = $this->db->query("
                                SELECT title as estado  FROM type_status_pds
								WHERE title != 'Cancelada'  ORDER BY id_status_pds ASC
								 ");

        return $titulo_incidencias_estado->result();
    }

    /* obtenemos el estado en el que esta la posicion de la incidencia*/
    public function get_statusdevice_incidencia($id_devices_pds,$id_incidencia) {


            $query = $this->db->select('devices_pds.status')
                ->join('devices_pds','devices_pds.id_devices_pds = incidencias.id_devices_pds')
                ->where('id_incidencia',$id_incidencia)
                ->where('incidencias.id_devices_pds',$id_devices_pds)
                ->get('incidencias');
            return $query->row_array();
    }

    public function set_parteTecnico($id,$parte)
    {
        $this->db->set('parte_pdf',$parte)
            ->where('id_incidencia',$id)
            ->update('incidencias');
    }

    public function delete_parteTecnico($id)
    {
        $query=$this->db->select('parte_pdf')
            ->where('id_incidencia',$id)
            ->get('incidencias');

        $parte= $query->row_array();

        $this->db->set('parte_pdf',"")
            ->where('id_incidencia',$id)
            ->update('incidencias');

        return $parte;
    }

    /*Nos devuelve el listado de los tipo de robso est??n en Alta*/
    public function get_tiposRobo(){
        $query = $this->db->select('tipo_robo.*')
            ->where('status','Alta')
            ->order_by('title','asc')
            ->get('tipo_robo');
        return $query->result();
    }


    /*Si puede cancelar la incidencia la devuelve y sino devuelve null*/
    public function cancelar_incidencia($incidencia){

        $sql = "SELECT * FROM incidencias WHERE id_incidencia = ".$incidencia->id_incidencia ." and (status_pds = 'En proceso' or status_pds='Alta realizada')";
        $query = $this->db->query($sql);
        $aux = $query->row();
        if(!empty($aux)) {

            /*Desasignar el material de la incidencia para que pase a almacen de nuevo*/
            $almacen=true;
            $type_incidencia=$this->tienda_model->get_type_incidencia($incidencia->id_incidencia);
            if(strtolower($type_incidencia['title'])==strtolower(RAZON_PARADA))
                $almacen=false;
            // Borramos el material asignado
            if($almacen)
                $this->desasignar_material($incidencia->id_incidencia,"todo",$almacen);

            /*Ponemos la posici??n en Alta*/
            if(!empty($incidencia->id_devices_pds)) {
                $sql = "UPDATE devices_pds SET status='Alta' WHERE id_devices_pds = " . $incidencia->id_devices_pds;
                $query = $this->db->query($sql);
            }

            /*Actualizamos el estado de la incidencia*/
            $sql = "UPDATE incidencias set status_pds='Cancelada' , status='Cancelada' where id_incidencia=" . $incidencia->id_incidencia;
            $query = $this->db->query($sql);
            /**
             * Guardar incidcencia en el hist??rico
             */
            $data = array(
                'fecha' => date('Y-m-d H:i:s'),
                'id_incidencia' => $incidencia->id_incidencia,
                'id_pds' => $incidencia->id_pds,
                'description' => NULL,
                'agent' => $this->session->userdata('sfid'),
                'status_pds' => 'Cancelada',
                'status' => 'Cancelada'
            );

            $this->tienda_model->historico($data);
        }else
            if($incidencia->status_pds=='En visita'){
                return -1;
            }else {
                if($incidencia->status_pds=='Finalizada'){
                    return -2;
                }else
                    return 0;
            }

        return $aux;
    }

    /*Exportar a fichero csv el informe de los robos entre dos a????os*/
    public function exportar_robos($ext,$anioI,$anioF){

        $this->load->dbutil();
        $this->load->helper(array('file', 'csv','download'));
        $this->load->model(array("informe_model"));
        $no_cancelada = " AND (i.status_pds != 'Cancelada' && i.status != 'Cancelada') "; // Condici??n where de contrl de incidencias NO CANCELADAS

        $acceso = $this->uri->segment(1);
        // Array de t??tulos de campo para la exportaci??n XLS/CSV
        $arr_titulos = array('Mueble','Robos','Tiendas Mueble','Total demos');
        $excluir = array();
        $sTitleFilename = "Incidencias_robos_".$anioI."_".$anioF."_";

        $resultado = $this->informe_model->get_robos_totales($anioI,$anioF,$no_cancelada);
        $datos = preparar_array_exportar($resultado, $arr_titulos, $excluir);
        exportar_fichero($ext,$datos,$sTitleFilename.date("d-m-Y")."T".date("H:i:s"));
        //print_r($resultado); exit;

    }

    /*Insertar la foto de cierre de la incidencia*/
    public function set_fotoCierre($id,$file)
    {
        $this->db->set('foto_cierre',$file)
            ->where('id_incidencia',$id)
            ->update('incidencias');
    }

    /*Borrado de la foto de cierre de una incidencia*/
    public function delete_fotoCierre($id)
    {
        $query=$this->db->select('foto_cierre')
            ->where('id_incidencia',$id)
            ->get('incidencias');

        $file= $query->row_array();

        $this->db->set('foto_cierre',"")
            ->where('id_incidencia',$id)
            ->update('incidencias');

        return $file;
    }


    /*Generar un fichero ZIP con todas las fotos de cierre de las incidencias*/
    function exportar_fotosCierre($fecha_inicio,$fecha_fin,$fabricante=NULL)
    {
        // REALIZAMOS LA CONSULTA A LA BD
        $query = $this->db->select('incidencias.foto_cierre,client.client')
            ->join('incidencias','facturacion.id_incidencia = incidencias.id_incidencia','left')
            ->join('displays_pds','facturacion.id_displays_pds = displays_pds.id_displays_pds','left')
            ->join('display','displays_pds.id_display = display.id_display','left')
            ->join('client','display.client_display= client.id_client','left')
            ->where('facturacion.fecha >=',urldecode($fecha_inicio))
            ->where('facturacion.fecha <=',urldecode($fecha_fin))
            ->where('client.facturable','1') //Que sea facturable
            ->where('client.type_profile_client','2') //Tipo de cliente fabricante
            ->where('foto_cierre !=','');


        if(!is_null($fabricante) && !empty($fabricante)){
            $query = $this->db->where('client.id_client',$fabricante);
        }
        $query = $this->db->get('facturacion');
        $resultado = $query->result();

        return $resultado;

    }
}

?>