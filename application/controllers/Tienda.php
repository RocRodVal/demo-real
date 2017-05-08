<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tienda extends MY_Controller {

	function __construct()
	{
        parent::__construct();

        // Ctrl configuration
        $this->setController('tienda');
        $this->setViewsFolder('tienda');
        $this->setEnvironmentProfile('tienda');
        $this->setHomeAction('estado_incidencias/abiertas');

        // Load Auth and check Entorno
        $this->setUserType(1);
        $this->load->library('auth',array(1));
        $this->auth->check_entorno('tienda');
    }

    /**
     * Callback para el login
     * @param int $type
     */
    public function do_login()
    {
	    return parent::do_login();
    }

    /**
     * Tabla de incidencias cuyo tipo son "abiertas" o "cerradas"
     * (Antiguo dashboard)
     */
    public function estado_incidencias($tipo)
    {
        //if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 1)) {
        if($this->auth->is_auth())
        {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();

            $this->load->model(array('intervencion_model', 'incidencia_model', 'tienda_model', 'sfid_model','chat_model'));
            $this->load->library('app/paginationlib');

            $sfid = $this->sfid_model->get_pds($data['id_pds']);

            $data['id_pds']     = $sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory']  = $sfid['territory'];
            $data['reference']  = $sfid['reference'];
            $data['address']    = $sfid['address'];
            $data['zip']        = $sfid['zip'];
            $data['city']       = $sfid['city'];



            // Comprobar si existe el segmento PAGE en la URI, si no inicializar a 1..
            $get_page = $this->uri->segment(5);
            if( $this->uri->segment(4) == "page") {
                $page = ( ! empty($get_page) ) ? $get_page : 1 ;
                $segment = 5;
            }else{
                $page = 1;
                $segment = null;
            }

            /**
             * Crear los filtros
             */
            $array_filtros = array(
                'status_pds'=>'',
                'brand_device'=>'',
                'id_display'=>'',
                'id_device'=>'',
                'id_incidencia'=>'',
                'reference'=> $data['sfid']
            );

            /* BORRAR BUSQUEDA */
            $borrar_busqueda = $this->uri->segment(4);
            if($borrar_busqueda === "borrar_busqueda")
            {
                $this->delete_filtros($array_filtros);
                redirect(site_url("/tienda/estado_incidencias/".$tipo),'refresh');
            }
            // Consultar a la session si ya se ha buscado algo y guardado allí.
            $array_sesion = $this->get_filtros($array_filtros);
            // Buscar en el POST si hay busqueda, y si la hay usarla y guardarla además en sesion

            if($this->input->post('do_busqueda')==="si") $array_sesion = $this->set_filtros($array_filtros);

            /* Creamos al vuelo las variables que vienen de los filtros */
            foreach($array_filtros as $filtro=>$value){
                $$filtro = $array_sesion[$filtro];
                $data[$filtro] = $array_sesion[$filtro]; // Pasamos los valores a la vista.
            }


            // viene del form de ordenacion
            $do_orden = $this->input->post('ordenar');
            if($do_orden==='true') {
                $array_orden = $this->set_orden($this->input->post('form'));
            }

            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();
            if(count($array_orden) > 0) {
                foreach ($array_orden as $key => $value) {
                    $data["campo_orden"] = $key;
                    $data["orden_campo"] = $value;
                }
            }else{
                $data["campo_orden"] = NULL;
                $data["orden_campo"] = NULL;
            }


            if($tipo==="abiertas")
            {
                $data['title'] = 'Incidencias abiertas';
            }
            else
            {
                $data['title'] = 'Incidencias cerradas';
            }

            $per_page = 100;
            $total_incidencias = $this->incidencia_model->get_estado_incidencias_quantity($array_sesion,$tipo);   // Sacar el total de incidencias, para el paginador
            $cfg_pagination = $this->paginationlib->init_pagination("tienda/estado_incidencias/$tipo/page/",$total_incidencias,$per_page,$segment);


            $this->load->library('pagination',$cfg_pagination);
            $this->pagination->initialize($cfg_pagination);

            $bounds = $this->paginationlib->get_bounds($total_incidencias,$page,$per_page);

            // Indicamos si habrá que mostrar el paginador en la vista
            $data['show_paginator'] = $bounds["show_paginator"];
            $data['num_resultados'] = $bounds["num_resultados"];
            $data['n_inicial'] = $bounds["n_inicial"];
            $data['n_final'] = $bounds["n_final"];
            $data["pagination_helper"]   = $this->pagination;
            $arr_agentes_excluidos=array(1,9,11,12);

            $incidencias = $this->incidencia_model->get_estado_incidencias($page,$cfg_pagination,$array_orden,$array_sesion,$tipo,$arr_agentes_excluidos);

            foreach ($incidencias as $incidencia) {
               // $incidencia->device = $this->sfid_model->get_device($incidencia->id_devices_pds);
                //$incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
               // $incidencia->nuevos  = $this->chat_model->contar_nuevos($incidencia->id_incidencia,$incidencia->reference);
                $incidencia->intervencion = $this->intervencion_model->get_intervencion_incidencia($incidencia->id_incidencia);
            }

            $data['incidencias'] = $incidencias;

            /* LISTADO DE TERRITORIOS PARA EL SELECT */
            $data["territorios"] = $this->tienda_model->get_territorios();
            /* LISTADO DE FABRICANTES PARA EL SELECT */
            $data["fabricantes"] = $this->tienda_model->get_fabricantes();
            /* LISTADO DE MUEBLES PARA EL SELECT */
            $data["muebles"] = $this->tienda_model->get_muebles();
            /* LISTADO DE TERMINALES PARA EL SELECT */
            $data["terminales"] = $this->tienda_model->get_terminales();

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('tienda/header', $data);
            $this->load->view('tienda/navbar', $data);
            $this->load->view('tienda/estado_incidencias/'.$tipo, $data);
            $this->load->view('tienda/footer');
        } else {
            redirect('tienda', 'refresh');
        }
    }

    public function exportar_incidencias($tipo="abiertas",$formato=NULL)
    {
        //if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 1)) {
        if($this->auth->is_auth())
        {
            $xcrud = xcrud_get_instance();
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');



            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model'));
            $tipo = $this->uri->segment(3); // TIPO DE INCIDENCIA

            // Filtros
            $array_filtros = array(
                'status_pds'=>'',
                'brand_device'=>'',
                'id_display'=>'',
                'id_device'=>'',
                'id_incidencia'=>'',
                'reference'=> $data['sfid']
            );
            $array_sesion = $this->get_filtros($array_filtros);


            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();

            $this->incidencia_model->exportar_incidencias($array_orden, $array_sesion, $tipo,$formato);


        } else {
            redirect('tienda', 'refresh');
        }
    }
	
	public function alta_incidencia()
	{
		//f($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

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


			//print_r($this->session->userdata('id_pds')); exit;

			$displays = $this->sfid_model->get_displays_pds($data['id_pds']);

			foreach($displays as $key=>$display) 
			{
				$num_devices = $this->sfid_model->count_devices_displays_pds($display->id_displays_pds);
				$display->devices_count = $num_devices;
			}
			
			$data['displays'] = $displays;

			$data['title'] = 'Alta incidencia';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

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
				redirect('tienda','refresh');
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
				$data['description_3']   = $incidencia['description_3'];
				$data['denuncia']        = $incidencia['denuncia'];
				$data['contacto']        = $incidencia['contacto'];
				$data['phone']           = $incidencia['phone'];
				$data['status_pds']      = $incidencia['status_pds'];
				
				$display = $this->sfid_model->get_display($incidencia['id_displays_pds']);

                if(!empty($display)) {
                    $data['display'] = $display['display'];
                    $data['picture_url_dis'] = $display['picture_url'];

                }else{
                    $data['display'] = NULL;
                }

				$device = $this->sfid_model->get_device($incidencia['id_devices_pds']);

                if(!empty($device)) {
                    $data['device'] = $device['device'];
                    $data['brand_name'] = $device['brand_name'];
                    $data['IMEI'] = $device['IMEI'];
                    $data['mac'] = $device['mac'];
                    $data['serial'] = $device['serial'];
                    $data['barcode'] = $device['barcode'];
                    $data['description'] = $device['description'];
                    $data['owner'] = $device['owner'];
                    $data['picture_url_dev'] = $device['picture_url'];

                }else{
                    $data['device'] = NULL;
                }
				$chats = $this->chat_model->get_chat_incidencia_pds($incidencia['id_incidencia']);
				$leido = $this->chat_model->marcar_leido($incidencia['id_incidencia'],'altabox');
				$data['chats'] = $chats;
				
				$data['title'] = 'Estado de incidencia Ref. '.$id_incidencia;

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
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

    public function insert_chat($id,$tabla='incidencia')
    {
       //if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('chat_model', 'sfid_model'));

            $config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"]) . '/uploads/chats/';
            $config['upload_url'] = base_url() . '/uploads/chats/';
            $config['allowed_types'] = 'gif|jpg|png';
            $new_name = $id . '-' . time();
            $config['file_name'] = $new_name;
            $config['overwrite'] = TRUE;
            $config['max_size'] = '10000KB';

            $this->load->library('upload', $config);

            $foto = NULL;
            if ($this->upload->do_upload()) {
                $dataF=$this->upload->data();
                $foto = $new_name.$dataF["file_ext"];
            } else {
                echo 'Ha fallado la carga de la foto.';
            }

            $texto_chat = $this->input->post('texto_chat');
            $texto_chat = $this->strip_html_tags($texto_chat);
            if($tabla=='incidencia') {
                if ($foto != '' || $texto_chat != '' && $texto_chat != ' ') {
                    $data = array(
                        'fecha' => date('Y-m-d H:i:s'),
                        'id_incidencia' => $id,
                        'agent' => $data['sfid'],
                        'texto' => $texto_chat,
                        'foto' => $foto,
                        'status' => 1,
                    );
                }
            }
            else {
                if ($foto != '' || $texto_chat != '' && $texto_chat != ' ') {
                    $data = array(
                        'fecha' => date('Y-m-d H:i:s'),
                        'id_pedido' => $id,
                        'agent' => $data['sfid'],
                        'texto' => $texto_chat,
                        'foto' => $foto,
                        'status' => 1,
                    );
                }
            }
            //print_r($data); exit;
            $chat = $this->chat_model->insert_chat($data,$tabla);

            if ($chat['add']) {
                redirect('tienda/detalle_'.$tabla.'/' . $id.'/'.$data['id_pds']);
            }

            else{
                redirect('tienda/detalle_'.$tabla.'/' . $id.'/'.$data['id_pds']);
            }
        }
        else
        {
            redirect('tienda','refresh');
        }
    }

	public function insert_incidencia()
	{
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');

            $form_tipo_averia = $this->input->post('tipo_averia');
            $denuncia = NULL;

            if($form_tipo_averia==1) {
                $config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"]) . '/uploads/';
                $config['upload_url'] = base_url() . '/uploads/';
                $config['allowed_types'] = 'doc|docx|pdf|jpg|png';
                $new_name = $data['sfid'] . '-' . time();
                $config['file_name'] = $new_name;
                $config['overwrite'] = TRUE;
                $config['max_size'] = '10000KB';

                $this->load->library('upload', $config);

                $denuncia = '';

                if ($this->upload->do_upload()) {
                    $denuncia = $new_name;
                } else {
                    echo 'Ha fallado la carga de la denuncia.';
                }
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

            $ahora = date("Y-m-d H:i:s");

			$data = array(
					'fecha'    	        => date('Y-m-d H:i:s'),
					'fecha_cierre'    	=> NULL,
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
					'description_3'  	=> '',
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
                    'last_updated' => $ahora
			);
				
			$incidencia = $this->sfid_model->insert_incidencia($data);
				
			if ($incidencia['add'])
			{
                // Envío de mail sólo en produccion
			    if(ENVIRONMENT === 'production') {

                    $pds = $this->sfid_model->get_pds($data['id_pds']);

                    $message_admin = 'Se ha registrado una nueva incidencia.' . "\r\n\r\n";
                    $message_admin .= 'La tienda con SFID ' . $pds['reference'] . ' ha creado una incidencia con ref. ' . $incidencia['id'] . '.' . "\r\n\r\n";
                    $message_admin .= 'En http://demoreal.focusonemotions.com/ podrás revisar la misma.' . "\r\n\r\n";
                    $message_admin .= 'Atentamente,' . "\r\n\r\n";
                    $message_admin .= 'Demo Real' . "\r\n";
                    $message_admin .= 'http://demoreal.focusonemotions.com/' . "\r\n";

                    $this->email->from('no-reply@altabox.net', 'Demo Real');
                    $this->email->to('demoreal@focusonemotions.com');
                    $this->email->subject('Demo Real - Registro de incidencia ref. ' . $incidencia['id']);
                    $this->email->message($message_admin);
                    $this->email->send();
                }else{

                }

                /////////////////////////////////////////////////////////////////////////////////
                //                   Comunicación  con Realdooh VU.                            //
                /////////////////////////////////////////////////////////////////////////////////
                        $response = alta_incidencia_realdooh(array(                                             //
                            'drId'=>  $incidencia['id'],                                       //
                            'assetDrId' => $this->uri->segment(3),                             //
                            'userSFID' => $this->session->userdata('sfid')                     //
                        ),array(                                                                    //
                            'user'=>$this->session->userdata('sfid'),                          //
                            'password' => 'demoreal'                                           //
                        ));

			           // print_r($response);
                // </ Fin Comunicación con Realdooh VU > ////////////////////////////////////////

	
				redirect('tienda/alta_incidencia_gracias/'.$incidencia['id']);
			}
			else
			{
				$data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
			}
		}
		else
		{
			redirect('tienda','refresh');
		}	
	}

	public function insert_incidencia_mueble_alarma()
	{
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {
			$data['id_pds'] = $this->session->userdata('id_pds');
			$data['sfid']   = $this->session->userdata('sfid');
		
			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');

            $ahora = date("Y-m-d H:i:s");

			$data = array(
					'fecha'    	        => date('Y-m-d H:i:s'),
					'fecha_cierre'    	=> NULL,
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
					'description_3'  	=> '',
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
                    'last_updated'      => $ahora
			);
				
			$incidencia = $this->sfid_model->insert_incidencia($data);
				
			if ($incidencia['add'])
			{
			    if(ENVIRONMENT === 'production')
			    {
                    $pds = $this->sfid_model->get_pds($data['id_pds']);

                    $message_admin = 'Se ha registrado una nueva incidencia.' . "\r\n\r\n";
                    $message_admin .= 'La tienda con SFID ' . $pds['reference'] . ' ha creado una incidencia con ref. ' . $incidencia['id'] . '.' . "\r\n\r\n";
                    $message_admin .= 'En http://demoreal.focusonemotions.com/ podrás revisar la misma.' . "\r\n\r\n";
                    $message_admin .= 'Atentamente,' . "\r\n\r\n";
                    $message_admin .= 'Demo Real' . "\r\n";
                    $message_admin .= 'http://demoreal.focusonemotions.com/' . "\r\n";

                    $this->email->from('no-reply@altabox.net', 'Demo Real');
                    $this->email->to('demoreal@focusonemotions.com');
                    $this->email->subject('Demo Real - Registro de incidencia ref. ' . $incidencia['id']);
                    $this->email->message($message_admin);
                    $this->email->send();
                }

                /////////////////////////////////////////////////////////////////////////////////
                //                                                                             //
                //                   Comunicación  con Realdooh VU.                            //
                //                                                                             //
                /////////////////////////////////////////////////////////////////////////////////
                //
                $response = alta_incidencia_realdooh(array(                                             //
                    'drId'=>  $incidencia['id'],                                       //
                    'assetDrId' => $this->uri->segment(3),                             //
                    'userSFID' => $this->session->userdata('sfid')                     //
                ),array(                                                                    //
                    'user'=>$this->session->userdata('sfid'),                          //
                    'password' => 'demoreal'                                           //
                ));

                //print_r($response); exit;
                //
                //
                //                                                                             //
                // </ Fin Comunicación con Realdooh VU > ////////////////////////////////////////


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
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {
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
				case 5:
					redirect('tienda/manuales','refresh');
					break;					
				default:
					$data['video']="ver_incidencias.mp4";
					$data['ayuda_title']="Mis solicitudes";
			}

			$data['title']      = 'Ayuda';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
	
	public function manuales()
	{
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {

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
				
			$data['title']       = 'Ayuda';
			$data['ayuda_title'] = 'Manuales';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/navbar',$data);
			$this->load->view('tienda/manuales',$data);
			$this->load->view('tienda/footer');
		}
		else
		{
			redirect('tienda','refresh');
		}
	}	

	public function logout()
	{
        if ($this->auth->is_auth()) {
			$this->session->unset_userdata('logged_in');
            $this->session->sess_destroy();
		}	
		redirect('tienda','refresh');
	}

    public function mantenimiento()
    {

        $data['bg_image'] = "bg-tienda.jpg";
        $data['title'] = 'Parada por mantenimiento';

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('common/mantenimiento', $data);
        $this->load->view('backend/footer');
    }

    /*
     * Las tiendas de tipo SMARTSTORE pueden realizar pedidos de sistemas de seguridad de los objetos conectados
     */
    public function alta_pedido()
    {
        //if($this->session->userdata('logged_in') && $this->session->userdata('hacePedidos'))
        if ($this->auth->is_auth()  && $this->session->userdata('hacePedidos')) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid']   = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model('sfid_model');
            $this->load->model('alarma_model');

            $sfid = $this->sfid_model->get_pds($data['id_pds']);

            $data['id_pds']     = $sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory']  = $sfid['territory'];
            $data['reference']  = $sfid['reference'];
            $data['address']    = $sfid['address'];
            $data['zip']        = $sfid['zip'];
            $data['city']       = $sfid['city'];

            $alarmas = $this->alarma_model->get_alarmas_pedido();

            $data['alarmas'] = $alarmas;
            $data['title'] = 'Alta Pedido';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('tienda/header',$data);
            $this->load->view('tienda/navbar',$data);
            $this->load->view('tienda/alta_pedido',$data);
            $this->load->view('tienda/footer');
        }
        else
        {
            redirect('tienda','refresh');
        }
    }

    /*
     * Insertar el pedido creado por la tienda en la base de datos
     */
    public function insert_pedido()
    {
      //  if($this->session->userdata('logged_in') && $this->session->userdata('hacePedidos'))
        if ($this->auth->is_auth() && $this->session->userdata('hacePedidos'))
        {
            $data['id_pds'] = $this->session->userdata('id_pds');
            //$data['sfid']   = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model('alarma_model');

            //$form_tipo_averia = $this->input->post('tipo_averia');
            $cantidades = $this->input->post('cantidades');


            $ahora = date("Y-m-d H:i:s");

            $data = array(
                'fecha'    	        => $ahora,
                //'fecha_cierre'    	=> NULL,
                'id_pds'            => $data['id_pds'],
                'contacto'  	    => $this->input->post('contacto'),
                'phone'  	        => $this->input->post('phone'),
                'email'  	        => $this->input->post('email'),
                //'status'	        => 1,
                'last_update' => $ahora
            );

            $pedido = $this->alarma_model->insert_pedido($data);

            if ($pedido['add'])
            {
                $data_detalle = array(
                    'id_pedido' => $pedido['id']
                );
                $seguir=false;
                $cantidadesCero=0;
                foreach ($cantidades as $key => $cantidad) {
                    if($cantidad>0) {
                        $data_detalle['id_alarma']=$key;
                        $data_detalle['cantidad']=$cantidad;
                        //print_r($detalle);
                        //Insertamos el detalle del pedido en la tabla pedidos_detalle
                        $detalle=$this->alarma_model->insert_detalle_pedido($data_detalle);
                        if ($detalle['add']) {
                            $seguir=true;
                        }
                        else {$seguir=false;}
                    }
                    else {$cantidadesCero++;}
                }
                if(!$seguir){
                    $this->data->message = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
                    redirect('tienda/alta_pedido',$this->data);

                }else {

                    redirect('tienda/alta_pedido_gracias/' . $pedido['id']);
                }
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

    /*
     * Indicar que el pedido ya ha sido creado y el id del mismo
     */
    public function alta_pedido_gracias()
    {
        if ($this->auth->is_auth() && $this->session->userdata('hacePedidos'))
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

            $data['title']   = 'Alta pedido';
            $data['content'] = 'Muchas gracias. Su pedido ha sido dado de alta con referencia número '.$referencia.'.';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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

    /**
     * Tabla de pedidos segun el tipo
     */
    public function pedidos($tipo="abiertos")
    {
        if ($this->auth->is_auth() && ($this->session->userdata('hacePedidos'))) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();


            $this->load->model(array('pedido_model', 'tienda_model', 'sfid_model','chat_model'));
            $this->load->library('app/paginationlib');

            $sfid = $this->sfid_model->get_pds($data['id_pds']);

            $data['id_pds']     = $sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory']  = $sfid['territory'];
            $data['reference']  = $sfid['reference'];
            $data['address']    = $sfid['address'];
            $data['zip']        = $sfid['zip'];
            $data['city']       = $sfid['city'];



            // Comprobar si existe el segmento PAGE en la URI, si no inicializar a 1..
            $get_page = $this->uri->segment(5);
            if( $this->uri->segment(4) == "page") {
                $page = ( ! empty($get_page) ) ? $get_page : 1 ;
                $segment = 5;
            }else{
                $page = 1;
                $segment = null;
            }

            /**
             * Crear los filtros
             */
            /*   $array_filtros = array(
                   'status_pds'=>'',
                   'brand_device'=>'',
                   'id_display'=>'',
                   'id_device'=>'',
                   'id_incidencia'=>'',
                   'reference'=> $data['sfid']
               );*/

            /* BORRAR BUSQUEDA */
            /* $borrar_busqueda = $this->uri->segment(4);
             if($borrar_busqueda === "borrar_busqueda")
             {
                 $this->delete_filtros($array_filtros);
                 redirect(site_url("/tienda/estado_incidencias/".$tipo),'refresh');
             }
             // Consultar a la session si ya se ha buscado algo y guardado allí.
             $array_sesion = $this->get_filtros($array_filtros);
             // Buscar en el POST si hay busqueda, y si la hay usarla y guardarla además en sesion

             if($this->input->post('do_busqueda')==="si") $array_sesion = $this->set_filtros($array_filtros);

             /* Creamos al vuelo las variables que vienen de los filtros */
            /*   foreach($array_filtros as $filtro=>$value){
                   $$filtro = $array_sesion[$filtro];
                   $data[$filtro] = $array_sesion[$filtro]; // Pasamos los valores a la vista.
               }*/


            // viene del form de ordenacion
            $do_orden = $this->input->post('ordenar');
            if($do_orden==='true') {
                $array_orden = $this->set_orden($this->input->post('form'));
            }

            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();
            if(count($array_orden) > 0) {
                foreach ($array_orden as $key => $value) {
                    $data["campo_orden"] = $key;
                    $data["orden_campo"] = $value;
                }
            }else{
                $data["campo_orden"] = NULL;
                $data["orden_campo"] = NULL;
            }

            if ($tipo=="abiertos") {
                $data['title'] = 'Pedidos abiertos';
                $data['title2'] = 'pedidos abiertos';
            }else {
                $data['title'] = 'Pedidos finalizados';
                $data['title2'] = 'pedidos finalizados';
            }


            $per_page = 20;
            $total_pedidos = $this->pedido_model->get_pedidos_quantity($data['id_pds'],$tipo);   // Sacar el total de pedidos, para el paginador


            $cfg_pagination = $this->paginationlib->init_pagination("tienda/pedidos/".$tipo."/page/",$total_pedidos,$per_page,$segment);


            $this->load->library('pagination',$cfg_pagination);
            $this->pagination->initialize($cfg_pagination);

            $bounds = $this->paginationlib->get_bounds($total_pedidos,$page,$per_page);

            // Indicamos si habrá que mostrar el paginador en la vista
            $data['show_paginator'] = $bounds["show_paginator"];
            $data['num_resultados'] = $bounds["num_resultados"];
            $data['n_inicial'] = $bounds["n_inicial"];
            $data['n_final'] = $bounds["n_final"];
            $data["pagination_helper"]   = $this->pagination;

            $pedidos = $this->pedido_model->get_pedidos($page,$cfg_pagination,$array_orden,$tipo,$data['id_pds']);
            // print_r($pedidos);
            foreach ($pedidos as $pedido) {
                // $pedido->detalle = $this->pedido_model->get_detalle($pedido->id,$data['id_pds']);
                // $incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
                $pedido->nuevos  = $this->chat_model->contar_nuevos($pedido->id,'altabox',"pedidos");
                //$incidencia->intervencion = $this->intervencion_model->get_intervencion_incidencia($incidencia->id_incidencia);
            }

            //print_r($pedidos);exit;

            $data['pedidos'] = $pedidos;
            $data['tipo'] = $tipo;
            //$data['chats'] = $chats;

            /* LISTADO DE TERRITORIOS PARA EL SELECT */
            //$data["territorios"] = $this->tienda_model->get_territorios();
            /* LISTADO DE FABRICANTES PARA EL SELECT */
            //$data["fabricantes"] = $this->tienda_model->get_fabricantes();
            /* LISTADO DE MUEBLES PARA EL SELECT */
            //$data["muebles"] = $this->tienda_model->get_muebles();
            /* LISTADO DE TERMINALES PARA EL SELECT */
            //$data["terminales"] = $this->tienda_model->get_terminales();

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('tienda/header', $data);
            $this->load->view('tienda/navbar', $data);
            $this->load->view('tienda/pedidos', $data);
            $this->load->view('tienda/footer');
        } else {
            redirect('tienda', 'refresh');
        }
    }

    /*
     * Obtener el detalle de un pedido seleccionado
     */
    public function detalle_pedido($id_pedido)
    {
        if ($this->auth->is_auth() && ($this->session->userdata('hacePedidos')))
        {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid']   = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('chat_model','sfid_model','pedido_model'));

            $sfid = $this->sfid_model->get_pds($data['id_pds']);

            $data['id_pds']     = $sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory']  = $sfid['territory'];
            $data['reference']  = $sfid['reference'];
            $data['address']    = $sfid['address'];
            $data['zip']        = $sfid['zip'];
            $data['city']       = $sfid['city'];



            $pedido = $this->pedido_model->get_pedido($id_pedido,$data['id_pds']);

            if($pedido == FALSE)
            {
                redirect('tienda','refresh');
            }
            else
            {
                $data['id_pedido']   = $pedido->id;
                $data['fecha']           = $pedido->fecha;
                $data['id_pds']          = $pedido->id_pds;
                $data['contacto']        = $pedido->contacto;
                $data['phone']           = $pedido->phone;
                $data['email']           = $pedido->email;
                $data['status']      = $pedido->status;

                $data['detalle'] = $this->pedido_model->get_detalle($id_pedido,$data['id_pds']);

                //echo "id_pedido ".$data['id_pedido'];
                //print_r($data['detalle']); exit;
                $chats = $this->chat_model->get_chat_pds($pedido->id,'pedidos');
                $leido = $this->chat_model->marcar_leido($pedido->id,'altabox','pedidos');
                $data['chats'] = $chats;

                $data['title'] = 'Estado del pedido Ref. '.$id_pedido;

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
                $this->load->view('tienda/header',$data);
                $this->load->view('tienda/navbar',$data);
                $this->load->view('tienda/detalle_pedido',$data);
                $this->load->view('tienda/footer');
            }
        }
        else
        {
            redirect('tienda','refresh');
        }
    }

    /*
     * exportar el listado de pedidos
     */
    public function exportar_pedidos($tipo="abiertos")
    {
        if ($this->auth->is_auth() && ($this->session->userdata('hacePedidos'))) {
            $xcrud = xcrud_get_instance();
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $this->load->model(array('pedido_model'));
            // $tipo = $this->uri->segment(3); // TIPO DE pedido

            // Filtros
            $array_filtros = array(
                'status_pds'=>'',
                'brand_device'=>'',
                'id_display'=>'',
                'id_device'=>'',
                'id_incidencia'=>'',
                'reference'=> $data['sfid']
            );
            $array_sesion = $this->get_filtros($array_filtros);


            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();

            $this->pedido_model->exportar_pedidos( $data['id_pds'],$array_orden, $array_sesion, $tipo);


        } else {
            redirect('tienda', 'refresh');
        }
    }
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */