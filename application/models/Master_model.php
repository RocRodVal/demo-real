<?php

class Tienda_model extends CI_Model {

	public function __construct()	{
		$this->load->database();
	}

	
	public function get_stock() {
	
		$query = $this->db->select('devices_pds.id_device, device.device, COUNT(devices_pds.id_device) AS unidades_pds, (ROUND((COUNT(devices_pds.id_device)*0.05))+2) AS stock_necesario,
									(
									SELECT  COUNT(*)
									FROM    devices_almacen
									WHERE   devices_almacen.id_device = devices_pds.id_device
									) AS deposito_almacen,
									(
									SELECT  COUNT(*)
									FROM    devices_almacen
									WHERE   devices_almacen.id_device = devices_pds.id_device
									) -
									(ROUND((COUNT(devices_pds.id_device)*0.05))+2) AS balance')
			   	 ->join('device','devices_pds.id_device = device.id_device')
		         //->where('devices_pds.status','Alta')
		         ->group_by('devices_pds.id_device')
		         ->order_by('device')
		         ->get('devices_pds');
	
		return $query->result();
	}
		
	
	public function search_pds($id) {
		if($id != FALSE) {
			$query = $this->db->select('pds.*,type_pds.pds,panelado.panelado,territory.territory')
				   ->join('type_pds','pds.type_pds = type_pds.id_type_pds')
				   ->join('panelado','pds.panelado_pds = panelado.id_panelado')
				   ->join('territory','pds.territory = territory.id_territory')
				   ->like('pds.reference',$id)
			       ->get('pds');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_displays_panelado($id) {
		if($id != FALSE) {
			$query = $this->db->select('pds.id_pds,pds.panelado_pds,displays_panelado.*,display.*')
			->join('displays_panelado', 'pds.panelado_pds = displays_panelado.id_panelado')
			->join('display','displays_panelado.id_display = display.id_display')
			->where('pds.id_pds', $id)
			->order_by('position')
			->get('pds');
				
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	public function get_panelados_maestros() {
		$query = $this->db->select('id_panelado,panelado,panelado_abx')
			   ->order_by('panelado_abx')
			   ->get('panelado');
	
		return $query->result();
	}	
	
	public function get_displays_panelado_maestros($id_panelado) {
		if($id_panelado != FALSE) {
			$query = $this->db->select('displays_panelado.*,display.*')
			->join('display','displays_panelado.id_display = display.id_display')
			->where('displays_panelado.id_panelado', $id_panelado)
			->order_by('position')
			->get('displays_panelado');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	/* Funciones específicas para muebles y dispositivos tienda basados en SFID */
	
	public function get_displays_pds($id) {
		if($id != FALSE) {
			$query = $this->db->select('displays_pds.*,display.*')
			->join('display','displays_pds.id_display = display.id_display')
			->where('displays_pds.id_pds', $id)
			->order_by('position')
			->get('displays_pds');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	public function get_display_pds($id) {
			if($id != FALSE) {
			$query = $this->db->select('displays_pds.id_displays_pds, displays_pds.description, display.*')
			->join('display','displays_pds.id_display = display.id_display')
			->where('displays_pds.id_displays_pds', $id)
			->get('displays_pds');

			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	/* Fin funciones específicas para muebles y dispositivos tienda basados en SFID */	
	
	public function get_inventario_panelado($id) {
		if($id != FALSE) {		
			$query = $this->db->select('displays_panelado.*,display.*')
			->join('display','displays_panelado.id_display = display.id_display')
			->where('displays_panelado.id_panelado', $id)
			->order_by('position')
			->get('displays_panelado');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	
	public function get_panelados() {
		$query = $this->db->select('*')
		->order_by('panelado_abx')
		->get('panelado');
	
		return $query->result();
	}	

	
	public function get_displays() {
		$query = $this->db->select('*')
		->order_by('display')
		->get('display');
	
		return $query->result();
	}	
	
	
	public function get_devices_pds($id) {

		if($id != FALSE) {
			$query = $this->db->select('devices_pds.*,device.*, COUNT(devices_pds.id_device) AS unidades')
			->join('device','devices_pds.id_device = device.id_device')
			->where('devices_pds.id_pds',$id)
			->where('devices_pds.status','Alta')
			->group_by('devices_pds.id_device')
			->order_by('device')
			->get('devices_pds');
				
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_devices_total() {

			$query = $this->db->select('devices_pds.*,device.*, COUNT(devices_pds.id_device) AS unidades')
			->join('device','devices_pds.id_device = device.id_device')
			->where('devices_pds.status','Alta')
			->group_by('devices_pds.id_device')
			->order_by('device')
			->get('devices_pds');
	
			return $query->result();
	}	
	
	
	public function get_displays_total() {
	
		$query = $this->db->select('displays_pds.*,display.*, COUNT(displays_pds.id_display) AS unidades')
		->join('display','displays_pds.id_display = display.id_display')
		->where('displays_pds.status','Alta')
		->group_by('displays_pds.id_display')
		->order_by('display')
		->get('displays_pds');
	
		return $query->result();
	}	
	
	
	public function get_devices_almacen() {
	
		$query = $this->db->select('devices_almacen.*,device.*, COUNT(devices_almacen.id_device) AS unidades')
		->join('device','devices_almacen.id_device = device.id_device')
		->where('devices_almacen.status','En stock')
		->group_by('devices_almacen.id_device')
		->order_by('device')
		->get('devices_almacen');
	
		return $query->result();
	}
	
	
	public function get_alarms_almacen() {
	
		$query = $this->db->select('alarms_almacen.*,alarm.*, COUNT(alarms_almacen.id_alarm) AS unidades')
		->join('alarm','alarms_almacen.id_alarm = alarm.id_alarm')
		->where('alarms_almacen.status','En stock')
		->group_by('alarms_almacen.id_alarm')
		->order_by('alarm')
		->get('alarms_almacen');
	
		return $query->result();
	}	
	
	
	public function get_devices_display($id) {
		if($id != FALSE) {
			$query = $this->db->select('devices_display.*,device.*')
			->join('device','devices_display.id_device = device.id_device')
			->where('devices_display.id_display',$id)
			->order_by('position')
			->get('devices_display');
				
			return $query->result();
		}
		else {
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
	
	
	public function get_pds($id) {
		if($id != FALSE) {
			$query = $this->db->select('pds.*,territory.territory')
			->join('territory','pds.territory = territory.id_territory')
			->where('pds.id_pds',$id)
			->get('pds');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_display($id) {
		if($id != FALSE) {
			$query = $this->db->select('*')
			->where('id_display',$id)
			->get('display');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}


			
	public function get_device($id) {
		if($id != FALSE) {
			$query = $this->db->select('*')
			->where('id_device',$id)
			->get('device');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}
	
	
	public function get_id($reference) {
		if($reference != FALSE) {
			$query = $this->db->select('*')
			->where('reference',$reference)
			->get('pds');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_incidencias() {
			$query = $this->db->select('incidencias.*,pds.reference as reference')
			        ->join('pds','incidencias.id_pds = pds.id_pds')
				    ->order_by('fecha ASC')
			        ->get('incidencias');
	
			return $query->result();
	}	
	
	
	public function get_incidencias_pds($id) {
		$query = $this->db->select('incidencias.*,pds.reference as reference')
		->join('pds','incidencias.id_pds = pds.id_pds')
		->where('incidencias.id_pds',$id)
		->where('incidencias.status != "Cancelada"')
		->order_by('fecha ASC')
		->get('incidencias');
	
		return $query->result();
	}	
	
	
	public function get_incidencia($id) {
		if($id != FALSE) {
			$query = $this->db->select('incidencias.*')
			->where('incidencias.id_incidencia',$id)
			->get('incidencias');
	
			return $query->row_array();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function incidencia_update($id,$status_pds,$status)
	{
		$this->db->set('status_pds', $status_pds, FALSE);
		$this->db->set('status', $status, FALSE);
		$this->db->where('id_incidencia',$id);
		$this->db->update('incidencias');
	}	
	
	
	public function get_alarms_display($id) {
		if($id != FALSE) {
			$query = $this->db->select('alarms_display_pds.*,alarm.*')
			->join('alarm','alarms_display_pds.id_alarm = alarm.id_alarm')
			->where('alarms_display_pds.id_displays_pds',$id)
			->get('alarms_display_pds');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}
		
	public function get_alarms_device($id) {
		if($id != FALSE) {
			$query = $this->db->select('alarms_device_pds.*,alarm.*')
			->join('alarm', 'alarms_device_pds.id_alarm = alarm.id_alarm')
			->where('alarms_device_pds.id_devices_pds',$id)
			->get('alarms_device_pds');
	
			return $query->result();
		}
		else {
			return FALSE;
		}
	}	
	
	
	public function get_all_displays($id) {
			$query = $this->db->select('*')
				   ->where('id_pds',$id)
				   ->get('displays_pds');
			
			return $query->num_rows();
	}	

	
	public function get_all_devices($id) {
			$query = $this->db->select('*')
				   ->where('id_pds',$id)
				   ->get('devices_pds');
				
			return $query->num_rows();
	}

	
	public function insert_incidencia($data)
	{
		$this->db->insert('incidencias',$data);
		$id=$this->db->insert_id();
		return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);
	}



	
	
	public function login($data)
	{
		$sfid     = $data['sfid'];
		$password = $data['password'];
	
		$this->db->select('agent_id,sfid,password,type');
		$this->db->from('agent');
		$this->db->where('sfid',$sfid);
		$this->db->where('password',$password);
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
	
}

?>