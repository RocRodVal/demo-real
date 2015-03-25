<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tienda extends CI_Controller {

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
		
		if ($this->form_validation->run() == true && $this->user_model->login($data))
		{
			redirect('tienda/dashboard');
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
						
			$data['title'] = 'Login';
			
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/login',$data);
			$this->load->view('tienda/footer');
		}		
	}
	
	
	public function dashboard()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
				
			$xcrud = xcrud_get_instance();
			$this->load->model(array('chat_model','sfid_model'));

			$sfid = $this->sfid_model->get_pds($data['id_pds']);
				
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];			

			$incidencias = $this->sfid_model->get_incidencias($data['id_pds']);
			
			foreach($incidencias as $incidencia)
			{
				$incidencia->device  = $this->sfid_model->get_device($incidencia->id_devices_pds);
				$incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
				$incidencia->nuevos  = $this->chat_model->contar_nuevos($incidencia->id_incidencia,'altabox');
			}
			
			$data['incidencias'] =  $incidencias;
	
			$data['title'] = 'Mis solicitudes';
			
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/dashboard',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}	
	}	

	
	public function alta_incidencia()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');		
	
			$sfid = $this->sfid_model->get_pds($data['id_pds']);
			
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];

			$displays = $this->sfid_model->get_displays_pds($data['id_pds']);
			
			foreach($displays as $key=>$display) 
			{
				$num_devices = $this->sfid_model->count_devices_displays_pds($display->id_displays_pds);
				$display->devices_count = $num_devices;
			}
			
			$data['displays'] = $displays;

			$data['title'] = 'Alta incidencia';
		
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/alta_incidencia',$data);
			$this->load->view('tienda/footer');		
		}
		else
		{
			redirect('tienda','refresh');
		}	
	}	

	
	public function alta_incidencia_mueble()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');	
		
			$sfid = $this->sfid_model->get_pds($data['id_pds']);
			
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
			
			$display = $this->sfid_model->get_display($this->uri->segment(3));
			
			$data['display']     = $display['display'];
			$data['picture_url'] = $display['picture_url'];		
		
			$data['devices'] = $this->sfid_model->get_devices_displays_pds($this->uri->segment(3));
		
			$data['title'] = 'Alta incidencia';
	
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/alta_incidencia_mueble',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}		
	}	
	
	
	public function alta_incidencia_dispositivo()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');
		
			$sfid = $this->sfid_model->get_pds($data['id_pds']);
			
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
			
			$display = $this->sfid_model->get_display($this->uri->segment(3));
		
			$data['id_displays_pds'] = $display['id_displays_pds'];
			$data['display']         = $display['display'];
			$data['picture_url_dis'] = $display['picture_url'];
			
			$device = $this->sfid_model->get_device($this->uri->segment(4));
	
			$data['id_devices_pds']           = $device['id_devices_pds'];
			$data['device']        		 	  = $device['device'];
			$data['brand_name']   			  = $device['brand_name'];
			$data['IMEI']          		 	  = $device['IMEI'];
			$data['mac']            		  = $device['mac'];
			$data['serial']          		  = $device['serial'];
			$data['barcode']                  = $device['barcode'];
			$data['description']    	      = $device['description'];
			$data['owner']          		  = $device['owner'];
			$data['picture_url_dev'] 		  = $device['picture_url'];			

			$data['title'] = 'Alta incidencia';
	
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/alta_incidencia_dispositivo',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}		
	}	
	
	
	public function alta_incidencia_mueble_alarma()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
	
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');
	
			$sfid = $this->sfid_model->get_pds($data['id_pds']);
	
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
				
			$display = $this->sfid_model->get_display($this->uri->segment(3));
		
			$data['id_displays_pds'] = $display['id_displays_pds'];
			$data['display']         = $display['display'];
			$data['picture_url_dis'] = $display['picture_url'];

			$data['title'] = 'Alta incidencia';
	
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/alta_incidencia_mueble_alarma',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}
	}
		
	
	public function detalle_incidencia($id_incidencia)
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
	
			$xcrud = xcrud_get_instance();
			$this->load->model(array('chat_model','sfid_model'));
	
			$sfid = $this->sfid_model->get_pds($data['id_pds']);
	
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
				redirect('tienda/dashboard','refresh');
			}
			else
			{
				$data['id_incidencia']   = $incidencia['id_incidencia'];
				$data['fecha']           = $incidencia['fecha'];
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
				$data['denuncia']        = $incidencia['denuncia'];
				$data['contacto']        = $incidencia['contacto'];
				$data['phone']           = $incidencia['phone'];
				$data['status_pds']      = $incidencia['status_pds'];
				
				$display = $this->sfid_model->get_display($incidencia['id_displays_pds']);
		
				$data['display']         = $display['display'];
				$data['picture_url_dis'] = $display['picture_url'];
					
				$device = $this->sfid_model->get_device($incidencia['id_devices_pds']);

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
				$leido = $this->chat_model->marcar_leido($incidencia['id_incidencia'],'altabox');
				$data['chats'] = $chats;
				
				$data['title'] = 'Estado de incidencia Ref. '.$id_incidencia;
		
				$this->load->view('tienda/header',$data);
				$this->load->view('tienda/navbar',$data);
				$this->load->view('tienda/detalle_incidencia',$data);
				$this->load->view('tienda/footer');
			}				
		}
		else
		{
			redirect('tienda','refresh');
		}
	}
	

	public function insert_chat($id_incidencia)
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
	
			$xcrud = xcrud_get_instance();
			$this->load->model(array('chat_model','sfid_model'));
				
			$config['upload_path']   = dirname($_SERVER["SCRIPT_FILENAME"]).'/chats/';
			$config['upload_url']    = base_url().'/chats/';
			$config['allowed_types'] = 'gif|jpg|png';
			$new_name                = $id_incidencia.'-'.time();
			$config['file_name']     = $new_name;
			$config['overwrite']     = TRUE;
			$config['max_size']      = '10000KB';
	
			$this->load->library('upload', $config);
				
			$foto = NULL;
				
			if($this->upload->do_upload())
			{
				$foto = $new_name;
			}
			else
			{
				echo 'Ha fallado la carga de la foto.';
			}
			
			$texto_chat = $this->input->post('texto_chat');
			$texto_chat = $this->strip_html_tags($texto_chat);
			
			if ($foto != '' || $texto_chat != '' && $texto_chat != ' ') {
				$data = array(
					'fecha' => date('Y-m-d H:i:s'),
					'id_incidencia' => $id_incidencia,
					'agent' => $data['sfid'],
					'texto' => $texto_chat,
					'foto' => $foto,
					'status' => 1,
				);

				$chat = $this->chat_model->insert_chat_incidencia($data);

				if ($chat['add']) {
					redirect('tienda/detalle_incidencia/' . $id_incidencia);
				}
			}
			else{
				redirect('tienda/detalle_incidencia/' . $id_incidencia);
			}
		}
		else
		{
			redirect('tienda','refresh');
		}
	}
	
	
	public function strip_html_tags($text)
	{
		$text = preg_replace(
			array(
				// Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
				// Add line breaks before and after blocks
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
				"\n\$0", "\n\$0",
			),
			$text);
		return strip_tags($text);
	}
	
	
	public function insert_incidencia()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');
			
			$config['upload_path']   = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/';
			$config['upload_url']    = base_url().'/uploads/';
			$config['allowed_types'] = 'doc|docx|pdf|jpg|png';
			$new_name                = $data['sfid'].'-'.time();
			$config['file_name']     = $new_name;
			$config['overwrite']     = TRUE;
			$config['max_size']      = '10000KB';
	
			$this->load->library('upload', $config);
			
			$denuncia = '';
			
			if($this->upload->do_upload())
			{
				$denuncia = $new_name;
			}
			else
			{
				echo 'Ha fallado la carga de la denuncia.';
			}		
			
			if ($this->input->post('tipo_averia') == 1)
			{ 
				$tipo_averia = 'Robo'; 
			}
			else
			{
				$tipo_averia = 'Avería';
			}	
		
			$description_1 = $this->input->post('description_1');
			$description_1 = $this->strip_html_tags($description_1);			
			
			$data = array(
					'fecha'    	        => date('Y-m-d H:i:s'),
					'id_pds'            => $data['id_pds'],
					'id_displays_pds' 	=> $this->uri->segment(3),
					'id_devices_pds' 	=> $this->uri->segment(4),
					'tipo_averia' 	    => $tipo_averia,
					'fail_device'       => $this->input->post('device'),
					'alarm_display'     => $this->input->post('alarm_display'),
					'alarm_device'      => $this->input->post('alarm_device'),
					'alarm_garra'       => $this->input->post('alarm_garra'),
					'description_1'  	=> $description_1,
					'description_2'  	=> '',
					'parte_pdf'  	    => '',
					'denuncia'  	    => $denuncia,
					'foto_url'  	    => '',
					'foto_url_2'  	    => '',
					'foto_url_3'  	    => '',
					'contacto'  	    => $this->input->post('contacto'),
					'phone'  	        => $this->input->post('phone'),
					'email'  	        => NULL,
					'id_operador'  	    => NULL,
					'intervencion'  	=> NULL,
					'status_pds'	    => 1,
					'status'	        => 1,
			);
				
			$incidencia = $this->sfid_model->insert_incidencia($data);
				
			if ($incidencia['add'])
			{
				/*
				$pds = $this->sfid_model->get_pds($data['id_pds']);
		
				$message_admin  = 'Se ha registrado una nueva incidencia.'."\r\n\r\n";
				$message_admin .= 'La tienda con SFID '.$pds['reference'].' ha creado una incidencia con ref. '.$incidencia['id'].'.'."\r\n\r\n";
				$message_admin .= 'En http://demoreal.focusonemotions.com/ podrás revisar la misma.'."\r\n\r\n";
				$message_admin .= 'Atentamente,'."\r\n\r\n";
				$message_admin .= 'Demo Real'."\r\n";
				$message_admin .= 'http://demoreal.focusonemotions.com/'."\r\n";
		
				$this->email->from('no-reply@altabox.net', 'Demo Real');
				$this->email->to('demoreal@focusonemotions.com');
				$this->email->subject('Demo Real - Registro de incidencia ref. '.$incidencia['id']);
				$this->email->message($message_admin);
				$this->email->send();
				*/
	
				redirect('tienda/alta_incidencia_gracias/'.$incidencia['id']);
			}
			else
			{
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
			}
		}
		else
		{
			redirect('tienda','refresh');
		}	
	}
	
	
	public function insert_incidencia_mueble_alarma()
	{
		if($this->session->userdata('logged_in'))
		{		
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');
		
			$data = array(
					'fecha'    	        => date('Y-m-d H:i:s'),
					'id_pds'            => $data['id_pds'],
					'id_displays_pds' 	=> $this->uri->segment(3),
					'id_devices_pds' 	=> NULL,
					'tipo_averia' 	    => 'Avería',
					'fail_device'       => 0,
					'alarm_display'     => 1,
					'alarm_device'      => 0,
					'alarm_garra'       => 0,
					'description_1'  	=> $this->input->post('description_1'),
					'description_2'  	=> '',
					'parte_pdf'  	    => '',
					'denuncia'  	    => '',
					'foto_url'  	    => '',
					'foto_url_2'  	    => '',
					'foto_url_3'  	    => '',
					'contacto'  	    => $this->input->post('contacto'),
					'phone'  	        => $this->input->post('phone'),
					'email'  	        => NULL,
					'id_operador'  	    => NULL,
					'intervencion'  	=> NULL,
					'status_pds'	    => 1,
					'status'	        => 1,
			);
				
			$incidencia = $this->sfid_model->insert_incidencia($data);
				
			if ($incidencia['add'])
			{
				$pds = $this->sfid_model->get_pds($data['id_pds']);
		
				$message_admin  = 'Se ha registrado una nueva incidencia.'."\r\n\r\n";
				$message_admin .= 'La tienda con SFID '.$pds['reference'].' ha creado una incidencia con ref. '.$incidencia['id'].'.'."\r\n\r\n";
				$message_admin .= 'En http://demoreal.focusonemotions.com/ podrás revisar la misma.'."\r\n\r\n";
				$message_admin .= 'Atentamente,'."\r\n\r\n";
				$message_admin .= 'Demo Real'."\r\n";
				$message_admin .= 'http://demoreal.focusonemotions.com/'."\r\n";
		
				$this->email->from('no-reply@altabox.net', 'Demo Real');
				$this->email->to('demoreal@focusonemotions.com');
				$this->email->subject('Demo Real - Registro de incidencia ref. '.$incidencia['id']);
				$this->email->message($message_admin);
				$this->email->send();
	
				redirect('tienda/alta_incidencia_gracias/'.$incidencia['id']);
			}
			else
			{
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
			}
		}
		else
		{
			redirect('tienda','refresh');
		}	
	}	
	
	
	public function alta_incidencia_gracias()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
			
			$referencia = $this->uri->segment(3);			
			
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');
			
			$sfid               = $this->sfid_model->get_pds($data['id_pds']);			
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
		 
			$data['title']   = 'Alta incidencia';
			$data['content'] = 'Muchas gracias. Su incidencia ha sido dada alta con referencia número '.$referencia.'.';
		 
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/content',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}		
	}
	
	
	public function ayuda($tipo)
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');

			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');

			$sfid               = $this->sfid_model->get_pds($data['id_pds']);
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
			$data['id_pds_url'] = $sfid['id_pds'];
			
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
				default:
					$data['video']="ver_incidencias.mp4";
					$data['ayuda_title']="Mis solicitudes";
			}

			$data['title']      = 'Ayuda';

			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/ayuda',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}
	}

	public function logout()
	{
		if($this->session->userdata('logged_in'))
		{		
			$this->session->unset_userdata('logged_in');
		}	
		redirect('tienda','refresh');
	}
			
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */