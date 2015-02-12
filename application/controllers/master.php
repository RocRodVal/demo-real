<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('xcrud','text');
		$this->load->library(array('encrypt','form_validation','session'));
	}
	
	
	public function index()
	{
		$this->load->model('master_model');
		
		$this->form_validation->set_rules('sfid','SFID','required|xss_clean');
		$this->form_validation->set_rules('password','password','required|xss_clean');
		
		if ($this->form_validation->run() == true)
		{
			$data = array(
					'sfid' 	   => strtolower($this->input->post('sfid')),
					'password' => $this->input->post('password'),
			);
		}
		
		if ($this->form_validation->run() == true && $this->master_model->login($data))
		{
			redirect('master/dashboard');
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
		
			$xcrud = xcrud_get_instance();
		
			$data['title']   = 'Login';
			
			$this->load->view('master/header', $data);
			$this->load->view('master/login', $this->data);
			$this->load->view('master/footer');
		}		
	}
	
	
	public function dashboard()
	{
		if($this->session->userdata('logged_in'))
		{
			$session_data     = $this->session->userdata('logged_in');
			$data['sfid']     = $this->session->userdata('sfid');
			$data['agent_id'] = $this->session->userdata('agent_id');
			$data['type']     = $this->session->userdata('type');
			
			$xcrud = xcrud_get_instance();
	
			$this->load->model('master_model');
			
			$data['tiendas']     =  $this->tienda_model->search_pds($this->input->post('sfid'));
			$data['incidencias'] =  $this->tienda_model->get_incidencias();
	
			$data['title']   = 'Información general';
	
			if ($this->session->userdata('type') == 9)
			{			
			$this->load->view('backend/header', $data);
			$this->load->view('backend/navbar', $data);
			$this->load->view('backend/dashboard', $data);
			$this->load->view('backend/footer');
			}
			else
			{
				$id_pds = $this->tienda_model->get_id($data['sfid']);
				
				$data['id_pds'] = $id_pds['id_pds'];
				
				redirect('admin/dashboard_pds','refresh');
			}
		}
		else
		{
			redirect('master','refresh');
		}	
	}
	
	public function clientes()
	{
		$xcrud = xcrud_get_instance();
		$xcrud->table('client');
		$xcrud->table_name('Cliente');
		$xcrud->relation('type_profile_client','type_profile','id_type_profile','type');
		$xcrud->change_type('picture_url', 'image');
		$xcrud->label('client','Cliente')->label('type_profile_client','Tipo')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
		$xcrud->columns('client,type_profile_client');
		$xcrud->fields('client,type_profile_client,picture_url,description,status');

		$data['title']   = 'Clientes';
		$data['content'] = $xcrud->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}
	
	
	public function incidencias()
	{
		$xcrud = xcrud_get_instance();
		$xcrud->table('incidencias');
		$xcrud->table_name('Incidencias');
		$xcrud->relation('id_pds','pds','id_pds','reference');
		//$xcrud->relation('id_displays_pds','displays_pds','id_displays_pds','id_displays_pds');
		//$xcrud->relation('id_devices_pds','devices_pds','id_devices_pds','id_devices_pds');
		$xcrud->relation('id_displays_pds','display','id_display','display');
		$xcrud->relation('id_devices_pds','device','id_device','device');		
		$xcrud->relation('id_operador','contact','id_contact','contact');
		$xcrud->change_type('denuncia', 'file');
		$xcrud->change_type('parte_pdf', 'file');
		$xcrud->change_type('foto_url', 'image');
		$xcrud->label('id_incidencia','REF.')->label('fecha','Fecha')->label('id_pds','SFID')->label('denuncia','Denuncia')->label('parte_pdf','Parte PDF')->label('foto_url','Foto')->label('id_displays_pds','Cod. mueble')->label('id_devices_pds','Cod. dispositivo')->label('alarm_display','Fallo alarma mueble')->label('alarm_device','Fallo alarma dispositivo')->label('description','Comentarios')->label('contact','Contacto')->label('email','E-mail')->label('phone','Teléfono')->label('id_operador','Instalador')->label('status','Estado');
		$xcrud->columns('id_incidencia,fecha,id_pds,contacto,phone,email,status');
		//$xcrud->fields('client,type_profile_client,picture_url,description,status');

		$data['title']   = 'Operativa incidencias';
		$data['content'] = $xcrud->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}	
	
	
	public function contactos()
	{
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('type_profile');
		$xcrud_1->table_name('Tipo');
		$xcrud_1->label('type','Tipo');
		$xcrud_1->columns('type');
		$xcrud_1->fields('type');		
		
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('contact');
		$xcrud_2->table_name('Contacto');
		$xcrud_2->relation('client_contact','client','id_client','client');
		$xcrud_2->relation('type_profile_contact','type_profile','id_type_profile','type');
		$xcrud_2->label('client_contact','Cliente')->label('type_profile_contact','Tipo')->label('contact','Contacto')->label('email','E-mail')->label('phone','Teléfono')->label('status','Estado');
		$xcrud_2->columns('client_contact,type_profile_contact,contact,email');
		$xcrud_2->fields('client_contact,type_profile_contact,contact,email,phone,status');		
		
		$data['title']   = 'Contactos';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}	
	

	public function alarmas()
	{
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('brand_alarm');
		$xcrud_1->table_name('Fabricante');
		$xcrud_1->label('brand','Fabricante');
		$xcrud_1->columns('brand');
		$xcrud_1->fields('brand');
		
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('type_alarm');
		$xcrud_2->table_name('Tipo');
		$xcrud_2->label('type','Tipo');
		$xcrud_2->columns('type');
		$xcrud_2->fields('type');
				
		$xcrud_3 = xcrud_get_instance();
		$xcrud_3->table('alarm');
		$xcrud_3->table_name('Modelo');
		$xcrud_3->relation('type_alarm','type_alarm','id_type_alarm','type');
		$xcrud_3->relation('brand_alarm','brand_alarm','id_brand_alarm','brand');
		$xcrud_3->change_type('picture_url', 'image');
		$xcrud_3->label('brand_alarm','Fabricante')->label('type_alarm','Tipo')->label('code','Código')->label('alarm','Modelo')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
		$xcrud_3->columns('brand_alarm,type_alarm,code,alarm,status');
		$xcrud_3->fields('brand_alarm,type_alarm,code,alarm,picture_url,description,status');
		
		$xcrud_4 = xcrud_get_instance();
		$xcrud_4->table('alarms_display');
		$xcrud_4->table_name('Relación alarmas mueble');
		$xcrud_4->relation('client_type_pds','client','id_client','client');
		$xcrud_4->relation('id_display','display','id_display','display');
		$xcrud_4->relation('id_alarm','alarm','id_alarm','alarm');
		$xcrud_4->label('client_type_pds','Cliente')->label('id_display','Mueble')->label('id_alarm','Alarma')->label('description','Comentarios')->label('status','Estado');
		$xcrud_4->columns('client_type_pds,id_display,id_alarm,status');
		$xcrud_4->fields('client_type_pds,id_display,id_alarm,description,status');

		$xcrud_5 = xcrud_get_instance();
		$xcrud_5->table('alarms_device_display');
		$xcrud_5->table_name('Relación alarmas dispositivo');
		$xcrud_5->relation('client_type_pds','client','id_client','client');
		$xcrud_5->relation('id_device','device','id_device','device');
		$xcrud_5->relation('id_display','display','id_display','display');
		$xcrud_5->relation('id_alarm','alarm','id_alarm','alarm');
		$xcrud_5->label('client_type_pds','Cliente')->label('id_device','Dispositivo')->label('id_display','Mueble')->label('id_alarm','Alarma')->label('description','Comentarios')->label('status','Estado');
		$xcrud_5->columns('client_type_pds,id_device,id_display,id_alarm,status');
		$xcrud_5->fields('client_type_pds,id_device,id_display,id_alarm,description,status');

		$data['title']   = 'Alarmas';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
		$data['content'] = $data['content'].$xcrud_3->render();
		$data['content'] = $data['content'].$xcrud_4->render();
		$data['content'] = $data['content'].$xcrud_5->render();
		
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}
	
	
	public function dispositivos()
	{
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('brand_device');
		$xcrud_1->table_name('Fabricante');
		$xcrud_1->label('brand','Fabricante');
		$xcrud_1->columns('brand');
		$xcrud_1->fields('brand');
		
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('type_device');
		$xcrud_2->table_name('Tipo');
		$xcrud_2->label('type','Tipo');
		$xcrud_2->columns('type');
		$xcrud_2->fields('type');		

		$xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('device');
        $xcrud_3->table_name('Modelo');
        $xcrud_3->relation('type_device','type_device','id_type_device','type');
        $xcrud_3->relation('brand_device','brand_device','id_brand_device','brand');
        $xcrud_3->change_type('picture_url', 'image');
        $xcrud_3->label('brand_device','Fabricante')->label('type_device','Tipo')->label('device','Modelo')->label('brand_name','Modelo fabricante')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
        $xcrud_3->columns('brand_device,type_device,device,brand_name,status');
        $xcrud_3->fields('brand_device,type_device,device,brand_name,picture_url,description,status');
	   
		$data['title']   = 'Dispositivos';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
		$data['content'] = $data['content'].$xcrud_3->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}

	
	public function muebles()
	{
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('panelado');
		$xcrud_1->table_name('Panelado');
		$xcrud_1->relation('client_panelado','client','id_client','client');
		$xcrud_1->relation('type_pds','type_pds','id_type_pds','pds');
		$xcrud_1->change_type('picture_url', 'image');
		$xcrud_1->label('client_panelado','Cliente')->label('type_pds','Tipo punto de venta')->label('panelado','Panelado Orange')->label('panelado_abx','REF.')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
		$xcrud_1->columns('client_panelado,type_pds,panelado,panelado_abx,status');
		$xcrud_1->fields('client_panelado,type_pds,panelado,panelado_abx,picture_url,description,status');
		
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('displays_panelado');
		$xcrud_2->table_name('Muebles panelado');
		$xcrud_2->relation('client_panelado','client','id_client','client');
		$xcrud_2->relation('id_panelado','panelado','id_panelado','panelado_abx');
		$xcrud_2->relation('id_display','display','id_display','display');
		$xcrud_2->label('client_panelado','Cliente')->label('id_panelado','REF.')->label('id_display','Modelo')->label('position','Posición')->label('description','Comentarios')->label('status','Estado');
		$xcrud_2->columns('client_panelado,id_panelado,id_display,position,status');
		$xcrud_2->fields('client_panelado,id_panelado,id_display,position,description,status');

		$xcrud_3 = xcrud_get_instance();
		$xcrud_3->table('display');
		$xcrud_3->table_name('Modelo');
		$xcrud_3->relation('client_display','client','id_client','client');
		$xcrud_3->change_type('picture_url', 'image');
		$xcrud_3->change_type('canvas_url', 'file');
		$xcrud_3->modal('picture_url');
		$xcrud_3->label('client_display','Cliente')->label('display','Modelo')->label('picture_url','Foto')->label('canvas_url','SVG')->label('description','Comentarios')->label('positions','Posiciones')->label('status','Estado');
		$xcrud_3->columns('client_display,display,picture_url,positions,status');
		$xcrud_3->fields('client_display,display,picture_url,canvas_url,description,positions,status');
		
		$xcrud_4 = xcrud_get_instance();
		$xcrud_4->table('devices_display');
		$xcrud_4->table_name('Dispositivos mueble');
		$xcrud_4->relation('client_panelado','client','id_client','client');
		$xcrud_4->relation('id_display','display','id_display','display');
		$xcrud_4->relation('id_device','device','id_device','device');
		$xcrud_4->label('client_panelado','Cliente')->label('id_panelado','REF.')->label('id_display','Mueble')->label('id_device','Dispositivo')->label('position','Posición')->label('description','Comentarios')->label('status','Estado');
		$xcrud_4->columns('client_panelado,id_display,id_device,position,status');
		$xcrud_4->fields('client_panelado,id_display,id_device,position,description,status');		
	
		$data['title']   = 'Muebles';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
		$data['content'] = $data['content'].$xcrud_3->render();
		$data['content'] = $data['content'].$xcrud_4->render();
			
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}
	
	
	public function puntos_de_venta()
	{
		$xcrud_1 = xcrud_get_instance();
		$xcrud_1->table('type_pds');
		$xcrud_1->table_name('Tipo');
		$xcrud_1->relation('client_type_pds','client','id_client','client');
		$xcrud_1->label('client_type_pds','Cliente')->label('pds','Tipo')->label('description','Comentarios')->label('status','Estado');
		$xcrud_1->columns('client_type_pds,pds,status');
		$xcrud_1->fields('client_type_pds,pds,description,status');
	
		$xcrud_2 = xcrud_get_instance();
		$xcrud_2->table('pds');
		$xcrud_2->table_name('Tienda');
		$xcrud_2->relation('client_pds','client','id_client','client');
		$xcrud_2->relation('type_pds','type_pds','id_type_pds','pds');
		$xcrud_2->relation('territory','territory','id_territory','territory');
		$xcrud_2->relation('panelado_pds','panelado','id_panelado','panelado');
		$xcrud_2->relation('type_via','type_via','id_type_via','via');
		$xcrud_2->relation('province','province','id_province','province');
		$xcrud_2->relation('county','county','id_county','county');
		$xcrud_2->relation('contact_contact_person','contact','id_contact','contact');
		$xcrud_2->relation('contact_in_charge','contact','id_contact','contact');
		$xcrud_2->relation('contact_supervisor','contact','id_contact','contact');
		$xcrud_2->change_type('picture_url', 'image');
		$xcrud_2->modal('picture_url');
		$xcrud_2->sum('m2_total','m2_fo','m2_bo');
		$xcrud_2->label('client_pds','Cliente')->label('reference','SFID')->label('type_pds','Tipo')->label('territory','Zona')->label('panelado_pds','Panelado Orange')->label('dispo','Disposición')->label('commercial','Nombre comercial')->label('cif','CIF')->label('picture_url','Foto')->label('m2_fo','M2 front-office')->label('m2_bo','M2 back-office')->label('m2_total','M2 total')->label('type_via','Tipo vía')->label('address','Dirección')->label('zip','C.P.')->label('city','Ciudad')->label('province','Provincia')->label('county','CC.AA.')->label('schedule','Horario')->label('phone','Teléfono')->label('mobile','Móvil')->label('email','Email')->label('contact_contact_person','Contacto')->label('contact_in_charge','Encargado')->label('contact_supervisor','Supervisor')->label('status','Estado');
		$xcrud_2->columns('client_pds,reference,type_pds,panelado_pds,commercial,territory,status');
		$xcrud_2->fields('client_pds,reference,type_pds,panelado_pds,dispo,commercial,cif,territory,picture_url,m2_fo,m2_bo,m2_total,type_via,address,zip,city,province,county,schedule,phone,mobile,email,contact_contact_person,contact_in_charge,contact_supervisor,status');
	
		$data['title']   = 'Puntos de venta';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}
		
	
	public function inventarios()
	{
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
		
		$data['title']   = 'Inventarios';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
		$data['content'] = $data['content'].$xcrud_3->render();
		$data['content'] = $data['content'].$xcrud_4->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}
		
	
	public function almacen()
	{
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

		$data['title']   = 'Almacén';
		$data['content'] = $xcrud_1->render();
		$data['content'] = $data['content'].$xcrud_2->render();
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}

	
	
	public function ayuda()
	{
		$xcrud = xcrud_get_instance();
		
		$data['title']   = 'Ayuda';
		$data['content'] = 'En construcción.';
	
		$this->load->view('backend/header', $data);
		$this->load->view('backend/navbar', $data);
		$this->load->view('backend/content', $data);
		$this->load->view('backend/footer');
	}	

		
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		redirect('/','refresh');
	}		
	
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */