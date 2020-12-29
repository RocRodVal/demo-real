<?php

class Pedido_model extends CI_Model {


	public function __construct()	{
		$this->load->database();
        //$this->load->model('chat_model');
	}


	/*public function get_pds($id) {
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
	}*/


    /**
     * Función que devuelve la condición a usar en un where para determinar si el pedido/s son abiertos o cerrados
     * según el estado status
     * @param string $tipo
     * @return mixed
     */
    public function get_condition_pedidos($tipo = "abiertos"){

        /*  ESTADOS ABIERTOS SAT: Nueva, Revisada, Instalador asignado, Material asignado, Comunicada
           ESTADOS CERRADOS SAT: Resuelta, Pendiente recogida, Cerrada, Cancelada */

        /*  ESTADOS ABIERTOS PDS: Alta realizada, En proceso, En visita
            ESTADOS CERRADOS PDS: Finalizada, Cancelada */

        if($tipo === "abiertos" )
        {
            $cond = '((pedidos.status != "Cancelado") &&  (pedidos.status != "Finalizado"))';
        }
        else
        {
            $cond = '( (pedidos.status = "Finalizado" || pedidos.status = "Cancelado")) ';
        }

        return $cond;
    }

    /*
     * Cantidad de pedidos de una tienda segun el tipo y la tienda; si el id_pds es 0 es que buscamos el total de pedidos de cualquier tienda
     */
    public function get_pedidos_quantity($id_pds,$tipo="abiertos",$filtros=NULL) {

        $this->db->select('COUNT(pedidos.id) AS cantidad')
            ->join('pds','pedidos.id_pds = pds.id_pds','left outer');
        if ($id_pds!=0) {
            $this->db->where('pedidos.id_pds', $id_pds);
        }
        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["id_pedido"])    && !empty($filtros["id_pedido"]))      $this->db->where('pedidos.id',$filtros['id_pedido']);
        if(isset($filtros["reference"])    && !empty($filtros["reference"]))      $this->db->where('reference',$filtros['reference']);
        /**
         * Determinado el tipo por parámetro añadir distinción de tipo: abiertos o cerrados.
         */
       $this->db->where($this->get_condition_pedidos($tipo));

        /* Obtener el resultado */
        $query =  $this->db->get('pedidos')->row();
       // echo $this->db->last_query();exit;
        return $query->cantidad;
    }


    /*
    * Obtener el pedido
    */
    public function get_pedido($id_pedido,$id_pds) {

        //  $bJoin['devices_pds'] = false;
        //  $bJoin['device'] = false;


        $this->db->select("pedidos.*,pds.reference as reference, pds.commercial,province.province as provincia,
                           territory.territory as territory",FALSE)

            ->join('pds','pedidos.id_pds = pds.id_pds','left outer')
            ->join('province','pds.province= province.id_province','left')
            ->join('territory','territory.id_territory=pds.territory','left outer')
            ->where('pedidos.id',$id_pedido);
        if ($id_pds!=0) {
            $this->db->where('pedidos.id_pds', $id_pds);
        }



        /** Aplicar filtros desde el array, de manera manual **/
        /*  if(isset($filtros["status"]) && !empty($filtros["status"])) $this->db->where('pedidos.status',$filtros['status']);
          if(isset($filtros["status_pds"]) && !empty($filtros["status_pds"])) $this->db->where('incidencias.status_pds',$filtros['status_pds']);
          if(isset($filtros["id_incidencia"]) && !empty($filtros["id_incidencia"])) $this->db->where('incidencias.id_incidencia',$filtros['id_incidencia']);
          */
        /**
         * Determinado el tipo por parámetro añadir distinción de tipo: abiertas o cerradas.
         */

        //$this->db->where($this->get_condition_pedidos("abiertos"));


        /* Obtener el resultado */
        $query =  $this->db->get('pedidos')->row();

        // echo $this->db->last_query();exit;
        return $query;
    }

    /*
     * Obtener el detalle de un pedido
     */
    public function get_detalle($id_pedido,$id_pds) {

        //  $bJoin['devices_pds'] = false;
        //  $bJoin['device'] = false;


        $this->db->select('alarm.id_alarm,alarm.alarm,alarm.picture_url as imagen,alarm.code,pedidos_detalle.cantidad,alarm.units')
            ->join('pedidos','pedidos.id = pedidos_detalle.id_pedido','left outer')
            ->join('alarm','pedidos_detalle.id_alarma = alarm.id_alarm','left outer')
            ->where('pedidos_detalle.id_pedido',$id_pedido)
            ->where('pedidos.id_pds',$id_pds)
            ->order_by('alarm.alarm ASC');
        //->where('pedidos.status','Nuevo');


        /* Obtener el resultado */
        $query =  $this->db->get('pedidos_detalle')->result();

        // echo $this->db->last_query();exit;
        return $query;
    }

    /*
 *  Devuelve conjunto de registros de pedido segun el tipo pasado como parametro,
 *  filtradas si procede, y el subconjunto limitado paginado si procede
 *
 * */
    public function get_pedidos($page = 1, $cfg_pagination = NULL,$array_orden= NULL, $tipo="abiertos",$id_pds,$filtros=NULL) {

        $arr_agentes_excluidos = $this->chat_model->get_agentes_excluidos();
        $agentes_excluidos = "";

        if(count($arr_agentes_excluidos)  > 0){
            foreach($arr_agentes_excluidos as $agente) $agentes_excluidos .= ("'".$agente."',");

        }
        $agentes_excluidos  = rtrim($agentes_excluidos,",");

        $this->db->select("pedidos.*,pds.reference as reference, pds.commercial,province.province as provincia,
                           territory.territory as territory,
                            (SELECT COUNT(*)
                                FROM pedidos_chat
                                JOIN agent ON pedidos_chat.agent = agent.sfid
                                WHERE pedidos_chat.status = 'Nuevo'
                                AND pedidos.id = pedidos_chat.id_pedido
                                AND agent.type NOT IN ($agentes_excluidos)) as nuevos",FALSE)

            ->join('pds','pedidos.id_pds = pds.id_pds','left outer')
            ->join('province','pds.province= province.id_province','left')
            ->join('territory','territory.id_territory=pds.territory','left outer');
        if ($id_pds!=0) {
            $this->db->where('pedidos.id_pds', $id_pds);
        }


        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["id_pedido"])    && !empty($filtros["id_pedido"]))      $this->db->where('pedidos.id',$filtros['id_pedido']);
        if(isset($filtros["reference"])    && !empty($filtros["reference"]))      $this->db->where('reference',$filtros['reference']);

        /* Obtenemos la condición por tipo de pedido */
        $this->db->where($this->get_condition_pedidos($tipo));

        $campo_orden = $orden = NULL;

        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($orden) && !empty($orden)) {
            $s_orden = $campo_orden. " ".$orden;
            $this->db->order_by($s_orden);
        }else{
            $this->db->order_by('fecha DESC');
        }

        $query =   $this->db->get('pedidos',$cfg_pagination['per_page'], ($page-1) * $cfg_pagination['per_page']);
//echo $this->db->last_query();exit;
        return $query->result();
    }

    /*
     * actualizar el estado de un pedido
     */
    public function pedido_update($id,$status)
    {
        $ahora = date("Y-m-d H:i:s");

        $this->db->set('status', $status, FALSE);
        $this->db->set('last_update', "'$ahora'", FALSE);
        $this->db->where('id',$id);
        $this->db->update('pedidos');
    }
    /*
     * Actualizar en el pedido la fecha de cierre
     */
    public function pedido_update_cierre($id,$fecha_cierre)
    {
        $ahora = date("Y-m-d H:i:s");
        $this->db->set('fecha_cierre', $fecha_cierre);
        $this->db->set('last_update', "'$ahora'", FALSE);
        $this->db->where('id',$id);
        $this->db->update('pedidos');
    }

    /*
     * insertar en el historico de pedidos el cambio de estado
     */
    public function historico($data)
    {
        $this->db->insert('pedidos_historico',$data);
        $id=$this->db->insert_id();
    }

    /*
     * comprobación del stock de cada una de las alarmas pedidas
     */
    public function comprobar_stock($id_pedido,$id_pds) {

        $seguir=true;
        $detalle=$this->get_detalle($id_pedido,$id_pds);
        foreach($detalle as $d) {
            if(($d->units-$d->cantidad)<=0) {
                $seguir=false;
                break;
            }
        }
        return $seguir;
    }

    /*
     * Restamos las unidades de las alarmas
     */
    public function restar_stock_alarmas($id_pedido,$id_pds)
    {
        $detalle=$this->get_detalle($id_pedido,$id_pds);
        foreach($detalle as $d) {
            //print_r($d);
            //exit;
            $this->db->set('units', 'units - ' . $d->cantidad, FALSE);
            $this->db->where('id_alarm', $d->id_alarm);

            $this->db->update('alarm');
        }
    }

     /*
     * Sumar las unidades de las alarmas
     */
    public function sumar_stock_alarmas($id_pedido,$id_pds)
    {
        $detalle=$this->get_detalle($id_pedido,$id_pds);
        foreach($detalle as $d) {
            
            $this->db->set('units', 'units + ' . $d->cantidad, FALSE);
            $this->db->where('id_alarm', $d->id_alarm);

            $this->db->update('alarm');
        }
    }


    /**
     *  Devuelve conjunto de registros de incidencias abiertas, para generar CSV
     *  filtradas si procede
     *
     * */
    public function exportar_pedidos($id_pds,$array_orden = NULL,$filtros=NULL,$tipo="abiertos",$formato="csv") {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');

        $acceso = $this->uri->segment(1);

        // Array de títulos de campo para la exportación XLS/CSV
        $arr_titulos = array('Id pedido','SFID','Fecha','Tienda','Territorio','Provincia','Contacto','Teléfono','Email',
            'Estado','Última modificación','Fecha cierre');
        $excluir = array();


        // ARRAY CON LOS DISTINTOS ACCESOS QUE NO COMPARTEN CAMPOS CON ELL INFORME DE ACCESO GLOBAL ADMIN
        $array_accesos_excluidos = array("master","territorio","tienda");
        if(in_array($acceso,$array_accesos_excluidos)){ // En master, excluimos de la exportación los campos...
            // Array de títulos de campo para la exportación XLS/CSV
            $arr_titulos = array('Id pedido','SFID','Fecha','Tienda','Territorio','Provincia','Contacto','Teléfono','Email','Estado');

            array_push($excluir,'last_update');
            array_push($excluir,'status');
        }


        $sql = 'SELECT pedidos.id,
                       pds.reference as `SFID`,
                       pedidos.fecha,
                       pds.commercial as Tienda,
                       territory.territory as `Territorio`,
                       province.province as provincia,
                       pedidos.contacto,
                       pedidos.phone,
                       pedidos.email,
                       ';

        if($acceso=="admin"){
            $sql .= 'pedidos.status `Estado`,';
            $sql .= 'pedidos.last_update,
                     pedidos.fecha_cierre,';
        }else{
            $sql .= 'pedidos.status as `Estado`';
        }
        $sql = rtrim($sql,",");


        $sql .='
                FROM pedidos

                LEFT OUTER JOIN pds ON pedidos.id_pds = pds.id_pds
                LEFT OUTER JOIN territory ON territory.id_territory=pds.territory
                LEFT JOIN province ON pds.province= province.id_province

                WHERE 1 = 1 ';
        if(!is_null($id_pds)) {
            $sql .=  ' AND pedidos.id_pds=' . $id_pds;
        }

        /** Aplicar filtros desde el array, de manera manual **/
        if(isset($filtros["id_pedido"]) && !empty($filtros["id_pedido"]))      $sql.= ' AND pedidos.id ='.$filtros['id_pedido'];
        if(isset($filtros["reference"]) && !empty($filtros["reference"]))      $sql.= ' AND reference = "'.$filtros['reference'].'"';

        $f="Finalizado";
        $c="Cancelado";
        if($tipo==="abiertos") {
            $sTitleFilename = "Pedidos_abiertos";
            $sql .= ' AND (pedidos.status !="'.$f.'" AND pedidos.status !="'.$c.'") ';
        }
        else {
            $sql .= ' AND (pedidos.status="'.$f.'" OR pedidos.status="'.$c. '") ';
            $sTitleFilename = "Pedidos_finalizados";
        }

        // Montamos las cláusulas where filtro, según el array pasado como param.
        $sFiltrosFilename = "-";


        $campo_orden = $orden = NULL;
        if(count($array_orden) > 0) {
            foreach ($array_orden as $key=>$value){
                $campo_orden = $key;
                $orden = $value;
            }
        }

        if(!is_null($id_pds)) {
            $sql .= 'ORDER BY pedidos.id DESC';
        }
        else {
            $sql .= 'ORDER BY pds.commercial ASC ,pedidos.id DESC';
        }


        $query = $this->db->query($sql);
       // echo $this->db->last_query();exit;
        $resultado=$query->result();

        $datos = preparar_array_exportar($resultado, $arr_titulos, $excluir);
        exportar_fichero($formato,$datos,$sTitleFilename.$sFiltrosFilename.date("d-m-Y")."T".date("H:i:s")."_".date("d-m-Y"));

    }

}

?>