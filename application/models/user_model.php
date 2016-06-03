<?php

class User_model extends CI_Model {

	public function __construct()
	{
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

		/*Agregado para comprobar si la tienda es MAIN SMARTSTORE*/
		$query2 = $this->db->select('*')
			->where('reference',$sfid)
			->where('id_segmento',20)
			->get('pds');

		//echo $query->last_query();

		if($query->num_rows()==1)
		{
			$row = $query->row();
			$data = array(
					'sfid'      => $row->sfid,
					'id_pds'    => $row->id_pds,
					'type'      => $row->type,
					'logged_in' => TRUE
			);
			if($query2->num_rows()==1) {
				$data['hacePedidos']=TRUE;
			}
			else {
				$data['hacePedidos']=FALSE;
			}
			$this->session->set_userdata($data);
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	
	public function login_admin($data)
	{
		$sfid     = $data['sfid'];
		$password = $data['password'];
	
		$query = $this->db->select('*')
			->where('sfid',$sfid)
			->where('password',$password)
			->where('type',10)
			->limit(1)
			->get('agent');



		if($query->num_rows()==1)
		{

			$row = $query->row();
			$data = array(
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
	
	public function login_master($data)
	{
		$sfid     = $data['sfid'];
		$password = $data['password'];
	
		$query = $this->db->select('*')
		->where('sfid',$sfid)
		->where('password',$password)
		->where('type',9)
		->limit(1)
		->get('agent');
			
		if($query->num_rows()==1)
		{
			$row = $query->row();
			$data = array(
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

    /**
     * Función genérica que intenta loguearse en base al tipo de usuario pasado como parámetro. Éste determina
     * si es un acceso admin, master, tienda, ot, territorio... etc
     *
     * @param $data
     * @param int $tipo_usuario
     * @return bool
     */
    public function login_tipo($data,$tipo_usuario = 1)
    {
        $sfid     = $data['sfid'];
        $password = $data['password'];

        $query = $this->db->select('*')
            ->where('sfid',$sfid)
            ->where('password',$password);

        if(is_numeric($tipo_usuario)) {
            $query = $this->db->where('type', $tipo_usuario);
        }
        elseif(is_array($tipo_usuario))
        {
            $query = $this->db->where_in('type',$tipo_usuario);
        }

        $query = $this->db->limit(1)->get('agent');

        if($query->num_rows()==1)
        {
            $row = $query->row();
            $data = array(
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


    public function login_ot($data)
    {
        $sfid     = $data['sfid'];
        $password = $data['password'];

        $query = $this->db->select('*')
            ->where('sfid',$sfid)
            ->where('password',$password)
            ->where('type',11)
            ->limit(1)
            ->get('agent');

        if($query->num_rows()==1)
        {
            $row = $query->row();
            $data = array(
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


    public function login_entorno($data){
        $sfid     = $data['sfid'];
        $password = $data['password'];

        $entorno = FALSE;

        // Acceso admin
        if($this->login_admin($data)){
            $entorno = "admin";

        }
        // Acceso master
        else if($this->login_master($data)){
            $entorno = "master";

        }
        // Acceso Oferta táctica
        else if($this->login_ot($data)){
            $entorno = "ot";

        }
        // Acceso Territorio
        else if($this->login_tipo($data,12)){
            $entorno = "territorio";

        }
        // Acceso TT PP
        else if($this->login_tipo($data,13)){
            $entorno = "ttpp";

        }
        // Acceso tienda
        else if($this->login($data)){
            $entorno = "tienda";
        }
        return $entorno;

    }

}

?>