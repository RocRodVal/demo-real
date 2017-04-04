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
    public function login_tipo($data, $tipo_usuario = 1)
    {
        $sfid     = $data['sfid'];
        $password = $data['password'];

        $query = $this->db->select('*');

        // Comprobación de PDS sólo para tienda...
        if($tipo_usuario === 1) $query->join('pds', 'agent.sfid = pds.reference');

        $query->where('sfid',$sfid);
        $query->where('password',$password);

        $arr_types = is_array($tipo_usuario) ? $tipo_usuario : array($tipo_usuario);
        $query = $this->db->where_in('type',$arr_types);

        $query = $this->db->limit(1)->get('agent');

        //echo $this->db->last_query();

        if($query->num_rows()==1)
        {

            $row = $query->row();
            $data = array(
                'sfid'      => $row->sfid,
                'type'      => $row->type,
                'logged_in' => TRUE,
                'id_pds'    => isset($row->id_pds) ? $row->id_pds : NULL
            );


            switch($tipo_usuario) {
                case 1:
                    // Check hace pedidos
                    $data = $this->hook_hacepedidos($sfid, $data);
                    break;
                default: break;
            }

            $this->session->set_userdata($data);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }



    /**
     * Agregado para comprobar si la tienda es MAIN SMARTSTORE y puede hacer pedidos
     */
    function hook_hacepedidos($sfid, $data){
        $data['hacePedidos']=FALSE;

        $query2 = $this->db->select('*')
            ->where('reference',$sfid)
            ->where('id_segmento',20)
            ->get('pds');
        if($query2->num_rows()==1) {
            $data['hacePedidos']=TRUE;
        }
        return $data;
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
        $sdomain = ini_get('session.cookie_domain');

        $response = array('entorno' => FALSE, 'type' => 0, 'sdomain' =>  $sdomain);

        // Acceso admin
        if($this->login_admin($data)){
            $response['entorno'] = 'admin';
            $response['type'] = 10;
        }
        // Acceso master
        else if($this->login_master($data)){
            $response['entorno'] = 'master';
            $response['type'] = 9;

        }
        // Acceso Oferta táctica
        else if($this->login_ot($data)){
            $response['entorno'] = 'ot';
            $response['type'] = 11;

        }
        // Acceso Territorio
        else if($this->login_tipo($data,  12)){
            $response['entorno'] = 'territorio';
            $response['type'] = 12;

        }
        // Acceso TT PP
        else if($this->login_tipo($data, 13)){
            $response['entorno'] = 'ttpp';
            $response['type'] = 13;
        }
        // Acceso tienda
        else if($this->login($data)){
            $response['entorno'] = 'tienda';
            $response['type'] = 1;
        }

        $this->session->set_userdata($response);
        return $response;

    }

}

?>