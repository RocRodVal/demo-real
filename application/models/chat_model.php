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
	
	public function insert_chat_incidencia($data)
	{
		$this->db->insert('chat',$data);
		$id = $this->db->insert_id();
		return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);

	}	

}

?>