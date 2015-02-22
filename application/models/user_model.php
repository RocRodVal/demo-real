<?php

class User_model extends CI_Model {

	public function __construct()	{
		$this->load->database();
	}

	public function login($data)
	{
		$sfid     = $data['sfid'];
		$password = $data['password'];
	
		$query = $this->db->select('agent.*,pds.id_pds AS id_pds')
			   ->join('pds','agent.sfid = pds.reference')
		       ->where('sfid',$sfid)
		       ->where('password',$password)
		       ->limit(1)
		       ->get('agent');		
			
		if($query->num_rows()==1)
		{
			$row = $query->row();
			$data = array(
					'sfid'      => $row->sfid,
					'id_pds'    => $row->id_pds,
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
	
}

?>