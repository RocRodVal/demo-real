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
			$query = $this->db->select('displays_pds.*,display.*')
			->join('display','displays_pds.id_display = display.id_display')
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
	
	/* Dispositivo */
	public function get_device($id_devices_pds)
	{
		if($id_devices_pds != FALSE)
		{
			$query = $this->db->select('devices_pds.*,device.*')
			->join('device','devices_pds.id_device = device.id_device')
			->where('devices_pds.id_devices_pds',$id_devices_pds)
			->where('devices_pds.status != "Baja"')
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
		$conditions = array('id_displays_pds'=>$id_displays_pds,'status'=>'Alta');
		$this->db->where($conditions);
		$this->db->from('devices_pds');
		$count = $this->db->count_all_results();
		return $count;
	}	

	
	/* Incidencias */
	public function get_incidencias($id_pds) 
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
	}	
	
	
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
			$query = $this->db->select('devices_pds.*,devices_pds.status as estado, device.*, devices_pds.id_devices_pds')
				->join('device','devices_pds.id_device = device.id_device')
				->where('devices_pds.id_displays_pds',$id_displays_pds)
				->where('devices_pds.status != "SAT"')
				->where('devices_pds.status != "Baja"')
				->order_by('devices_pds.position')
				->get('devices_pds');

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

}

?>