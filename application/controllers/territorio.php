<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Territorio extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('email','text','xcrud'));
		$this->load->library(array('email','encrypt','form_validation','session'));
        $this->load->library('uri');


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


        $entrada = "territorio/estado_incidencias/abiertas";

        // Ya está logueado....
        if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 12)) redirect($entrada);

	
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
				
			$this->load->view('territorio/header',$data);
			$this->load->view('territorio/login',$data);
			$this->load->view('territorio/footer');

	}


    public function do_login(){

        $this->load->model('user_model');
        $data = array(
            'sfid' => strtolower($this->input->post('sfid-login')),
            'password' => $this->input->post('password'),
        );
        if($this->user_model->login_tipo($data,12)){
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
        if(is_array($array_filtros)){
            foreach($array_filtros as $filtro){
                if(!in_array($filtro,$array_excepciones)) {
                    $this->session->unset_userdata($filtro);
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
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 12)) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();


            $this->load->model(array('intervencion_model', 'incidencia_model', 'tienda_model', 'sfid_model','chat_model'));
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
                'id_display'=>'',
                'id_device'=>'',
                'id_incidencia' => '',
                'reference' => ''
            );

            /* BORRAR BUSQUEDA */
            $borrar_busqueda = $this->uri->segment(4);
            if($borrar_busqueda === "borrar_busqueda")
            {
                $this->delete_filtros($array_filtros);
                redirect(site_url("/territorio/estado_incidencias/".$tipo),'refresh');
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
            $cfg_pagination = $this->paginationlib->init_pagination("territorio/estado_incidencias/$tipo/page/",$total_incidencias,$per_page,$segment);


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
                $incidencia->device = $this->sfid_model->get_device($incidencia->id_devices_pds);
                $incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
                $incidencia->nuevos  = $this->chat_model->contar_nuevos($incidencia->id_incidencia,$incidencia->reference);
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

            $this->load->view('territorio/header', $data);
            $this->load->view('territorio/navbar', $data);
            $this->load->view('territorio/estado_incidencias/'.$tipo, $data);
            $this->load->view('territorio/footer');
        } else {
            redirect('territorio', 'refresh');
        }
    }



    public function exportar_incidencias($tipo="abiertas",$formato=NULL)
    {
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 12)) {
            $xcrud = xcrud_get_instance();

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG

            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model'));
            $tipo = $this->uri->segment(3); // TIPO DE INCIDENCIA


            // Filtros
            $array_filtros = array(
                'status'=>'',
                'status_pds'=>'',
                'territory'=>'',
                'brand_device'=>'',
                'id_display'=>'',
                'id_device'=>'',
                'id_incidencia'=>'',
                'reference'=> ''
            );
            $array_sesion = $this->get_filtros($array_filtros);


            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();



                $this->incidencia_model->exportar_incidencias($array_orden, $array_sesion, $tipo,$ext);



        } else {
            redirect('territorio', 'refresh');
        }
    }



    public function detalle_incidencia($id_incidencia,$id_pds)
	{
		if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 12))
		{
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
				redirect('territorio','refresh');
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
		
				$this->load->view('territorio/header',$data);
				$this->load->view('territorio/navbar',$data);
				$this->load->view('territorio/detalle_incidencia',$data);
				$this->load->view('territorio/footer');
			}				
		}
		else
		{
			redirect('territorio','refresh');
		}
	}
	

	
	public function logout()
	{
		if($this->session->userdata('logged_in'))
		{		
			$this->session->unset_userdata('logged_in');
            $this->session->sess_destroy();
		}	
		redirect('territorio','refresh');
	}


    public function mantenimiento()
    {

        $data['bg_image'] = "bg-master.jpg";
        $data['title'] = 'Parada por mantenimiento';

        $this->load->view('territorio/header', $data);
        $this->load->view('common/mantenimiento', $data);
        $this->load->view('territorio/footer');
    }
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */