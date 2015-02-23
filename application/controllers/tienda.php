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
		$this->load->model('tienda_model');
		
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
						
			$data['title']   = 'Login tienda';
			
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
			$this->load->model('tienda_model');			

			$incidencias = $this->tienda_model->get_incidencias_pds($data['id_pds']);
			
			foreach($incidencias as $incidencia)
			{
				$incidencia->device= $this->tienda_model->get_device($incidencia->id_devices_pds);
				$incidencia->display= $this->tienda_model->get_display($incidencia->id_displays_pds);

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
	
			$data['title']   = 'Mis solicitudes';
			
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
			$this->load->model('tienda_model');		
			
			if ($this->uri->segment(3) != '') {$denuncia = $this->uri->segment(3);}
			else {$denuncia = 'no-robo';}	
			
			$sfid = $this->tienda_model->get_pds($data['id_pds']);
			
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
			$data['denuncia']   = $denuncia;

			$displays = $this->tienda_model->get_displays_panelado($data['id_pds']);
			
			foreach($displays as $key=>$display) 
			{
				$num_devices = $this->tienda_model->count_devices_display($display->id_display);
				$display->devices_count = $num_devices;
			}
			$data['displays'] = $displays;

			$data['title']    = 'Alta incidencia';
		
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
	
	
	public function alta_incidencia_robo()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
				
			$sfid = $this->tienda_model->get_pds($data['id_pds']);
				
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
		
			$data['title'] = 'Alta incidencia';
		
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/alta_incidencia_robo',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}		
	}
		

	public function subir_denuncia()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
		
			$sfid = $this->tienda_model->get_pds($data['id_pds']);
		
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];		
	
			$config['upload_path']   = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/';
			$config['upload_url']    = base_url().'/uploads/';
			$config['allowed_types'] = 'doc|docx|pdf|jpg|png';
			$new_name                = $data['sfid'].'-'.time().'-'.$_FILES["userfile"]['name'];
			$config['file_name']     = $new_name;
			$config['overwrite']     = TRUE;
			$config['max_size']      = '1000KB';

			$this->load->library('upload', $config);

			if($this->upload->do_upload())
			{
				redirect('tienda/alta_incidencia/'.$new_name,'refresh');
			} 
			else
			{
				echo "Ha fallado la carga de la denuncia.";		
			}
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
			$this->load->model('tienda_model');
		
			$sfid = $this->tienda_model->get_pds($data['id_pds']);
		
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
			
			$data['denuncia']   = $this->uri->segment(3);
			$display = $this->tienda_model->get_display($this->uri->segment(4));
	
			$data['id_display']  = $display['id_display'];
			$data['display']     = $display['display'];
			$data['picture_url'] = $display['picture_url'];		
		
			$data['devices'] = $this->tienda_model->get_devices_display($this->uri->segment(4));
		
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
			$this->load->model('tienda_model');
		
			$sfid = $this->tienda_model->get_pds($data['id_pds']);
		
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
			
			$data['denuncia']   = $this->uri->segment(3);
			$display = $this->tienda_model->get_display($this->uri->segment(4));	
		
			$data['id_display']      = $display['id_display'];
			$data['display']         = $display['display'];
			$data['picture_url_dis'] = $display['picture_url'];
			
			$device = $this->tienda_model->get_device($this->uri->segment(5));
	
			$data['id_device']       = $device['id_device'];
			$data['device']          = $device['device'];
			$data['picture_url_dev'] = $device['picture_url'];
			$data['device_'] = $device;

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
	

	
	public function insert_incidencia()
	{
		$id_pds   = $this->session->userdata('id_pds');
		$denuncia = $this->uri->segment(3);
		$id_dis   = $this->uri->segment(4);
		$id_dev   = $this->uri->segment(5);
	
		$xcrud = xcrud_get_instance();
		$this->load->model('tienda_model');
	
		if ($denuncia == 'no-robo') { $denuncia = ''; };
		
		if ($this->input->post('tipo_averia') == 1)
		{ 
			$tipo_averia = 'Robo'; 
		}
		else
		{
			$tipo_averia = 'Avería';
		}	
	
		$data = array(
				'fecha'    	        => date('Y-m-d H:i:s'),
				'id_pds'            => $id_pds,
				'id_displays_pds' 	=> $id_dis,
				'id_devices_pds' 	=> $id_dev,
				'tipo_averia' 	    => $tipo_averia,
				'alarm_display'     => $this->input->post('alarm_display'),
				'alarm_device'      => $this->input->post('alarm_device'),
				'description_1'  	=> $this->input->post('description_1'),
				'description_2'  	=> '',
				'parte_pdf'  	    => '',
				'denuncia'  	    => $denuncia,
				'foto_url'  	    => '',
				'foto_url_2'  	    => '',
				'foto_url_3'  	    => '',
				'contacto'  	    => $this->input->post('contacto'),
				'phone'  	        => $this->input->post('phone'),
				'email'  	        => $this->input->post('email'),
				'id_operador'  	    => NULL,
				'intervencion'  	=> NULL,
				'status_pds'	    => 1,
				'status'	        => 1,
		);
			
		$incidencia = $this->tienda_model->insert_incidencia($data);
			
		if ($incidencia['add'])
		{
	
			$pds = $this->tienda_model->get_pds($id_pds);
				
				
			$message_admin  = 'Se ha registrado una nueva incidencia.'."\r\n\r\n";
			$message_admin .= 'La tienda con SFID '.$pds['reference'].' ha creado una incidencia. En http://demoreal.focusonemotions.com/ podrás revisar la misma.'."\r\n\r\n";
			$message_admin .= 'Atentamente,'."\r\n\r\n";
			$message_admin .= 'Demo Real'."\r\n";
			$message_admin .= 'http://demoreal.focusonemotions.com/'."\r\n";
	
			$this->email->from('no-reply@altabox.net', 'Demo Real');
			$this->email->to('gzapico@altabox.net');
			$this->email->subject('Demo Real - Registro de incidencia');
			$this->email->message($message_admin);
			$this->email->send();

			$this->email->clear();
	
			$message_pds  = 'Se ha registrado una nueva incidencia.'."\r\n\r\n";
			$message_pds .= 'En breve recibirá más información de la evolución de la misma.'."\r\n\r\n";
			$message_pds .= 'Atentamente,'."\r\n\r\n";
			$message_pds .= 'Demo Real'."\r\n";
			$message_pds .= 'http://demoreal.focusonemotions.com/'."\r\n";
				
			$this->email->from('no-reply@altabox.net', 'Demo Real');
			$this->email->to('gzapico@altabox.net');
			$this->email->subject('Demo Real - Registro de incidencia');
			$this->email->message($message_pds);
			$this->email->send();
	
			redirect('tienda/alta_incidencia_gracias');
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
		}
	
	}
	
	
	public function alta_incidencia_gracias()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
			
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
			
			$sfid               = $this->tienda_model->get_pds($data['id_pds']);			
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
		 
			$data['title']   = 'Alta incidencia';
			$data['content'] = 'Muchas gracias.';
		 
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
	

	public function planograma()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
			
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
			
			$sfid               = $this->tienda_model->get_pds($data['id_pds']);			
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];

			$displays           = $this->tienda_model->get_displays_panelado($data['id_pds']);
			foreach($displays as $key=>$display) {
				$num_devices = $this->tienda_model->count_devices_display($display->id_display);
				$display->devices_count = $num_devices;
			}
			
			$data['displays']   = $displays;
			$data['title']      = 'Mi tienda';
	
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/planograma',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}
	}
	
	
	public function planograma_mueble()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
			
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
				
			$sfid               = $this->tienda_model->get_pds($data['id_pds']);
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];			

			$display             = $this->tienda_model->get_display($this->uri->segment(3));
			$data['id_display']  = $display['id_display'];
			$data['display']     = $display['display'];
			$data['picture_url'] = $display['picture_url'];
			
			$data['devices']     = $this->tienda_model->get_devices_display($this->uri->segment(3));
			$data['title']       = 'Mi tienda';
	
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/planograma_mueble',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}		
	}	
	
	
	public function ayuda()
	{
		if($this->session->userdata('logged_in'))
		{
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
			
			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');			
						
			$sfid               = $this->tienda_model->get_pds($data['id_pds']);
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];
			$data['id_pds_url'] = $sfid['id_pds'];
			
			$data['title']      = 'Ayuda';
			$data['content']    = 'En construcción.';

			$this->load->view('tienda/header', $data);
			$this->load->view('tienda/navbar', $data);
			$this->load->view('tienda/content', $data);
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