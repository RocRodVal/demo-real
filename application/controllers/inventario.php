<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('email','text','xcrud'));
		$this->load->library(array('email','form_validation','ion_auth','encrypt','form_validation','session'));
	}
	
	
	public function index()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
			
			$data['stocks']          = $this->tienda_model->get_stock();
			$data['alarms_almacen']  = $this->tienda_model->get_alarms_almacen();
			$data['devices_almacen'] = $this->tienda_model->get_devices_almacen();
			$data['displays_pds']    = $this->tienda_model->get_displays_total();
			$data['devices_pds']     = $this->tienda_model->get_devices_total();
			
			$data['title']   = 'Depósito';
		
			$this->load->view('backend/header', $data);
			$this->load->view('backend/navbar', $data);
			$this->load->view('backend/deposito', $data);
			$this->load->view('backend/footer');
		}
		else
		{
			redirect('admin','refresh');
		}		
	}

	
	public function inventarios()
	{
		$this->load->model('tienda_model');
	
		$data['displays']=$this->tienda_model->get_displays_total();
		$data['devices']=$this->tienda_model->get_devices_total();
		
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('displays_pds');
		$xcrud_1->table_name('Inventario muebles');
		$xcrud_1->relation('client_type_pds','client','id_client','client');
		$xcrud_1->relation('id_pds','pds','id_pds','reference');
		$xcrud_1->relation('id_type_pds','type_pds','id_type_pds','pds');
		$xcrud_1->relation('id_panelado','panelado','id_panelado','panelado');
		$xcrud_1->relation('id_display','display','id_display','display');
		$xcrud_1->label('client_type_pds','Cliente')->label('id_displays_pds','REF.')->label('id_type_pds','Tipo')->label('id_pds','SFID')->label('id_panelado','Panelado Orange')->label('id_display','Mueble')->label('position','Posición Orange')->label('description','Comentarios')->label('status','Estado');
		$xcrud_1->columns('client_type_pds,id_displays_pds,id_type_pds,id_pds,id_panelado,id_display,position,status');
		$xcrud_1->fields('client_type_pds,id_displays_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status');
		$xcrud_1->order_by('id_pds','asc');
		$xcrud_1->order_by('position','asc');
		$xcrud_1->show_primary_ai_column(true);
		$xcrud_1->unset_numbers();
		$xcrud_1->start_minimized(true);		
		
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('alarms_display_pds');
		$xcrud_2->table_name('Inventario alarmas mueble');
		$xcrud_2->relation('client_type_pds','client','id_client','client');
		$xcrud_2->relation('id_pds','pds','id_pds','reference');
		$xcrud_2->relation('id_displays_pds','displays_pds','id_displays_pds','id_displays_pds');
		$xcrud_2->relation('id_alarm','alarm','id_alarm','alarm');
		$xcrud_2->label('client_type_pds','Cliente')->label('id_alarms_display_pds','REF.')->label('id_pds','SFID')->label('id_displays_pds','Cod. mueble')->label('id_alarm','Alarma')->label('description','Comentarios')->label('status','Estado');
		$xcrud_2->columns('client_type_pds,id_alarms_display_pds,id_pds,id_displays_pds,id_alarm,status');
		$xcrud_2->fields('client_type_pds,id_alarms_display_pds,id_pds,id_displays_pds,id_alarm,description,status');
		$xcrud_2->order_by('id_pds','asc');
		$xcrud_2->order_by('id_displays_pds','asc');
		$xcrud_2->show_primary_ai_column(true);
		$xcrud_2->unset_numbers();
		$xcrud_2->start_minimized(true);		

		$xcrud_3 = xcrud_get_instance();
		$xcrud_3->table('devices_pds');
		$xcrud_3->table_name('Inventario dispositivos');
		$xcrud_3->relation('client_type_pds','client','id_client','client');
		$xcrud_3->relation('id_pds','pds','id_pds','reference');
		$xcrud_3->relation('id_displays_pds','displays_pds','id_displays_pds','id_displays_pds');
		$xcrud_3->relation('id_display','display','id_display','display');
		$xcrud_3->relation('id_device','device','id_device','device');
		$xcrud_3->relation('id_color_device','color_device','id_color_device','color_device');
		$xcrud_3->relation('id_complement_device','complement_device','id_complement_device','complement_device');
		$xcrud_3->relation('id_status_device','status_device','id_status_device','status_device');
		$xcrud_3->relation('id_status_packaging_device','status_packaging_device','id_status_packaging_device','status_packaging_device');
		$xcrud_3->change_type('picture_url_1', 'image');
		$xcrud_3->change_type('picture_url_2', 'image');
		$xcrud_3->change_type('picture_url_3', 'image');
		$xcrud_3->modal('picture_url_1');
		$xcrud_3->modal('picture_url_2');
		$xcrud_3->modal('picture_url_3');
		$xcrud_3->label('client_type_pds','Cliente')->label('id_devices_pds','REF.')->label('id_pds','SFID')->label('id_displays_pds','Cod. mueble')->label('id_display','Mueble')->label('position','Posición')->label('id_device','Dispositivo')->label('IMEI','IMEI')->label('mac','MAC')->label('serial','Nº de serie')->label('barcode','Código de barras')->label('id_color_device','Color')->label('id_complement_device','Complementos')->label('id_status_device','Estado dispositivo')->label('id_status_packaging_device','Estado packaging')->label('picture_url_1','Foto #1')->label('picture_url_2','Foto #2')->label('picture_url_3','Foto #3')->label('description','Comentarios')->label('status','Estado');
		$xcrud_3->columns('client_type_pds,id_devices_pds,id_pds,id_displays_pds,id_display,id_device,position,IMEI,mac,status');
		$xcrud_3->fields('client_type_pds,id_devices_pds,id_pds,id_displays_pds,id_display,id_device,position,serial,IMEI,mac,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status');
		$xcrud_3->order_by('id_pds','asc');
		$xcrud_3->order_by('id_displays_pds','asc');
		$xcrud_3->order_by('position','asc');
		$xcrud_3->show_primary_ai_column(true);
		$xcrud_3->unset_numbers();
		$xcrud_3->start_minimized(true);		

		$xcrud_4 = xcrud_get_instance();
		$xcrud_4->table('alarms_device_pds');
		$xcrud_4->table_name('Inventario alarmas dispositivo');
		$xcrud_4->relation('client_type_pds','client','id_client','client');
		$xcrud_4->relation('id_pds','pds','id_pds','reference');
		$xcrud_4->relation('id_devices_pds','devices_pds','id_devices_pds','id_devices_pds');
		$xcrud_4->relation('id_displays_pds','displays_pds','id_displays_pds','id_displays_pds');
		$xcrud_4->relation('id_alarm','alarm','id_alarm','alarm');
		$xcrud_4->label('client_type_pds','Cliente')->label('id_alarms_device_pds','REF.')->label('id_pds','SFID')->label('id_devices_pds','Cod. dispositivo')->label('id_displays_pds','Cod. mueble')->label('id_alarm','Alarma')->label('description','Comentarios')->label('status','Estado');
		$xcrud_4->columns('client_type_pds,id_alarms_device_pds,id_pds,id_devices_pds,id_displays_pds,id_alarm,status');
		$xcrud_4->fields('client_type_pds,id_alarms_device_pds,id_pds,id_devices_pds,id_displays_pds,id_alarm,description,status');
		$xcrud_4->order_by('id_pds','asc');
		$xcrud_4->order_by('id_displays_pds','asc');
		$xcrud_4->order_by('id_devices_pds','asc');
		$xcrud_4->show_primary_ai_column(true);
		$xcrud_4->unset_numbers();
		$xcrud_4->start_minimized(true);
		
		$data['title']   = 'Inventarios tiendas';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
		$data['content'] = $data['content'].$xcrud_3->render();
		$data['content'] = $data['content'].$xcrud_4->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/inventario', $data);
		$this->load->view('backend/footer');
	}
		
	public function inventarios_panelados()
	{
		$this->load->model('tienda_model');

		$id_panelado = $this->input->post('id_panelado');
		$displays    = $this->tienda_model->get_inventario_panelado($id_panelado);
		
		
		if ($id_panelado != '')
		{	
		foreach($displays as $key=>$display) {
			$num_devices = $this->tienda_model->count_devices_display($display->id_display);
			$display->devices_count = $num_devices;
		}
		}

		$data['panelados'] = $this->tienda_model->get_panelados();
		$data['displays']  = $displays;
	
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('panelado');
		$xcrud_1->table_name('Panelado');
		$xcrud_1->relation('client_panelado','client','id_client','client');
		$xcrud_1->relation('type_pds','type_pds','id_type_pds','pds');
		$xcrud_1->change_type('picture_url', 'image');
		$xcrud_1->label('client_panelado','Cliente')->label('type_pds','Tipo punto de venta')->label('panelado','Panelado Orange')->label('panelado_abx','REF.')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
		$xcrud_1->columns('client_panelado,type_pds,panelado,panelado_abx,status');
		$xcrud_1->fields('client_panelado,type_pds,panelado,panelado_abx,picture_url,description,status');
		$xcrud_1->show_primary_ai_column(true);
		$xcrud_1->unset_numbers();
		$xcrud_1->start_minimized(true);
				
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('displays_panelado');
		$xcrud_2->table_name('Muebles panelado');
		$xcrud_2->relation('client_panelado','client','id_client','client');
		$xcrud_2->relation('id_panelado','panelado','id_panelado','panelado_abx');
		$xcrud_2->relation('id_display','display','id_display','display');
		$xcrud_2->label('client_panelado','Cliente')->label('id_panelado','REF.')->label('id_display','Modelo')->label('position','Posición')->label('description','Comentarios')->label('status','Estado');
		$xcrud_2->columns('client_panelado,id_panelado,id_display,position,status');
		$xcrud_2->fields('client_panelado,id_panelado,id_display,position,description,status');
		$xcrud_2->show_primary_ai_column(true);
		$xcrud_2->unset_numbers();
		$xcrud_2->start_minimized(true);

		$data['title']   = 'Panelados tiendas';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();

	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/inventario_panelados', $data);
		$this->load->view('backend/footer');
	}	
	
	
	public function inventarios_planogramas()
	{
		$this->load->model('tienda_model');

		$id_display = $this->input->post('id_display');
		$devices    = $this->tienda_model->get_devices_display($id_display);

		$data['displays'] = $this->tienda_model->get_displays();
		$data['devices']  = $devices;

		if ($id_display != '')
		{		
			$display = $this->tienda_model->get_display($id_display);
			$data['picture_url'] = $display['picture_url'];		
		}
		
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('display');
		$xcrud_1->table_name('Modelo');
		$xcrud_1->relation('client_display','client','id_client','client');
		$xcrud_1->change_type('picture_url', 'image');
		$xcrud_1->change_type('canvas_url', 'file');
		$xcrud_1->modal('picture_url');
		$xcrud_1->label('client_display','Cliente')->label('display','Modelo')->label('picture_url','Foto')->label('canvas_url','SVG')->label('description','Comentarios')->label('positions','Posiciones')->label('status','Estado');
		$xcrud_1->columns('client_display,display,picture_url,positions,status');
		$xcrud_1->fields('client_display,display,picture_url,canvas_url,description,positions,status');
		$xcrud_1->show_primary_ai_column(true);
		$xcrud_1->unset_numbers();
		$xcrud_1->start_minimized(true);		
		
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('devices_display');
		$xcrud_2->table_name('Dispositivos mueble');
		$xcrud_2->relation('client_panelado','client','id_client','client');
		$xcrud_2->relation('id_display','display','id_display','display');
		$xcrud_2->relation('id_device','device','id_device','device');
		$xcrud_2->label('client_panelado','Cliente')->label('id_panelado','REF.')->label('id_display','Mueble')->label('id_device','Dispositivo')->label('position','Posición')->label('description','Comentarios')->label('status','Estado');
		$xcrud_2->columns('client_panelado,id_display,id_device,position,status');
		$xcrud_2->fields('client_panelado,id_display,id_device,position,description,status');		
		$xcrud_2->show_primary_ai_column(true);
		$xcrud_2->unset_numbers();
		$xcrud_2->start_minimized(true);		
	
		$data['title']   = 'Planogramas muebles';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
	
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/inventario_planogramas', $data);
		$this->load->view('backend/footer');
	}	
	
	public function almacen()
	{
		$this->load->model('tienda_model');
		
		$data['alarms']=$this->tienda_model->get_alarms_almacen();
		$data['devices']=$this->tienda_model->get_devices_almacen();		
		
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('alarms_almacen');
		$xcrud_1->table_name('Inventario alarmas almacén');
		$xcrud_1->relation('id_alarm','alarm','id_alarm','alarm');
		$xcrud_1->label('id_alarms_almacen','Ref.')->label('id_alarm','Alarma')->label('code','Código fabricante')->label('barcode','Código de barras')->label('description','Comentarios')->label('status','Estado');
		$xcrud_1->columns('id_alarms_almacen,id_alarm,code,barcode,status');
		$xcrud_1->fields('id_alarms_almacen,id_alarm,code,barcode,description,status');
		$xcrud_1->order_by('id_alarm','asc');
		$xcrud_1->order_by('status','asc');
		$xcrud_1->order_by('id_alarms_almacen','asc');
		$xcrud_1->show_primary_ai_column(true);
		$xcrud_1->unset_numbers();
		$xcrud_1->start_minimized(true);
	
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('devices_almacen');
		$xcrud_2->table_name('Inventario dispositivos almacén');
		$xcrud_2->relation('id_device','device','id_device','device');
		$xcrud_2->relation('id_color_device','color_device','id_color_device','color_device');
		$xcrud_2->relation('id_complement_device','complement_device','id_complement_device','complement_device');
		$xcrud_2->relation('id_status_device','status_device','id_status_device','status_device');
		$xcrud_2->relation('id_status_packaging_device','status_packaging_device','id_status_packaging_device','status_packaging_device');
		$xcrud_2->change_type('picture_url_1', 'image');
		$xcrud_2->change_type('picture_url_2', 'image');
		$xcrud_2->change_type('picture_url_3', 'image');
		$xcrud_2->modal('picture_url_1');
		$xcrud_2->modal('picture_url_2');
		$xcrud_2->modal('picture_url_3');
		$xcrud_2->label('id_devices_almacen','Ref.')->label('id_device','Dispositivo')->label('serial','Nº de serie')->label('IMEI','IMEI')->label('mac','MAC')->label('barcode','Código de barras')->label('id_color_device','Color')->label('id_complement_device','Complementos')->label('id_status_device','Estado dispositivo')->label('id_status_packaging_device','Estado packaging')->label('picture_url_1','Foto #1')->label('picture_url_2','Foto #2')->label('picture_url_3','Foto #3')->label('description','Comentarios')->label('status','Estado');
		$xcrud_2->columns('id_devices_almacen,id_device,IMEI,mac,barcode,status');
		$xcrud_2->fields('id_devices_almacen,id_device,serial,IMEI,mac,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status');
		$xcrud_2->order_by('id_device','asc');
		$xcrud_2->order_by('status','asc');
		$xcrud_2->order_by('id_devices_almacen','asc');
		$xcrud_2->show_primary_ai_column(true);
		$xcrud_2->unset_numbers();
		$xcrud_2->start_minimized(true);		

		$data['title']   = 'Depósito';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/almacen', $data);
		$this->load->view('backend/footer');
	}

	public function exp_alta_incidencia()
	{
		$id_pds   = $this->uri->segment(3);

		$xcrud = xcrud_get_instance();
		$this->load->model('tienda_model');
	
		$sfid = $this->tienda_model->get_pds($id_pds);
	
		$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
		$data['commercial'] = $sfid['commercial'];
		$data['territory']  = $sfid['territory'];
		$data['reference']  = $sfid['reference'];
		$data['address']    = $sfid['address'];
		$data['zip']        = $sfid['zip'];
		$data['city']       = $sfid['city'];
		$data['id_pds_url'] = $id_pds;
	
		$displays = $this->tienda_model->get_displays_panelado($id_pds);
	
		foreach($displays as $key=>$display) {
			$num_devices = $this->tienda_model->count_devices_display($display->id_display);
			$display->devices_count = $num_devices;
		}
	
		$data['displays']=$displays;
	
		$data['title'] = 'Panelado tienda';
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/exp_alta_incidencia', $data);
		$this->load->view('backend/footer');
	}	
	
	public function exp_alta_incidencia_mueble()
	{
		$id_pds   = $this->uri->segment(3);
		$id_dis   = $this->uri->segment(4);
	
		$xcrud = xcrud_get_instance();
		$this->load->model('tienda_model');
	
		$sfid = $this->tienda_model->get_pds($id_pds);
	
		$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
		$data['commercial'] = $sfid['commercial'];
		$data['territory']  = $sfid['territory'];
		$data['reference']  = $sfid['reference'];
		$data['address']    = $sfid['address'];
		$data['zip']        = $sfid['zip'];
		$data['city']       = $sfid['city'];
	
		$display = $this->tienda_model->get_display($id_dis);
	
		$data['id_display']  = $display['id_display'];
		$data['display']     = $display['display'];
		$data['picture_url'] = $display['picture_url'];
	
		$data['devices'] = $this->tienda_model->get_devices_display($id_dis);
	
		$data['id_pds_url']  = $id_pds;
		$data['id_dis_url']  = $id_dis;
	
		$data['title'] = 'Planograma mueble';
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/exp_alta_incidencia_display', $data);
		$this->load->view('backend/footer');
	}	
	
	public function exp_alta_incidencia_device()
	{
		$id_pds   = $this->uri->segment(3);
		$id_dis   = $this->uri->segment(4);
		$id_dev   = $this->uri->segment(5);
	
		$xcrud = xcrud_get_instance();
		$this->load->model('tienda_model');
	
		$sfid = $this->tienda_model->get_pds($id_pds);
	
		$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
		$data['commercial'] = $sfid['commercial'];
		$data['territory']  = $sfid['territory'];
		$data['reference']  = $sfid['reference'];
		$data['address']    = $sfid['address'];
		$data['zip']        = $sfid['zip'];
		$data['city']       = $sfid['city'];
	
		$display = $this->tienda_model->get_display($id_dis);
	
		$data['id_display']      = $display['id_display'];
		$data['display']         = $display['display'];
		$data['picture_url_dis'] = $display['picture_url'];
	
	
		$device = $this->tienda_model->get_device($id_dev);
	
		$data['id_device']       = $device['id_device'];
		$data['device']          = $device['device'];
		$data['picture_url_dev'] = $device['picture_url'];
		$data['device_'] = $device;
	
		$data['id_pds_url']  = $id_pds;
		$data['id_dis_url']  = $id_dis;
		$data['id_dev_url']  = $id_dev;
	
		$data['title'] = 'Detalle dispositivo';
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/exp_alta_incidencia_device', $data);
		$this->load->view('backend/footer');
	}

	
	public function inventario_tienda()
	{
		$id_pds   = $this->uri->segment(3);
	
		$xcrud = xcrud_get_instance();
		$this->load->model('tienda_model');
	
		$sfid = $this->tienda_model->get_pds($id_pds);
	
		$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
		$data['commercial'] = $sfid['commercial'];
		$data['territory']  = $sfid['territory'];
		$data['reference']  = $sfid['reference'];
		$data['address']    = $sfid['address'];
		$data['zip']        = $sfid['zip'];
		$data['city']       = $sfid['city'];
		$data['id_pds_url'] = $id_pds;
	
		$data['devices']=$this->tienda_model->get_devices_pds($id_pds);
	
		$data['title'] = 'Dispositivos';
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/devices_pds', $data);
		$this->load->view('backend/footer');
	}	
	
	public function planograma()
	{
		$id_pds   = $this->uri->segment(3);

		$xcrud = xcrud_get_instance();
		$this->load->model('tienda_model');
		
		$sfid = $this->tienda_model->get_pds($id_pds);
		
		$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
		$data['commercial'] = $sfid['commercial'];
		$data['territory']  = $sfid['territory'];
		$data['reference']  = $sfid['reference'];
		$data['address']    = $sfid['address'];
		$data['zip']        = $sfid['zip'];
		$data['city']       = $sfid['city'];
		$data['id_pds_url'] = $id_pds;

		$displays = $this->tienda_model->get_displays_panelado($id_pds);

		foreach($displays as $key=>$display) {
			$num_devices = $this->tienda_model->count_devices_display($display->id_display);
			$display->devices_count = $num_devices;
		}

		$data['displays']=$displays;
		
		$data['title'] = 'Planograma';
		
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/planograma', $data);
		$this->load->view('backend/footer');	
	}	
	
	
	public function planograma_mueble()
	{
		$id_pds   = $this->uri->segment(3);
		$id_dis   = $this->uri->segment(4);
	
		$xcrud = xcrud_get_instance();
		$this->load->model('tienda_model');
	
		$sfid = $this->tienda_model->get_pds($id_pds);
	
		$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
		$data['commercial'] = $sfid['commercial'];
		$data['territory']  = $sfid['territory'];
		$data['reference']  = $sfid['reference'];
		$data['address']    = $sfid['address'];
		$data['zip']        = $sfid['zip'];
		$data['city']       = $sfid['city'];
	
		$display = $this->tienda_model->get_display($id_dis);
	
		$data['id_display']  = $display['id_display'];
		$data['display']     = $display['display'];
		$data['picture_url'] = $display['picture_url'];
	
		$data['devices'] = $this->tienda_model->get_devices_display($id_dis);
	
		$data['id_pds_url']  = $id_pds;
		$data['id_dis_url']  = $id_dis;
	
		$data['title'] = 'Planograma';
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/planograma_display', $data);
		$this->load->view('backend/footer');
	}

}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */