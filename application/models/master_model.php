<?php

class Master_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	
	public function login($data)
	{
		$sfid     = $data['sfid'];
		$password = $data['password'];
	
		$this->db->select('agent_id,sfid,password,type');
		$this->db->from('agent');
		$this->db->where('sfid',$sfid);
		$this->db->where('password',$password);
		$this->db->where('type','9');
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
	
	
	public function get_all_displays($id)
	{
		$query = $this->db->select('*')
			   ->where('id_pds',$id)
		       ->get('displays_pds');
			
		return $query->num_rows();
	}
	
	
	public function get_all_devices($id)
	{
		$query = $this->db->select('*')
			   ->where('id_pds',$id)
		       ->get('devices_pds');
	
		return $query->num_rows();
	}	

}

?>