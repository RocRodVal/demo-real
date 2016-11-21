<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('email','text','xcrud'));
		$this->load->library(array('email','encrypt','form_validation','session'));
        $this->load->library('uri');

        $this->load->library('auth',array(9));

        // Carga de la clase de Colección de datos, para pasar variables a la vista.
        $this->load->library('data');
        $this->data->set("controlador","master");
        $this->data->set("acceso","master");
        $this->data->set("accion_home","estado_incidencias/abiertas");
        $this->data->set("entrada",($this->data->get("controlador") . '/' . $this->data->get("accion_home")));

        $this->load->config('files');
        $this->cfg = $this->config->config;
        $this->export = config_item("export");
        $this->ext = $this->export["default_ext"];




    }
		
	
	public function index()
	{
		$xcrud = xcrud_get_instance();
		$this->load->model('user_model');
	
		$this->form_validation->set_rules('sfid-login','SFID','required|xss_clean');
		$this->form_validation->set_rules('password','password','required|xss_clean');


        $entrada = $this->data->get("entrada");

        // Ya está logueado....
        if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9)) redirect($entrada);

	
		if ($this->form_validation->run() == true)
		{
			$data = array(
					'sfid' 	   => strtolower($this->input->post('sfid-login')),
					'password' => $this->input->post('password'),
			);
		}
	
		if ($this->form_validation->run() == true)
		{
            $this->form_validation->set_rules('sfid-login','SFID','callback_do_login');

            if($this->form_validation->run() == true){
                redirect($entrada);
            }else{
                $data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
            }

		}

			$data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
	
			$data['title'] = 'Login';
            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
			$this->load->view('master/header',$data);
			$this->load->view('master/login',$data);
			$this->load->view('master/footer');

	}


    public function do_login(){

        $this->load->model('user_model');
        $data = array(
            'sfid' => strtolower($this->input->post('sfid-login')),
            'password' => sha1($this->input->post('password')),
        );
        if($this->user_model->login_master($data)){
            return true;
        }else{
            // Redirigir al entorno adecuado al usuario logueado...
            $entorno =$this->user_model->login_entorno($data);
            if($entorno != FALSE) redirect($entorno,"refresh");

            $this->form_validation->set_message('do_login','"Username" or "password" are incorrect.');
            return false;
        }
    }


    /**
     *  Método que inicializa la paginación y devuelve el array de configuración de la misma
     *
     * @param $uri          URL Base que contendrá la paginación
     * @param $total_rows   Total de filas a paginar
     * @param int $per_page Numero de filas por página
     * @param int $segment  Segmento de la URI que corresponderá al nº de página
     * @return $config array con la configuración del paginador
     */
    /* public function init_pagination($uri,$total_rows,$per_page=10,$segment=4){

         $ci                          =& get_instance();
         $config['per_page']          = $per_page;
         $config['uri_segment']       = $segment;
         $config['base_url']          = base_url().$uri;
         $config['total_rows']        = $total_rows;
         $config['use_page_numbers']  = TRUE;

         $ci->pagination->initialize($config);
         return $config;
     }*/

    /**
     * Función que guarda en sesión el valor de los filtros del POST, al venir de un form de filtrado
     * @param $array_filtros
     */
    public function set_filtros($array_filtros){
        $array_valores = NULL;
        if(is_array($array_filtros))
        {
            $array_valores = array();
            foreach ($array_filtros as $filter=>$value)
            {
                if(empty($value)) {
                    $valor_filter = $this->input->post($filter);
                }else{
                    $valor_filter  = $value;
                }
                $this->session->set_userdata($filter, $valor_filter);
                $array_valores[$filter] = $valor_filter;
            }

        }
        return $array_valores;
    }


    /**
     * Método que borra de la sesión, X variables, pasado sus nombres en un array
     * Si el parámetro es un array (de variables), lo recorremos y eliminamos de la sesión cualquier valor que tenga
     * la variable de sesión de ese nombre
     */
    public function delete_filtros($array_filtros,$array_excepciones=array()){
        /*if(is_array($array_filtros)){
            foreach($array_filtros as $filtro){
                if(!in_array($filtro,$array_excepciones)) {
                    $this->session->unset_userdata($filtro);
                }
            }
        }*/
        if(is_array($array_filtros)){
            foreach($array_filtros as $key =>$filtro){
                if(!in_array($key,$array_excepciones)) {
                    $this->session->unset_userdata($key);
                }
            }
        }
    }

    /**
     * Recibe el array de filtros (campos del buscador/filtrador) y buscará su valor en la sesión, y cargará otro array
     * con los pares VARIABLE=>VALOR SESION.
     * @param $array_filtros
     * @return array|null
     */
    public function get_filtros($array_filtros){
        $array_session = NULL;

        if(is_array($array_filtros)){
            $array_session = array();
            foreach($array_filtros as $filter=>$value){

                if(!empty($value)){
                    $sess_filter = $value;
                }else {
                    $sess_filter = $this->session->userdata($filter);
                }
                $array_session[$filter] = (!empty($sess_filter)) ? $sess_filter : NULL;

            }
        }
        return $array_session;
    }

    public function set_orden($formulario)
    {

        $array_orden = array();
        $campo_orden = $this->input->post($formulario . '_campo_orden');
        $orden_campo = $this->input->post($formulario . '_orden_campo');

        $this->session->set_userdata('campo_orden', $campo_orden);
        $this->session->set_userdata('orden_campo', $orden_campo);

        $array_orden[$campo_orden]= $orden_campo;
//print_r($array_orden);
        return $array_orden;

    }


    public function get_orden()
    {
        $sess_campo_orden = $this->session->userdata('campo_orden');
        $sess_orden_campo = $this->session->userdata('orden_campo');
        $array_orden = NULL;
        if(!empty($sess_campo_orden)){
            $array_orden = array();
            if(!empty($sess_orden_campo)){
                $array_orden[$sess_campo_orden] = $sess_orden_campo;
            }else{
                $array_orden[$sess_campo_orden] = "ASC";
            }
        }
        return $array_orden;
    }

    /**
     * Tabla de incidencias cuyo tipo son "abiertas" o "cerradas"
     * (Antiguo dashboard)
     */
    public function estado_incidencias($tipo)
    {

        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();


            $this->load->model(array('intervencion_model', 'incidencia_model', 'tienda_model', 'sfid_model','chat_model','categoria_model'));
            $this->load->library('app/paginationlib');

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
                'status' => '',
                'status_pds' => '',
                'territory' => '',
                'brand_device' => '',
                'id_display' => '',
                'id_device' => '',
                'id_supervisor' => '',
                'id_provincia' => '',

                'id_incidencia' => '',
                'reference' => '',

                'id_tipo'=>'',
                'id_subtipo'=>'',
                'id_segmento'=>'',
                'id_tipologia'=>'',
                'id_tipo_incidencia'=>''
            );

            /* BORRAR BUSQUEDA */
            $borrar_busqueda = $this->uri->segment(4);
            if($borrar_busqueda === "borrar_busqueda")
            {
                $this->delete_filtros($array_filtros);
                redirect(site_url("/master/estado_incidencias/".$tipo),'refresh');
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
            //$do_orden = $this->input->post('ordenar');
            //if($do_orden==='true') {
                $array_orden = $this->set_orden($this->input->post('form'));
            //}

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
            $cfg_pagination = $this->paginationlib->init_pagination("master/estado_incidencias/$tipo/page/",$total_incidencias,$per_page,$segment);


            $this->load->library('pagination',$cfg_pagination);
            $this->pagination->initialize($cfg_pagination);

            $bounds = $this->paginationlib->get_bounds($total_incidencias,$page,$per_page);

            // Indicamos si habrá que mostrar el paginador en la vista
            $data['show_paginator'] = $bounds["show_paginator"];
            $data['num_resultados'] = $bounds["num_resultados"];
            $data['n_inicial'] = $bounds["n_inicial"];
            $data['n_final'] = $bounds["n_final"];
            $data["pagination_helper"]   = $this->pagination;

            $incidencias = $this->incidencia_model->get_estado_incidencias($page,$cfg_pagination,$array_orden,$array_sesion,$tipo);

            foreach ($incidencias as $incidencia) {
               // $incidencia->device = $this->sfid_model->get_device($incidencia->id_devices_pds);
                //$incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
                //$incidencia->nuevos  = $this->chat_model->contar_nuevos($incidencia->id_incidencia,$incidencia->reference);
                $incidencia->intervencion = $this->intervencion_model->get_intervencion_incidencia($incidencia->id_incidencia);
            }

            $data['incidencias'] = $incidencias;

            /* LISTADO DE TERRITORIOS PARA EL SELECT */
            $data["territorios"] = $this->tienda_model->get_territorios();
            /* LISTADO DE FABRICANTES PARA EL SELECT */
            $data["fabricantes"] = $this->tienda_model->get_fabricantes();
            /* LISTADO DE MUEBLES PARA EL SELECT */
            $data["muebles"] = $this->tienda_model->get_displays_demoreal();
            /* LISTADO DE TERMINALES PARA EL SELECT */
            $data["terminales"] = $this->tienda_model->get_terminales();

            $data["supervisores"] = $this->tienda_model->get_supervisores();
            $data["provincias"] = $this->tienda_model->get_provincias();

            /* SELECTORES CATEGORIA PDS */
            $data["tipos"] = $this->categoria_model->get_tipos_pds();
            $data["subtipos"] = $this->categoria_model->get_subtipos_pds();
            $data["segmentos"] = $this->categoria_model->get_segmentos_pds();
            $data["tipologias"] = $this->categoria_model->get_tipologias_pds();

            /* SELECTOR DEL TIPO DE INCIDENCIA*/
            $data['tipos_incidencia'] = $this->tienda_model->get_tipos_incidencia();

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header', $data);
            $this->load->view('master/navbar', $data);
            $this->load->view('master/estado_incidencias/'.$tipo, $data);
            $this->load->view('master/footer');
        } else {
            redirect('master', 'refresh');
        }
    }



    public function exportar_incidencias($tipo="abiertas",$formato=NULL,$portipo=NULL)
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
            $xcrud = xcrud_get_instance();

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG

            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model'));

            // Filtros
            $array_filtros = array(
                'status'=>'',
                'status_pds'=>'',
                'territory'=>'',
                'brand_device'=>'',
                'id_display' => '',
                'id_device' => '',

                'id_supervisor' => '',
                'id_provincia' => '',

                'id_incidencia'=>'',
                'reference'=> '',

                'id_tipo'=>'',
                'id_subtipo'=>'',
                'id_segmento'=>'',
                'id_tipologia'=>'',
                'id_tipo_incidencia'=>''
            );
            $array_sesion = $this->get_filtros($array_filtros);

            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();


            $this->incidencia_model->exportar_incidencias($array_orden, $array_sesion, $tipo,$ext,$portipo);



        } else {
            redirect('master', 'refresh');
        }
    }



    public function detalle_incidencia($id_incidencia,$id_pds)
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
			$this->load->model('sfid_model');
			$this->load->model(array('chat_model','sfid_model'));





			$sfid = $this->sfid_model->get_pds($id_pds);
	
			$data['id_pds']     = $sfid['id_pds'];
			$data['commercial'] = $sfid['commercial'];
			$data['territory']  = $sfid['territory'];
			$data['reference']  = $sfid['reference'];
			$data['address']    = $sfid['address'];
			$data['zip']        = $sfid['zip'];
			$data['city']       = $sfid['city'];




			$incidencia = $this->sfid_model->get_incidencia($id_incidencia,$id_pds);


			if($incidencia == FALSE)
			{
				redirect('master','refresh');
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



                if(!empty($display)) {
                    $data['id_display']      = $display['id_display'];
                    $data['display']         = $display['display'];
                    $data['picture_url_dis'] = $display['picture_url'];
                }else{
                    $data['display'] = NULL;
                    $data['picture_url_dis']  = NULL;
                }


					
				$device = $this->sfid_model->get_device($incidencia['id_devices_pds']);

                if(!empty($device)) {
                    $data['id_device'] = $device['id_device'];
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
                    $data["device"] = NULL;
                }
				$chats = $this->chat_model->get_chat_incidencia_pds($incidencia['id_incidencia']);
				$data['chats'] = $chats;
		
				$data['title'] = 'Estado de incidencia Ref. '.$id_incidencia.' [SFID-'.$data['reference'].']';

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
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
			$xcrud->label('client_pds','Cliente')->label('reference','SFID')->label('type_pds','Tipo')->label('territory','Territorio')->label('panelado_pds','Panelado Orange')->label('dispo','Disposición')->label('commercial','Nombre comercial')->label('cif','CIF')->label('picture_url','Foto')->label('m2_fo','M2 front-office')->label('m2_bo','M2 back-office')->label('m2_total','M2 total')->label('type_via','Tipo vía')->label('address','Dirección')->label('zip','C.P.')->label('city','Ciudad')->label('province','Provincia')->label('county','CC.AA.')->label('schedule','Horario')->label('phone','Teléfono')->label('mobile','Móvil')->label('email','Email')->label('contact_contact_person','Contacto')->label('contact_in_charge','Encargado')->label('contact_supervisor','Supervisor')->label('status','Estado');
			$xcrud->columns('client_pds,reference,type_pds,panelado_pds,commercial,territory,status');
			$xcrud->fields('client_pds,reference,type_pds,panelado_pds,dispo,commercial,cif,territory,picture_url,m2_fo,m2_bo,m2_total,type_via,address,zip,city,province,county,schedule,phone,mobile,email,contact_contact_person,contact_in_charge,contact_supervisor,status');
		
			$xcrud->show_primary_ai_column(false);
			$xcrud->unset_add();
			$xcrud->unset_edit();
			$xcrud->unset_remove();
			$xcrud->unset_numbers();		
			
			$data['title']   = 'Puntos de venta';
			$data['content'] = $xcrud->render();


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

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
					territory.territory AS Territorio,
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/content',$data);
			$this->load->view('master/footer');
		} else {
			redirect('master', 'refresh');
		}
	}

    public function cdm_incidencias($anio = NULL)
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $this->load->helper("common");
            $this->load->model(array("incidencia_model","informe_model","tablona_model"));
            //$this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model','incidencia_model'));


            $b_filtrar_tipo = $this->input->post("filtrar_tipo");
            $tipo_tienda = '';
            $estado_incidencia = '';

            if($b_filtrar_tipo === "si"){
                $tipo_tienda = $this->input->post("tipo_tienda");
                $estado_incidencia  = $this->input->post("estado_incidencia");
            }

            $este_anio = (is_null($anio)) ? date("Y") : $anio;
            $data['anio'] = $este_anio;

            setlocale(LC_ALL, 'es_ES');

            $data["tipo_tienda"] = $tipo_tienda;
            $data["estado_incidencia"] = $estado_incidencia;
            // Saco los tipos de tienda, pero sólo aquellos cuyos PDS tienen algun tipo de incidencia.
            $data["estados_incidencia"] = $this->incidencia_model->get_estados_incidencia();


            $xcrud_1 = xcrud_get_instance();
            $xcrud_1->table_name('Incidencias');


            $s_where = $s_where_incidencia= '';
            if(!empty($tipo_tienda)){
                $s_where = " AND pds.type_pds = ".$tipo_tienda;
            }
            if(!empty($estado_incidencia)){
                $s_where_incidencia .= " AND incidencias.status_pds LIKE '".$estado_incidencia."'";
            }

            $ctrl_no_cancelada = " AND (status_pds != 'Cancelada' && status != 'Cancelada') "; // Condición where de contrl de incidencias NO CANCELADAS

            /*
             * Por si alguna de las incidencias Finalizadas no tienen puesta fecha de cierre hacemos una busqueda en la tabla historico y actualizamos la de incidencias
             */
          //  $this->informe_model->actualizar_fechacierre_historico($este_anio);

            /**
             * Primer bloque de la tabla, Totales incidencias, dias operativos y media
             */

            // Rango de meses que mostrarán las columnas de la tabla, basándome en el mínimo y máximo mes que hay incidencias, este año.
            $rango_meses = $this->informe_model->get_rango_meses($este_anio);
            $primer_mes = $rango_meses->min;
            $meses_columna = $this->informe_model->get_meses_columna($rango_meses->min,$rango_meses->max);
            $data["primer_mes"] = $primer_mes;
            $data["meses_columna"] = $meses_columna;



            // Sacamos la primera línea. Total incidencias
            $resultados_1 = $this->informe_model->get_cmd_incidencias_totales($este_anio,$ctrl_no_cancelada);
            $total_incidencias_total = $this->informe_model->get_total_cdm_incidencias($resultados_1);
            $valor_resultados_1 = $this->informe_model->get_array_incidencias_totales($resultados_1);


            $dias_operativos = $this->informe_model->get_dias_operativos_mes($rango_meses,$este_anio);
            $total_dias_operativos = $this->informe_model->get_total_array($dias_operativos);


            $incidencias_dia = $this->informe_model->get_medias($valor_resultados_1,$dias_operativos,$rango_meses);
            $total_media = round($this->informe_model->get_total_array($incidencias_dia) / count($dias_operativos));


            $nombre_mes = array();

            $data["tabla_1"] = $resultados_1;


            /**
             * Segundo bloque de la tabla, Incidencias mensuales por estado PdS
             */
            $titulo_incidencias_estado = $this->incidencia_model->get_titulos_estado();
            $resultados_2 = $this->informe_model->get_cmd_incidencias_totales_estado($este_anio, $ctrl_no_cancelada);
            $incidencias_estado = $this->informe_model->get_cmd_incidencias_estado($titulo_incidencias_estado,$resultados_2,$meses_columna);

            /**
             * TErcer bloque de la tabla, -72h y +72h
             */
            // Limpieza de tabla temporal, poner una fecha de cierre en las finalizadas que no tengan.

            $menos_72 = $this->informe_model->finalizadas_menos_72($este_anio,$meses_columna);
            $mas_72 = $this->informe_model->finalizadas_mas_72($este_anio,$meses_columna);



            $proceso_menos_72 = $this->informe_model->proceso_menos_72($este_anio,$meses_columna);
            $proceso_mas_72 = $this->informe_model->proceso_mas_72($este_anio,$meses_columna);

            /*
             * Tabla temporal con datos sobre la facturacion
             */
            $this->tablona_model->crear_facturaciontemp($este_anio);

            /*Numero total de intervenciones de un determinado año*/
            $resultados_3 = $this->tablona_model->get_totalIntervenciones();




            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO                                                                                                                                                                                                                      EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $intervenciones_anio = array();
            foreach($meses_columna as $num_mes=>$mes)
            {
                $intervenciones_anio[$num_mes] = new StdClass();
                $intervenciones_anio[$num_mes]->cantidad = 0;
                $intervenciones_anio[$num_mes]->mes = $num_mes;
                $intervenciones_anio[$num_mes]->anio = $este_anio;

                foreach($resultados_3 as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $intervenciones_anio[$num_mes] = $valor;
                        break;
                    }
                }
            }
            $data["intervenciones_anio"] = $intervenciones_anio;

            // Línea 2: Alarmas
            $resultados_4 = $this->tablona_model->get_alarmas($este_anio);

            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $alarmas_anio = array();
            foreach($meses_columna as $num_mes=>$mes)
            {
                $alarmas_anio[$num_mes] = new StdClass();
                $alarmas_anio[$num_mes]->cantidad = 0;
                $alarmas_anio[$num_mes]->mes = $num_mes;
                $alarmas_anio[$num_mes]->anio = $este_anio;

                foreach($resultados_4 as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $alarmas_anio[$num_mes] = $valor;
                        break;
                    }
                }
            }
            $data["alarmas_anio"] = $alarmas_anio;


            // Línea 3: Terminales
            /*$resultados_5 = $this->db->query("
                SELECT  SUM(material_incidencias.cantidad) as cantidad,
                        MONTH(material_incidencias.fecha) as mes,
                        YEAR(material_incidencias.fecha) as anio
                FROM material_incidencias
                JOIN incidencias ON material_incidencias.id_incidencia = incidencias.id_incidencia
                WHERE incidencias.status_pds = 'Finalizada' AND YEAR(material_incidencias.fecha) = '".$este_anio."'
                AND id_alarm IS NULL
                GROUP BY mes
            ");*/
            $resultados_5= $this->tablona_model->get_terminales($este_anio);


            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $terminales_anio = array();
            foreach($meses_columna as $num_mes=>$mes)
            {
                $terminales_anio[$num_mes] = new StdClass();
                $terminales_anio[$num_mes]->cantidad = 0;
                $terminales_anio[$num_mes]->mes = $num_mes;
                $terminales_anio[$num_mes]->anio = $este_anio;

                foreach($resultados_5 as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $terminales_anio[$num_mes] = $valor;
                        break;
                    }
                }
            }
            $data["terminales_anio"] = $terminales_anio;



            $resultados_6 = $this->tablona_model->get_IncidenciasResueltas();

            /*$resultados_6 = $this->db->query("SELECT COUNT('id_incidencia') as cantidad,
                                                MONTH(fecha) as mes, YEAR(fecha) as anio
                                                FROM facturacion WHERE YEAR(fecha) = '".$este_anio."'
                                                GROUP BY  anio, mes");*/

            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $incidencias_resueltas = array();
            foreach($meses_columna as $num_mes=>$mes)
            {
                $incidencias_resueltas[$num_mes] = new StdClass();
                $incidencias_resueltas[$num_mes]->cantidad = 0;
                $incidencias_resueltas[$num_mes]->mes = $num_mes;
                $incidencias_resueltas[$num_mes]->anio = $este_anio;

                foreach($resultados_6 as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $incidencias_resueltas[$num_mes] = $valor;
                        break;
                    }
                }
            }
            $data["incidencias_resueltas"] = $incidencias_resueltas;



            // Línea 5: Media incidencias / intervenciones.
            $resultados_7 = array();

            $total_num = $total_denom = 0;
            foreach($incidencias_resueltas as $key=>$valor)
            {
                $resultados_7[$key] = new StdClass();

                $num =  $valor->cantidad;
                $denom = $intervenciones_anio[$key]->cantidad;

                if($denom == 0)
                {
                    $resultados_7[$key]->cantidad = 0;
                }
                else
                {
                    $resultados_7[$key]->cantidad = number_format(round($num / $denom, 2), 2, ",", ".");

                }

                $total_num +=  $valor->cantidad;
                $total_denom += $intervenciones_anio[$key]->cantidad;
            }


            $data["media_inc_int"] = $resultados_7;

            if($total_denom > 0) {
                $data["total_media_inc_int"] = number_format(round($total_num / $total_denom, 2), 2, ",", ".");;
            }else{
                $data["total_media_inc_int"] = 0;
            }


            /* LINEAS NUM INC POR ROBO */
            /* $sql_aux = 'SELECT COUNT(id_incidencia) FROM incidencias
                         WHERE month(fecha) = mes AND YEAR(fecha) = "'.$este_anio.'" '.$ctrl_no_cancelada.' ';


             $resultados_8 = $this->db->query('SELECT COUNT(id_incidencia) as cantidad,

                                             YEAR(f.fecha) as anio, MONTH(f.fecha) as mes,
                                             ('.$sql_aux.') as total

                                                 FROM incidencias f
                                                 WHERE YEAR(f.fecha) = "'.$este_anio.'" AND f.tipo_averia = "Robo"
                                                 '.$ctrl_no_cancelada.'
                                                 GROUP BY mes');*/

            /*LINEAS NUMERO INCIDENCIAS POR ROBO*/
            $resultados_8 = $this->tablona_model->get_IncidenciasTipo($este_anio,$ctrl_no_cancelada,'Robo');

            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $incidencias_robo = array();
            $total_inc_robo = 0;
            $total_inc_tipo = 0;
            foreach($meses_columna as $num_mes=>$mes)
            {
                $incidencias_robo[$num_mes] = new StdClass();
                $incidencias_robo[$num_mes]->cantidad = 0;
                $incidencias_robo[$num_mes]->total = 0;
                $incidencias_robo[$num_mes]->mes = $num_mes;
                $incidencias_robo[$num_mes]->anio = $este_anio;

                foreach($resultados_8 as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $incidencias_robo[$num_mes] = $valor;
                        $total_inc_robo += $valor->cantidad;
                        $total_inc_tipo += $valor->total;
                        break;
                    }
                }
            }
            $data["incidencias_robo"] = $incidencias_robo;
            $data["total_inc_robo"] = ($total_inc_robo > 0) ? $total_inc_robo : 1; // Evitar división por 0;
            $data["total_inc_tipo"] = ($total_inc_tipo > 0) ? $total_inc_tipo : 1; // Evitar división por 0


            /* LINEAS NUM INC POR AVERIA */
            $resultados_9 = $this->tablona_model->get_IncidenciasTipo($este_anio,$ctrl_no_cancelada,'Averia');
            /*$this->db->query('SELECT COUNT(id_incidencia) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes,
        ('.$sql_aux.') as total
                                            FROM incidencias f
                                            WHERE YEAR(f.fecha) = "'.$este_anio.'" AND f.tipo_averia = "Avería"
                                            '.$ctrl_no_cancelada.'
                                            GROUP BY mes');
*/
            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $incidencias_averia = array();
            $total_inc_averia = 0;
            foreach($meses_columna as $num_mes=>$mes)
            {
                $incidencias_averia[$num_mes] = new StdClass();
                $incidencias_averia[$num_mes]->cantidad = 0;
                $incidencias_averia[$num_mes]->total = 0;
                $incidencias_averia[$num_mes]->mes = $num_mes;
                $incidencias_averia[$num_mes]->anio = $este_anio;

                foreach($resultados_9 as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $incidencias_averia[$num_mes] = $valor;
                        $total_inc_averia += $valor->cantidad;
                        break;
                    }
                }
            }
            $data["incidencias_averia"] = $incidencias_averia;
            $data["total_inc_averia"] = ($total_inc_averia > 0) ? $total_inc_averia : 1; // Evitar división por 0;;





            $data["menos_72"] = $menos_72;
            $data["mas_72"] = $mas_72;

            $data["proceso_menos_72"] = $proceso_menos_72;
            $data["proceso_mas_72"] = $proceso_mas_72;

            $data['tala_1'] = $resultados_1;
            $data['incidencias_estado'] = $incidencias_estado;
            $data['titulo_incidencias_estado'] = $titulo_incidencias_estado;



            $data['nombre_mes'] = $nombre_mes;
            $data['dias_operativos'] = $dias_operativos;
            $data['incidencias_dia'] = $incidencias_dia;

            $data['total_incidencias_total'] = $total_incidencias_total;
            $data['total_dias_operativos'] = $total_dias_operativos;
            $data['total_media'] = $total_media;

            $data['title'] = 'Estado incidencias '.$anio;


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/cdm_incidencias',$data);
            $this->load->view('master/footer');
        } else {
            redirect('master', 'refresh');
        }
    }
    public function cdm_incidencias_old2($anio = NULL)
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $this->load->helper("common");
            $this->load->model(array("incidencia_model","informe_model"));



            $b_filtrar_tipo = $this->input->post("filtrar_tipo");
            $tipo_tienda = '';
            $estado_incidencia = '';

            if($b_filtrar_tipo === "si"){
                $tipo_tienda = $this->input->post("tipo_tienda");
                $estado_incidencia  = $this->input->post("estado_incidencia");
            }

            $este_anio = (is_null($anio)) ? date("Y") : $anio;
            $data['anio'] = $este_anio;

            setlocale(LC_ALL, 'es_ES');

            $data["tipo_tienda"] = $tipo_tienda;
            $data["estado_incidencia"] = $estado_incidencia;
            // Saco los tipos de tienda, pero sólo aquellos cuyos PDS tienen algun tipo de incidencia.
            $data["estados_incidencia"] = $this->incidencia_model->get_estados_incidencia();


            $xcrud_1 = xcrud_get_instance();
            $xcrud_1->table_name('Incidencias');


            $s_where = $s_where_incidencia= '';
            if(!empty($tipo_tienda)){
                $s_where = " AND pds.type_pds = ".$tipo_tienda;
            }
            if(!empty($estado_incidencia)){
                $s_where_incidencia .= " AND incidencias.status_pds LIKE '".$estado_incidencia."'";
            }

            $ctrl_no_cancelada = " AND (status_pds != 'Cancelada' && status != 'Cancelada') "; // Condición where de contrl de incidencias NO CANCELADAS

            /**
             * Primer bloque de la tabla, Totales incidencias, dias operativos y media
             */

            // Rango de meses que mostrarán las columnas de la tabla, basándome en el mínimo y máximo mes que hay incidencias, este año.
            $rango_meses = $this->informe_model->get_rango_meses($este_anio);
            $primer_mes = $rango_meses->min;
            $meses_columna = $this->informe_model->get_meses_columna($rango_meses->min,$rango_meses->max);
            $data["primer_mes"] = $primer_mes;
            $data["meses_columna"] = $meses_columna;



            // Sacamos la primera línea. Total incidencias
            $resultados_1 = $this->informe_model->get_cmd_incidencias_totales($este_anio,$ctrl_no_cancelada);
            $total_incidencias_total = $this->informe_model->get_total_cdm_incidencias($resultados_1);
            $valor_resultados_1 = $this->informe_model->get_array_incidencias_totales($resultados_1);


            $dias_operativos = $this->informe_model->get_dias_operativos_mes($rango_meses);
            $total_dias_operativos = $this->informe_model->get_total_array($dias_operativos);


            $incidencias_dia = $this->informe_model->get_medias($valor_resultados_1,$dias_operativos,$rango_meses);
            $total_media = round($this->informe_model->get_total_array($incidencias_dia) / count($dias_operativos));


            $nombre_mes = array();

            $data["tabla_1"] = $resultados_1;



            /**
             * Segundo bloque de la tabla, Incidencias mensuales por estado PdS
             */
            $titulo_incidencias_estado = $this->incidencia_model->get_titulos_estado();
            $resultados_2 = $this->informe_model->get_cmd_incidencias_totales_estado($este_anio, $ctrl_no_cancelada);
            $incidencias_estado = $this->informe_model->get_cmd_incidencias_estado($titulo_incidencias_estado,$resultados_2,$meses_columna);

            /**
             * TErcer bloque de la tabla, -72h y +72h
             */
            // Limpieza de tabla temporal, poner una fecha de cierre en las finalizadas que no tengan.

            $menos_72 = $this->informe_model->finalizadas_menos_72($este_anio,$meses_columna);
            $mas_72 = $this->informe_model->finalizadas_mas_72($este_anio,$meses_columna);



            $proceso_menos_72 = $this->informe_model->proceso_menos_72($este_anio,$meses_columna);
            $proceso_mas_72 = $this->informe_model->proceso_mas_72($este_anio,$meses_columna);






            //, COUNT(facturacion.id_incidencia) AS incidencias";"

            $this->db->query(" DROP TABLE IF EXISTS facturacion_temp; ");
            $this->db->query('
                CREATE TEMPORARY TABLE facturacion_temp AS
                (
                    SELECT f.fecha as fecha, COUNT(f.id_incidencia) AS incidencias,  SUM(f.units_device) AS dispositivos, SUM(f.units_alarma) AS otros
                    FROM facturacion f
                    LEFT JOIN intervenciones ON  f.id_intervencion = intervenciones.id_intervencion
                    WHERE YEAR(f.fecha) = "'.$este_anio.'"
                    GROUP BY f.id_intervencion
                );
            ');


            $resultados_3 = $this->db->query('SELECT COUNT(*) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes
                                                FROM facturacion_temp f
                                                GROUP BY mes');




            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO                                                                                                                                                                                                                      EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $intervenciones_anio = array();
            foreach($meses_columna as $num_mes=>$mes)
            {
                $intervenciones_anio[$num_mes] = new StdClass();
                $intervenciones_anio[$num_mes]->cantidad = 0;
                $intervenciones_anio[$num_mes]->mes = $num_mes;
                $intervenciones_anio[$num_mes]->anio = $este_anio;

                foreach($resultados_3->result() as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $intervenciones_anio[$num_mes] = $valor;
                        break;
                    }
                }
            }
            $data["intervenciones_anio"] = $intervenciones_anio;

            // Línea 2: Alarmas
            $resultados_4 = $this->db->query("
                SELECT  SUM(material_incidencias.cantidad) as cantidad,
                        MONTH(material_incidencias.fecha) as mes,
                        YEAR(material_incidencias.fecha) as anio
                FROM material_incidencias
                JOIN incidencias ON material_incidencias.id_incidencia = incidencias.id_incidencia
                WHERE incidencias.status_pds = 'Finalizada' AND YEAR(material_incidencias.fecha) = '".$este_anio."'
                AND id_devices_almacen IS NULL
                GROUP BY mes
            ");

            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $alarmas_anio = array();
            foreach($meses_columna as $num_mes=>$mes)
            {
                $alarmas_anio[$num_mes] = new StdClass();
                $alarmas_anio[$num_mes]->cantidad = 0;
                $alarmas_anio[$num_mes]->mes = $num_mes;
                $alarmas_anio[$num_mes]->anio = $este_anio;

                foreach($resultados_4->result() as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $alarmas_anio[$num_mes] = $valor;
                        break;
                    }
                }
            }
            $data["alarmas_anio"] = $alarmas_anio;


            // Línea 3: Terminales
            $resultados_5 = $this->db->query("
                SELECT  SUM(material_incidencias.cantidad) as cantidad,
                        MONTH(material_incidencias.fecha) as mes,
                        YEAR(material_incidencias.fecha) as anio
                FROM material_incidencias
                JOIN incidencias ON material_incidencias.id_incidencia = incidencias.id_incidencia
                WHERE incidencias.status_pds = 'Finalizada' AND YEAR(material_incidencias.fecha) = '".$este_anio."'
                AND id_alarm IS NULL
                GROUP BY mes
            ");

            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $terminales_anio = array();
            foreach($meses_columna as $num_mes=>$mes)
            {
                $terminales_anio[$num_mes] = new StdClass();
                $terminales_anio[$num_mes]->cantidad = 0;
                $terminales_anio[$num_mes]->mes = $num_mes;
                $terminales_anio[$num_mes]->anio = $este_anio;

                foreach($resultados_5->result() as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $terminales_anio[$num_mes] = $valor;
                        break;
                    }
                }
            }
            $data["terminales_anio"] = $terminales_anio;






            $resultados_6 = $this->db->query('SELECT SUM(incidencias) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes
                                                FROM facturacion_temp f
                                                GROUP BY mes');

            /*$resultados_6 = $this->db->query("SELECT COUNT('id_incidencia') as cantidad,
                                                MONTH(fecha) as mes, YEAR(fecha) as anio
                                                FROM facturacion WHERE YEAR(fecha) = '".$este_anio."'
                                                GROUP BY  anio, mes");*/

            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $incidencias_resueltas = array();
            foreach($meses_columna as $num_mes=>$mes)
            {
                $incidencias_resueltas[$num_mes] = new StdClass();
                $incidencias_resueltas[$num_mes]->cantidad = 0;
                $incidencias_resueltas[$num_mes]->mes = $num_mes;
                $incidencias_resueltas[$num_mes]->anio = $este_anio;

                foreach($resultados_6->result() as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $incidencias_resueltas[$num_mes] = $valor;
                        break;
                    }
                }
            }
            $data["incidencias_resueltas"] = $incidencias_resueltas;



            // Línea 5: Media incidencias / intervenciones.
            $resultados_7 = array();

            $total_num = $total_denom = 0;
            foreach($incidencias_resueltas as $key=>$valor)
            {
                $resultados_7[$key] = new StdClass();

                $num =  $valor->cantidad;
                $denom = $intervenciones_anio[$key]->cantidad;

                if($denom == 0)
                {
                    $resultados_7[$key]->cantidad = 0;
                }
                else
                {
                        $resultados_7[$key]->cantidad = number_format(round($num / $denom, 2), 2, ",", ".");

                }

                $total_num +=  $valor->cantidad;
                $total_denom += $intervenciones_anio[$key]->cantidad;
            }


            $data["media_inc_int"] = $resultados_7;

            if($total_denom > 0) {
                $data["total_media_inc_int"] = number_format(round($total_num / $total_denom, 2), 2, ",", ".");;
            }else{
                $data["total_media_inc_int"] = 0;
            }


            /* LINEAS NUM INC POR ROBO */
            $sql_aux = 'SELECT COUNT(id_incidencia) FROM incidencias
                        WHERE month(fecha) = mes AND YEAR(fecha) = "'.$este_anio.'" '.$ctrl_no_cancelada.' ';


            $resultados_8 = $this->db->query('SELECT COUNT(id_incidencia) as cantidad,

                                            YEAR(f.fecha) as anio, MONTH(f.fecha) as mes,
                                            ('.$sql_aux.') as total

                                                FROM incidencias f
                                                WHERE YEAR(f.fecha) = "'.$este_anio.'" AND f.tipo_averia = "Robo"
                                                '.$ctrl_no_cancelada.'
                                                GROUP BY mes');

            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $incidencias_robo = array();
            $total_inc_robo = 0;
            $total_inc_tipo = 0;
            foreach($meses_columna as $num_mes=>$mes)
            {
                $incidencias_robo[$num_mes] = new StdClass();
                $incidencias_robo[$num_mes]->cantidad = 0;
                $incidencias_robo[$num_mes]->total = 0;
                $incidencias_robo[$num_mes]->mes = $num_mes;
                $incidencias_robo[$num_mes]->anio = $este_anio;

                foreach($resultados_8->result() as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $incidencias_robo[$num_mes] = $valor;
                        $total_inc_robo += $valor->cantidad;
                        $total_inc_tipo += $valor->total;
                        break;
                    }
                }
            }
            $data["incidencias_robo"] = $incidencias_robo;
            $data["total_inc_robo"] = ($total_inc_robo > 0) ? $total_inc_robo : 1; // Evitar división por 0;
            $data["total_inc_tipo"] = ($total_inc_tipo > 0) ? $total_inc_tipo : 1; // Evitar división por 0


            /* LINEAS NUM INC POR AVERIA */
            $resultados_9 = $this->db->query('SELECT COUNT(id_incidencia) as cantidad, YEAR(f.fecha) as anio, MONTH(f.fecha) as mes,
            ('.$sql_aux.') as total
                                                FROM incidencias f
                                                WHERE YEAR(f.fecha) = "'.$este_anio.'" AND f.tipo_averia = "Avería"
                                                '.$ctrl_no_cancelada.'
                                                GROUP BY mes');

            // CREAMOS UN ARRAY CON TODOS LOS MESES Y LO RELLENAMOS CON LOS RESULTADOS, SI NO EXISTE RESULTADO, ESE MES
            // SERA DE CANTIDAD 0
            $incidencias_averia = array();
            $total_inc_averia = 0;
            foreach($meses_columna as $num_mes=>$mes)
            {
                $incidencias_averia[$num_mes] = new StdClass();
                $incidencias_averia[$num_mes]->cantidad = 0;
                $incidencias_averia[$num_mes]->total = 0;
                $incidencias_averia[$num_mes]->mes = $num_mes;
                $incidencias_averia[$num_mes]->anio = $este_anio;

                foreach($resultados_9->result() as $key=>$valor)
                {
                    if(array_key_exists("mes",$valor) && $valor->mes == $num_mes)
                    {
                        $incidencias_averia[$num_mes] = $valor;
                        $total_inc_averia += $valor->cantidad;
                        break;
                    }
                }
            }
            $data["incidencias_averia"] = $incidencias_averia;
            $data["total_inc_averia"] = ($total_inc_averia > 0) ? $total_inc_averia : 1; // Evitar división por 0;;





            $data["menos_72"] = $menos_72;
            $data["mas_72"] = $mas_72;

            $data["proceso_menos_72"] = $proceso_menos_72;
            $data["proceso_mas_72"] = $proceso_mas_72;

            $data['tala_1'] = $resultados_1;
            $data['incidencias_estado'] = $incidencias_estado;
            $data['titulo_incidencias_estado'] = $titulo_incidencias_estado;



            $data['nombre_mes'] = $nombre_mes;
            $data['dias_operativos'] = $dias_operativos;
            $data['incidencias_dia'] = $incidencias_dia;

            $data['total_incidencias_total'] = $total_incidencias_total;
            $data['total_dias_operativos'] = $total_dias_operativos;
            $data['total_media'] = $total_media;

            $data['title'] = 'Estado incidencias';


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/cdm_incidencias',$data);
            $this->load->view('master/footer');
        } else {
            redirect('master', 'refresh');
        }
    }


    public function cdm_incidencias_OLD()
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor


            $b_filtrar_tipo = $this->input->post("filtrar_tipo");
            $tipo_tienda = '';
            $estado_incidencia = '';

            if($b_filtrar_tipo === "si"){
                $tipo_tienda = $this->input->post("tipo_tienda");
                $estado_incidencia  = $this->input->post("estado_incidencia");
            }

            $data["tipo_tienda"] = $tipo_tienda;
            $data["estado_incidencia"] = $estado_incidencia;

            // Saco los tipos de tienda, pero sólo aquellos cuyos PDS tienen algun tipo de incidencia.
            $tipos_tienda = $this->db->query("SELECT id_type_pds as id_tipo, pds as tipo FROM type_pds
                                              WHERE status='Alta' AND client_type_pds !=2  AND id_type_pds IN (
                                                  SELECT DISTINCT(pds.type_pds) FROM pds INNER JOIN incidencias ON incidencias.id_pds = pds.id_pds
                                              )");
            $data["tipos_tienda"] = $tipos_tienda->result();


            // Saco los tipos de tienda, pero sólo aquellos cuyos PDS tienen algun tipo de incidencia.
            $estados_incidencia = $this->db->query("SELECT DISTINCT(status_pds) FROM incidencias ");
            $data["estados_incidencia"] = $estados_incidencia->result();


			$xcrud_1 = xcrud_get_instance();
			$xcrud_1->table_name('Incidencias');


            $s_where = $s_where_incidencia= '';
            if(!empty($tipo_tienda)){
                $s_where = " AND pds.type_pds = ".$tipo_tienda;
            }
            if(!empty($estado_incidencia)){
                $s_where_incidencia .= " AND incidencias.status_pds LIKE '".$estado_incidencia."'";
            }


			$xcrud_1->query("SELECT
								YEAR(incidencias.fecha) AS Year, 
								MONTH(incidencias.fecha) AS Mes,

								COUNT(*) AS Incidencias,
						    	(
									SELECT
										COUNT(*) 
										FROM historico
										INNER JOIN pds ON pds.id_pds = historico.id_pds
										INNER JOIN type_pds ON type_pds.id_type_pds = pds.type_pds
										WHERE
										(
											((historico.status_pds = 'Cancelada' AND historico.status = 'Cancelada') OR
											(historico.status_pds = 'Finalizada' AND historico.status = 'Resuelta')) AND
						            		(DATE_ADD(incidencias.fecha, INTERVAL 96 HOUR) >= historico.fecha) AND
						            		(YEAR(historico.fecha) = Year AND MONTH(historico.fecha) = Mes)
						            		$s_where
										)

						    	) AS '- 72 h.',    
								(
									SELECT
										COUNT(*) 
										FROM incidencias
										INNER JOIN pds ON pds.id_pds = incidencias.id_pds
										INNER JOIN type_pds ON type_pds.id_type_pds = pds.type_pds
										WHERE 
										(
											(incidencias.status_pds = 'Finalizada' OR incidencias.status_pds = 'Cancelada') AND
											(YEAR(incidencias.fecha) = Year AND MONTH(incidencias.fecha) = Mes)
											$s_where
										)
								) AS Cerradas
							FROM incidencias
							INNER JOIN pds ON pds.id_pds = incidencias.id_pds
                            INNER JOIN type_pds ON type_pds.id_type_pds = pds.type_pds
                            WHERE 1=1 $s_where_incidencia
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
            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
			$this->load->view('master/header',$data);
			$this->load->view('master/navbar',$data);
			$this->load->view('master/cdm_incidencias',$data);
			$this->load->view('master/footer');
		} else {
			redirect('master', 'refresh');
		}
	}	
	
	
	public function cdm_tipo_incidencia()
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');

            $data['title']   = 'Sistemas de seguridad';

            $balance_alarmas = $this->tienda_model->get_balance_alarmas();
			$data['stocks']  = $this->tienda_model->get_cdm_alarmas();



            $data['stock_balance'] = $balance_alarmas;

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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

    public function cdm_alarmas_balance()
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');

            $data['title']   = 'Sistemas de seguridad';

            $balance_alarmas = $this->tienda_model->get_balance_alarmas();
            //$data['stocks']  = $this->tienda_model->get_cdm_alarmas();



            $data['stock_balance'] = $balance_alarmas;

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/cdm_alarmas_balance',$data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }
    }

    public function cdm_alarmas_incidencias()
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');

            $data['title']   = 'Sistemas de seguridad';

            //$balance_alarmas = $this->tienda_model->get_balance_alarmas();
            $data['stocks']  = $this->tienda_model->get_cdm_alarmas();



            // $data['stock_balance'] = $balance_alarmas;

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/cdm_alarmas_incidencias',$data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }
    }

    /**
     * Muestra por mes el consumo de los diferentes modelos de alarmas
     * Tomando el estado de las incidencias "En visita" y la fecha en la cual la incidencia se puso en ese estado
     */
    public function cdm_alarmas_consumo()
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
            //$controlador = $this->data->get("controlador");
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();

            $this->load->model(array('tablona_model', 'informe_model','alarma_model'));
            $this->load->helper("common");

            /**
             * Crear los filtros
             */
            $array_filtros = array('anio' => '');

            // Consultar a la session si ya se ha buscado algo y guardado allí.
            $array_sesion = $this->get_filtros($array_filtros);
            // Buscar en el POST si hay busqueda, y si la hay usarla y guardarla además en sesion
            if($this->input->post('filtrar_anio')==="si") $array_sesion = $this->set_filtros($array_filtros);

            /* Creamos al vuelo las variables que vienen de los filtros */
            foreach($array_filtros as $filtro=>$value){
                $$filtro = $array_sesion[$filtro];
                $data[$filtro] = $array_sesion[$filtro]; // Pasamos los valores a la vista.
            }

            $title = 'Análisis de consumo de Sistemas de seguridad';
            $anio='';
            if (!empty($_POST)) {

                $anio=$_POST['anio'];
                $estado="En visita";
                if (!empty($anio)) {
                    $this->tablona_model->crear_historicotemp($anio,$estado);

                    $title .= ' ' . $anio;
                    setlocale(LC_ALL, 'es_ES');

                    $xcrud_1 = xcrud_get_instance();
                    $xcrud_1->table_name('Alarmas');

                    $alarmas=$this->alarma_model->get_alarmas();

                    // Rango de meses que mostrarán las columnas de la tabla, basándome en el mínimo y máximo mes que hay incidencias, este año.
                    $rango_meses = $this->informe_model->get_rango_meses($anio);
                    // $primer_mes = $rango_meses->min;
                    $meses_columna = $this->informe_model->get_meses_columna($rango_meses->min,$rango_meses->max);
                    $data["primer_mes"] =  $rango_meses->min;;
                    $data["ultimo_mes"] = $rango_meses->max;
                    $data["meses_columna"] = $meses_columna;

                    $resultado = $this->alarma_model->get_sistemas_seguridad_totales();
//print_r($resultado);exit;
                    $valor_resultado = $this->alarma_model->get_array_sistemas_seguridad($resultado,$rango_meses->min,$rango_meses->max,$alarmas);

                    $data['valor_resultado'] = $valor_resultado;
                }
            }
            $data['title']=$title;
            $data['anio']=$anio;

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('/master/header', $data);
            $this->load->view('/master/navbar', $data);
            $this->load->view('/master/informe_sistemas_seguridad.php', $data);
            $this->load->view('/master/footer');
        } else {
            redirect('master', 'refresh');
        }
    }


    public function cdm_dispositivos()
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');

            $data['stocks'] = $this->tienda_model->get_stock_cruzado();
			$data['stocks_dispositivos']  = $this->tienda_model->get_cdm_dispositivos();
				
			$data['title']   = 'Dispositivos';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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

    public function cdm_dispositivos_balance()
    {
    if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        /** Crear los filtros*/
        $array_filtros = array(
            'id_modelo' =>  '',
            'id_marca'  =>  ''
        );


        // Consultar a la session si ya se ha buscado algo y guardado allí.
        $array_sesion = $this->get_filtros($array_filtros);

        /* BORRAR BUSQUEDA */
        //echo  $this->uri->segment(4);exit;
        $borrar_busqueda = $this->uri->segment(3);
        if($borrar_busqueda === "borrar_busqueda")
        {
            $this->delete_filtros($array_filtros);
            //print_r($array_filtros);
            redirect(site_url("/master/cdm_dispositivos_balance"),'refresh');
        }

        if($this->input->post('do_busqueda')==="si") $array_sesion = $this->set_filtros($array_filtros);

        /* Creamos al vuelo las variables que vienen de los filtros */
        foreach($array_filtros as $filtro=>$value){
            $$filtro = $array_sesion[$filtro];
            $data[$filtro] = $array_sesion[$filtro]; // Pasamos los valores a la vista.
        }

        $data['modelos']=$this->tienda_model->get_terminales();
        $data['marcas'] =$this->tienda_model->get_fabricantes();
        $data['stocks'] = $this->tienda_model->get_stock_cruzado($array_sesion);
        // $data['stocks_dispositivos']  = $this->tienda_model->get_cdm_dispositivos();

        $data['title']   = 'Dispositivos';

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('master/header',$data);
        $this->load->view('master/navbar',$data);
        $this->load->view('master/cdm_dispositivos_balance',$data);
        $this->load->view('master/footer');
    }
    else
    {
        redirect('master','refresh');
    }
    }

    public function cdm_dispositivos_incidencias()
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');

            //$data['stocks'] = $this->tienda_model->get_stock_cruzado();
            $data['stocks_dispositivos']  = $this->tienda_model->get_cdm_dispositivos();

            $data['title']   = 'Dispositivos';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/cdm_dispositivos_incidencias',$data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }
    }
	
	public function cdm_inventario()
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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

    /**
     * Método del controlador, que invoca al modelo para generar un CSV con el balance de activos.
     */
    public function cdm_balance_activos_csv()
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $this->load->model('tienda_model');
            $data['stocks'] = $this->tienda_model->get_stock_cruzado_csv();
        }
        else
        {
            redirect('master','refresh');
        }
    }


    /**
     * Método del controlador, que invoca al modelo para generar un CSV con el balance de activos.
     */
    public function exportar_balance_activos($formato=NULL)
    {
        if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
        {
            //print_r($this->session->userdata); exit;
            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $this->load->model('tienda_model');
            $data['stocks'] = $this->tienda_model->exportar_stock_cruzado($ext,$this->session->userdata('sfid'));
        }
        else
        {
            redirect('master','refresh');
        }
    }

    /**
     * Método del controlador, que invoca al modelo para generar un CSV con el balance de incidencias.
     */
    public function exportar_balance_incidencias($formato=NULL)
    {
        if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
        {
            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $this->load->model('tienda_model');
            $data['stocks_dispositivos'] = $this->tienda_model->exportar_cdm_dispositivos($ext);
        }
        else
        {
            redirect('master','refresh');
        }
    }

    /**
     * Método del controlador, que invoca al modelo para generar un CSV con el balance de activos.
     */
    public function exportar_balance_alarmas($formato=NULL)
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $this->load->model('tienda_model');
            $data['stocks'] = $this->tienda_model->exportar_balance_alarmas($ext);

        }
        else
        {
            redirect('master','refresh');
        }
    }



    public function inventario()
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
	
			$data['tiendas'] =  $this->tienda_model->search_pds($this->input->post('sfid'));
	
			$data['title'] = 'Planograma tienda';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
        //
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
        //
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
        //
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');

			$id_display = $this->input->post('id_display');
			$devices    = $this->tienda_model->get_devices_display($id_display);

			$data['displays'] = $this->tienda_model->get_displays_demoreal();
			$data['devices']  = $devices;

			if ($id_display != '')
			{
				$display = $this->tienda_model->get_display($id_display);
				$data['display_name'] = $display['display'];
				$data['picture_url']  = $display['picture_url'];
			}

			$data['title']   = 'Planograma mueble';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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



    public function informe_pdv()
    {

        if($this->auth->is_auth()) {
            $xcrud = xcrud_get_instance();
            $this->load->model('sfid_model');
            $this->load->model('tienda_model');
            $this->load->model('informe_model');
            $this->load->model('categoria_model');

            $data["title"] = "Informe de Puntos de Venta (Bloom)";


            $resultados = array();


            $data["generado"] = FALSE;

            $data["resultados"] = $resultados;


            /*// COMENTADO SELECT DEMOREAL $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal();
            $data["tipos_tienda"] = $this->sfid_model->get_types_pds();
            // COMENTADO SELECT DEMOREAL $panelados = $this->tienda_model->get_panelados_maestros_demoreal();
            $data["panelados"] = $this->tienda_model->get_panelados_maestros();*/
            // COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal();
            $data["muebles"] = $this->tienda_model->get_displays_demoreal();
            // COMENTADO SELECT DEMOREAL $terminales = $this->tienda_model->get_devices_demoreal();
            //$data["terminales"] = $this->tienda_model->get_devices();

            $data["pds_tipos"] = $this->categoria_model->get_tipos_pds();
            $data["pds_subtipos"] = $this->categoria_model->get_subtipos_pds();
            $data["pds_segmentos"] = $this->categoria_model->get_segmentos_pds();
            $data["pds_tipologias"] = $this->categoria_model->get_tipologias_pds();


            $data["terminales"] = $this->tienda_model->get_devices_demoreal();
            /* LISTADO DE TERRITORIOS PARA EL SELECT */
            $data["territorios"] = $this->tienda_model->get_territorios();
            /* LISTADO DE FABRICANTES PARA EL SELECT */
            $data["fabricantes"] = $this->tienda_model->get_fabricantes();


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header', $data);
            $this->load->view('master/navbar', $data);
            $this->load->view('master/informes/bloom/pdv/puntos_venta_form', $data);
            $this->load->view('master/informes/bloom/pdv/puntos_venta', $data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }
    }


    /**
     * Método que se llama por AJAX desde el Informe PDV, al añadir o quitar un elemento del multifiltro.
     */
    public function resultado_pdv($exportar = NULL,$formato=NULL)
    {
        if($this->auth->is_auth()) {
            $xcrud = xcrud_get_instance();

            $this->load->model('informe_model');

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG

            $arr_campos = array(
                "id_tipo" => '',
                "id_subtipo" => '',
                "id_segmento" => '',
                "id_tipologia" => '',
                "id_display" => '',
                "id_device" => '',
                "territory" => '',
                "brand_device" => ''
            );

            foreach ($arr_campos as $campo => $valor) {
                $$campo = $valor;
                $data[$campo] = $valor;
            }

            $campo_orden = NULL;
            $ordenacion = NULL;

            $total_registros = 0;

            $controlador_origen = "master"; //  Controlador por defecto


            if ($this->input->post("generar_informe") === "si") {


                $controlador_origen = $this->input->post("controlador");
                $data["controlador"] = $controlador_origen;

                $campos_sess_informe = array();
                // TIPO TIENDA
                $id_tipo = array();
                $campos_sess_informe["id_tipo"] = NULL;
                if (is_array($this->input->post("id_tipo_multi"))) {
                    foreach ($this->input->post("id_tipo_multi") as $tt) $id_tipo[] = $tt;
                    $campos_sess_informe["id_tipo"] = $id_tipo;
                }

                // SUBTIPO TIENDA
                $id_subtipo = array();
                $campos_sess_informe["id_subtipo"] = NULL;
                if (is_array($this->input->post("id_subtipo_multi"))) {
                    foreach ($this->input->post("id_subtipo_multi") as $tt) $id_subtipo[] = $tt;
                    $campos_sess_informe["id_subtipo"] = $id_subtipo;
                }

                // SEGMENTO TIENDA
                $id_segmento = array();
                $campos_sess_informe["id_segmento"] = NULL;
                if (is_array($this->input->post("id_segmento_multi"))) {
                    foreach ($this->input->post("id_segmento_multi") as $tt) $id_segmento[] = $tt;
                    $campos_sess_informe["id_segmento"] = $id_segmento;
                }

                // TIPOLOGIA TIENDA
                $id_tipologia = array();
                $campos_sess_informe["id_tipologia"] = NULL;
                if (is_array($this->input->post("id_tipologia_multi"))) {
                    foreach ($this->input->post("id_tipologia_multi") as $tt) $id_tipologia[] = $tt;
                    $campos_sess_informe["id_tipologia"] = $id_tipologia;
                }

                // MUEBLE
                $id_display = array();
                $campos_sess_informe["id_display"] = NULL;
                if (is_array($this->input->post("id_display_multi"))) {
                    foreach ($this->input->post("id_display_multi") as $tt) $id_display[] = $tt;
                    $campos_sess_informe["id_display"] = $id_display;
                }
                // DEVICE
                $id_device = array();
                $campos_sess_informe["id_device"] = NULL;
                if (is_array($this->input->post("id_device_multi"))) {
                    foreach ($this->input->post("id_device_multi") as $tt) $id_device[] = $tt;
                    $campos_sess_informe["id_device"] = $id_device;
                }


                // TERRITORY
                $territory = array();
                $campos_sess_informe["territory"] = NULL;
                if (is_array($this->input->post("territory_multi"))) {
                    foreach ($this->input->post("territory_multi") as $tt) $territory[] = $tt;
                    $campos_sess_informe["territory"] = $territory;
                }

                // DEVICE BRAND
                $brand_device = array();
                $campos_sess_informe["brand_device"] = NULL;
                if (is_array($this->input->post("brand_device_multi"))) {
                    foreach ($this->input->post("brand_device_multi") as $tt) $brand_device[] = $tt;
                    $campos_sess_informe["brand_device"] = $brand_device;
                }


                // Guardamos en la sesión el objeto $campos_sess_informe
                $this->session->set_userdata("campos_sess",$campos_sess_informe);
                $this->session->set_userdata("generado",TRUE);

                $data["id_tipo"] = $id_tipo;
                $data["id_subtipo"] = $id_subtipo;
                $data["id_segmento"] = $id_segmento;
                $data["id_tipologia"] = $id_tipologia;


                $data["id_display"] = $id_display;
                $data["id_device"] = $id_device;
                $data["territory"] = $territory;
                $data["brand_device"] = $brand_device;

                $data["generado"] = TRUE;
                $data["controlador"] = $this->uri->segment(1);

            }


            // Recuperar de la sesion
            if($this->session->userdata("generado"))
            {
                foreach($this->session->userdata("campos_sess") as $nombre_var=>$valores)
                {
                    $$nombre_var = $valores;             // Creamos variable al vuelo..
                    $data[$nombre_var] = $valores;      // Guardamos los mismos valores para la variable de la vista.
                }
            }


            if(is_null($exportar))
            {
                $resultados = $this->informe_model->get_informe_pdv($data);
                $data["total_registros"] = count($resultados);
                $data["resultados"] = $resultados;

                $resp = $this->load->view('master/informes/bloom/pdv/puntos_venta_ajax', $data, TRUE);
                echo $resp;

            }
            // Informe CSV
            else
            {

                $this->informe_model->exportar_informe_pdv($data,$ext);
            }

        }
    }



    public function informe_pdv_exportar_OLD()
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $xcrud = xcrud_get_instance();
            $this->load->model('sfid_model');
            $this->load->model('tienda_model');
            $this->load->model('informe_model');

            $data["title"] = "Informe de Puntos de Venta";

            $tipo_tienda = "";
            $panelado = "";
            $mueble = "";
            $terminal = "";


            $campo_orden = NULL;
            $ordenacion = NULL;

            $total_registros = 0;
            $generado = FALSE;

            $resultados = array();

            if ($this->input->post("generar_informe") === "si") {
                $tipo_tienda = $this->input->post("tipo_tienda");
                $panelado = $this->input->post("panelado");
                $mueble = $this->input->post("mueble");
                $terminal = $this->input->post("terminal");
                $sfid = $this->input->post("sfid");


                $data["tipo_tienda"] = $tipo_tienda;
                $data["panelado"] = $panelado;
                $data["mueble"] = $mueble;
                $data["terminal"] = $terminal;
                $data["sfid"] = $sfid;
                $data["generado"] = TRUE;




                $total_registros = $this->informe_model->get_informe_pdv_quantity($data);
                $data["total_registros"] = $total_registros;


                $this->session->set_userdata($data);
                $generado = TRUE;
            }else{

                // OBTENER DE LA SESION, SI EXISTE
                if( $this->session->userdata("generado")!==NULL  && $this->session->userdata("generado")===TRUE){



                    $tipo_tienda = $this->session->userdata("tipo_tienda");
                    $panelado = $this->session->userdata("panelado");
                    $mueble = $this->session->userdata("mueble");
                    $terminal = $this->session->userdata("terminal");
                    $sfid = $this->session->userdata("sfid");
                    $generado = $this->session->userdata("generado");
                    $total_registros = $this->session->userdata("total_registros");

                    $data["tipo_tienda"] = $tipo_tienda;
                    $data["panelado"] = $panelado;
                    $data["mueble"] = $mueble;
                    $data["terminal"] = $terminal;
                    $data["sfid"] = $sfid;
                    $data["generado"] = TRUE;
                    $data["total_registros"] = $total_registros;



                }
            }



            $data["generado"] = $generado;

            $this->informe_model->get_informe_csv($campo_orden,$ordenacion,$data);
        }
        else
        {
            redirect('master','refresh');
        }
    }

    /**
     * Punto de entrada del Informe sobre planogramas.
     * Mostrará la vista principal con el formulario de filtrado, y recogerá los datos enviados y los procesará
     * como corresponda.
     */
    public function informe_planogramas()
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor


            /* Incluir los modelos */
            $xcrud = xcrud_get_instance();
            $this->load->model('sfid_model');
            $this->load->model('tienda_model');
            $this->load->model('informe_model');

            $mueble_plano = "";
            $sfid_plano = "";
            $generado_planograma = "";

            $data["mueble_plano"] = $mueble_plano;
            $data["sfid_plano"] = $sfid_plano;
            $data["generado_planograma"] = FALSE;


            $data["title"] = "Informe de Planogramas";

            $vista = 0;

            if ($this->input->post("generar_informe") === "si") {
                $mueble_plano = $this->input->post("mueble_plano");
                $sfid_plano = $this->input->post("sfid_plano");
                $generado_planograma = TRUE;

                $data["mueble_plano"] = $mueble_plano;
                $data["sfid_plano"] = $sfid_plano;
                $data["generado_planograma"] = TRUE;

                $this->session->set_userdata(array(
                    "mueble_plano" => $mueble_plano,
                    "sfid_plano" => $sfid_plano,
                    "generado_planograma" => $generado_planograma
                ));

            } else {
                // OBTENER DE LA SESION, SI EXISTE
                if ($this->session->userdata("generado_planograma") !== NULL && $this->session->userdata("generado_planograma") === TRUE) {
                    $mueble_plano = $this->session->userdata("mueble_plano");
                    $sfid_plano = $this->session->userdata("sfid_plano");
                    $generado_planograma = $this->session->userdata("generado_planograma");

                    $data["mueble_plano"] = $mueble_plano;
                    $data["sfid_plano"] = $sfid_plano;
                    $data["generado_planograma"] = TRUE;

                }
            }



            if(!empty($sfid_plano)){
                if(!empty($mueble_plano)){
                   /*
                    *  Planograma del mueble para el sfid indicado
                    */
                    $display_maestro = $this->tienda_model->get_display($mueble_plano);
                    $data['display'] = " - " .$display_maestro["display"];
                    $data['picture_url'] = $display_maestro["picture_url"];


                    $tiendas = $this->tienda_model->search_pds($sfid_plano,'Alta');
                    if (!empty($tiendas) && count($tiendas) == 1) {

                        $tienda = NULL;
                        foreach ($tiendas as $tienda_1) {
                            $tienda = $tienda_1;
                        }


                        $id_pds = $tienda->id_pds;

                        $sfid = $this->tienda_model->get_pds($id_pds);
                        $data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
                        $data['commercial'] = $sfid['commercial'];
                        $data['territory']  = $sfid['territory'];
                        $data['reference']  = $sfid['reference'];
                        $data['address']    = $sfid['address'];
                        $data['zip']        = $sfid['zip'];
                        $data['city']       = $sfid['city'];
                        $data['id_pds_url'] = $id_pds;


                        $arr_displays_pds = $this->sfid_model->get_id_displays_pds($id_pds,$mueble_plano);

                        $displays = array();
                        $data['display'] = "";


                        if(!empty($arr_displays_pds)) {
                            $id_displays_pds = $arr_displays_pds[0]->id_displays_pds;

                            $displays = $this->sfid_model->get_devices_displays_pds($id_displays_pds);
                            $data["id_dis_url"] = $id_displays_pds;

                            foreach ($displays as $key => $display) {
                                $num_devices = $this->tienda_model->count_devices_display($display->id_display);
                                $display->devices_count = $num_devices;
                            }

                            $devices = $this->sfid_model->get_devices_displays_pds($id_displays_pds);
                            $data['devices'] = $devices;
                            $data['displays'] = $displays;



                        }


                    }

                    $data['subtitle'] = 'Planograma tienda: SFID-' . $sfid_plano . ' - '.$display_maestro['display'];
                    $vista = 1;

                }else{
                    /*
                     *  Panelado de la tienda
                     */
                    $tiendas = $this->tienda_model->search_pds($sfid_plano,'Alta');



                    if (!empty($tiendas) && count($tiendas) == 1) {

                        $tienda = NULL;
                        foreach ($tiendas as $tienda_1) {
                            $tienda = $tienda_1;
                        }


                        $id_pds = $tienda->id_pds;

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

                        $data['subtitle'] = 'Panelado tienda: SFID-' . $sfid_plano. '';
                        $vista = 3;
                    }

                }

            }else{
                if(!empty($mueble_plano)){
                    /*
                     *      Maestro del mueble
                     */
                    $devices = $this->tienda_model->get_devices_display($mueble_plano);
                    $data['displays'] = $this->tienda_model->get_displays_demoreal();
                    $data['devices'] = $devices;


                        $display = $this->tienda_model->get_display($mueble_plano);
                        $data['display_name'] = $display['display'];
                        $data['picture_url'] = $display['picture_url'];


                    $data['subtitle'] = 'Planograma mueble: ' . $display['display'];
                    $vista = 2;

                }else{
                    // Form vacio
                    $vista = 0;
                }
            }








        $data["generado_planograma"] = $generado_planograma;


        // Comprobar si existe el segmento PAGE en la URI, si no inicializar a 1..
        $get_page = $this->uri->segment(3);

        if ($get_page === "reset") {
            $this->session->unset_userdata("mueble_plano");
            $this->session->unset_userdata("sfid_plano");
            $this->session->unset_userdata("generado_planograma");

            redirect("master/informe_planogramas", "refresh");

        }


            /** COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal(); */
            $muebles = $this->tienda_model->get_displays_demoreal();
            $data["muebles"] = $muebles;

        $data["vista"] = $vista;






            /* Pasar a la vista */
            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
        $this->load->view('master/header', $data);
        $this->load->view('master/navbar', $data);
        $this->load->view('master/informes/informe_planograma_form', $data);

        switch ($vista) {
            case 1:
                $this->load->view('master/informes/informe_planograma_mueble_sfid',$data);
                break;
            case 2:
                $this->load->view('master/informes/informe_planograma_mueble', $data);
                break;
            case 3:
                $this->load->view('master/informes/informe_planograma_sfid', $data);
                break;
            default:
                $this->load->view('master/informes/informe_planograma', $data);

        }


        $this->load->view('master/footer',$data);


    }
        else
        {
            redirect('master/informe_planogramas','refresh');
        }
    }



    public function informe_planograma_mueble_pds(){

        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $id_pds   = $this->uri->segment(3);
            $id_dis   = $this->uri->segment(4);

            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');
            $this->load->model('sfid_model');
            $this->load->model('informe_model');

            $sfid = $this->tienda_model->get_pds($id_pds);

            $data["generado_planograma"] = FALSE;


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

            // OBTENER DE LA SESION, SI EXISTE
            if ($this->session->userdata("generado_planograma") !== NULL && $this->session->userdata("generado_planograma") === TRUE) {
                $mueble_plano = $this->session->userdata("mueble_plano");
                $sfid_plano = $this->session->userdata("sfid_plano");
                $generado_planograma = $this->session->userdata("generado_planograma");

                $data["mueble_plano"] = $mueble_plano;
                $data["sfid_plano"] = $sfid_plano;
                $data["generado_planograma"] = TRUE;

            }

            /** COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal(); */
            $muebles = $this->tienda_model->get_displays_demoreal();
            $data["muebles"] = $muebles;
            $data['title'] = 'Planograma tienda';
            $data['subtitle'] = 'Planograma tienda [SFID-'.$data['reference'].'] - '.$data['display'];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/informes/informe_planograma_form',$data);
            $this->load->view('master/informes/informe_planograma_ficha_mueble',$data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }

    }


    public function informe_planograma_terminal(){
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

        $data["generado_planograma"] = FALSE;

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

            // OBTENER DE LA SESION, SI EXISTE
            if ($this->session->userdata("generado_planograma") !== NULL && $this->session->userdata("generado_planograma") === TRUE) {
                $mueble_plano = $this->session->userdata("mueble_plano");
                $sfid_plano = $this->session->userdata("sfid_plano");
                $generado_planograma = $this->session->userdata("generado_planograma");

                $data["mueble_plano"] = $mueble_plano;
                $data["sfid_plano"] = $sfid_plano;
                $data["generado_planograma"] = TRUE;

            }

            /** COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal(); */
            $muebles = $this->tienda_model->get_displays_demoreal();
            $data["muebles"] = $muebles;
        $data['title'] = 'Planograma tienda [SFID-'.$data['reference'].']';
        $data['subtitle'] = $data["display"] .' - '. $data["device"];

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('master/header',$data);
        $this->load->view('master/navbar',$data);
        $this->load->view('master/informes/informe_planograma_form',$data);
        $this->load->view('master/informes/informe_planograma_ficha_terminal',$data);
        $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }
}


    /**
     * Tercer informe: visual (panelado)
     */

    /**
     * Punto de entrada del Informe sobre planogramas.
     * Mostrará la vista principal con el formulario de filtrado, y recogerá los datos enviados y los procesará
     * como corresponda.
     */
    public function informe_visual()
    {
        if ($this->auth->is_auth()) {

            /* Incluir los modelos */
            $xcrud = xcrud_get_instance();

            $this->load->model('sfid_model');
            $this->load->model('tienda_model');
            $this->load->model('informe_model');
            $this->load->model('categoria_model');

            $id_tipo_visual = NULL;
            $id_subtipo_visual = NULL;
            $id_segmento_visual = NULL;
            $id_tipologia_visual = NULL;

            $sfid_visual = "";
            $generado_visual = FALSE;
            $vista = NULL;

            $data["title"] = "Informe Visual";


            // Comprobar si existe el segmento PAGE en la URI, si no inicializar a 1..
            $get_page = $this->uri->segment(3);

            if ($get_page === "reset") {
                $this->session->unset_userdata("id_tipo_visual");
                $this->session->unset_userdata("id_subtipo_visual");
                $this->session->unset_userdata("id_segmento_visual");
                $this->session->unset_userdata("id_tipologia_visual");


                $this->session->unset_userdata("sfid_visual");
                $this->session->unset_userdata("generado_visual");
                redirect("master/informe_visual", "refresh");
            }


            if ($this->input->post("generar_informe") === "si") {

                $id_tipo_visual = $this->input->post("id_tipo_visual");
                $id_subtipo_visual = $this->input->post("id_subtipo_visual");
                $id_segmento_visual = $this->input->post("id_segmento_visual");
                $id_tipologia_visual = $this->input->post("id_tipologia_visual");

                $sfid_visual = $this->input->post("sfid_visual");
                $generado_visual = TRUE;

            } else {
                // OBTENER DE LA SESION, SI EXISTE
                if ($this->session->userdata("generado_visual") !== NULL && $this->session->userdata("generado_visual") === TRUE) {

                    $id_tipo_visual = $this->session->userdata("id_tipo_visual");
                    $id_subtipo_visual = $this->session->userdata("id_subtipo_visual");
                    $id_segmento_visual = $this->session->userdata("id_segmento_visual");
                    $id_tipologia_visual = $this->session->userdata("id_tipologia_visual");

                    $sfid_visual = $this->session->userdata("sfid_visual");
                    $generado_visual = $this->session->userdata("generado_visual");
                }
            }

            
            $this->session->set_userdata($data);




            /* Obtener los tipos de tienda para el select */
            /** COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal(); */
            $muebles = $this->tienda_model->get_displays_demoreal();
            $data["muebles"] = $muebles;

            /** COMENTADO SELECT DEMOREAL $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal(); */
            $data["tipos"] = $this->categoria_model->get_tipos_pds();
            $data["subtipos"] = array();
            $data["segmentos"] = $this->categoria_model->get_segmentos_pds($id_segmento_visual);
            $data["tipologias"] = array();



            $data["subtitle"] = "";
            $data["error_panelado"] = FALSE;



            if($generado_visual) {


                if (empty($sfid_visual)) {
                    if (empty($id_tipo_visual) || empty($id_subtipo_visual) || empty($id_segmento_visual) || empty($id_tipologia_visual)) {
                        // Validación de los 4 campos de categorización, que son obligatorios.
                        $vista = 0;
                        $data["error_panelado"] = TRUE;
                    } else {
                        // Cargar muebles del panelado maestro escogido
                        /*
                         *  Panelado de la categoría
                         */
                        $displays = $this->categoria_model->get_displays_categoria($id_tipo_visual,$id_subtipo_visual,$id_segmento_visual,$id_tipologia_visual);

                        //$o_panelado = $this->tienda_model->get_panelado_maestro($panelado_visual);

                        foreach ($displays as $key => $display) {
                            $num_devices = $this->tienda_model->count_devices_display($display->id_display);
                            $display->devices_count = $num_devices;
                        }

                        $data['displays'] = $displays;
                        $data['subtitle'] = 'Tipo de tienda';
                        $vista = 2;
                    }

                } else {
                    // Cargar panelado del sfid.
                    /*
                        *  Panelado de la tienda
                        */
                    $tiendas = $this->tienda_model->search_pds($sfid_visual,'Alta');

                    $id_tipo_visual = $id_subtipo_visual = $id_segmento_visual = $id_tipologia_visual = NULL;
                    if (!empty($tiendas) && count($tiendas) == 1) {

                        $tienda = NULL;
                        foreach ($tiendas as $tienda_1) {
                            $tienda = $tienda_1;
                        }


                        $id_pds = $tienda->id_pds;

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

                        $data['subtitle'] = 'Panelado tienda: SFID-' . $sfid_visual . '';

                    }
                    $vista = 1;
                }
            }


            $data["id_tipo_visual"] = $id_tipo_visual;
            $data["id_subtipo_visual"] = $id_subtipo_visual;
            $data["id_segmento_visual"] = $id_segmento_visual;
            $data["id_tipologia_visual"] = $id_tipologia_visual;

            $data["sfid_visual"] = $sfid_visual;
            $data["generado_visual"] = $generado_visual;
            $this->session->set_userdata($data);
            
            
            $data["vista"] = $vista;

            /* Pasar a la vista */
            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header', $data);
            $this->load->view('master/navbar', $data);
            $this->load->view('master/informes/bloom/visual/informe_visual_form', $data);

            switch ($vista) {
                case 2 :
                    $this->load->view('master/informes/bloom/visual/informe_visual_panelado',$data);
                    break;
                case 1 :
                    $this->load->view('master/informes/bloom/visual/informe_visual_sfid', $data);
                    break;
                default:
                    $this->load->view('master/informes/bloom/visual/informe_visual', $data);

            }

            $this->load->view('master/footer');




        }
        else
        {
            redirect('master','refresh');
        }
    }



    public function informe_visual_mueble($id_mueble){
        if($this->auth->is_auth())
        {

            $id_dis   = $id_mueble;

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model','sfid_model','categoria_model'));
            

            $display = $this->tienda_model->get_display($id_mueble);
            


            $data['id_display']  = $display['id_display'];
            $data['display']     = $display['display'];
            $data['picture_url'] = $display['picture_url'];

            $data['devices'] = $this->tienda_model->get_devices_display($id_dis);

            // Inicialización de campos-categoría
            $id_tipo_visual = $id_subtipo_visual = $id_segmento_visual = 
                    $id_tipologia_visual = $sfid_visual = $generado_visual=  NULL;
            
                       
            
            // OBTENER DE LA SESION, SI EXISTE
            if ($this->session->userdata("generado_visual") !== NULL && $this->session->userdata("generado_visual") === TRUE) {
                $id_tipo_visual = $this->session->userdata("id_tipo_visual");
                $id_subtipo_visual = $this->session->userdata("id_subtipo_visual");
                $id_segmento_visual = $this->session->userdata("id_segmento_visual");
                $id_tipologia_visual = $this->session->userdata("id_tipologia_visual");

                $sfid_visual = $this->session->userdata("sfid_visual");
                $generado_visual = $this->session->userdata("generado_visual");    
            }         
           
            
            $data["id_tipo_visual"] = $id_tipo_visual;
            $data["id_subtipo_visual"] = $id_subtipo_visual;
            $data["id_segmento_visual"] = $id_segmento_visual;
            $data["id_tipologia_visual"] = $id_tipologia_visual;
            $data["sfid_visual"] = $sfid_visual;
            $data["generado_visual"] = $generado_visual;
             $this->session->set_userdata($data);
             
            
            /** COMENTADO SELECT DEMOREAL $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal(); */
            $data["tipos"] = $this->categoria_model->get_tipos_pds();
            $data["subtipos"] = array();
            $data["segmentos"] = $this->categoria_model->get_segmentos_pds($id_segmento_visual);
            $data["tipologias"] = array();

        
            $data['title'] = 'Muebles genéricos de la categoría';
            $data['subtitle'] = 'Planograma mueble  - '.$data['display'];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/informes/bloom/visual/informe_visual_form',$data);
            $this->load->view('master/informes/bloom/visual/informe_visual_maestro_mueble',$data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }

    }

    public function informe_visual_terminal($id_mueble,$id_device)
    {
        if ($this->auth->is_auth()) {
            $id_pds = $this->uri->segment(3);
            $id_dis = $this->uri->segment(4);
            $id_dev = $this->uri->segment(5);

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model','sfid_model','categoria_model'));
            

            /* Obtener los tipos de tienda para el select */
            /** COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal(); */
            $muebles = $this->tienda_model->get_displays();
            $data["muebles"] = $muebles;
            /** COMENTADO SELECT DEMOREAL $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal(); */
            $data["tipos_tienda"] = $this->sfid_model->get_types_pds();


            $display = $this->tienda_model->get_display($id_mueble);

            $data['id_display'] = $display['id_display'];
            $data['display'] = $display['display'];
            $data['picture_url_dis'] = $display['picture_url'];


            $device = $this->tienda_model->get_device($id_device);

            $data['id_device'] = $device['id_device'];
            $data['device'] = $device['device'];
            $data['brand_name'] = $device['brand_name'];
            $data['description'] = $device['description'];
            $data['picture_url_dev'] = $device['picture_url'];


            // Inicialización de campos-categoría
            $id_tipo_visual = $id_subtipo_visual = $id_segmento_visual = $id_tipologia_visual = $sfid_visual = $generado_visual=  NULL;
            
            // OBTENER DE LA SESION, SI EXISTE
            if ($this->session->userdata("generado_visual") !== NULL && $this->session->userdata("generado_visual") === TRUE) {
                $id_tipo_visual = $this->session->userdata("id_tipo_visual");
                $id_subtipo_visual = $this->session->userdata("id_subtipo_visual");
                $id_segmento_visual = $this->session->userdata("id_segmento_visual");
                $id_tipologia_visual = $this->session->userdata("id_tipologia_visual");

                $sfid_visual = $this->session->userdata("sfid_visual");
                $generado_visual = $this->session->userdata("generado_visual");    
            }            
            
            
            echo $id_tipo_visual;
            $data["id_tipo_visual"] = $id_tipo_visual;
            $data["id_subtipo_visual"] = $id_subtipo_visual;
            $data["id_segmento_visual"] = $id_segmento_visual;
            $data["id_tipologia_visual"] = $id_tipologia_visual;
            $data["sfid_visual"] = $sfid_visual;
            $data["generado_visual"] = $generado_visual;
            
            /** COMENTADO SELECT DEMOREAL $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal(); */
            $data["tipos"] = $this->categoria_model->get_tipos_pds();
            $data["subtipos"] = array();
            $data["segmentos"] = $this->categoria_model->get_segmentos_pds($id_segmento_visual);
            $data["tipologias"] = array();


            /** COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal(); */
            $muebles = $this->tienda_model->get_displays();
            $data["muebles"] = $muebles;
            /** COMENTADO SELECT DEMOREAL $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal(); */
            $data["tipos_tienda"] = $this->sfid_model->get_types_pds();

            $data['title'] = 'Panelado genérico';
            $data['subtitle'] = $data["display"] . ' - ' . $data["device"];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header', $data);
            $this->load->view('master/navbar', $data);
            $this->load->view('master/informes/bloom/visual/informe_visual_form', $data);
            $this->load->view('master/informes/bloom/visual/informe_visual_terminal', $data);
            $this->load->view('master/footer');
        } else {
            redirect('master', 'refresh');
        }
    }



    public function informe_visual_mueble_sfid(){
        if($this->auth->is_auth())
        {
            $id_pds   = $this->uri->segment(3);
            $id_dis   = $this->uri->segment(4);

            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');
            $this->load->model('sfid_model');

            $sfid = $this->tienda_model->get_pds($id_pds);

            $data["generado_visual"] = FALSE;


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

            // OBTENER DE LA SESION, SI EXISTE
            if ($this->session->userdata("generado_visual") !== NULL && $this->session->userdata("generado_visual") === TRUE) {
                $tipo_tienda_visual = $this->session->userdata("tipo_tienda_visual");
                $panelado_visual = $this->session->userdata("panelado_visual");
                $sfid_visual = $this->session->userdata("sfid_visual");
                $generado_visual = $this->session->userdata("generado_visual");

                $data["tipo_tienda_visual"] = $tipo_tienda_visual;
                $data["panelado_visual"] = $panelado_visual;
                $data["sfid_visual"] = $sfid_visual;
                $data["generado_visual"] = $generado_visual;

            }
            
            
            // Anular selectores de campos de categoría. Va por SFID
            $data["id_tipo_visual"] = NULL;
            $data["id_subtipo_visual"] = NULL;
            $data["id_segmento_visual"] = NULL;
            $data["id_tipologia_visual"] = NULL;
            
            
            /** COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal(); */
            $muebles = $this->tienda_model->get_displays();
            $muebles = $this->tienda_model->get_displays_demoreal();

            $data["muebles"] = $muebles;
            /** COMENTADO SELECT DEMOREAL $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal(); */
            $data["tipos_tienda"] = $this->sfid_model->get_types_pds();
            $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal();

            $data['title'] = 'Planograma mueble';
            $data['subtitle'] = 'Planograma tienda [SFID-'.$data['reference'].'] - '.$data['display'];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/informes/bloom/visual/informe_visual_form',$data);
            $this->load->view('master/informes/bloom/visual/informe_visual_mueble_sfid',$data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }

    }


    public function informe_visual_ficha_terminal(){
        if($this->auth->is_auth())
        {
            $data["generado_visual"] = FALSE;

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

            // OBTENER DE LA SESION, SI EXISTE
            if ($this->session->userdata("generado_visual") !== NULL && $this->session->userdata("generado_visual") === TRUE) {
                $tipo_tienda_visual = $this->session->userdata("tipo_tienda_visual");
                $panelado_visual = $this->session->userdata("panelado_visual");
                $sfid_visual = $this->session->userdata("sfid_visual");
                $generado_visual = $this->session->userdata("generado_visual");

                $data["tipo_tienda_visual"] = $tipo_tienda_visual;
                $data["panelado_visual"] = $panelado_visual;
                $data["sfid_visual"] = $sfid_visual;
                $data["generado_visual"] = $generado_visual;

            }
            
            // Anular selectores de campos de categoría. Va por SFID
            $data["id_tipo_visual"] = NULL;
            $data["id_subtipo_visual"] = NULL;
            $data["id_segmento_visual"] = NULL;
            $data["id_tipologia_visual"] = NULL;
            
            /** COMENTADO SELECT DEMOREAL $muebles = $this->tienda_model->get_displays_demoreal(); */
            $muebles = $this->tienda_model->get_displays();
            $data["muebles"] = $muebles;

            /** COMENTADO SELECT DEMOREAL $data["tipos_tienda"] = $this->sfid_model->get_types_pds_demoreal(); */
            $data["tipos_tienda"] = $this->sfid_model->get_types_pds();

            $data['title'] = 'Panelado tienda [SFID-'.$data['reference'].']';
            $data['subtitle'] = $data["display"] .' - '. $data["device"];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('master/header',$data);
            $this->load->view('master/navbar',$data);
            $this->load->view('master/informes/bloom/visual/informe_visual_form',$data);
            $this->load->view('master/informes/bloom/visual/informe_visual_ficha_terminal',$data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }
    }


    /**
     * Acción de entrada del Informe: Tiendas por tipología
     */
    public function tiendas_tipologia()
    {
        $controlador = $this->data->get("controlador");
        $entrada = $this->data->get("entrada");

        if($this->auth->is_auth()) {
            $xcrud = xcrud_get_instance();

            $this->load->model("informe_model");
            $this->load->helper("common");

            $data = array();
            $data['title'] = "Resumen de tiendas por tipología";


            $tiendas_tipologia = $this->informe_model->get_informe_tiendas_tipologia();
            $data["tiendas_tipologia"] = $tiendas_tipologia;

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view($controlador . '/header', $data);
            $this->load->view($controlador . '/navbar', $data);
            $this->load->view($controlador . '/informes/tiendas_tipologia/list', $data);
            $this->load->view($controlador . '/footer');
        }
        else
        {
            redirect($entrada,"refresh");
        }
    }

    
    /**
     * Acción de entrada del Informe: Tiendas por fabricante
     */
    public function tiendas_fabricante()
    {
        $controlador = $this->data->get("controlador");
        $entrada = $this->data->get("entrada");

        if($this->auth->is_auth()) {
            $xcrud = xcrud_get_instance();

            $this->load->model("informe_model");
            $this->load->helper("common");
                                  
            $data = array();
            $data['title'] = "Informe de tiendas por fabricante";
            
            // Recoger el fabricante del form
            $data['id_fabricante'] = ($this->input->post('id_fabricante')) ? $this->input->post('id_fabricante') : NULL;       
            
            // Sacar el listado de fabricantes
            $data['fabricantes'] = $this->informe_model->get_displays_fabricantes();            
            // Obtener el resultado.
            $data['resultado'] = $this->informe_model->get_informe_tiendas_fabricante($data['id_fabricante']);            

            
            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view($controlador . '/header', $data);
            $this->load->view($controlador . '/navbar', $data);
            $this->load->view($controlador . '/informes/tiendas_fabricante/list', $data);
            $this->load->view($controlador . '/footer');
        }
        else
        {
            redirect($entrada,"refresh");
        }
    }

    /**
     * Funcion que exporta los datos de las alarmas utilizadas en un año
     */
    public function exportar_sistemas_seguridad($anio,$tipo="incidencias",$formato=NULL) {
        if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 9))
        {
            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $this->load->model('alarma_model');
            $data['alarmas'] = $this->alarma_model->exportar_sistemas_seguridad($anio,$tipo,$ext);
        }
        else
        {
            redirect('master','refresh');
        }
    }

    /**
     * Acción de entrada de la Ayuda
     * @param type $tipo Define qué ayuda mostrar.
     */
	public function ayuda($tipo)
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
	
			$data['title']       = 'Ayuda';
			$data['ayuda_title'] = 'Manuales';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
			$xcrud = xcrud_get_instance();
	
			$data['title']       = 'Ayuda';
			$data['ayuda_title'] = 'Muebles fabricantes';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
            $this->session->sess_destroy();
		}	
		redirect('master','refresh');
	}


    public function mantenimiento()
    {

        $data['bg_image'] = "bg-master.jpg";
        $data['title'] = 'Parada por mantenimiento';

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('common/mantenimiento', $data);
        $this->load->view('backend/footer');
    }

    /**
     * Tabla de pedidos segun el tipo
     */
    public function pedidos($tipo="abiertos")
    {
        if ($this->session->userdata('logged_in')) {

            $xcrud = xcrud_get_instance();
            $this->load->model(array('pedido_model', 'tienda_model', 'sfid_model'));
            $this->load->library('app/paginationlib');


            // Comprobar si existe el segmento PAGE en la URI, si no inicializar a 1..
            $get_page = $this->uri->segment(5); echo ($this->uri->segment(6));
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
                'status'=>'',
                'brand_device'=>'',
                'id_display'=>'',
                'id_device'=>'',
                'id_pedido'=>'',
                'reference'=>''
            );

            /* BORRAR BUSQUEDA */
            $borrar_busqueda = $this->uri->segment(4);
            if($borrar_busqueda === "borrar_busqueda")
            {
                $this->delete_filtros($array_filtros);
                redirect(site_url("/admin/pedidos/".$tipo),'refresh');
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

            if ($tipo=="abiertos") {
                $data['title'] = 'Pedidos abiertos';
                $data['title2'] = 'pedidos abiertos';
            }else {
                $data['title'] = 'Pedidos finalizados';
                $data['title2'] = 'pedidos finalizados';
            }


            $per_page = 20;

            $total_pedidos = $this->pedido_model->get_pedidos_quantity(0,$tipo,$array_sesion);   // Sacar el total de pedidos, para el paginador

            $cfg_pagination = $this->paginationlib->init_pagination("admin/pedidos/".$tipo."/page/",$total_pedidos,$per_page,$segment);


            $this->load->library('pagination',$cfg_pagination);
            $this->pagination->initialize($cfg_pagination);

            $bounds = $this->paginationlib->get_bounds($total_pedidos,$page,$per_page);

            // Indicamos si habrá que mostrar el paginador en la vista
            $data['show_paginator'] = $bounds["show_paginator"];
            $data['num_resultados'] = $bounds["num_resultados"];
            $data['n_inicial'] = $bounds["n_inicial"];
            $data['n_final'] = $bounds["n_final"];
            $data["pagination_helper"]   = $this->pagination;

            $pedidos = $this->pedido_model->get_pedidos($page,$cfg_pagination,$array_orden,$tipo,0,$array_sesion);

            foreach ($pedidos as $pedido) {
                $pedido->nuevos  = $this->chat_model->contar_nuevos($pedido->id,$pedido->reference,"pedidos");
            }



            $data['pedidos'] = $pedidos;
            $data['tipo'] = $tipo;

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
            $this->load->view('master/header', $data);
            $this->load->view('master/navbar', $data);
            $this->load->view('master/pedidos/pedidos', $data);
            $this->load->view('master/footer');
        } else {
            redirect('master', 'refresh');
        }
    }

    /*
    * Obtener el detalle de un pedido seleccionado
    */
    public function detalle_pedido($id_pedido)
    {
        if($this->session->userdata('logged_in'))
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
                redirect('master','refresh');
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

                $chats = $this->chat_model->get_chat_pds($pedido->id,'pedidos');
                //$leido = $this->chat_model->marcar_leido($pedido->id,'altabox','pedidos');
                $data['chats'] = $chats;

                $data['title'] = 'Estado del pedido Ref. '.$id_pedido;

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
                $this->load->view('master/header',$data);
                $this->load->view('master/navbar',$data);
                $this->load->view('master/pedidos/detalle_pedido',$data);
                $this->load->view('master/footer');
            }
        }
        else
        {
            redirect('master','refresh');
        }
    }
    /*
    * exportar el listado de pedidos
    */
    public function exportar_pedidos($tipo="abiertos")
    {
        if ($this->session->userdata('logged_in')) {
            $xcrud = xcrud_get_instance();
            //$data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');
            $id_pds=null;

            $this->load->model(array('pedido_model'));
            // $tipo = $this->uri->segment(3); // TIPO DE pedido

            // Filtros
            $array_filtros = array(
                'status_pds'=>'',
                'brand_device'=>'',
                'id_display'=>'',
                'id_device'=>'',
                'id_pedido'=>'',
                'reference'=> ''
            );
            $array_sesion = $this->get_filtros($array_filtros);


            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();

            $this->pedido_model->exportar_pedidos( $id_pds,$array_orden, $array_sesion, $tipo);


        } else {
            redirect('master', 'refresh');
        }
    }
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */