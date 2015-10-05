<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tienda extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('email','text','xcrud'));
		$this->load->library(array('email','encrypt','form_validation','session'));
        $this->load->library('uri');

        // Carga de la clase de Colección de datos, para pasar variables a la vista.
        $this->load->library('data');
        $this->data->set("controlador","tienda");
        $this->data->set("accion_home","estado_incidencias/abiertas");
        $this->data->set("entrada",($this->data->get("controlador") . '/' . $this->data->get("accion_home")));

        // Config exportacion de ficheros
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
        if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 1)) redirect($entrada);

		if ($this->form_validation->run() == true)
		{
			$data = array(
					'sfid' 	   => strtolower($this->input->post('sfid-login')),
					'password' => $this->input->post('password'),
			);
		}
		
		if ($this->form_validation->run() == true )
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
			$this->load->view('tienda/header',$data);
			$this->load->view('tienda/login',$data);
			$this->load->view('tienda/footer');

	}


    public function do_login(){

        $this->load->model('user_model');
        $data = array(
            'sfid' => strtolower($this->input->post('sfid-login')),
            'password' => $this->input->post('password'),
        );
        if($this->user_model->login($data)){
            return true;
        }else{
            // Redirigir al entorno adecuado al usuario logueado...
            $entorno =$this->user_model->login_entorno($data);
            if($entorno != FALSE) redirect($entorno,"refresh");

            $this->form_validation->set_message('do_login','"SFID" or "password" are incorrect.');
            return false;
        }
    }



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
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 1)) {
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
        if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 1)) {
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


    public function dashboard()
    {
        if ($this->session->userdata('logged_in')) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');



            $xcrud = xcrud_get_instance();


            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model'));

            $data['tiendas'] = $this->tienda_model->search_pds($data['sfid']);

            $sfid = $this->tienda_model->get_pds($data['id_pds']);


            $data['id_pds']     = $sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory']  = $sfid['territory'];
            $data['reference']  = $sfid['reference'];
            $data['address']    = $sfid['address'];
            $data['zip']        = $sfid['zip'];
            $data['city']       = $sfid['city'];


            // Comprobar si existe el segmento PAGE en la URI, si no inicializar a 1..
            $get_page = $this->uri->segment(4);
            if( $this->uri->segment(3) == "incidencias") {
                $page = ( ! empty($get_page) ) ? $get_page : 1 ;
                $segment = 4;
            }else{
                $page = 1;
                $segment = null;
            }


            // Realizar búsqueda por INCIDENCIA o SFID
            $buscar_incidencia = NULL;
            $buscar_sfid = NULL;

            $borrar_busqueda = $this->uri->segment(3);
            if($borrar_busqueda === "borrar_busqueda")
            {
                $this->session->unset_userdata('buscar_incidencia');

                $this->session->unset_userdata('filtro');
                $this->session->unset_userdata('filtro_pds');

                $this->session->unset_userdata('filtro_finalizadas');
                $this->session->unset_userdata('filtro_finalizadas_pds');

                redirect(site_url("/dashboard"),'refresh');
            }

            // Consultar a la session si ya se ha buscado algo y guardado allí.
            $sess_buscar_incidencia = $this->session->userdata('buscar_incidencia');
            if(! empty($sess_buscar_incidencia)) $buscar_incidencia = $sess_buscar_incidencia;

            // Buscar en el POST si hay busqueda, y si la hay usarla y guardarla además en sesion
            $do_busqueda = $this->input->post('do_busqueda');

            // Obtener los filtros: status SAT y stats PDS, primero de Session y despues del post, si procede..
            $filtro = NULL;
            $sess_filtro = $this->session->userdata('filtro');
            if(! empty($sess_filtro)) $filtro = $sess_filtro;

            $filtro_pds = NULL;
            $sess_filtro_pds = $this->session->userdata('filtro_pds');
            if(! empty($sess_filtro_pds)) $filtro_pds = $sess_filtro_pds;


            // Obtener el filtro, primero de Session y despues del post, si procede..
            $do_busqueda_finalizadas = $this->input->post('do_busqueda_finalizadas');

            $filtro_finalizadas = NULL;
            $filtro_finalizadas_pds = NULL;

            $sess_filtro_finalizadas = $this->session->userdata('filtro_finalizadas');
            if(! empty($sess_filtro_finalizadas)) $filtro_finalizadas = $sess_filtro_finalizadas;

            $sess_filtro_finalizadas_pds = $this->session->userdata('filtro_finalizadas_pds');
            if(! empty($sess_filtro_finalizadas_pds)) $filtro_finalizadas_pds = $sess_filtro_finalizadas_pds;

            $post_finalizadas =$this->input->post('filtrar_finalizadas');
            $post_finalizadas_pds =$this->input->post('filtrar_finalizadas_pds');



            if($do_busqueda==="si")
            {

                $buscar_incidencia = $this->input->post('buscar_incidencia');
                $this->session->set_userdata('buscar_incidencia', $buscar_incidencia);

                $post_filtro =$this->input->post('filtrar');
                $filtro = $post_filtro;
                $this->session->set_userdata('filtro',$filtro);

                $post_filtro_pds =$this->input->post('filtrar_pds');
                $filtro_pds = $post_filtro_pds;
                $this->session->set_userdata('filtro_pds',$filtro_pds);


                $filtro_finalizadas = $post_finalizadas;
                $this->session->set_userdata('filtro_finalizadas',$filtro_finalizadas);

                $filtro_finalizadas_pds = $post_finalizadas_pds;
                $this->session->set_userdata('filtro_finalizadas_pds',$filtro_finalizadas_pds);



            }
            $buscador['buscar_sfid']        = $data['sfid']; // Filtramso la tabla por el SFID en sesión
            $buscador['buscar_incidencia']  = $buscar_incidencia;

            $data['buscar_sfid']        = $data['sfid']; // Filtramso la tabla por el SFID en sesión
            $data['buscar_incidencia']  = $buscar_incidencia;

            $data["filtro"] = $filtro;
            $data["filtro_pds"] = $filtro_pds;

            $filtros = array();

            if($filtro != NULL) $filtros["status"] = $filtro;
            if($filtro_pds != NULL) $filtros["status_pds"] = $filtro_pds;

            $data["filtro_finalizadas"] = $filtro_finalizadas;
            $data["filtro_finalizadas_pds"] = $filtro_finalizadas_pds;

            $filtros_finalizadas = array();

            if($filtro_finalizadas != NULL) $filtros_finalizadas["status"] = $filtro_finalizadas;
            if($filtro_finalizadas_pds != NULL) $filtros_finalizadas["status_pds"] = $filtro_finalizadas_pds;


            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $campo_orden_activas = NULL;
            $orden_activas = NULL;

            $sess_campo_orden_activas =  $this->session->userdata('campo_orden_activas');
            if(! empty($sess_campo_orden_activas)) $campo_orden_activas = $sess_campo_orden_activas;
            $sess_orden_activas =  $this->session->userdata('orden_activas');
            if(! empty($sess_orden_activas)) $orden_activas = $sess_orden_activas;

            // viene del form de ordenacion
            $do_orden = $this->input->post('ordenar');

            if($do_orden==='true') {
                $post_orden_form = $this->input->post('form');

                $campo_orden_activas = $this->input->post($post_orden_form.'_campo');
                $orden_activas = $this->input->post($post_orden_form.'_orden');

                $this->session->set_userdata('campo_orden_activas', $campo_orden_activas);
                $this->session->set_userdata('orden_activas', $orden_activas);
            }

            $data["campo_orden_activas"] = $campo_orden_activas;
            $data["orden_activas"] = $orden_activas;

            $this->load->library('app/paginationlib');

            $data['title']           = 'Mis solicitudes';
            $data['title_iniciadas'] = 'Incidencias abiertas';

            $per_page = 100;
            $total_incidencias = $this->tienda_model->get_incidencias_quantity($filtros,$buscador);   // Sacar el total de incidencias, para el paginador
            $cfg_pagination = $this->paginationlib->init_pagination("dashboard/incidencias/",$total_incidencias,$per_page,$segment);


            $this->load->library('pagination',$cfg_pagination);
            $this->pagination->initialize($cfg_pagination);

            // Indicamos si habrá que mostrar el paginador en la vista
            $data['show_paginator'] = false;
            if($total_incidencias > $cfg_pagination['per_page']) $data['show_paginator'] = true;
            // Mostrar párrafo de info páginas
            $data['num_resultados'] = $total_incidencias;

            $n_inicial = ($page - 1) * $per_page + 1;
            $n_inicial = ($n_inicial == 0) ? 1 : $n_inicial;

            $data['n_inicial'] = $n_inicial;

            $n_final = ($n_inicial) + $per_page -1 ;
            $n_final = ($total_incidencias < $n_final) ? $total_incidencias : $n_final;

            $data['n_final'] = $n_final;

            $data["pagination_helper"]   = $this->pagination;

            $incidencias = $this->tienda_model->get_incidencias($page,$cfg_pagination,$campo_orden_activas,$orden_activas,$filtros,$buscador);

            foreach ($incidencias as $incidencia) {
                $incidencia->device = $this->sfid_model->get_device($incidencia->id_devices_pds);
                $incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
                $incidencia->nuevos  = $this->chat_model->contar_nuevos($incidencia->id_incidencia,$incidencia->reference);
                $incidencia->intervencion = $this->intervencion_model->get_intervencion_incidencia($incidencia->id_incidencia);
            }

            $data['incidencias'] = $incidencias;

            /** *****************************************************************************************************
             *          Sacar la info para la tabla de Incidencias Finalizadas.
             * *****************************************************************************************************/
            $data['title_finalizadas'] = 'Incidencias finalizadas';


            // Obtener la página actual del GET y si no existe, definirla a 1
            $get_page = $this->uri->segment(4);
            if( $this->uri->segment(3) == "finalizadas") {
                $page_finalizadas = ( ! empty($get_page) ) ? $get_page : 1 ;
                $segment_finalizadas = 4;
            }else{
                $page_finalizadas = 1;
                $segment_finalizadas = null;
            }




            $per_page = 100;


            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $campo_orden_cerradas = NULL;
            $orden_cerradas = NULL;

            $sess_campo_orden_cerradas =  $this->session->userdata('campo_orden_cerradas');
            if(! empty($sess_campo_orden_cerradas)) $campo_orden_cerradas = $sess_campo_orden_cerradas;
            $sess_orden_cerradas =  $this->session->userdata('orden_cerradas');
            if(! empty($sess_orden_cerradas)) $orden_cerradas = $sess_orden_cerradas;

            // viene del form de ordenacion
            $do_orden = $this->input->post('ordenar_cerradas');
            if($do_orden==='true') {

                $post_orden_form = $this->input->post('form');

                $campo_orden_cerradas = $this->input->post($post_orden_form.'_campo');
                $orden_cerradas = $this->input->post($post_orden_form.'_orden');

                $this->session->set_userdata('campo_orden_cerradas', $campo_orden_cerradas);
                $this->session->set_userdata('orden_cerradas', $orden_cerradas);

            }

            $data["campo_orden_cerradas"] = $campo_orden_cerradas;
            $data["orden_cerradas"] = $orden_cerradas;

            $total_incidencias = $this->tienda_model->get_incidencias_cerradas_quantity($filtros_finalizadas,$buscador);   // Sacar el total de incidencias, para el paginador
            $cfg_pagination = $this->paginationlib->init_pagination("dashboard/finalizadas/",$total_incidencias,$per_page,$segment_finalizadas);

            $cfg_pagination["suffix"] = '#incidencias_cerradas';

            $this->load->library('pagination',$cfg_pagination,'pagination_finalizadas');
            $this->pagination_finalizadas->initialize($cfg_pagination);
            $data["pagination_finalizadas_helper"]   = $this->pagination_finalizadas;

            // Indicamos si habrá que mostrar el paginador en la vista
            $data['show_paginator_finalizadas'] = false;
            if($total_incidencias > $cfg_pagination['per_page']) $data['show_paginator_finalizadas'] = true;
            // Mostrar párrafo de info páginas

            $data['num_resultados_finalizadas'] = $total_incidencias;
            $n_inicial_finalizadas = ($page_finalizadas - 1) * $per_page + 1;
            $n_inicial_finalizadas = ($n_inicial_finalizadas == 0) ? 1 : $n_inicial_finalizadas;

            $data['n_inicial_finalizadas'] = $n_inicial_finalizadas;

            $n_final_finalizadas = ($n_inicial_finalizadas) + $per_page -1 ;
            $n_final_finalizadas = ($total_incidencias < $n_final_finalizadas) ? $total_incidencias : $n_final_finalizadas;
            $data['n_final_finalizadas'] = $n_final_finalizadas;


            $incidencias_finalizadas = $this->tienda_model->get_incidencias_cerradas($page_finalizadas,$cfg_pagination,$filtros_finalizadas,$buscador,$campo_orden_cerradas,$orden_cerradas);

            foreach ($incidencias_finalizadas as $incidencia) {
                $incidencia->device = $this->sfid_model->get_device($incidencia->id_devices_pds);
                $incidencia->display = $this->sfid_model->get_display($incidencia->id_displays_pds);
                $incidencia->nuevos  = $this->chat_model->contar_nuevos($incidencia->id_incidencia,$incidencia->reference);
                $incidencia->intervencion = $this->intervencion_model->get_intervencion_incidencia($incidencia->id_incidencia);
            }

            $data['incidencias_finalizadas'] = $incidencias_finalizadas;

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('tienda/header', $data);
            $this->load->view('tienda/navbar', $data);
            $this->load->view('tienda/dashboard', $data);
            $this->load->view('tienda/footer');
        } else {
            redirect('', 'refresh');
        }
    }


    public function dashboard_exportar()
    {
        if ($this->session->userdata('logged_in')) {
            $xcrud = xcrud_get_instance();


            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model'));
            $acceso = $this->uri->segment(1); // TIPO DE INCIDENCIA
            $tipo = $this->uri->segment(3); // TIPO DE INCIDENCIA


            // Realizar búsqueda por INCIDENCIA o SFID
            $buscar_incidencia = NULL;
            $buscar_sfid = NULL;
            // Consultar a la session si ya se ha buscado algo y guardado allí.
            $sess_buscar_sfid = $this->session->userdata('buscar_sfid');
            $sess_buscar_incidencia = $this->session->userdata('buscar_incidencia');
            if(! empty($sess_buscar_sfid)) $buscar_sfid = $sess_buscar_sfid;
            if(! empty($sess_buscar_incidencia)) $buscar_incidencia = $sess_buscar_incidencia;


            // Obtener los filtros: status SAT y stats PDS, primero de Session y despues del post, si procede..
            $filtro = NULL;
            $sess_filtro = $this->session->userdata('filtro');
            if(! empty($sess_filtro)) $filtro = $sess_filtro;

            $filtro_pds = NULL;
            $sess_filtro_pds = $this->session->userdata('filtro_pds');
            if(! empty($sess_filtro_pds)) $filtro_pds = $sess_filtro_pds;


            if($acceso === "tienda") $buscar_sfid = $this->session->userdata('sfid');
            $buscador['buscar_sfid']        = $buscar_sfid;
            $buscador['buscar_incidencia']  = $buscar_incidencia;

            $filtros = array();

            if($filtro != NULL) $filtros["status"] = $filtro;
            if($filtro_pds != NULL) $filtros["status_pds"] = $filtro_pds;

            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $campo_orden_activas = NULL;
            $orden_activas = NULL;

            $sess_campo_orden_activas =  $this->session->userdata('campo_orden_activas');
            if(! empty($sess_campo_orden_activas)) $campo_orden_activas = $sess_campo_orden_activas;
            $sess_orden_activas =  $this->session->userdata('orden_activas');
            if(! empty($sess_orden_activas)) $orden_activas = $sess_orden_activas;







            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $campo_orden_cerradas = NULL;
            $orden_cerradas = NULL;

            $sess_campo_orden_cerradas =  $this->session->userdata('campo_orden_cerradas');
            if(! empty($sess_campo_orden_cerradas)) $campo_orden_cerradas = $sess_campo_orden_cerradas;
            $sess_orden_cerradas =  $this->session->userdata('orden_cerradas');
            if(! empty($sess_orden_cerradas)) $orden_cerradas = $sess_orden_cerradas;

            $filtro_finalizadas = NULL;
            $filtro_finalizadas_pds = NULL;

            $sess_filtro_finalizadas = $this->session->userdata('filtro_finalizadas');
            if(! empty($sess_filtro_finalizadas)) $filtro_finalizadas = $sess_filtro_finalizadas;

            $sess_filtro_finalizadas_pds = $this->session->userdata('filtro_finalizadas_pds');
            if(! empty($sess_filtro_finalizadas_pds)) $filtro_finalizadas_pds = $sess_filtro_finalizadas_pds;

            $filtros_finalizadas = array();
            if($filtro_finalizadas != NULL) $filtros_finalizadas["status"] = $filtro_finalizadas;
            if($filtro_finalizadas_pds != NULL) $filtros_finalizadas["status_pds"] = $filtro_finalizadas_pds;


            if($tipo === "abiertas") {
                $this->tienda_model->get_incidencias_csv($campo_orden_activas, $orden_activas, $filtros, $buscador, "abiertas");
            }else {
                $this->tienda_model->get_incidencias_csv($campo_orden_cerradas, $orden_cerradas, $filtros_finalizadas, $buscador, "cerradas");
            }


        } else {
            redirect('tienda', 'refresh');
        }
    }

	
	public function dashboard_OLD()
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
                    'last_updated' => $ahora,
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
		if($this->session->userdata('logged_in'))
		{		
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
			
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */