<?php

class Sfid_model extends CI_Model {

	public function __construct()	{
		$this->load->database();
	}

	public function get_pds($id) 
	{
		if($id != FALSE) 
		{
			$query = $this->db->select('pds.*,territory.territory')
				->join('territory','pds.territory = territory.id_territory')
				->where('pds.id_pds',$id)
				->get('pds');
	
			return $query->row_array();
		}
		else 
		{
			return FALSE;
		}
	}	
	
	
	public function get_display_pds($id) 
	{
		if($id != FALSE) {
			$query = $this->db->select('*')
				->where('id_display',$id)
				->get('display');
	
			return $query->row_array();
		}
		else 
		{
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
	
	public function get_device_pds($id) 
	{
		if($id != FALSE) {
			$query = $this->db->select('*')
				->where('id_device',$id)
				->get('device');
	
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}	
	
	
	public function get_incidencias_pds($id) 
	{
		if($id != FALSE)
		{		
			$query = $this->db->select('incidencias.*,pds.reference as reference')
				->join('pds','incidencias.id_pds = pds.id_pds')
				->where('incidencias.id_pds',$id)
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
	
	
	public function get_incidencia($id,$pds) 
	{
		if($id != FALSE)
		{
			$query = $this->db->select('incidencias.*')
				->where('incidencias.id_incidencia',$id)
				->where('incidencias.id_pds',$pds)
				->where('incidencias.status != "Cancelada"')
				->get('incidencias');
	
			return $query->row_array();
		}
		else 
		{
			return FALSE;
		}
	}	
	
	public function get_displays_pds($id)
	{
		if($id != FALSE) 
		{
			$query = $this->db->select('displays_pds.*,display.*')
				->join('display','displays_pds.id_display = display.id_display')
				->where('displays_pds.id_pds', $id)
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
	
	
	public function get_display($id)
	{
		if($id != FALSE)
		{
			$query = $this->db->select('displays_pds.*,display.*')
			->join('display','displays_pds.id_display = display.id_display')
			->where('displays_pds.id_displays_pds', $id)
			->where('displays_pds.status = "Alta"')
			->get('displays_pds');
	
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}

	
	public function get_devices_display_pds($id)
	{
		if($id != FALSE) 
		{
			$query = $this->db->select('devices_pds.*,device.*')
				->join('device','devices_pds.id_device = device.id_device')
				->where('devices_pds.id_displays_pds', $id)
				->where('devices_pds.status = "Alta"')
				->order_by('devices_pds.position')
				->get('devices_pds');
	
			return $query->result();
		}
		else 
		{
			return FALSE;
		}
	}

	public function get_device($id)
	{
		if($id != FALSE)
		{
			$query = $this->db->select('devices_pds.*,device.*')
			->join('device','devices_pds.id_device = device.id_device')
			->where('devices_pds.id_devices_pds', $id)
			->where('devices_pds.status = "Alta"')
			->order_by('devices_pds.position')
			->get('devices_pds');
	
			return $query->row_array();
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


}

?>