<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('email', 'text', 'xcrud'));
        $this->load->library(array('email', 'encrypt', 'form_validation', 'session'));
    }


    public function index()
    {
        $xcrud = xcrud_get_instance();
        $this->load->model('user_model');

        $this->form_validation->set_rules('sfid', 'SFID', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'required|xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array(
                'sfid' => strtolower($this->input->post('sfid')),
                'password' => $this->input->post('password'),
            );
        }

        if ($this->form_validation->run() == true && $this->user_model->login_admin($data)) {
            redirect('admin/dashboard');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));

            $data['title'] = 'Login';

            $this->load->view('backend/header', $data);
            $this->load->view('backend/login', $data);
            $this->load->view('backend/footer');
        }
    }


    public function dashboard()
    {
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model'));

            $data['tiendas'] = $this->tienda_model->search_pds($this->input->post('sfid'));
            
            $sfid = $this->tienda_model->get_pds($data['id_pds']);
            
            $data['id_pds']     = $sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory']  = $sfid['territory'];
            $data['reference']  = $sfid['reference'];
            $data['address']    = $sfid['address'];
            $data['zip']        = $sfid['zip'];
            $data['city']       = $sfid['city'];            
            
            $incidencias = $this->tienda_model->get_incidencias();

            foreach ($incidencias as $incidencia) {
                $incidencia->device = $this->sfid_model->get_device($incidencia->id_devices_pds);
                $incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
                $incidencia->nuevos  = $this->chat_model->contar_nuevos($incidencia->id_incidencia,$incidencia->reference);
                $incidencia->intervencion = $this->intervencion_model->get_intervencion_incidencia($incidencia->id_incidencia);
            }

            $data['incidencias'] = $incidencias;

            $data['title'] = 'Mis solicitudes';

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/dashboard', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function material_retorno()
    {
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model', 'sfid_model'));

            $data['material_retorno'] = $this->tienda_model->material_retorno();

            $data['title'] = 'Material retorno';

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/material_retorno', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }
    
    
    public function cambio_sfid()
    {
    	if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
    		$data['id_pds'] = $this->session->userdata('id_pds');
    		$data['sfid'] = $this->session->userdata('sfid');
    
    		$xcrud = xcrud_get_instance();
    		$this->load->model(array('tienda_model', 'sfid_model'));
    
			$data['tiendas'] =  $this->tienda_model->search_pds($this->input->post('sfid'));
    
    		$data['title'] = 'Cambio de SFID';
    
    		$this->load->view('backend/header', $data);
    		$this->load->view('backend/navbar', $data);
    		$this->load->view('backend/cambio_sfid', $data);
    		$this->load->view('backend/footer');
    	} else {
    		redirect('admin', 'refresh');
    	}
    }
    
    
    public function update_dispositivo()
    {
    	if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
    		$data['id_pds'] = $this->session->userdata('id_pds');
    		$data['sfid'] = $this->session->userdata('sfid');
    
    		$xcrud = xcrud_get_instance();
    		$this->load->model(array('tienda_model', 'sfid_model'));
    
    		$data['dispositivos']    =  $this->tienda_model->search_dispositivo_id($this->input->post('dipositivo_almacen_1'));
    
    		$data['title'] = 'Carga datos dispositivo';
    
    		$this->load->view('backend/header', $data);
    		$this->load->view('backend/navbar', $data);
    		$this->load->view('backend/carga_datos_dispositivo_edit', $data);
    		$this->load->view('backend/footer');
    	} else {
    		redirect('admin', 'refresh');
    	}
    }
        
    
    public function update_sfid()
    {
    	if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10))
    	{
    		$this->load->model(array('tienda_model', 'sfid_model'));
    	
	    	if ($this->input->post('sfid_new') <> '')
	    	{
	    		$historico_sfid = array(
	    				'id_pds' => $this->input->post('id_pds'),
	    				'fecha' => date('Y-m-d H:i:s'),
	    				'sfid_old' => $this->input->post('sfid_old'),
	    				'sfid_new' => $this->input->post('sfid_new')
	    		);
	    		
	    		$this->tienda_model->incidencia_update_sfid($this->input->post('sfid_old'),$this->input->post('sfid_new'));
	    		$this->tienda_model->incidencia_update_historico_sfid($historico_sfid);
	    	}
	    	
	    	redirect('admin/cambio_sfid', 'refresh');
	    } 
	    else 
	    {
	    	redirect('admin', 'refresh');
	    }	    		
    }    

    
    public function operar_incidencia()
    {
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
            $id_pds = $this->uri->segment(3);
            $id_inc = $this->uri->segment(4);

            $xcrud = xcrud_get_instance();
            $this->load->model(array('chat_model', 'intervencion_model', 'tienda_model', 'sfid_model'));

            $sfid = $this->tienda_model->get_pds($id_pds);

            $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory'] = $sfid['territory'];
            $data['reference'] = $sfid['reference'];
            $data['address'] = $sfid['address'];
            $data['zip'] = $sfid['zip'];
            $data['city'] = $sfid['city'];
            $data['province'] = $sfid['province'];
            $data['phone_pds'] = $sfid['phone'];

            $data['id_pds_url'] = $id_pds;
            $data['id_inc_url'] = $id_inc;

            $incidencia = $this->tienda_model->get_incidencia($id_inc);
            
            $historico_material_asignado = $this->tienda_model->historico_fecha($id_inc,'Material asignado');
            
            if (isset($historico_material_asignado['fecha']))
            {
            	$data['historico_material_asignado'] = $historico_material_asignado['fecha'];
            }
            else
            {
            	$data['historico_material_asignado'] = '---';
            }
                        
            $historico_fecha_comunicada = $this->tienda_model->historico_fecha($id_inc,'Comunicada');
            
            if (isset($historico_fecha_comunicada['fecha']))
            {
            	$data['historico_fecha_comunicada'] = $historico_fecha_comunicada['fecha'];
            }
            else
            {
            	$data['historico_fecha_comunicada'] = '---';
            }  
            
            $incidencia['intervencion'] = $this->intervencion_model->get_intervencion_incidencia($id_inc);
            $incidencia['device'] = $this->sfid_model->get_device($incidencia['id_devices_pds']);
            $incidencia['display'] = $this->sfid_model->get_display($incidencia['id_displays_pds']);
            $data['incidencia'] = $incidencia;
            
            $material_dispositivos = $this->tienda_model->get_material_dispositivos($incidencia['id_incidencia']);
            $data['material_dispositivos'] = $material_dispositivos;            
            
            $material_alarmas = $this->tienda_model->get_material_alarmas($incidencia['id_incidencia']);
            $data['material_alarmas'] = $material_alarmas;
                
            $chats = $this->chat_model->get_chat_incidencia_pds($incidencia['id_incidencia']);
            $leido = $this->chat_model->marcar_leido($incidencia['id_incidencia'],$sfid['reference']);
            $data['chats'] = $chats;            

            $data['title'] = 'Operativa incidencia Ref. '.$data['id_inc_url'];

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/operar_incidencia', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }
    
    
    public function imprimir_incidencia()
    {
    	if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
    		$id_pds = $this->uri->segment(3);
    		$id_inc = $this->uri->segment(4);
    
    		$xcrud = xcrud_get_instance();
    		$this->load->model(array('chat_model', 'intervencion_model', 'tienda_model', 'sfid_model'));
    		$this->load->helper(array('dompdf', 'file'));
    
    		$sfid = $this->tienda_model->get_pds($id_pds);
    
    		$data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
    		$data['commercial'] = $sfid['commercial'];
    		$data['territory'] = $sfid['territory'];
    		$data['reference'] = $sfid['reference'];
    		$data['address'] = $sfid['address'];
    		$data['zip'] = $sfid['zip'];
    		$data['city'] = $sfid['city'];
    		$data['province'] = $sfid['province'];
    		$data['phone_pds'] = $sfid['phone'];
    
    		$data['id_pds_url'] = $id_pds;
    		$data['id_inc_url'] = $id_inc;
    
    		$incidencia = $this->tienda_model->get_incidencia($id_inc);
    
    		$historico_material_asignado = $this->tienda_model->historico_fecha($id_inc,'Material asignado');
    
    		if (isset($historico_material_asignado['fecha']))
    		{
    			$data['historico_material_asignado'] = $historico_material_asignado['fecha'];
    		}
    		else
    		{
    			$data['historico_material_asignado'] = '---';
    		}
    
    		$historico_fecha_comunicada = $this->tienda_model->historico_fecha($id_inc,'Comunicada');
    
    		if (isset($historico_fecha_comunicada['fecha']))
    		{
    			$data['historico_fecha_comunicada'] = $historico_fecha_comunicada['fecha'];
    		}
    		else
    		{
    			$data['historico_fecha_comunicada'] = '---';
    		}
    
    		$incidencia['intervencion'] = $this->intervencion_model->get_intervencion_incidencia($id_inc);
    		$incidencia['device'] = $this->sfid_model->get_device($incidencia['id_devices_pds']);
    		$incidencia['display'] = $this->sfid_model->get_display($incidencia['id_displays_pds']);
    		$data['incidencia'] = $incidencia;
    
    		$material_dispositivos = $this->tienda_model->get_material_dispositivos($incidencia['id_incidencia']);
    		$data['material_dispositivos'] = $material_dispositivos;
    
    		$material_alarmas = $this->tienda_model->get_material_alarmas($incidencia['id_incidencia']);
    		$data['material_alarmas'] = $material_alarmas;
    
    		$chats = $this->chat_model->get_chat_incidencia_pds($incidencia['id_incidencia']);
    		$leido = $this->chat_model->marcar_leido($incidencia['id_incidencia'],$sfid['reference']);
    		$data['chats'] = $chats;
    
    		$data['title'] = 'DOCUMENTACIÓN DE RESOLUCIÓN DE INCIDENCIA';
    
    		// Salida PDF
    		$html = $this->load->view('backend/imprimir_incidencia', $data, true);
    		pdf_create($html, 'intervencion-'.$incidencia['intervencion'].'_incidencia-'.$incidencia['id_incidencia']);
    				
    		// Salida HTML
    		// $this->load->view('backend/imprimir_incidencia', $data);
    		
    	} else {
    		redirect('admin', 'refresh');
    	}
    }    

    public function insert_chat($id_incidencia)
    {
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
            $id_pds = $this->uri->segment(3);
            $id_inc = $this->uri->segment(4);

            $xcrud = xcrud_get_instance();
            $this->load->model('chat_model');

            $config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"]) . '/chats/';
            $config['upload_url'] = base_url() . '/chats/';
            $config['allowed_types'] = 'gif|jpg|png';
            $new_name = $id_inc . '-' . time();
            $config['file_name'] = $new_name;
            $config['overwrite'] = TRUE;
            $config['max_size'] = '10000KB';

            $this->load->library('upload', $config);

            $foto = NULL;

            if ($this->upload->do_upload()) {
                $foto = $new_name;
            } else {
                echo 'Ha fallado la carga de la foto.';
            }
            
            $texto_chat = $this->input->post('texto_chat');
            $texto_chat = $this->strip_html_tags($texto_chat);
            
            if ($foto != '' || $texto_chat != '' && $texto_chat != ' ') {

                $data = array(
                    'fecha' => date('Y-m-d H:i:s'),
                    'id_incidencia' => $id_inc,
                    'agent' => 'altabox',
                    'texto' => $texto_chat,
                    'foto' => $foto,
                    'status' => 1,
                );

                $chat = $this->chat_model->insert_chat_incidencia($data);

                if ($chat['add']) {
                    redirect('admin/operar_incidencia/' . $id_pds . '/' . $id_inc);
                }
            } else {
                redirect('admin/operar_incidencia/' . $id_pds . '/' . $id_inc);
            }

        } else {
            redirect('admin', 'refresh');
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

    public function listado_panelados()
    {

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $panelados = $this->tienda_model->get_panelados_maestros();

        foreach ($panelados as $key => $panelado) {
            $displays = $this->tienda_model->get_displays_panelado_maestros($panelado->id_panelado);
            $data['displays'] = $displays;
        }

        $data['panelados'] = $panelados;

        $data['title'] = 'Panelado tienda';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/maestro', $data);
        $this->load->view('backend/footer');
    }


    public function update_incidencia()
    {
        $id_pds = $this->uri->segment(3);
        $id_inc = $this->uri->segment(4);
        $status_pds = $this->uri->segment(5);
        $status = $this->uri->segment(6);

        $xcrud = xcrud_get_instance();
        $this->load->model(array('tienda_model','intervencion_model'));

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];

        $data['id_pds_ulr'] = $id_pds;
        $data['id_inc_ulr'] = $id_inc;

        $this->tienda_model->incidencia_update($id_inc, $status_pds, $status);

        $incidencia = $this->tienda_model->get_incidencia($id_inc);
        
        if ($status == 2) {
            
            if ($incidencia['fail_device'] == 1) {
                $this->tienda_model->incidencia_update_device_pds($incidencia['id_devices_pds'], 2);
            }
        }
        
        if ($status == 5) {
        	$intervencion = $this->intervencion_model->get_intervencion_incidencia($id_inc);
        	        	
        	$this->tienda_model->reservar_dispositivos($this->input->post('dipositivo_almacen_1'),3);
        	$this->tienda_model->reservar_dispositivos($this->input->post('dipositivo_almacen_2'),3);
        	$this->tienda_model->reservar_dispositivos($this->input->post('dipositivo_almacen_3'),3);
        	
        	$dispositivos = $this->tienda_model->get_devices_incidencia($id_inc);
        	$alarmas = $this->tienda_model->get_alarms_incidencia($id_inc);
        	
        	$facturacion_data= array(
        			'fecha' => date('Y-m-d H:i:s'),
        			'id_pds' => $id_pds,
        			'id_intervencion' => $intervencion,
        			'id_incidencia' => $id_inc,
        			'id_displays_pds' => $incidencia['id_displays_pds'],
        			'units_device' => $dispositivos['dispositivos'],
        			'units_alarma' => $alarmas['alarmas'],
        			'description' => NULL
        	);   
     	        	
        	$this->tienda_model->facturacion($facturacion_data);
        }        

        $data = array(
            'fecha' => date('Y-m-d H:i:s'),
            'id_incidencia' => $id_inc,
            'id_pds' => $id_pds,
            'description' => NULL,
            'agent' => $this->session->userdata('sfid'),
            'status_pds' => $status_pds,
            'status' => $status
        );

        $this->tienda_model->historico($data);
        
        if ($status == 5) 
        {
        	redirect('admin/imprimir_incidencia/'.$id_pds.'/'.$id_inc, 'refresh');
        }
        else
        {	
        	redirect('admin/operar_incidencia/'.$id_pds.'/'.$id_inc, 'refresh');
        }	
    }

    public function update_materiales_incidencia()
    {
    	$id_pds = $this->uri->segment(3);
    	$id_inc = $this->uri->segment(4);
    	$status_pds = $this->uri->segment(5);
    	$status = $this->uri->segment(6);
    
    	$xcrud = xcrud_get_instance();
    	$this->load->model('tienda_model');
    
    	
    	if ($this->input->post('units_dipositivo_almacen_1') <> '')
    	{	
    	$dipositivo_almacen_1 = array(
    			'fecha' => date('Y-m-d H:i:s'),
    			'id_incidencia' => $id_inc,
    			'id_pds' => $id_pds,
    			'id_alarm' => NULL,
    			'id_devices_almacen' => $this->input->post('dipositivo_almacen_1'),
    			'cantidad' => $this->input->post('units_dipositivo_almacen_1')
    	);

    	$this->tienda_model->incidencia_update_material($dipositivo_almacen_1);
    	$this->tienda_model->reservar_dispositivos($this->input->post('dipositivo_almacen_1'),2);
    	}
    	
    	if ($this->input->post('units_dipositivo_almacen_2') <> '')
    	{    	
    	$dipositivo_almacen_2 = array(
    			'fecha' => date('Y-m-d H:i:s'),
    			'id_incidencia' => $id_inc,
    			'id_pds' => $id_pds,
    			'id_alarm' => NULL,
    			'id_devices_almacen' => $this->input->post('dipositivo_almacen_2'),
    			'cantidad' => $this->input->post('units_dipositivo_almacen_2')
    	);    	
    	
    	$this->tienda_model->incidencia_update_material($dipositivo_almacen_2);
    	$this->tienda_model->reservar_dispositivos($this->input->post('dipositivo_almacen_2'),2);
    	}
    	
    	if ($this->input->post('units_dipositivo_almacen_3') <> '')
    	{
    		$dipositivo_almacen_3 = array(
    				'fecha' => date('Y-m-d H:i:s'),
    				'id_incidencia' => $id_inc,
    				'id_pds' => $id_pds,
    				'id_alarm' => NULL,
    				'id_devices_almacen' => $this->input->post('dipositivo_almacen_3'),
    				'cantidad' => $this->input->post('units_dipositivo_almacen_3')
    		);
    		 
    		$this->tienda_model->incidencia_update_material($dipositivo_almacen_3);
    		$this->tienda_model->reservar_dispositivos($this->input->post('dipositivo_almacen_3'),2);
    	}    	
    	
    	if ($this->input->post('units_alarma_almacen_1') <> '')
    	{  
	    	$alarma_almacen_1 = array(
	    			'fecha' => date('Y-m-d H:i:s'),
	    			'id_incidencia' => $id_inc,
	    			'id_pds' => $id_pds,
	    			'id_alarm' => $this->input->post('alarma_almacen_1'),
	    			'id_devices_almacen' => NULL,
	    			'cantidad' => $this->input->post('units_alarma_almacen_1')
	    	);    	
	    	
	    	$this->tienda_model->incidencia_update_material($alarma_almacen_1);
    	}
    	 
    	if ($this->input->post('units_alarma_almacen_2') <> '')
    	{  	
	    	$alarma_almacen_2 = array(
	    			'fecha' => date('Y-m-d H:i:s'),
	    			'id_incidencia' => $id_inc,
	    			'id_pds' => $id_pds,
	    			'id_alarm' => $this->input->post('alarma_almacen_2'),
	    			'id_devices_almacen' => NULL,
	    			'cantidad' => $this->input->post('units_alarma_almacen_2'),
	    	);
	    	
	    	$this->tienda_model->incidencia_update_material($alarma_almacen_2);
    	}
    	 
    	if ($this->input->post('units_alarma_almacen_3') <> '')
    	{    	
	    	$alarma_almacen_3 = array(
	    			'fecha' => date('Y-m-d H:i:s'),
	    			'id_incidencia' => $id_inc,
	    			'id_pds' => $id_pds,
	    			'id_alarm' => $this->input->post('alarma_almacen_3'),
	    			'id_devices_almacen' => NULL,
	    			'cantidad' => $this->input->post('units_alarma_almacen_3'),
	    	);
	
	    	$this->tienda_model->incidencia_update_material($alarma_almacen_3);    	
    	}
    	 
    	if ($this->input->post('units_alarma_almacen_4') <> '')
    	{    	
	    	$alarma_almacen_4 = array(
	    			'fecha' => date('Y-m-d H:i:s'),
	    			'id_incidencia' => $id_inc,
	    			'id_pds' => $id_pds,
	    			'id_alarm' => $this->input->post('alarma_almacen_4'),
	    			'id_devices_almacen' => NULL,
	    			'cantidad' => $this->input->post('units_alarma_almacen_4'),
	    	);    	
	    	
	    	$this->tienda_model->incidencia_update_material($alarma_almacen_4);    	
    	}
    	 
    	if ($this->input->post('units_alarma_almacen_5') <> '')
    	{    	
	    	$alarma_almacen_5 = array(
	    			'fecha' => date('Y-m-d H:i:s'),
	    			'id_incidencia' => $id_inc,
	    			'id_pds' => $id_pds,
	    			'id_alarm' => $this->input->post('alarma_almacen_5'),
	    			'id_devices_almacen' => NULL,
	    			'cantidad' => $this->input->post('units_alarma_almacen_5'),
	    	);
	    	 
	    	$this->tienda_model->incidencia_update_material($alarma_almacen_5);    	
    	}
    	
    	if ($this->input->post('units_alarma_almacen_6') <> '')
    	{
    		$alarma_almacen_6 = array(
    				'fecha' => date('Y-m-d H:i:s'),
    				'id_incidencia' => $id_inc,
    				'id_pds' => $id_pds,
    				'id_alarm' => $this->input->post('alarma_almacen_6'),
    				'id_devices_almacen' => NULL,
    				'cantidad' => $this->input->post('units_alarma_almacen_6'),
    		);
    	
    		$this->tienda_model->incidencia_update_material($alarma_almacen_6);
    	}    	
    	
    	if ($this->input->post('units_alarma_almacen_7') <> '')
    	{
    		$alarma_almacen_7 = array(
    				'fecha' => date('Y-m-d H:i:s'),
    				'id_incidencia' => $id_inc,
    				'id_pds' => $id_pds,
    				'id_alarm' => $this->input->post('alarma_almacen_7'),
    				'id_devices_almacen' => NULL,
    				'cantidad' => $this->input->post('units_alarma_almacen_7'),
    		);
    	
    		$this->tienda_model->incidencia_update_material($alarma_almacen_7);
    	}    	
    	
    	if ($this->input->post('units_alarma_almacen_8') <> '')
    	{
    		$alarma_almacen_8 = array(
    				'fecha' => date('Y-m-d H:i:s'),
    				'id_incidencia' => $id_inc,
    				'id_pds' => $id_pds,
    				'id_alarm' => $this->input->post('alarma_almacen_8'),
    				'id_devices_almacen' => NULL,
    				'cantidad' => $this->input->post('units_alarma_almacen_8'),
    		);
    	
    		$this->tienda_model->incidencia_update_material($alarma_almacen_8);
    	}    	
    	
    	if ($this->input->post('units_alarma_almacen_9') <> '')
    	{
    		$alarma_almacen_9 = array(
    				'fecha' => date('Y-m-d H:i:s'),
    				'id_incidencia' => $id_inc,
    				'id_pds' => $id_pds,
    				'id_alarm' => $this->input->post('alarma_almacen_9'),
    				'id_devices_almacen' => NULL,
    				'cantidad' => $this->input->post('units_alarma_almacen_9'),
    		);
    	
    		$this->tienda_model->incidencia_update_material($alarma_almacen_9);
    	}

    	if ($this->input->post('units_alarma_almacen_10') <> '')
    	{
    		$alarma_almacen_10 = array(
    				'fecha' => date('Y-m-d H:i:s'),
    				'id_incidencia' => $id_inc,
    				'id_pds' => $id_pds,
    				'id_alarm' => $this->input->post('alarma_almacen_10'),
    				'id_devices_almacen' => NULL,
    				'cantidad' => $this->input->post('units_alarma_almacen_10'),
    		);
    	
    		$this->tienda_model->incidencia_update_material($alarma_almacen_10);
    	}    	
    	    	
    	$this->tienda_model->incidencia_update($id_inc, $status_pds, $status);    	
    	
    	$data = array(
    			'fecha' => date('Y-m-d H:i:s'),
    			'id_incidencia' => $id_inc,
    			'id_pds' => $id_pds,
    			'description' => NULL,
    			'agent' => $this->session->userdata('sfid'),
    			'status_pds' => $status_pds,
    			'status' => $status
    	);
    	    	
    	
    	$this->tienda_model->historico($data);
    
    	redirect('admin/operar_incidencia/'.$id_pds.'/'.$id_inc, 'refresh');
    }   
    
    
    public function insert_comentario_incidencia()
    {
    	$id_pds = $this->uri->segment(3);
    	$id_inc = $this->uri->segment(4);
    
    	$xcrud = xcrud_get_instance();
    	$this->load->model('tienda_model');
    
        $description_2 = $this->input->post('description_2');
        $description_2 = $this->strip_html_tags($description_2);
    	$this->tienda_model->comentario_incidencia_update($id_inc, $description_2);
    
    	redirect('admin/operar_incidencia/'.$id_pds.'/'.$id_inc, 'refresh');
    }    

    public function insert_comentario_incidencia_instalador()
    {
    	$id_pds = $this->uri->segment(3);
    	$id_inc = $this->uri->segment(4);
    
    	$xcrud = xcrud_get_instance();
    	$this->load->model('tienda_model');
    
    	$description_3 = $this->input->post('description_3');
    	$description_3 = $this->strip_html_tags($description_3);
    	$this->tienda_model->comentario_incidencia_instalador_update($id_inc, $description_3);
    
    	redirect('admin/operar_incidencia/'.$id_pds.'/'.$id_inc, 'refresh');
    }
    
    public function update_incidencia_materiales()
    {
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
            $id_pds = $this->uri->segment(3);
            $id_inc = $this->uri->segment(4);
            $status_pds = $this->uri->segment(5);
            $status = $this->uri->segment(6);

            $xcrud = xcrud_get_instance();
            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model'));

            $sfid = $this->tienda_model->get_pds($id_pds);

            $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory'] = $sfid['territory'];
            $data['reference'] = $sfid['reference'];
            $data['address'] = $sfid['address'];
            $data['zip'] = $sfid['zip'];
            $data['city'] = $sfid['city'];
            $data['province'] = $sfid['province'];
            $data['phone_pds'] = $sfid['phone'];

            $data['id_pds_url'] = $id_pds;
            $data['id_inc_url'] = $id_inc;

            $incidencia = $this->tienda_model->get_incidencia($id_inc);
            $incidencia['intervencion'] = $this->intervencion_model->get_intervencion_incidencia($id_inc);
            $incidencia['device'] = $this->sfid_model->get_device($incidencia['id_devices_pds']);
            $incidencia['display'] = $this->sfid_model->get_display($incidencia['id_displays_pds']);
            $data['incidencia'] = $incidencia;

            $data['alarms_almacen'] = $this->tienda_model->get_alarms_almacen_reserva();
            $data['devices_almacen'] = $this->tienda_model->get_devices_almacen_reserva();

            $data['title'] = 'Operativa incidencia Ref. '.$data['id_inc_url'];

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/operar_incidencia_materiales', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }
    
    public function carga_datos_dispositivo()
    {
    	if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
    		$data['id_pds'] = $this->session->userdata('id_pds');
    		$data['sfid'] = $this->session->userdata('sfid');
    
    		$xcrud = xcrud_get_instance();
    		$this->load->model(array('tienda_model', 'sfid_model'));
    
    		$data['dispositivos']    =  $this->tienda_model->search_dispositivo($this->input->post('codigo'));
    		$data['devices_almacen'] = $this->tienda_model->get_devices_almacen_reserva();
    
    		$data['title'] = 'Carga datos dispositivo';
    
    		$this->load->view('backend/header', $data);
    		$this->load->view('backend/navbar', $data);
    		$this->load->view('backend/carga_datos_dispositivo', $data);
    		$this->load->view('backend/footer');
    	} else {
    		redirect('admin', 'refresh');
    	}
    }    
    
    
    public function clientes()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('client');
        $xcrud->table_name('Empresa');
        $xcrud->relation('type_profile_client', 'type_profile', 'id_type_profile', 'type');
        $xcrud->change_type('picture_url', 'image');
        $xcrud->label('client', 'Empresa')->label('type_profile_client', 'Tipo')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud->columns('client,type_profile_client');
        $xcrud->fields('client,type_profile_client,picture_url,description,status');

        $data['title'] = 'Empresas';
        $data['content'] = $xcrud->render();

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function incidencias()
    {
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {

            $xcrud_SQL = xcrud_get_instance();
            $xcrud_SQL->query('SELECT 
					incidencias.id_incidencia AS Incidencia,
					incidencias.fecha AS Fecha,
					pds.reference AS Referencia,
					display.display AS Mueble,
					device.device AS Dispositivo,
					incidencias.tipo_averia AS Tipo,
					incidencias.fail_device AS "Fallo dispositivo",
					incidencias.alarm_display "Alarma mueble",
					incidencias.alarm_device "Alarma dispositivo",
					incidencias.alarm_garra "Sistema de alarma",
					incidencias.description_1 AS "Comentarios",
					incidencias.description_2 AS "Comentarios SAT",
            		incidencias.description_3 AS "Comentarios Instalador",
					incidencias.contacto,
					incidencias.phone AS "Teléfono",
            		incidencias.status_pds AS "Estado tienda",
					incidencias.status AS "Estado SAT"
				FROM incidencias
				JOIN pds ON incidencias.id_pds = pds.id_pds
				JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
				JOIN display ON displays_pds.id_display = display.id_display
				LEFT JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
				LEFT JOIN device ON devices_pds.id_device = device.id_device');

            
            $data['title'] = 'Export incidencias';
            $data['content'] = $xcrud_SQL->render();

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/content', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }
    
    
    public function contactos()
    {
        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('type_profile');
        $xcrud_1->table_name('Tipo');
        $xcrud_1->label('type', 'Tipo');
        $xcrud_1->columns('type');
        $xcrud_1->fields('type');

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('contact');
        $xcrud_2->table_name('Contacto');
        $xcrud_2->relation('client_contact', 'client', 'id_client', 'client');
        $xcrud_2->relation('type_profile_contact', 'type_profile', 'id_type_profile', 'type');
        $xcrud_2->relation('territory', 'territory', 'id_territory', 'territory');
        $xcrud_2->relation('panelado_pds', 'panelado', 'id_panelado', 'panelado');
        $xcrud_2->relation('type_via', 'type_via', 'id_type_via', 'via');
        $xcrud_2->relation('province', 'province', 'id_province', 'province');
        $xcrud_2->relation('county', 'county', 'id_county', 'county');
        $xcrud_2->label('client_contact', 'Empresa')->label('type_profile_contact', 'Tipo')->label('contact', 'Contacto')->label('type_via', 'Tipo vía')->label('address', 'Dirección')->label('zip', 'C.P.')->label('city', 'Ciudad')->label('province', 'Provincia')->label('county', 'CC.AA.')->label('schedule', 'Horario')->label('phone', 'Teléfono')->label('mobile', 'Móvil')->label('email', 'Email')->label('status', 'Estado');
        $xcrud_2->columns('client_contact,type_profile_contact,contact,email');
        $xcrud_2->fields('client_contact,type_profile_contact,contact,type_via,address,zip,city,province,county,schedule,phone,mobile,email,status');


        $data['title'] = 'Contactos';
        //$data['content'] = $xcrud_1->render();
        //$data['content'] = $data['content'].$xcrud_2->render();
        $data['content'] = $xcrud_2->render();


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
        $xcrud_1->label('brand', 'Fabricante');
        $xcrud_1->columns('brand');
        $xcrud_1->fields('brand');

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('type_alarm');
        $xcrud_2->table_name('Tipo');
        $xcrud_2->label('type', 'Tipo');
        $xcrud_2->columns('type');
        $xcrud_2->fields('type');

        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('alarm');
        $xcrud_3->table_name('Modelo');
        $xcrud_3->relation('type_alarm', 'type_alarm', 'id_type_alarm', 'type');
        $xcrud_3->relation('brand_alarm', 'brand_alarm', 'id_brand_alarm', 'brand');
        $xcrud_3->change_type('picture_url', 'image');
        $xcrud_3->modal('picture_url');
        $xcrud_3->label('brand_alarm', 'Fabricante')->label('type_alarm', 'Tipo')->label('code', 'Código')->label('alarm', 'Modelo')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('units', 'Unidades')->label('status', 'Estado');
        $xcrud_3->columns('brand_alarm,type_alarm,code,alarm,picture_url,units,status');
        $xcrud_3->fields('brand_alarm,type_alarm,code,alarm,picture_url,description,units,status');

        $xcrud_4 = xcrud_get_instance();
        $xcrud_4->table('alarms_display');
        $xcrud_4->table_name('Relación alarmas mueble');
        $xcrud_4->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_4->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_4->relation('id_alarm', 'alarm', 'id_alarm', 'alarm');
        $xcrud_4->label('client_type_pds', 'Cliente')->label('id_display', 'Mueble')->label('id_alarm', 'Alarma')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_4->columns('client_type_pds,id_display,id_alarm,status');
        $xcrud_4->fields('client_type_pds,id_display,id_alarm,description,status');

        $xcrud_5 = xcrud_get_instance();
        $xcrud_5->table('alarms_device_display');
        $xcrud_5->table_name('Relación alarmas dispositivo');
        $xcrud_5->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_5->relation('id_device', 'device', 'id_device', 'device');
        $xcrud_5->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_5->relation('id_alarm', 'alarm', 'id_alarm', 'alarm');
        $xcrud_5->label('client_type_pds', 'Cliente')->label('id_device', 'Dispositivo')->label('id_display', 'Mueble')->label('id_alarm', 'Alarma')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_5->columns('client_type_pds,id_device,id_display,id_alarm,status');
        $xcrud_5->fields('client_type_pds,id_device,id_display,id_alarm,description,status');

        $data['title'] = 'Alarmas';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_3->render();
        $data['content'] = $data['content'] . $xcrud_4->render();
        $data['content'] = $data['content'] . $xcrud_5->render();

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
        $xcrud_1->label('brand', 'Fabricante');
        $xcrud_1->columns('brand');
        $xcrud_1->fields('brand');

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('type_device');
        $xcrud_2->table_name('Tipo');
        $xcrud_2->label('type', 'Tipo');
        $xcrud_2->columns('type');
        $xcrud_2->fields('type');

        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('device');
        $xcrud_3->table_name('Modelo');
        $xcrud_3->relation('type_device', 'type_device', 'id_type_device', 'type');
        $xcrud_3->relation('brand_device', 'brand_device', 'id_brand_device', 'brand');
        $xcrud_3->change_type('picture_url', 'image');
        $xcrud_3->modal('picture_url');
        $xcrud_3->label('brand_device', 'Fabricante')->label('type_device', 'Tipo')->label('device', 'Modelo')->label('brand_name', 'Modelo fabricante')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_3->columns('brand_device,type_device,device,picture_url,brand_name,status');
        $xcrud_3->fields('brand_device,type_device,device,brand_name,picture_url,description,status');

        $data['title'] = 'Dispositivos';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_3->render();

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
        $xcrud_1->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_1->relation('type_pds', 'type_pds', 'id_type_pds', 'pds');
        $xcrud_1->change_type('picture_url', 'image');
        $xcrud_1->label('client_panelado', 'Cliente')->label('type_pds', 'Tipo punto de venta')->label('panelado', 'Panelado Orange')->label('panelado_abx', 'REF.')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_1->columns('client_panelado,type_pds,panelado,panelado_abx,status');
        $xcrud_1->fields('client_panelado,type_pds,panelado,panelado_abx,picture_url,description,status');

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('displays_panelado');
        $xcrud_2->table_name('Muebles panelado');
        $xcrud_2->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_2->relation('id_panelado', 'panelado', 'id_panelado', 'panelado_abx');
        $xcrud_2->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_2->label('client_panelado', 'Cliente')->label('id_panelado', 'REF.')->label('id_display', 'Modelo')->label('position', 'Posición')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_2->columns('client_panelado,id_panelado,id_display,position,status');
        $xcrud_2->fields('client_panelado,id_panelado,id_display,position,description,status');

        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('display');
        $xcrud_3->table_name('Modelo');
        $xcrud_3->relation('client_display', 'client', 'id_client', 'client');
        $xcrud_3->change_type('picture_url', 'image');
        $xcrud_3->change_type('canvas_url', 'file');
        $xcrud_3->modal('picture_url');
        $xcrud_3->label('client_display', 'Cliente')->label('display', 'Modelo')->label('picture_url', 'Foto')->label('canvas_url', 'SVG')->label('description', 'Comentarios')->label('positions', 'Posiciones')->label('status', 'Estado');
        $xcrud_3->columns('client_display,display,picture_url,positions,status');
        $xcrud_3->fields('client_display,display,picture_url,canvas_url,description,positions,status');

        $xcrud_4 = xcrud_get_instance();
        $xcrud_4->table('devices_display');
        $xcrud_4->table_name('Dispositivos mueble');
        $xcrud_4->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_4->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_4->relation('id_device', 'device', 'id_device', 'device');
        $xcrud_4->label('client_panelado', 'Cliente')->label('id_panelado', 'REF.')->label('id_display', 'Mueble')->label('id_device', 'Dispositivo')->label('position', 'Posición')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_4->columns('client_panelado,id_display,id_device,position,status');
        $xcrud_4->fields('client_panelado,id_display,id_device,position,description,status');

        $data['title'] = 'Muebles';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_3->render();
        $data['content'] = $data['content'] . $xcrud_4->render();

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
        $xcrud_1->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_1->label('client_type_pds', 'Cliente')->label('pds', 'Tipo')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_1->columns('client_type_pds,pds,status');
        $xcrud_1->fields('client_type_pds,pds,description,status');

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('pds');
        $xcrud_2->table_name('Tienda');
        $xcrud_2->relation('client_pds', 'client', 'id_client', 'client');
        $xcrud_2->relation('type_pds', 'type_pds', 'id_type_pds', 'pds');
        $xcrud_2->relation('territory', 'territory', 'id_territory', 'territory');
        $xcrud_2->relation('panelado_pds', 'panelado', 'id_panelado', 'panelado_abx');
        $xcrud_2->relation('type_via', 'type_via', 'id_type_via', 'via');
        $xcrud_2->relation('province', 'province', 'id_province', 'province');
        $xcrud_2->relation('county', 'county', 'id_county', 'county');
        $xcrud_2->relation('contact_contact_person', 'contact', 'id_contact', 'contact');
        $xcrud_2->relation('contact_in_charge', 'contact', 'id_contact', 'contact');
        $xcrud_2->relation('contact_supervisor', 'contact', 'id_contact', 'contact');
        $xcrud_2->change_type('picture_url', 'image');
        $xcrud_2->modal('picture_url');
        //$xcrud_2->readonly('reference');
        $xcrud_2->disabled('reference','edit');
        $xcrud_2->sum('m2_total', 'm2_fo', 'm2_bo');
        $xcrud_2->label('client_pds', 'Cliente')->label('reference', 'SFID')->label('type_pds', 'Tipo')->label('territory', 'Zona')->label('panelado_pds', 'Panelado')->label('dispo', 'Disposición')->label('commercial', 'Nombre comercial')->label('cif', 'CIF')->label('picture_url', 'Foto')->label('m2_fo', 'M2 front-office')->label('m2_bo', 'M2 back-office')->label('m2_total', 'M2 total')->label('type_via', 'Tipo vía')->label('address', 'Dirección')->label('zip', 'C.P.')->label('city', 'Ciudad')->label('province', 'Provincia')->label('county', 'CC.AA.')->label('schedule', 'Horario')->label('phone', 'Teléfono')->label('mobile', 'Móvil')->label('email', 'Email')->label('contact_contact_person', 'Contacto')->label('contact_in_charge', 'Encargado')->label('contact_supervisor', 'Supervisor')->label('status', 'Estado');
        $xcrud_2->columns('client_pds,reference,type_pds,panelado_pds,commercial,territory,status');
        $xcrud_2->fields('client_pds,reference,type_pds,panelado_pds,dispo,commercial,cif,territory,picture_url,m2_fo,m2_bo,m2_total,type_via,address,zip,city,province,county,schedule,phone,mobile,email,contact_contact_person,contact_in_charge,contact_supervisor,status');
        
        
        $data['title'] = 'Puntos de venta';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }

    public function descripcion()
    {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $data['sfid'] = $this->session->userdata('sfid');
            $data['agent_id'] = $this->session->userdata('agent_id');
            $data['type'] = $this->session->userdata('type');

            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');

            $data['tiendas'] = $this->tienda_model->search_pds($this->input->post('sfid'));

            $data['title'] = 'Planograma tiendas';

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/descripcion', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function inventarios()
    {
        $this->load->model('tienda_model');

        $data['displays'] = $this->tienda_model->get_displays_total();
        $data['devices'] = $this->tienda_model->get_devices_total();

        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('displays_pds');
        $xcrud_1->table_name('Inventario muebles');
        $xcrud_1->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_1->relation('id_pds', 'pds', 'id_pds', 'reference');
        $xcrud_1->relation('id_type_pds', 'type_pds', 'id_type_pds', 'pds');
        $xcrud_1->relation('id_panelado', 'panelado', 'id_panelado', 'panelado_abx');
        $xcrud_1->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_1->label('client_type_pds', 'Cliente')->label('id_displays_pds', 'REF.')->label('id_type_pds', 'Tipo')->label('id_pds', 'SFID')->label('id_panelado', 'Panelado')->label('id_display', 'Mueble')->label('position', 'Posición Orange')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_1->columns('client_type_pds,id_displays_pds,id_type_pds,id_pds,id_panelado,id_display,position,status');
        $xcrud_1->fields('client_type_pds,id_displays_pds,id_type_pds,id_pds,id_panelado,id_display,position,description,status');
        $xcrud_1->order_by('id_pds', 'asc');
        $xcrud_1->order_by('position', 'asc');
        $xcrud_1->show_primary_ai_column(true);
        $xcrud_1->unset_numbers();
        $xcrud_1->start_minimized(true);

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('alarms_display_pds');
        $xcrud_2->table_name('Inventario alarmas mueble');
        $xcrud_2->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_2->relation('id_pds', 'pds', 'id_pds', 'reference');
        $xcrud_2->relation('id_displays_pds', 'displays_pds', 'id_displays_pds', 'id_displays_pds');
        $xcrud_2->relation('id_alarm', 'alarm', 'id_alarm', 'alarm');
        $xcrud_2->label('client_type_pds', 'Cliente')->label('id_alarms_display_pds', 'REF.')->label('id_pds', 'SFID')->label('id_displays_pds', 'Cod. mueble')->label('id_alarm', 'Alarma')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_2->columns('client_type_pds,id_alarms_display_pds,id_pds,id_displays_pds,id_alarm,status');
        $xcrud_2->fields('client_type_pds,id_alarms_display_pds,id_pds,id_displays_pds,id_alarm,description,status');
        $xcrud_2->order_by('id_pds', 'asc');
        $xcrud_2->order_by('id_displays_pds', 'asc');
        $xcrud_2->show_primary_ai_column(true);
        $xcrud_2->unset_numbers();
        $xcrud_2->start_minimized(true);

        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('devices_pds');
        $xcrud_3->table_name('Inventario dispositivos');
        $xcrud_3->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_3->relation('id_pds', 'pds', 'id_pds', 'reference');
        $xcrud_3->relation('id_displays_pds', 'displays_pds', 'id_displays_pds', 'id_displays_pds');
        $xcrud_3->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_3->relation('id_device', 'device', 'id_device', 'device');
        $xcrud_3->relation('id_color_device', 'color_device', 'id_color_device', 'color_device');
        $xcrud_3->relation('id_complement_device', 'complement_device', 'id_complement_device', 'complement_device');
        $xcrud_3->relation('id_status_device', 'status_device', 'id_status_device', 'status_device');
        $xcrud_3->relation('id_status_packaging_device', 'status_packaging_device', 'id_status_packaging_device', 'status_packaging_device');
        $xcrud_3->change_type('picture_url_1', 'image');
        $xcrud_3->change_type('picture_url_2', 'image');
        $xcrud_3->change_type('picture_url_3', 'image');
        $xcrud_3->modal('picture_url_1');
        $xcrud_3->modal('picture_url_2');
        $xcrud_3->modal('picture_url_3');
        $xcrud_3->label('client_type_pds', 'Cliente')->label('id_devices_pds', 'REF.')->label('id_pds', 'SFID')->label('id_displays_pds', 'Cod. mueble')->label('id_display', 'Mueble')->label('alta', 'Fecha de alta')->label('position', 'Posición')->label('id_device', 'Dispositivo')->label('IMEI', 'IMEI')->label('mac', 'MAC')->label('serial', 'Nº de serie')->label('barcode', 'Código de barras')->label('id_color_device', 'Color')->label('id_complement_device', 'Complementos')->label('id_status_device', 'Estado dispositivo')->label('id_status_packaging_device', 'Estado packaging')->label('picture_url_1', 'Foto #1')->label('picture_url_2', 'Foto #2')->label('picture_url_3', 'Foto #3')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_3->columns('client_type_pds,id_devices_pds,id_pds,id_displays_pds,id_display,id_device,position,IMEI,mac,status');
        $xcrud_3->fields('client_type_pds,id_devices_pds,id_pds,id_displays_pds,id_display,alta,id_device,position,serial,IMEI,mac,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,status');
        $xcrud_3->order_by('id_pds', 'asc');
        $xcrud_3->order_by('id_displays_pds', 'asc');
        $xcrud_3->order_by('position', 'asc');
        $xcrud_3->show_primary_ai_column(true);
        $xcrud_3->unset_numbers();
        $xcrud_3->start_minimized(true);

        $xcrud_4 = xcrud_get_instance();
        $xcrud_4->table('alarms_device_pds');
        $xcrud_4->table_name('Inventario alarmas dispositivo');
        $xcrud_4->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_4->relation('id_pds', 'pds', 'id_pds', 'reference');
        $xcrud_4->relation('id_devices_pds', 'devices_pds', 'id_devices_pds', 'id_devices_pds');
        $xcrud_4->relation('id_displays_pds', 'displays_pds', 'id_displays_pds', 'id_displays_pds');
        $xcrud_4->relation('id_alarm', 'alarm', 'id_alarm', 'alarm');
        $xcrud_4->label('client_type_pds', 'Cliente')->label('id_alarms_device_pds', 'REF.')->label('id_pds', 'SFID')->label('id_devices_pds', 'Cod. dispositivo')->label('id_displays_pds', 'Cod. mueble')->label('id_alarm', 'Alarma')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_4->columns('client_type_pds,id_alarms_device_pds,id_pds,id_devices_pds,id_displays_pds,id_alarm,status');
        $xcrud_4->fields('client_type_pds,id_alarms_device_pds,id_pds,id_devices_pds,id_displays_pds,id_alarm,description,status');
        $xcrud_4->order_by('id_pds', 'asc');
        $xcrud_4->order_by('id_displays_pds', 'asc');
        $xcrud_4->order_by('id_devices_pds', 'asc');
        $xcrud_4->show_primary_ai_column(true);
        $xcrud_4->unset_numbers();
        $xcrud_4->start_minimized(true);

        $data['title'] = 'Inventarios tiendas';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_3->render();
        $data['content'] = $data['content'] . $xcrud_4->render();

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/inventario', $data);
        $this->load->view('backend/footer');
    }

    public function inventarios_panelados()
    {
        $this->load->model('tienda_model');

        $id_panelado = $this->input->post('id_panelado');
        $displays = $this->tienda_model->get_inventario_panelado($id_panelado);


        if ($id_panelado != '') {
            foreach ($displays as $key => $display) {
                $num_devices = $this->tienda_model->count_devices_display($display->id_display);
                $display->devices_count = $num_devices;
            }
        }

        $data['panelados'] = $this->tienda_model->get_panelados();
        $data['displays'] = $displays;

        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('panelado');
        $xcrud_1->table_name('Panelado');
        $xcrud_1->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_1->relation('type_pds', 'type_pds', 'id_type_pds', 'pds');
        $xcrud_1->change_type('picture_url', 'image');
        $xcrud_1->label('client_panelado', 'Cliente')->label('type_pds', 'Tipo punto de venta')->label('panelado', 'Panelado Orange')->label('panelado_abx', 'REF.')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_1->columns('client_panelado,type_pds,panelado,panelado_abx,status');
        $xcrud_1->fields('client_panelado,type_pds,panelado,panelado_abx,picture_url,description,status');
        $xcrud_1->show_primary_ai_column(true);
        $xcrud_1->unset_numbers();
        $xcrud_1->start_minimized(true);

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('displays_panelado');
        $xcrud_2->table_name('Muebles panelado');
        $xcrud_2->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_2->relation('id_panelado', 'panelado', 'id_panelado', 'panelado_abx');
        $xcrud_2->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_2->label('client_panelado', 'Cliente')->label('id_panelado', 'REF.')->label('id_display', 'Modelo')->label('position', 'Posición')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_2->columns('client_panelado,id_panelado,id_display,position,status');
        $xcrud_2->fields('client_panelado,id_panelado,id_display,position,description,status');
        $xcrud_2->show_primary_ai_column(true);
        $xcrud_2->unset_numbers();
        $xcrud_2->start_minimized(true);

        $data['title'] = 'Panelado genérico';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();


        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/inventario_panelados', $data);
        $this->load->view('backend/footer');
    }


    public function inventarios_planogramas()
    {
        $this->load->model('tienda_model');

        $id_display = $this->input->post('id_display');
        $devices = $this->tienda_model->get_devices_display($id_display);

        $data['displays'] = $this->tienda_model->get_displays();
        $data['devices'] = $devices;

        if ($id_display != '') {
            $display = $this->tienda_model->get_display($id_display);
            $data['picture_url'] = $display['picture_url'];
        }

        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('display');
        $xcrud_1->table_name('Modelo');
        $xcrud_1->relation('client_display', 'client', 'id_client', 'client');
        $xcrud_1->change_type('picture_url', 'image');
        $xcrud_1->change_type('canvas_url', 'file');
        $xcrud_1->modal('picture_url');
        $xcrud_1->label('client_display', 'Cliente')->label('display', 'Modelo')->label('picture_url', 'Foto')->label('canvas_url', 'SVG')->label('description', 'Comentarios')->label('positions', 'Posiciones')->label('status', 'Estado');
        $xcrud_1->columns('client_display,display,picture_url,positions,status');
        $xcrud_1->fields('client_display,display,picture_url,canvas_url,description,positions,status');
        $xcrud_1->show_primary_ai_column(true);
        $xcrud_1->unset_numbers();
        $xcrud_1->start_minimized(true);

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('devices_display');
        $xcrud_2->table_name('Dispositivos mueble');
        $xcrud_2->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_2->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_2->relation('id_device', 'device', 'id_device', 'device');
        $xcrud_2->label('client_panelado', 'Cliente')->label('id_panelado', 'REF.')->label('id_display', 'Mueble')->label('id_device', 'Dispositivo')->label('position', 'Posición')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_2->columns('client_panelado,id_display,id_device,position,status');
        $xcrud_2->fields('client_panelado,id_display,id_device,position,description,status');
        $xcrud_2->show_primary_ai_column(true);
        $xcrud_2->unset_numbers();
        $xcrud_2->start_minimized(true);

        $data['title'] = 'Planograma genérico';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();


        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/inventario_planogramas', $data);
        $this->load->view('backend/footer');
    }

    public function almacen()
    {
        $this->load->model('tienda_model');

        $data['devices'] = $this->tienda_model->get_devices_almacen();

        $xcrud = xcrud_get_instance();
        $xcrud->table('devices_almacen');
        $xcrud->table_name('Inventario dispositivos almacén');
        $xcrud->relation('id_device', 'device', 'id_device', 'device');
        $xcrud->relation('id_color_device', 'color_device', 'id_color_device', 'color_device');
        $xcrud->relation('id_complement_device', 'complement_device', 'id_complement_device', 'complement_device');
        $xcrud->relation('id_status_device', 'status_device', 'id_status_device', 'status_device');
        $xcrud->relation('id_status_packaging_device', 'status_packaging_device', 'id_status_packaging_device', 'status_packaging_device');
        $xcrud->change_type('picture_url_1', 'image');
        $xcrud->change_type('picture_url_2', 'image');
        $xcrud->change_type('picture_url_3', 'image');
        $xcrud->modal('picture_url_1');
        $xcrud->modal('picture_url_2');
        $xcrud->modal('picture_url_3');
        $xcrud->label('id_devices_almacen', 'Ref.')->label('alta', 'Fecha de alta')->label('id_device', 'Dispositivo')->label('serial', 'Nº de serie')->label('IMEI', 'IMEI')->label('mac', 'MAC')->label('barcode', 'Código de barras')->label('id_color_device', 'Color')->label('id_complement_device', 'Complementos')->label('id_status_device', 'Estado dispositivo')->label('id_status_packaging_device', 'Estado packaging')->label('picture_url_1', 'Foto #1')->label('picture_url_2', 'Foto #2')->label('picture_url_3', 'Foto #3')->label('description', 'Comentarios')->label('owner', 'Dueño')->label('status', 'Estado');
        $xcrud->columns('id_devices_almacen,id_device,IMEI,mac,barcode,status');
        $xcrud->fields('id_devices_almacen,alta,id_device,serial,IMEI,mac,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description,owner,status');
        $xcrud->order_by('id_device', 'asc');
        $xcrud->order_by('status', 'asc');
        $xcrud->order_by('id_devices_almacen', 'asc');
        $xcrud->show_primary_ai_column(true);
        $xcrud->unset_numbers();
        $xcrud->start_minimized(true);

        $data['title'] = 'Almacén';
        $data['content'] = $xcrud->render();

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/almacen', $data);
        $this->load->view('backend/footer');
    }


    public function listado_incidencias()
    {
        $xcrud = xcrud_get_instance();

        $data['title'] = 'Listado de incidencias';
        $data['content'] = 'En construcción.';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function alta_incidencia()
    {
        $id_pds = $this->uri->segment(3);
        if ($this->uri->segment(4) != '') {
            $denuncia = $this->uri->segment(4);
        } else {
            $denuncia = 'no-robo';
        }
        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];
        $data['id_pds_url'] = $id_pds;
        $data['denuncia'] = $denuncia;

        $displays = $this->tienda_model->get_displays_panelado($id_pds);

        foreach ($displays as $key => $display) {
            $num_devices = $this->tienda_model->count_devices_display($display->id_display);
            $display->devices_count = $num_devices;
        }

        $data['displays'] = $displays;

        $data['title'] = 'Alta incidencia';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/alta_incidencia', $data);
        $this->load->view('backend/footer');
    }

    public function exp_alta_incidencia()
    {
        $id_pds = $this->uri->segment(3);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');
        $this->load->model('sfid_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];
        $data['id_pds_url'] = $id_pds;

        $displays = $this->sfid_model->get_displays_pds($id_pds);

        foreach ($displays as $key => $display) {
            $num_devices = $this->tienda_model->count_devices_display($display->id_display);
            $display->devices_count = $num_devices;
        }

        $data['displays'] = $displays;

        $data['title'] = 'Panelado tienda';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/exp_alta_incidencia', $data);
        $this->load->view('backend/footer');
    }

    public function exp_alta_incidencia_mueble()
    {
        $id_pds = $this->uri->segment(3);
        $id_dis = $this->uri->segment(4);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');
        $this->load->model('sfid_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];

        $display = $this->sfid_model->get_display($this->uri->segment(4));

        $data['id_display'] = $display['id_display'];
        $data['display'] = $display['display'];
        $data['picture_url'] = $display['picture_url'];

        $data['devices'] = $this->sfid_model->get_devices_displays_pds($id_dis);

        $data['id_pds_url'] = $id_pds;
        $data['id_dis_url'] = $id_dis;

        $data['title'] = 'Planograma mueble';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/exp_alta_incidencia_display', $data);
        $this->load->view('backend/footer');
    }

    public function exp_alta_incidencia_device()
    {
        $id_pds = $this->uri->segment(3);
        $id_dis = $this->uri->segment(4);
        $id_dev = $this->uri->segment(5);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');
        $this->load->model('sfid_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];

        $display = $this->sfid_model->get_display($this->uri->segment(4));

        $data['id_display'] = $display['id_display'];
        $data['display'] = $display['display'];
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
        
        
        $data['id_pds_url'] = $id_pds;
        $data['id_dis_url'] = $id_dis;
        $data['id_dev_url'] = $id_dev;

        $data['title'] = 'Detalle dispositivo';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/exp_alta_incidencia_device', $data);
        $this->load->view('backend/footer');
    }


    public function alta_incidencia_robo()
    {
        $id_pds = $this->uri->segment(3);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];

        $data['displays'] = $this->tienda_model->get_displays_panelado($id_pds);

        $data['id_pds_url'] = $id_pds;

        $data['title'] = 'Alta incidencia';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/alta_incidencia_robo', $data);
        $this->load->view('backend/footer');
    }

    public function subir_denuncia()
    {
        $id_pds = $this->uri->segment(3);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];

        $config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"]) . '/uploads/';
        $config['upload_url'] = base_url() . '/uploads/';
        $config['allowed_types'] = 'doc|docx|pdf|jpg|png';
        $new_name = $sfid['reference'] . '-' . time();
        $config['file_name'] = $new_name;
        $config['overwrite'] = TRUE;
        $config['max_size'] = '1000KB';

        $this->load->library('upload', $config);

        if ($this->upload->do_upload()) {
            redirect('admin/alta_incidencia/' . $id_pds . '/' . $new_name, 'refresh');
        } else {
            echo "File upload failed";
        }
    }


    public function alta_incidencia_mueble()
    {
        $id_pds = $this->uri->segment(3);
        $denuncia = $this->uri->segment(4);
        $id_dis = $this->uri->segment(5);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];
        $data['denuncia'] = $this->uri->segment(4);

        $display = $this->tienda_model->get_display($id_dis);

        $data['id_display'] = $display['id_display'];
        $data['display'] = $display['display'];
        $data['picture_url'] = $display['picture_url'];

        $data['devices'] = $this->tienda_model->get_devices_display($id_dis);

        $data['id_pds_url'] = $id_pds;
        $data['id_dis_url'] = $id_dis;

        $data['title'] = 'Alta incidencia';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/alta_incidencia_display', $data);
        $this->load->view('backend/footer');
    }

    public function inventario_tienda()
    {
        $id_pds = $this->uri->segment(3);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];
        $data['id_pds_url'] = $id_pds;

        $data['devices'] = $this->tienda_model->get_devices_pds($id_pds);

        $data['title'] = 'Dispositivos';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/devices_pds', $data);
        $this->load->view('backend/footer');
    }

    public function planograma()
    {
        $id_pds = $this->uri->segment(3);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];
        $data['id_pds_url'] = $id_pds;

        $displays = $this->tienda_model->get_displays_panelado($id_pds);

        foreach ($displays as $key => $display) {
            $num_devices = $this->tienda_model->count_devices_display($display->id_display);
            $display->devices_count = $num_devices;
        }

        $data['displays'] = $displays;

        $data['title'] = 'Planograma';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/planograma', $data);
        $this->load->view('backend/footer');
    }


    public function planograma_mueble()
    {
        $id_pds = $this->uri->segment(3);
        $id_dis = $this->uri->segment(4);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];

        $display = $this->tienda_model->get_display($id_dis);

        $data['id_display'] = $display['id_display'];
        $data['display'] = $display['display'];
        $data['picture_url'] = $display['picture_url'];

        $data['devices'] = $this->tienda_model->get_devices_display($id_dis);

        $data['id_pds_url'] = $id_pds;
        $data['id_dis_url'] = $id_dis;

        $data['title'] = 'Planograma';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/planograma_display', $data);
        $this->load->view('backend/footer');
    }


    public function alta_incidencia_device()
    {
        $id_pds = $this->uri->segment(3);
        $denuncia = $this->uri->segment(4);
        $id_dis = $this->uri->segment(5);
        $id_dev = $this->uri->segment(6);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $sfid = $this->tienda_model->get_pds($id_pds);

        $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
        $data['commercial'] = $sfid['commercial'];
        $data['territory'] = $sfid['territory'];
        $data['reference'] = $sfid['reference'];
        $data['address'] = $sfid['address'];
        $data['zip'] = $sfid['zip'];
        $data['city'] = $sfid['city'];
        $data['denuncia'] = $this->uri->segment(4);

        $display = $this->tienda_model->get_display($id_dis);

        $data['id_display'] = $display['id_display'];
        $data['display'] = $display['display'];
        $data['picture_url_dis'] = $display['picture_url'];


        $device = $this->tienda_model->get_device($id_dev);

        $data['id_device'] = $device['id_device'];
        $data['device'] = $device['device'];
        $data['picture_url_dev'] = $device['picture_url'];
        $data['device_'] = $device;

        $data['id_pds_url'] = $id_pds;
        $data['id_dis_url'] = $id_dis;
        $data['id_dev_url'] = $id_dev;

        $data['title'] = 'Alta incidencia';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/alta_incidencia_device', $data);
        $this->load->view('backend/footer');
    }


    public function insert_incidencia()
    {

        $id_pds = $this->uri->segment(3);
        $denuncia = $this->uri->segment(4);
        $id_dis = $this->uri->segment(5);
        $id_dev = $this->uri->segment(6);

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        /*
        if ($this->form_validation->run() == true)
        {
            */
        if ($denuncia == 'no-robo') {
            $denuncia = '';
        };

        if ($this->input->post('tipo_averia') == 1) {
            $tipo_averia = 'Robo';
        } else {
            $tipo_averia = 'Avería';
        }

        $description_1 = $this->input->post('description_1');
        $description_1 = $this->strip_html_tags($description_1);        
        
        $data = array(
            'fecha' => date('Y-m-d H:i:s'),
            'id_pds' => $id_pds,
            'id_displays_pds' => $id_dis,
            'id_devices_pds' => $id_dev,
            'tipo_averia' => $this->input->post('tipo_averia'),
            'alarm_display' => $this->input->post('alarm_display'),
            'alarm_device' => $this->input->post('alarm_device'),
            'alarm_garra' => $this->input->post('alarm_garra'),
            'description_1' => $description_1,
            'description_2' => '',
        	'description_3' => '',
            'parte_pdf' => '',
            'denuncia' => $denuncia,
            'foto_url' => '',
            'foto_url_2' => '',
            'foto_url_3' => '',
            'contacto' => $this->input->post('contacto'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email'),
            'id_operador' => NULL,
            'intervencion' => NULL,
            'status_pds' => 1,
            'status' => 1,
        );

        //}

        $incidencia = $this->tienda_model->insert_incidencia($data);


        //if ($this->tienda_model->insert_incidencia($data))
        if ($incidencia['add']) {

            $pds = $this->tienda_model->get_pds($id_pds);


            $message_admin = 'Se ha registrado una nueva incidencia.' . "\r\n\r\n";
            $message_admin .= 'La tienda con SFID ' . $pds['reference'] . ' ha creado una incidencia. En http://demoreal.focusonemotions.com/ podrás revisar la misma.' . "\r\n\r\n";
            $message_admin .= 'Atentamente,' . "\r\n\r\n";
            $message_admin .= 'Demo Real' . "\r\n";
            $message_admin .= 'http://demoreal.focusonemotions.com/' . "\r\n";

            $this->email->from('no-reply@altabox.net', 'Demo Real');
            $this->email->to('gzapico@altabox.net');
            //$this->email->cc('gzapico@altabox.net');
            //$this->email->bcc('gzapico@altabox.net');
            //$this->email->subject('Demo Real - Registro de incidencia #'.$incidencia['id']);
            $this->email->subject('Demo Real - Registro de incidencia');
            $this->email->message($message_admin);
            $this->email->send();

            $this->email->clear();


            $message_pds = 'Se ha registrado una nueva incidencia.' . "\r\n\r\n";
            $message_pds .= 'En breve recibirá más información de la evolución de la misma.' . "\r\n\r\n";
            $message_pds .= 'Atentamente,' . "\r\n\r\n";
            $message_pds .= 'Demo Real' . "\r\n";
            $message_pds .= 'http://demoreal.focusonemotions.com/' . "\r\n";

            $this->email->from('no-reply@altabox.net', 'Demo Real');
            //$this->email->to(strtolower($this->input->post('email')));
            $this->email->to('gzapico@altabox.net');
            //$this->email->cc('gzapico@altabox.net');
            //$this->email->bcc('gzapico@altabox.net');
            //$this->email->subject('Demo Real - Registro de incidencia #'.$incidencia['id']);
            $this->email->subject('Demo Real - Registro de incidencia');
            $this->email->message($message_pds);
            $this->email->send();

            redirect('admin/alta_incidencia_gracias');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
        }

    }

    public function alta_incidencia_gracias()
    {
        $xcrud = xcrud_get_instance();

        if ($this->session->userdata('type') == 1) {
            $this->load->model('tienda_model');

            $sfid = $this->session->userdata('sfid');
            $id_pds = $this->tienda_model->get_id($sfid);
            $data['id_pds_url'] = $id_pds['id_pds'];
            $data['id_pds'] = $id_pds['id_pds'];

            $sfid = $this->tienda_model->get_pds($id_pds['id_pds']);
            $data['commercial'] = $sfid['commercial'];
            $data['territory'] = $sfid['territory'];
            $data['reference'] = $sfid['reference'];
            $data['address'] = $sfid['address'];
            $data['zip'] = $sfid['zip'];
            $data['city'] = $sfid['city'];
        }

        $data['title'] = 'Alta incidencia';
        $data['content'] = 'Muchas gracias.';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function auditorias()
    {
        $xcrud = xcrud_get_instance();

        $data['title'] = 'Auditorías';
        $data['content'] = 'En construcción.';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function facturacion()
    {
    	if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
    		$data['id_pds'] = $this->session->userdata('id_pds');
    		$data['sfid'] = $this->session->userdata('sfid');
    
    		$xcrud = xcrud_get_instance();
    		$this->load->model(array('tienda_model','sfid_model'));
    
    		$fecha_inicio = $this->input->post('fecha_inicio');
    		$fecha_fin    = $this->input->post('fecha_fin');
    		
    		$data['facturacion'] = $this->tienda_model->facturacion_estado($fecha_inicio,$fecha_fin);
    		
    		$data['fecha_inicio'] = $fecha_inicio;
    		$data['fecha_fin']   = $fecha_fin;
    		
    		$data['title'] = 'Facturación';
    
    		$this->load->view('backend/header', $data);
    		$this->load->view('backend/navbar', $data);
    		$this->load->view('backend/facturacion', $data);
    		$this->load->view('backend/footer');
    	} else {
    		redirect('admin', 'refresh');
    	}
    }
        
    
    public function facturacion_csv()
    {
    	if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 10)) {
    		$data['id_pds'] = $this->session->userdata('id_pds');
    		$data['sfid'] = $this->session->userdata('sfid');
    		
    		$fecha_inicio = $this->uri->segment(3);
    		$fecha_fin    = $this->uri->segment(4);
    		
    		$xcrud = xcrud_get_instance();
    		$this->load->model(array('tienda_model','sfid_model'));
  
       		$data['facturacion_csv'] = $this->tienda_model->facturacion_estado_csv($fecha_inicio,$fecha_fin);

    	} else {
    		redirect('admin', 'refresh');
    	}
    }    

    public function operaciones()
    {
        $xcrud = xcrud_get_instance();

        $data['title'] = 'Operaciones';
        $data['content'] = 'En construcción.';

        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function ayuda($tipo)
    {
        if ($this->session->userdata('logged_in')) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();

            switch ($tipo) {
                case 1:
                    $data['video'] = "ver_incidencias.mp4";
                    $data['ayuda_title'] = "Mis solicitudes";
                    break;
                case 2:
                    $data['video'] = "nueva_averia.mp4";
                    $data['ayuda_title'] = "Alta incidencia";
                    break;
                case 3:
                    $data['video'] = "nueva_incidencia_mueble.mp4";
                    $data['ayuda_title'] = "Alta incidencia sistema seguridad general del mueble";
                    break;
                case 4:
                    $data['video'] = "nuevo_robo.mp4";
                    $data['ayuda_title'] = "Incidencias frecuentes";
                    break;
                default:
                    $data['video'] = "ver_incidencias.mp4";
                    $data['ayuda_title'] = "Mis solicitudes";
            }

            $data['title'] = 'Ayuda';

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/ayuda', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function logout()
    {
        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('logged_in');
        }
        redirect('admin', 'refresh');
    }

}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */