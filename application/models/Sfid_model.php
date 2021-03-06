<?php

class Sfid_model extends CI_Model {

	public function __construct()	{
		$this->load->database();
	}

	
	/* SFID */	
	public function get_pds($id_pds) 
	{
		if($id_pds != FALSE) 
		{
			$query = $this->db->select('pds.*,territory.territory')
				->join('territory','pds.territory = territory.id_territory')
				->where('pds.id_pds',$id_pds)
				->get('pds');

			return $query->row_array();
		}
		else 
		{
			return FALSE;
		}
	}	
	
	
	/* Mueble */
	public function get_display($id_displays_pds)
	{
		if($id_displays_pds != FALSE)
		{
			$query = $this->db->select('displays_pds.*,display.*,tipo_alarmado.title as alarmado')
			->join('display','displays_pds.id_display = display.id_display')
            ->join('tipo_alarmado','displays_pds.id_tipo_alarmado = tipo_alarmado.id','left')
			->where('displays_pds.id_displays_pds',$id_displays_pds)
			->where('displays_pds.status = "Alta"')
			->get('displays_pds');
	
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}

    /* Mueble */
    public function get_display_incidencia($id_displays_pds)
    {
        if($id_displays_pds != FALSE)
        {
            $query = $this->db->select('displays_pds.*,display.*,tipo_alarmado.title as alarmado,displays_pds.status as estado')
                ->join('display','displays_pds.id_display = display.id_display')
                ->join('tipo_alarmado','displays_pds.id_tipo_alarmado = tipo_alarmado.id','left')
                ->where('displays_pds.id_displays_pds',$id_displays_pds)
                //->where('displays_pds.status = "Alta"')
                ->get('displays_pds');

            return $query->row_array();
        }
        else
        {
            return FALSE;
        }
    }
	
	/* Dispositivo */
	public function get_device($id_devices_pds)
	{
		if($id_devices_pds != FALSE)
		{
			$query = $this->db->select('devices_pds.*,device.*')
			->join('device','devices_pds.id_device = device.id_device')
			->where('devices_pds.id_devices_pds',$id_devices_pds)
			//->where('devices_pds.status != "Baja"')
			->order_by('devices_pds.position')
			->get('devices_pds');
	
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
		
	
	/* Contador dispositivos mueble */
	public function count_devices_displays_pds($id_displays_pds)
	{
		//$conditions = array('id_displays_pds'=>$id_displays_pds,'status='=>array('Alta','Incidencia','SAT'));
        $conditions = array('id_displays_pds'=>$id_displays_pds);
		$this->db->where($conditions);

		$this->db->from('devices_pds');
		$count = $this->db->count_all_results();
		return $count;
	}	

	
	/* Incidencias */
	/*public function get_incidencias($id_pds)
	{
		if($id_pds != FALSE)
		{		
			$query = $this->db->select('incidencias.*,pds.reference as reference')
				->join('pds','incidencias.id_pds = pds.id_pds')
				->where('incidencias.id_pds',$id_pds)
				->where('incidencias.status != "Cancelada"')
				->order_by('fecha ASC')
				->get('incidencias');
	
			return $query->result();
		}
		else 
		{
			return FALSE;
		}
	}	*/
	
	
	public function get_incidencia($id_incidencia)
	{


		if($id_incidencia != FALSE)
		{
			$query = $this->db->select('incidencias.*')
				->where('incidencias.id_incidencia',$id_incidencia)
				->get('incidencias');

			return $query->row_array();
		}
		else 
		{
			return FALSE;
		}
	}	
	
	
	public function get_displays_pds($id_pds)
	{
		if($id_pds != FALSE) 
		{
			$query = $this->db->select('displays_pds.*,display.*')
				->join('display','displays_pds.id_display = display.id_display')
				->where('displays_pds.id_pds',$id_pds)
				->where('displays_pds.status = "Alta"')
				->order_by('displays_pds.position')
				->get('displays_pds');

            //echo $this->db->last_query();
			return $query->result();
		}
		else 
		{
			return FALSE;
		}
	}

    public function get_id_displays_pds($id_pds=FALSE,$id_display=FALSE)
    {
        if($id_pds != FALSE && $id_display != FALSE)
        {
            $query = $this->db->select('displays_pds.id_displays_pds')
                ->join('display','displays_pds.id_display = display.id_display')
                ->where('displays_pds.id_pds',$id_pds)
                ->where('displays_pds.id_display',$id_display)
                ->where('displays_pds.status = "Alta"')
                ->order_by('displays_pds.position')
                ->get('displays_pds');

            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }
	
	
	public function get_devices_displays_pds($id_displays_pds)
	{

		if($id_displays_pds != FALSE) 
		{
			//$query = $this->db->select('distinct(devices_pds.id_devices_pds),devices_pds.*,devices_pds.status as estado, device.*, devices_display.display')
			$query = $this->db->select('devices_pds.*,devices_pds.status as estado, device.*, devices_pds.id_devices_pds,mueble_display.name as muebledisplayname')
				->join('device','devices_pds.id_device = device.id_device')
				->join('mueble_display','devices_pds.id_muebledisplay = mueble_display.id_muebledisplay','left')
				->where('devices_pds.id_displays_pds',$id_displays_pds)
				->where('devices_pds.status != "SAT"')
				->where('devices_pds.status != "Baja"')
                ->where('devices_pds.status != "RMA"')
				->order_by('devices_pds.position')
				->get('devices_pds');
            //echo $this->db->last_query(); exit;
			return $query->result();
		}
		else 
		{
			return FALSE;
		}
	}

	
	public function insert_incidencia($data)
	{
		$this->db->insert('incidencias',$data);
		$id=$this->db->insert_id();
		return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);
	}


    /*
     * Devuelve los tipos de tiendas
     */

    public function get_types_pds()
    {
        $query = $this->db->select("id_type_pds, pds")
                ->where("client_type_pds",1)
                ->where("status","Alta")
                ->get("type_pds");

        return $query->result();

    }

    /*
     * Devuelve el tipo $id de tienda
     */

    public function get_type_pds($id)
    {
        $query = $this->db->select("id_type_pds, pds")
            ->where("client_type_pds",1)
            ->where("id_type_pds",$id)
            ->where("status","Alta")
            ->get("type_pds")->row();

        return $query;

    }

    /*
     * Devuelve los tipos de tiendas que tienen demoreal
     */

    public function get_types_pds_demoreal()
    {


        $panelados = $this->tienda_model->get_panelados_maestros_demoreal();
        $arr_panelados_id = array();
        foreach($panelados as $panel){
            $arr_panelados_id[] = $panel->id_panelado;
        }
        $s_panelados = implode(",",$arr_panelados_id);


        $query = $this->db->query("SELECT distinct(id_type_pds),pds
                                    FROM type_pds type
                                    LEFT JOIN pds ON  pds.type_pds = type.id_type_pds
                                    WHERE   client_type_pds = 1
                                            AND pds.panelado_pds IN ($s_panelados)
                                            AND type.status= 'Alta'
                                    ORDER BY pds ASC");

        return $query->result();

    }


    /**
     * Comprobar si existen incidencias asociadas a un SFID
     */

    public function check_incidencias_abiertas($sfid)
    {
        $respuesta = NULL;
        if(!empty($sfid))
        {
            $incidencias = $this->db->select("COUNT(id_incidencia) as abiertas")
                ->join("pds"," incidencias.id_pds = pds.id_pds ")
                ->where("pds.reference =".$sfid)
                ->where_in("incidencias.status_pds",array('Alta realizada','En proceso', 'En visita'))
                //->where_in("incidencias.status_pds",array('Finalizada', 'Cancelada'))
                ->get("incidencias");

            $respuesta = $incidencias->row();
        }

        return $respuesta;
    }



    /**
     * Insertar info en hist??rico sobre el cierre de SFID
     */

    public function alta_historico_cierre_sfid($data)
    {
        $this->db->insert('historico_cierre_sfid',$data);
        $id=$this->db->insert_id();
        return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);
    }

    /**
     * Obtener info del PDS desde el hist??rico sobre el cierre de SFID
     */

    public function get_historico_cierre_sfid($sfid = NULL,$tipo = "array")
    {
        if(!is_null($sfid))
        {

            $query = $this->db->select('id_pds')
                ->where('sfid',$sfid)
                ->group_by('id_pds')
                ->get('historico_cierre_sfid');

            return $query->row();
        }

        return NULL;
    }
}

?>