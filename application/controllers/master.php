<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('email','text','xcrud'));
		$this->load->library(array('email','encrypt','form_validation','session'));
	}
		
	
	public function index()
	{
		$xcrud = xcrud_get_instance();
		$this->load->model('user_model');
	
		$this->form_validation->set_rules('sfid','SFID','required|xss_clean');
		$this->form_validation->set_rules('password','password','required|xss_clean');
	
		if ($this->form_validation->run() == true)
		{
			$data = array(
					'sfid' 	   => strtolower($this->input->post('sfid')),
					'password' => $this->input->post('password'),
			);
		}
	
		if ($this->form_validation->run() == true && $this->user_model->login_master($data))
		{
			redirect('master/dashboard');
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
	
			$data['title'] = 'Login';
				
			$this->load->view('master/header',$data);
			$this->load->view('master/login',$data);
			$this->load->view('master/footer');
		}
	}

	
	public function dashboard()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
	
			$xcrud = xcrud_get_instance();
			$this->load->model(array('chat_model','sfid_model','tienda_model'));

			$data['tiendas'] =  $this->tienda_model->search_pds($this->input->post('sfid'));
			
			$incidencias = $this->tienda_model->get_incidencias();
				
			foreach($incidencias as $incidencia)
			{
				$incidencia->device  = $this->sfid_model->get_device($incidencia->id_devices_pds);
				$incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
			}
			
			$data['incidencias'] =  $incidencias;
				
			$sfid = $this->tienda_model->get_pds($data['id_pds']);
				
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
	
			$data['title'] = 'Mis solicitudes';
				
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/dashboard',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}
	
	
	public function detalle_incidencia($id_incidencia)
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');
			$this->load->model(array('chat_model','sfid_model'));
	
			$sfid = $this->sfid_model->get_pds($this->uri->segment(4));
	
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];

			$incidencia = $this->sfid_model->get_incidencia($id_incidencia,$data['id_pds']);
			
			if($incidencia == FALSE)
			{
				redirect('master/dashboard','refresh');
			}
			else
			{
				$data['id_incidencia']   = $incidencia['id_incidencia'];
				$data['fecha']           = $incidencia['fecha'];
				$data['fecha_cierre']    = $incidencia['fecha_cierre'];
				$data['id_pds']          = $incidencia['id_pds'];
				$data['id_displays_pds'] = $incidencia['id_displays_pds'];
				$data['id_devices_pds']  = $incidencia['id_devices_pds'];
				$data['alarm_display']   = $incidencia['alarm_display'];
				$data['tipo_averia']     = $incidencia['tipo_averia'];
				$data['fail_device']     = $incidencia['fail_device'];			
				$data['alarm_device']    = $incidencia['alarm_device'];
				$data['alarm_garra']     = $incidencia['alarm_garra'];
				$data['description_1']   = $incidencia['description_1'];
				$data['description_2']   = $incidencia['description_2'];
				$data['description_3']   = $incidencia['description_3'];
				$data['denuncia']        = $incidencia['denuncia'];
				$data['contacto']        = $incidencia['contacto'];
				$data['phone']           = $incidencia['phone'];
				$data['status_pds']      = $incidencia['status_pds'];
				
				$display = $this->sfid_model->get_display($incidencia['id_displays_pds']);
		
				$data['id_display']      = $display['id_display'];
				$data['display']         = $display['display'];
				$data['picture_url_dis'] = $display['picture_url'];
					
				$device = $this->sfid_model->get_device($incidencia['id_devices_pds']);
				
				$data['id_device']      		  = $device['id_device'];
				$data['device']        		 	  = $device['device'];
				$data['brand_name']   			  = $device['brand_name'];
				$data['IMEI']          		 	  = $device['IMEI'];
				$data['mac']            		  = $device['mac'];
				$data['serial']          		  = $device['serial'];
				$data['barcode']                  = $device['barcode'];
				$data['description']    	      = $device['description'];
				$data['owner']          		  = $device['owner'];
				$data['picture_url_dev'] 		  = $device['picture_url'];

				$chats = $this->chat_model->get_chat_incidencia_pds($incidencia['id_incidencia']);
				$data['chats'] = $chats;
		
				$data['title'] = 'Estado de incidencia Ref. '.$id_incidencia.' [SFID-'.$data['reference'].']';
		
				$this->load->view('master/header',$data);
				$this->load->view('master/navbar',$data);
				$this->load->view('master/detalle_incidencia',$data);
				$this->load->view('master/footer');
			}				
		}
		else
		{
			redirect('master','refresh');
		}
	}
	

	public function alarmas()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{		
			$xcrud = xcrud_get_instance();
			$xcrud->table('alarm');
			$xcrud->table_name('Modelo');
			$xcrud->relation('type_alarm','type_alarm','id_type_alarm','type');
			$xcrud->relation('brand_alarm','brand_alarm','id_brand_alarm','brand');
			$xcrud->change_type('picture_url','image');
			$xcrud->modal('picture_url');
			$xcrud->label('brand_alarm','Fabricante')->label('type_alarm','Tipo')->label('code','Código')->label('alarm','Modelo')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
			$xcrud->columns('brand_alarm,type_alarm,code,alarm,picture_url,status');
			$xcrud->fields('brand_alarm,type_alarm,code,alarm,picture_url,description,status');
			
			$xcrud->show_primary_ai_column(false);
			$xcrud->unset_add();
			$xcrud->unset_edit();
			$xcrud->unset_remove();
			$xcrud->unset_numbers();		
	
			$data['title']   = 'Alarmas';
			$data['content'] = $xcrud->render();
			
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/content',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}		
	}
	
	
	public function dispositivos()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{		
			$xcrud = xcrud_get_instance();
	        $xcrud->table('device');
	        $xcrud->table_name('Modelo');
	        $xcrud->relation('type_device','type_device','id_type_device','type');
	        $xcrud->relation('brand_device','brand_device','id_brand_device','brand');
	        $xcrud->change_type('picture_url','image');
	        $xcrud->modal('picture_url');
	        $xcrud->label('brand_device','Fabricante')->label('type_device','Tipo')->label('device','Modelo')->label('brand_name','Modelo fabricante')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
	        $xcrud->columns('brand_device,type_device,device,picture_url,brand_name,status');
	        $xcrud->fields('brand_device,type_device,device,brand_name,picture_url,description,status');
	        
	        $xcrud->show_primary_ai_column(false);
	        $xcrud->unset_add();
	        $xcrud->unset_edit();
	        $xcrud->unset_remove();
	        $xcrud->unset_numbers();      
		   
			$data['title']   = 'Dispositivos';
			$data['content'] = $xcrud->render();
		
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/content',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}		
	}

	
	public function muebles()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{		
			$xcrud = xcrud_get_instance();
			$xcrud->table('display');
			$xcrud->table_name('Modelo');
			$xcrud->relation('client_display','client','id_client','client');
			$xcrud->change_type('picture_url', 'image');
			$xcrud->change_type('canvas_url','file');
			$xcrud->modal('picture_url');
			$xcrud->label('client_display','Cliente')->label('display','Modelo')->label('picture_url','Foto')->label('canvas_url','SVG')->label('description','Comentarios')->label('positions','Posiciones')->label('status','Estado');
			$xcrud->columns('client_display,display,picture_url,positions,status');
			$xcrud->fields('client_display,display,picture_url,canvas_url,description,positions,status');
			
			$xcrud->show_primary_ai_column(false);
			$xcrud->unset_add();
			$xcrud->unset_edit();
			$xcrud->unset_remove();
			$xcrud->unset_numbers();		
		
			$data['title']   = 'Muebles';
			$data['content'] = $xcrud->render();
				
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/content',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}		
	}
	
	
	public function puntos_de_venta()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{		
			$xcrud = xcrud_get_instance();
			$xcrud->table('pds');
			$xcrud->table_name('Tienda');
			$xcrud->relation('client_pds','client','id_client','client');
			$xcrud->relation('type_pds','type_pds','id_type_pds','pds');
			$xcrud->relation('territory','territory','id_territory','territory');
			$xcrud->relation('panelado_pds','panelado','id_panelado','panelado');
			$xcrud->relation('type_via','type_via','id_type_via','via');
			$xcrud->relation('province','province','id_province','province');
			$xcrud->relation('county','county','id_county','county');
			$xcrud->relation('contact_contact_person','contact','id_contact','contact');
			$xcrud->relation('contact_in_charge','contact','id_contact','contact');
			$xcrud->relation('contact_supervisor','contact','id_contact','contact');
			$xcrud->change_type('picture_url', 'image');
			$xcrud->modal('picture_url');
			$xcrud->sum('m2_total','m2_fo','m2_bo');
			$xcrud->label('client_pds','Cliente')->label('reference','SFID')->label('type_pds','Tipo')->label('territory','Zona')->label('panelado_pds','Panelado Orange')->label('dispo','Disposición')->label('commercial','Nombre comercial')->label('cif','CIF')->label('picture_url','Foto')->label('m2_fo','M2 front-office')->label('m2_bo','M2 back-office')->label('m2_total','M2 total')->label('type_via','Tipo vía')->label('address','Dirección')->label('zip','C.P.')->label('city','Ciudad')->label('province','Provincia')->label('county','CC.AA.')->label('schedule','Horario')->label('phone','Teléfono')->label('mobile','Móvil')->label('email','Email')->label('contact_contact_person','Contacto')->label('contact_in_charge','Encargado')->label('contact_supervisor','Supervisor')->label('status','Estado');
			$xcrud->columns('client_pds,reference,type_pds,panelado_pds,commercial,territory,status');
			$xcrud->fields('client_pds,reference,type_pds,panelado_pds,dispo,commercial,cif,territory,picture_url,m2_fo,m2_bo,m2_total,type_via,address,zip,city,province,county,schedule,phone,mobile,email,contact_contact_person,contact_in_charge,contact_supervisor,status');
		
			$xcrud->show_primary_ai_column(false);
			$xcrud->unset_add();
			$xcrud->unset_edit();
			$xcrud->unset_remove();
			$xcrud->unset_numbers();		
			
			$data['title']   = 'Puntos de venta';
			$data['content'] = $xcrud->render();
		
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/content',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}		
	}
	
	
	public function incidencias()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{	
			$xcrud_SQL = xcrud_get_instance();
			$xcrud_SQL->table_name('Incidencias');
			$xcrud_SQL->query("SELECT
					incidencias.id_incidencia AS Incidencia,
					DATE_FORMAT(incidencias.fecha,'%d/%m/%Y') AS 'Fecha alta',
					type_pds.pds AS 'Tipo Pds',
					pds.reference AS Referencia,
            		pds.commercial AS 'Nombre comercial',
            		pds.address AS Dirección,
            		pds.zip AS CP,
            		pds.city AS Ciudad,
					territory.territory AS Zona,
					display.display AS Mueble,
					device.device AS Dispositivo,
					incidencias.tipo_averia AS Tipo,
					incidencias.fail_device AS 'Fallo dispositivo',
					incidencias.alarm_display 'Alarma mueble',
					incidencias.alarm_device 'Alarma dispositivo',
					incidencias.alarm_garra 'Sistema de alarma',
					incidencias.description_1 AS 'Comentarios',
					incidencias.contacto,
					incidencias.phone AS Teléfono,
            		incidencias.status_pds AS 'Estado tienda',
					DATE_FORMAT(incidencias.fecha_cierre,'%d/%m/%Y') AS 'Fecha cierre'
				FROM incidencias
				JOIN pds ON incidencias.id_pds = pds.id_pds
				JOIN type_pds ON pds.type_pds = type_pds.id_type_pds
            	LEFT JOIN province ON pds.province = province.id_province
            	LEFT JOIN county ON pds.county = county.id_county
			    LEFT JOIN territory ON pds.territory = territory.id_territory
				JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
				JOIN display ON displays_pds.id_display = display.id_display
				LEFT JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
				LEFT JOIN device ON devices_pds.id_device = device.id_device");
	
			$data['title'] = 'Export incidencias';
			$data['content'] = $xcrud_SQL->render();
	
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/content',$data);
			$this->load->view('master/footer');
		} else {
			redirect('master', 'refresh');
		}
	}	

	public function cdm_incidencias()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud_1 = xcrud_get_instance();
			$xcrud_1->table_name('Incidencias');
			$xcrud_1->query("SELECT
								YEAR(incidencias.fecha) AS Year, 
								MONTH(incidencias.fecha) AS Mes, 
								COUNT(*) AS Incidencias,
						    	(
									SELECT
										COUNT(*) 
										FROM historico
										WHERE
										(
											((historico.status_pds = 'Cancelada' AND historico.status = 'Cancelada') OR
											(historico.status_pds = 'Finalizada' AND historico.status = 'Resuelta')) AND
						            		(DATE_ADD(incidencias.fecha, INTERVAL 96 HOUR) >= historico.fecha) AND
						            		(YEAR(historico.fecha) = Year AND MONTH(historico.fecha) = Mes)
										)
						    	) AS '- 72 h.',    
								(
									SELECT
										COUNT(*) 
										FROM incidencias
										WHERE 
										(
											(incidencias.status_pds = 'Finalizada' OR incidencias.status_pds = 'Cancelada') AND
											(YEAR(incidencias.fecha) = Year AND MONTH(incidencias.fecha) = Mes)
										)
								) AS Cerradas
							FROM incidencias
							GROUP BY 
								YEAR(incidencias.fecha),
								MONTH(incidencias.fecha)");
										
				
			$data['title'] = 'Estado incidencias';
				
			$data['content'] = $xcrud_1->render();
			/*
			$data['content'] = $data['content'] . $xcrud_2->render();
			$data['content'] = $data['content'] . $xcrud_3->render();
			$data['content'] = $data['content'] . $xcrud_4->render();
			$data['content'] = $data['content'] . $xcrud_5->render();
			*/
				
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/content',$data);
			$this->load->view('master/footer');
		} else {
			redirect('master', 'refresh');
		}
	}	
	
	
	public function cdm_tipo_incidencia()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud_1 = xcrud_get_instance();
			$xcrud_1->table_name('Dispositivo');
			$xcrud_1->query("SELECT 
  									YEAR(fecha) AS Año, 
  									MONTH(fecha) AS Mes, 
  									COUNT(*) AS Unidades
								FROM incidencias
								WHERE fail_device = 1
								GROUP BY 
  									YEAR(fecha),
  									MONTH(fecha)");
			
			$xcrud_2 = xcrud_get_instance();
			$xcrud_2->table_name('Alarma mueble');
			$xcrud_2->query("SELECT
  									YEAR(fecha) AS Año,
  									MONTH(fecha) AS Mes,
  									COUNT(*) AS Unidades
								FROM incidencias
								WHERE alarm_display = 1
								GROUP BY
  									YEAR(fecha),
  									MONTH(fecha)");			
			
			$xcrud_3 = xcrud_get_instance();
			$xcrud_3->table_name('Alarma/Cableado');
			$xcrud_3->query("SELECT
  									YEAR(fecha) AS Año,
  									MONTH(fecha) AS Mes,
  									COUNT(*) AS Unidades
								FROM incidencias
								WHERE alarm_device = 1
								GROUP BY
  									YEAR(fecha),
  									MONTH(fecha)");	
			
			$xcrud_4 = xcrud_get_instance();
			$xcrud_4->table_name('Soporte/Anclaje');
			$xcrud_4->query("SELECT
  									YEAR(fecha) AS Año,
  									MONTH(fecha) AS Mes,
  									COUNT(*) AS Unidades
								FROM incidencias
								WHERE alarm_garra = 1
								GROUP BY
  									YEAR(fecha),
  									MONTH(fecha)");			

			
			$xcrud_5 = xcrud_get_instance();
			$xcrud_5->table_name('Dispositivo + Sistema de seguridad');
			$xcrud_5->query("SELECT
  									YEAR(fecha) AS Año,
  									MONTH(fecha) AS Mes,
  									COUNT(*) AS Unidades
								FROM incidencias
								WHERE fail_device = 1 AND (alarm_device = 1 OR alarm_garra = 1)
								GROUP BY
  									YEAR(fecha),
  									MONTH(fecha)");			
			
			
			$data['title'] = 'Tipo incidencia';
			
			$data['content'] = $xcrud_1->render();
			$data['content'] = $data['content'] . $xcrud_2->render();
			$data['content'] = $data['content'] . $xcrud_3->render();
			$data['content'] = $data['content'] . $xcrud_4->render();
			$data['content'] = $data['content'] . $xcrud_5->render();
			
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/content',$data);
			$this->load->view('master/footer');
		} else {
			redirect('master', 'refresh');
		}
	}	
	
	public function cdm_alarmas()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
	
			$data['stocks']  = $this->tienda_model->get_cdm_alarmas();
	
			$data['title']   = 'Sistemas de seguridad';
	
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/cdm_alarmas',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}	
	
	public function cdm_dispositivos()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
	
			$data['stocks']  = $this->tienda_model->get_cdm_dispositivos();
				
			$data['title']   = 'Dispositivos';
	
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/cdm_dispositivos',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}	
	
	public function cdm_inventario()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
	
			$data['stocks'] = $this->tienda_model->get_stock_cruzado();
	
			$xcrud_stocks_almacen = xcrud_get_instance();
			$xcrud_stocks_almacen->table_name('Balance de activos');
			$xcrud_stocks_almacen->query("SELECT
					brand_device.brand AS Marca, device.device AS Modelo,
									(
										SELECT COUNT(*)
										FROM devices_pds
								        WHERE (devices_pds.id_device = device.id_device) AND
										(devices_pds.status = 'Alta')
								    ) AS 'Unidades tienda',
									(SELECT  COUNT(*)
										FROM devices_almacen
										WHERE (devices_almacen.id_device = device.id_device) AND
										(devices_almacen.status = 'En stock')
									) AS 'Deposito en almacén'
				FROM device
				JOIN brand_device ON device.brand_device = brand_device.id_brand_device
				ORDER BY brand_device.brand, device.device");			
			
			$xcrud_stocks_almacen->show_primary_ai_column(false);
			$xcrud_stocks_almacen->unset_add();
			$xcrud_stocks_almacen->unset_view();
			$xcrud_stocks_almacen->unset_edit();
			$xcrud_stocks_almacen->unset_remove();
			$xcrud_stocks_almacen->unset_numbers();
			$xcrud_stocks_almacen->start_minimized(true);
			
			$data['stocks_almacen'] = $xcrud_stocks_almacen->render();
			
			$xcrud->table('alarm');
			$xcrud->table_name('Alarmas almacén');
			$xcrud->relation('type_alarm','type_alarm','id_type_alarm','type');
			$xcrud->relation('brand_alarm','brand_alarm','id_brand_alarm','brand');
			$xcrud->change_type('picture_url','image');
			$xcrud->modal('picture_url');
			$xcrud->label('brand_alarm','Fabricante')->label('type_alarm','Tipo')->label('code','Código')->label('alarm','Modelo')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
			$xcrud->columns('brand_alarm,alarm,picture_url,units');
	
			$xcrud->show_primary_ai_column(false);
			$xcrud->unset_add();
			$xcrud->unset_view();
			$xcrud->unset_edit();
			$xcrud->unset_remove();
			$xcrud->unset_numbers();
	
			$data['alarms_almacen'] = $xcrud->render();
	
			$data['devices_almacen'] = $this->tienda_model->get_devices_almacen();
			$data['displays_pds']    = $this->tienda_model->get_displays_total();
			$data['devices_pds']     = $this->tienda_model->get_devices_total();
	
			$data['title']   = 'Inventario/Depósito';
	
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/inventario',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}	
	
	
	public function inventario()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
				
			$data['stocks']          = $this->tienda_model->get_stock();
			//$data['alarms_almacen']  = $this->tienda_model->get_alarms_almacen();
			
			$xcrud->table('alarm');
			$xcrud->table_name('Alarmas almacén');
			$xcrud->relation('type_alarm','type_alarm','id_type_alarm','type');
			$xcrud->relation('brand_alarm','brand_alarm','id_brand_alarm','brand');
			$xcrud->change_type('picture_url','image');
			$xcrud->modal('picture_url');
			$xcrud->label('brand_alarm','Fabricante')->label('type_alarm','Tipo')->label('code','Código')->label('alarm','Modelo')->label('picture_url','Foto')->label('description','Comentarios')->label('status','Estado');
			$xcrud->columns('brand_alarm,alarm,picture_url,units');
			
			$xcrud->show_primary_ai_column(false);
			$xcrud->unset_add();
			$xcrud->unset_view();
			$xcrud->unset_edit();
			$xcrud->unset_remove();
			$xcrud->unset_numbers();
			
			$data['alarms_almacen'] = $xcrud->render();
			
			$data['devices_almacen'] = $this->tienda_model->get_devices_almacen();
			$data['displays_pds']    = $this->tienda_model->get_displays_total();
			$data['devices_pds']     = $this->tienda_model->get_devices_total();
				
			$data['title']   = 'Depósito';
	
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/inventario',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}	

	
	public function descripcion()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
	
			$data['tiendas'] =  $this->tienda_model->search_pds($this->input->post('sfid'));
	
			$data['title'] = 'Planograma tienda';
	
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/descripcion',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}

	
	public function exp_alta_incidencia()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{		
			$id_pds   = $this->uri->segment(3);
		
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
			$this->load->model('sfid_model');
		
			$sfid = $this->tienda_model->get_pds($id_pds);
		
			$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
			$data['id_pds_url'] = $id_pds;
		
			$displays = $this->sfid_model->get_displays_pds($id_pds);
		
			foreach($displays as $key=>$display) {
				$num_devices = $this->tienda_model->count_devices_display($display->id_display);
				$display->devices_count = $num_devices;
			}
		
			$data['displays']=$displays;
		
			$data['title'] = 'Planograma tienda [SFID-'.$data['reference'].']';
		
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/exp_alta_incidencia',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}		
	}
		
	
	public function exp_alta_incidencia_mueble()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{		
			$id_pds   = $this->uri->segment(3);
			$id_dis   = $this->uri->segment(4);
		
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
			$this->load->model('sfid_model');
		
			$sfid = $this->tienda_model->get_pds($id_pds);
		
			$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
		
			$display = $this->sfid_model->get_display($this->uri->segment(4));
		
			$data['id_display']  = $display['id_display'];
			$data['display']     = $display['display'];
			$data['picture_url'] = $display['picture_url'];
		
			$data['devices'] = $this->sfid_model->get_devices_displays_pds($id_dis);
		
			$data['id_pds_url']  = $id_pds;
			$data['id_dis_url']  = $id_dis;
		
			$data['title'] = 'Planograma tienda [SFID-'.$data['reference'].']';
		
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/exp_alta_incidencia_display',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}		
	}
	
	
	public function exp_alta_incidencia_device()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{		
			$id_pds   = $this->uri->segment(3);
			$id_dis   = $this->uri->segment(4);
			$id_dev   = $this->uri->segment(5);
		
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
			$this->load->model('sfid_model');
		
			$sfid = $this->tienda_model->get_pds($id_pds);
		
			$data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
		
			$display = $this->sfid_model->get_display($this->uri->segment(4));
		
			$data['id_display']      = $display['id_display'];
			$data['display']         = $display['display'];
			$data['picture_url_dis'] = $display['picture_url'];
		
		
			$device = $this->sfid_model->get_device($this->uri->segment(5));
			
			$data['id_device']      		  = $device['id_device'];
			$data['device']        		 	  = $device['device'];
			$data['brand_name']   			  = $device['brand_name'];
			$data['IMEI']          		 	  = $device['IMEI'];
			$data['mac']            		  = $device['mac'];
			$data['serial']          		  = $device['serial'];
			$data['barcode']                  = $device['barcode'];
			$data['description']    	      = $device['description'];
			$data['owner']          		  = $device['owner'];
			$data['picture_url_dev'] 		  = $device['picture_url'];
					
			$data['id_pds_url']  = $id_pds;
			$data['id_dis_url']  = $id_dis;
			$data['id_dev_url']  = $id_dev;
		
			$data['title'] = 'Planograma tienda [SFID-'.$data['reference'].']';
		
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/exp_alta_incidencia_device',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}		
	}	
	
	
	public function inventarios_planogramas()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{	
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
		
			$id_display = $this->input->post('id_display');
			$devices    = $this->tienda_model->get_devices_display($id_display);
		
			$data['displays'] = $this->tienda_model->get_displays();
			$data['devices']  = $devices;
		
			if ($id_display != '')
			{
				$display = $this->tienda_model->get_display($id_display);
				$data['display_name'] = $display['display'];
				$data['picture_url']  = $display['picture_url'];
			}
		
			$data['title']   = 'Planograma mueble';
		
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/inventario_planogramas',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}	
	}
		
	
	public function ayuda($tipo)
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();

			switch($tipo){
				case 1: 
					$data['video']="ver_incidencias.mp4";
					$data['ayuda_title']="Mis solicitudes";
					break;
				case 2: 
					$data['video']="nueva_averia.mp4";
					$data['ayuda_title']="Alta incidencia";
					break;
				case 3: 
					$data['video']="nueva_incidencia_mueble.mp4";
					$data['ayuda_title']="Alta incidencia sistema seguridad general del mueble";
					break;
				case 4: 
					$data['video']="nuevo_robo.mp4";
					$data['ayuda_title']="Incidencias frecuentes";
					break;
				case 5:
					redirect('master/manuales','refresh');
					break;	
				case 6:
					redirect('master/muebles_fabricantes','refresh');
					break;									
				default:
					$data['video']="ver_incidencias.mp4";
					$data['ayuda_title']="Mis solicitudes";
			}

			$data['title'] = 'Ayuda';

			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/ayuda',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}

	
	public function manuales()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
	
			$data['title']       = 'Ayuda';
			$data['ayuda_title'] = 'Manuales';
				
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/manuales',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}

	public function muebles_fabricantes()
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
		{
			$xcrud = xcrud_get_instance();
	
			$data['title']       = 'Ayuda';
			$data['ayuda_title'] = 'Muebles fabricantes';
	
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/muebles_fabricantes',$data);
			$this->load->view('master/footer');
		}
		else
		{
			redirect('master','refresh');
		}
	}	
	
	public function logout()
	{
		if($this->session->userdata('logged_in'))
		{		
			$this->session->unset_userdata('logged_in');
		}	
		redirect('master','refresh');
	}	
	
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */