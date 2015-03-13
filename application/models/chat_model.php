<?php

class Chat_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	
	public function get_chat_incidencia_pds($id_incidencia) {
		if($id_incidencia != FALSE)
		{		
			$query = $this->db->select('chat.*')
				->where('chat.id_incidencia',$id_incidencia)
				->where('status <>','Privado')
				->order_by('chat.fecha ASC')
				->get('chat');
		
			return $query->result();
		}	
		else
		{
			return FALSE;
		}	
	}

	
	public function get_chat_incidencia_sat($id_incidencia) {
		if($id_incidencia != FALSE)
		{
			$query = $this->db->select('chat.*')
			->where('chat.id_incidencia',$id_incidencia)
			->order_by('chat.fecha ASC')
			->get('chat');
	
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}	
	
	
	public function contar_nuevos($id_incidencia,$agent) {
		if($id_incidencia != FALSE)
		{
			$query = $this->db->select('COUNT(*) AS nuevos')
			->where('chat.id_incidencia',$id_incidencia)
			->where('chat.agent',$agent)
			->where('chat.status','Nuevo')
			->get('chat');
	
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}	
	
	
	public function marcar_leido($id_incidencia,$agent)
	{
		$this->db->set('status', 2, FALSE);
		$this->db->where('id_incidencia',$id_incidencia);
		$this->db->where('agent',$agent);
		$this->db->where('status !=','Privado');
		$this->db->update('chat');
	}	
	
	
	public function insert_chat_incidencia($data)
	{
		$this->db->insert('chat',$data);
		$id = $this->db->insert_id();
		return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);

	}	

}

?>