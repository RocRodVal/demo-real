<?php

class Chat_model extends CI_Model {

    private $agentes_excluidos = array();
	public function __construct()
	{
		$this->load->database();
        $this->agentes_excluidos = array(9,10,11,12,13);
	}


    public function get_agentes_excluidos()
    {
        return $this->agentes_excluidos;
    }

    public function set_agentes_excluidos($arr_agentes)
    {
        if(!is_array($arr_agentes))
        {
            $this->agentes_excluidos = $arr_agentes;
        }
        else
        {
            if(empty($arr_agentes)) $this->agentes_excluidos = array();
        }
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


    public function existen_mensajes_nuevos($tipo_incidencias) {
            $this->load->model('incidencia_model');


            $query = $this->db->select('COUNT(*) AS nuevos')
                ->join('agent','agent.sfid=chat.agent')
                ->join('incidencias','chat.id_incidencia=incidencias.id_incidencia')
                ->where('chat.status','Nuevo')
                ->where($this->incidencia_model->get_condition_tipo_incidencia($tipo_incidencias));


            $tipo_agente = $this->get_agentes_excluidos();
            if(is_array($tipo_agente) && !empty($tipo_agente))
            {
                $query->where_not_in('agent.type',$tipo_agente);
            }
            $query = $this->db->get('chat');
            $resultado =$query->row_array();


        /*echo $this->db->last_query();
        print_r($tipo_agente);*/

            if(!is_array($tipo_agente) || empty($tipo_agente)) $res = 0;
            else $res =  $resultado['nuevos'];

            return $res;

    }


    public function contar_nuevos($id_incidencia,$agent) {
		if($id_incidencia != FALSE)
		{
			$query = $this->db->select('COUNT(*) AS nuevos')
                ->join('agent','agent.sfid=chat.agent')
			->where('chat.id_incidencia',$id_incidencia)
			->where('chat.agent',$agent)
			->where('chat.status','Nuevo');

            $tipo_agente = $this->get_agentes_excluidos();

            $query = $this->db->where_not_in('agent.type',$tipo_agente)
			        ->get('chat');


            /*echo $this->db->last_query();*/
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