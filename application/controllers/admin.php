<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('email', 'text', 'xcrud'));

        $this->load->library(array('email', 'encrypt', 'form_validation', 'session','pagination'));
        $this->load->library('uri');
        $this->load->library('auth',array(10));

        // Carga de la clase de Colección de datos, para pasar variables a la vista.
        $this->load->library('data');
        $this->data->set("controlador","admin");
        $this->data->set("acceso","admin");
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



        $this->form_validation->set_rules('sfid-login', 'SFID', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'required|xss_clean');
        $this->form_validation->set_message('login_error', '"Username" or "Password" are incorrect.');


        // Ya está logueado....
        $entrada = $this->data->get("entrada");
        if($this->auth->is_auth()) redirect($entrada);

        if ($this->form_validation->run() == true) {
            $data = array(
                'sfid' => strtolower($this->input->post('sfid-login')),
                'password' => $this->input->post('password'),
            );
        }

        if ($this->form_validation->run() == true) {
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
        $this->load->view('backend/header', $data);
        $this->load->view('backend/login', $data);
        $this->load->view('backend/footer');

    }


    public function do_login(){

        $this->load->model('user_model');
        $data = array(
            'sfid' => strtolower($this->input->post('sfid-login')),
            'password' => sha1($this->input->post('password')),
        );
        if($this->user_model->login_admin($data)){
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



    public function get_multifiltro_post($nombre_campo){

        $resultado = array();
        foreach($this->input->post($nombre_campo) as $valor_post){
            $resultado[] = $valor_post;
        }
        return $resultado;
    }

    /**
     * Tabla de incidencias cuyo tipo son "abiertas" o "cerradas"
     * (Antiguo dashboard)
     */
    public function estado_incidencias($tipo)
    {

        if ($this->auth->is_auth()) {
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
                'id_display'=>'',
                'id_device'=>'',

                'id_supervisor' => '',
                'id_provincia' => '',

                'id_incidencia' => '',
                'reference' => '',
                'id_intervencion'=>'',

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
                redirect(site_url("/admin/estado_incidencias/".$tipo),'refresh');
            }
            // Consultar a la session si ya se ha buscado algo y guardado allí.
            $array_sesion = $this->get_filtros($array_filtros);
            // Buscar en el POST si hay busqueda, y si la hay usarla y guardarla además en sesion

            if($this->input->post('do_busqueda')==="si") $array_sesion = $this->set_filtros($array_filtros);

            //Descomentar en el caso de tener que forzar el estado de las incidencias al seleccionar el tipo de incidencia
            /*if($array_sesion['id_tipo_incidencia']!="" && $tipo==="abiertas") {
                $array_sesion['status']="Revisada";
            }*/


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
            $cfg_pagination = $this->paginationlib->init_pagination("admin/estado_incidencias/$tipo/page/",$total_incidencias,$per_page,$segment);


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
                //$incidencia->nuevos  = $this->chat_model->contar_nuevos($incidencia->id_incidencia,$incidencia->reference);
                $incidencia->intervencion = $this->intervencion_model->get_intervencion_incidencia($incidencia->id_incidencia);
            }




            $data['incidencias'] = $incidencias;
            $data['tipo'] = $tipo;

            $data['mensajes_nuevos'] = $this->chat_model->existen_mensajes_nuevos($tipo);
            if($tipo=='abiertas') {
                $data['mensajes_nuevosC'] = $this->chat_model->existen_mensajes_nuevos('cerradas');
            }


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

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/estado_incidencias/'.$tipo, $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }



    public function exportar_incidencias($tipo="abiertas",$formato=NULL,$porrazon=NULL)
    {
        if ($this->auth->is_auth()) {
            $xcrud = xcrud_get_instance();
            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model','incidencia_model'));

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG



            // Filtros abiertas
            $array_filtros = array(
                'status' => '',
                'status_pds' => '',
                'territory' => '',
                'brand_device' => '',
                'id_display'=>'',
                'id_device'=>'',
                'id_supervisor' => '',
                'id_provincia' => '',

                'id_incidencia' => '',
                'reference' => '',
                'id_intervencion'=>'',

                'id_tipo'=>'',
                'id_subtipo'=>'',
                'id_segmento'=>'',
                'id_tipologia'=>'',
                'id_tipo_incidencia'=>''
            );
            $array_sesion = $this->get_filtros($array_filtros);


            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();



            $this->incidencia_model->exportar_incidencias($array_orden, $array_sesion, $tipo, $ext,$porrazon);



        } else {
            redirect('admin', 'refresh');
        }
    }


    /**
     * Método que comprueba si una incidencia ya está comunicada, o en un paso posterior.
     * Devuelve true si lo está y false en caso contrario.
     */

    public function material_editable($status=''){
        $resultado = TRUE;

        /**
         * Orden numérico de estados del SAT, para poder usar en la vista.
         */
        $n_status_sat = array();
        $n_status_sat["Nueva"] = 1;
        $n_status_sat["Revisada"] = 2;
        $n_status_sat["Instalador asignado"] = 3;
        $n_status_sat["Material asignado"] = 4;
        $n_status_sat["Comunicada"] = 5;
        $n_status_sat["Resuelta"] = 6;
        $n_status_sat["Pendiente recogida"] = 7;
        $n_status_sat["Cerrada"] = 8;
        $n_status_sat["Cancelada"] = 9;


        if($n_status_sat[$status] == 1 || $n_status_sat[$status] >= $n_status_sat["Comunicada"]) $resultado = FALSE;
        return $resultado;
    }



    public function material_retorno()
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model', 'sfid_model'));

            $data['material_retorno'] = $this->tienda_model->material_retorno();

            $data['title'] = 'Material retorno';


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/material_retorno', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function cambio_sfid($sfid_enuso=NULL)
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model', 'sfid_model'));

            $data['tiendas'] =  $this->tienda_model->search_pds($this->input->post('sfid'),'Alta');

            $data['title'] = 'Cambio de SFID';

            $data['enuso'] = $sfid_enuso;


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/cambio_sfid', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function cierre_pdv($sfid='', $paso = NULL, $resultado=NULL)
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model', 'sfid_model'));

            $baja_sfid = $this->input->post('sfid');

            $data['tiendas'] =  $this->tienda_model->search_pds($this->input->post('sfid'),'Alta');
            $data['baja_sfid'] = ($this->input->post('sfid')) ? $this->input->post('sfid') : $sfid;
            $data['id_pds'] = $this->tienda_model->search_id_pds($baja_sfid);

            $data['title'] = 'Cierre PdV';

            // Comprobar si viene de algún paso que haya dado error
            $err = NULL;
            if(!is_null($paso) && !$resultado)
            {
                switch($paso)
                {
                    case 0: $err = "incidencias"; break;
                    case 1: $err = false; break;    // Fin proceso
                }
            }
            $data['error'] = $err;

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/cierre_pdv', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }



    public function update_cierre_pdv($paso=0)
    {
        if ($this->auth->is_auth())
        {
            $this->load->model(array('tienda_model', 'sfid_model'));

            $sfid = $this->input->post('reference');    // Recoger SFID del form

            $continuar_proceso = NULL;

            // Comprobar si hay incidencias abiertas asociadas
            $incidencias = $this->sfid_model->check_incidencias_abiertas($sfid);
            $continuar_proceso = ($incidencias->abiertas > 0) ? false : true;


            if($continuar_proceso)
            {
                // Damos de alta en el historico de cierres
                $id_pds = $this->input->post("id_pds");

                if(!empty($id_pds))
                {
                    $data_sfid = array("sfid"=>$sfid, "id_pds"=>$id_pds, "fecha"=>date("Y-m-d H:i:s"));
                    $this->sfid_model->alta_historico_cierre_sfid($data_sfid);
                    $this->tienda_model->borrar_agente($sfid);
                    $this->tienda_model->borrar_dispositivos($id_pds);
                    $this->tienda_model->borrar_muebles($id_pds);
                    $this->tienda_model->cerrar_pds($sfid,$id_pds);
                    //$this->tienda_model->borrar_pds($this->input->post('reference'));

                    redirect('admin/cierre_pdv/' . $sfid . '/1', 'refresh');
                }




            }
            else
            {
                redirect('admin/cierre_pdv/' . $sfid . '/' . $paso . '/' . $continuar_proceso, 'refresh');
            }
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }


    public function update_cierre_pdv_OLD()
    {
        if ($this->auth->is_auth())
        {
            $this->load->model(array('tienda_model', 'sfid_model'));

            /* TODO Gestionar cierre incidencias */
            $this->tienda_model->borrar_agente($this->input->post('reference'));
            /* TODO Listado material retorno */
            $this->tienda_model->borrar_dispositivos($this->input->post('reference'));
            $this->tienda_model->borrar_muebles($this->input->post('reference'));
            $this->tienda_model->cerrar_pds($this->input->post('reference'));
            //$this->tienda_model->borrar_pds($this->input->post('reference'));

            redirect('admin/cierre_pdv', 'refresh');
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }

    /**
     * Operación de apertura de PDV, dándole un SFID. Una vez indicado, pasa al form de update_apertura_pdv.
     * Y desde allí, vuelve de nuevo aquí.
     */
    public function apertura_pdv()
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $accion = $this->uri->segment(3);

            if($accion=="alta" || $accion=="existe"){
                $data['alta_sfid'] = $this->uri->segment(4);
            }
            $data["accion"] = $accion;

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model', 'sfid_model'));

            $sfid_alta = $this->input->post('sfid');
            $id_pds =  $this->tienda_model->search_id_pds($sfid_alta);


            $data['tiendas'] =  $this->tienda_model->search_pds($sfid_alta);
            $data['id_pds'] = $id_pds;

            //print_r($data['tiendas']);
            $data['title'] = 'Apertura PdV';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/apertura_pdv', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    /**
     * Una vez se aporta el SFID, ésta le asigna los muebles y dispositivos que correspondan.
     */
    public function update_apertura_pdv()
    {
        if ($this->auth->is_auth())
        {
            $this->load->model(array('tienda_model', 'sfid_model','categoria_model'));

            $sfid = $this->input->post("reference");

            /* FIXME: revisar alta correcta en tabla de agentes */
            $data = array(
                'sfid'      => $sfid,
                'password'  => sha1('demoreal'),
                'type'      => 1
            );

            $id_agent = $this->tienda_model->alta_agente($data);

            //if(is_null($id_agent)) redirect('admin/apertura_pdv/alta/'.$sfid, 'refresh');

            // Validar "panelado" BLOOM
            $PDS = $this->tienda_model->get_sfid($sfid,"object");

            $existe_panelado = $this->categoria_model->existe_mobiliario($PDS->id_tipo,$PDS->id_subtipo,$PDS->id_segmento,$PDS->id_tipologia);

            // Comprobamos que no exista ya la apertura
            $muebles_pds = $this->tienda_model->get_displays_pds($PDS->id_pds);
            $ya_abierto = (!empty($muebles_pds)) ? TRUE : FALSE;

            if($existe_panelado && !$ya_abierto){

                $muebles = $this->categoria_model->get_displays_categoria($PDS->id_tipo,$PDS->id_subtipo,$PDS->id_segmento,$PDS->id_tipologia);
                foreach($muebles as $mueble)
                {
                    $this->tienda_model->anadir_mueble_sfid($mueble,$PDS,$mueble->position);
                }

                redirect('admin/apertura_pdv/alta/'.$sfid, 'refresh');
            }
            /*$this->tienda_model->borrar_dispositivos($sfid);
            $this->tienda_model->borrar_muebles($sfid);*/

            //redirect('admin/get_inventarios_sfid/'.$this->input->post('reference').'/alta/volver/apertura_pdv');
            redirect('admin/apertura_pdv/existe/'.$sfid, 'refresh');

        }
        else
        {
            redirect('admin', 'refresh');
        }
    }



    public function update_dispositivo()
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model', 'sfid_model'));

            $data['dispositivos']    =  $this->tienda_model->search_dispositivo_id($this->input->post('dipositivo_almacen_1'));

            $data['title'] = 'Carga datos dispositivo';


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////

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
        if ($this->auth->is_auth())
        {
            $this->load->model(array('tienda_model', 'sfid_model'));

            $sfid_new = $this->input->post('sfid_new');
            if ( $sfid_new  <> '')
            {
                // Comprobamos que el SFID nuevo no esté en uso

                $checkSfid = $this->tienda_model->search_pds($sfid_new,'Alta');



                if(empty($checkSfid))
                {
                    $historico_sfid = array(
                        'id_pds' => $this->input->post('id_pds'),
                        'fecha' => date('Y-m-d H:i:s'),
                        'sfid_old' => $this->input->post('sfid_old'),
                        'sfid_new' => $this->input->post('sfid_new')
                    );

                    $this->tienda_model->incidencia_update_sfid($this->input->post('sfid_old'), $this->input->post('sfid_new'));
                    $this->tienda_model->incidencia_update_historico_sfid($historico_sfid);
                    redirect('admin/cambio_sfid/FALSE', 'refresh');
                }else
                {
                    redirect('admin/cambio_sfid/'.$sfid_new, 'refresh');
                }
            }


        }
        else
        {
            redirect('admin', 'refresh');
        }
    }

    public function operar_incidencia()
    {
        if ($this->auth->is_auth()) {
            /*$id_pds = $this->uri->segment(3);
            $id_inc = $this->uri->segment(4);*/

            $id_pds = $this->uri->segment(3);
            $id_inc = $this->uri->segment(4);

            $xcrud = xcrud_get_instance();
            $this->load->model(array('chat_model', 'intervencion_model', 'tienda_model', 'sfid_model'));

            $sfid = $this->tienda_model->get_pds($id_pds);

            $data["pds"] = $sfid;

            $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
            ////$data['type_pds'] = $sfid['pds'];
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

            $data['last_updated'] = date("d/m/Y",strtotime($incidencia['last_updated']));
            $data['status_pds'] = $incidencia['status'];
            $historico_revisada = $this->tienda_model->historico_fecha($id_inc,'Revisada');
            $data['historico_revisada'] =  isset($historico_revisada['fecha']) ? date("d/m/Y",strtotime($historico_revisada['fecha'])) : '';

            $historico_instalador_asignado = $this->tienda_model->historico_fecha($id_inc,'Instalador asignado');
            $data['historico_instalador_asignado'] =  isset($historico_instalador_asignado['fecha']) ? date("d/m/Y",strtotime($historico_instalador_asignado['fecha'])) : '';

            $historico_material_asignado = $this->tienda_model->historico_fecha($id_inc,'Material asignado');
            $data['historico_material_asignado'] =  isset($historico_material_asignado['fecha']) ? date("d/m/Y",strtotime($historico_material_asignado['fecha'])) : '';

            $historico_fecha_comunicada = $this->tienda_model->historico_fecha($id_inc,'Comunicada');
            $data['historico_fecha_comunicada'] =  isset($historico_fecha_comunicada['fecha']) ? date("d/m/Y",strtotime($historico_fecha_comunicada['fecha'])) : '';

            $historico_fecha_resuelta = $this->tienda_model->historico_fecha($id_inc,'Resuelta');
            $data['historico_fecha_resuelta'] =  isset($historico_fecha_resuelta['fecha']) ? date("d/m/Y",strtotime($historico_fecha_resuelta['fecha'])) : '';

            $incidencia['intervencion'] = $this->intervencion_model->get_intervencion_incidencia($id_inc);

            $incidencia['device'] = $this->sfid_model->get_device($incidencia['id_devices_pds']);
            $incidencia['display'] = $this->sfid_model->get_display($incidencia['id_displays_pds']);
            $data['incidencia'] = $incidencia;


            $data['fail_device'] = $incidencia['fail_device'];
            $data['alarm_display'] = $incidencia['alarm_display'];
            $data['alarm_device'] = $incidencia['alarm_device'];
            $data['alarm_garra'] = $incidencia['alarm_garra'];

            $material_editable = $this->material_editable($incidencia['status']);
            $data['material_editable'] = $material_editable;

            $material_dispositivos = $this->tienda_model->get_material_dispositivos($incidencia['id_incidencia']);
            $data['material_dispositivos'] = $material_dispositivos;

            $material_alarmas = $this->tienda_model->get_material_alarmas($incidencia['id_incidencia']);
            $data['material_alarmas'] = $material_alarmas;

            $data['tipos_incidencia'] = $this->tienda_model->get_tipos_incidencia();
            $data['soluciones'] = $this->tienda_model->get_soluciones_incidencia();


            $chats = $this->chat_model->get_chat_incidencia_pds($incidencia['id_incidencia']);
            $leido = $this->chat_model->marcar_leido($incidencia['id_incidencia'],$sfid['reference']);
            $data['chats'] = $chats;

            $data['title'] = 'Operativa incidencia Ref. '.$data['id_inc_url'];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/operar_incidencia', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function actualizar_averia()
    {
        if ($this->auth->is_auth())
        {
            $this->load->model('incidencia_model');

            $actualizar_averia = $this->input->post('actualizar_averia');
            if($actualizar_averia=="si")
            {
                $id_incidencia = $this->input->post('id_incidencia');

                if(!empty($id_incidencia) && is_numeric($id_incidencia))
                {
                    $averia = array();
                    $averia['id_type_incidencia'] =  ($this->input->post('tipo_averia') != "NULL") ? $this->input->post('tipo_averia') : 'NULL';
                    $averia['fail_device'] =    ($this->input->post('fail_device')   == "on") ? 1 : 0;
                    $averia['alarm_display'] =  ($this->input->post('alarm_display') == "on") ? 1 : 0;
                    $averia['alarm_device'] =   ($this->input->post('alarm_device')  == "on") ? 1 : 0;
                    $averia['alarm_garra'] =    ($this->input->post('alarm_garra')   == "on") ? 1 : 0;
                    $averia['id_solucion_incidencia'] =  ($this->input->post('id_solucion_incidencia') != "NULL") ? $this->input->post('id_solucion_incidencia') : 'NULL';


                    $respuesta = $this->incidencia_model->actualizar_averia($id_incidencia,$averia);

                    echo ($respuesta) ? "Datos actualizados" : 'Datos no actualizados';
                }
            }
        }
    }

    public function imprimir_incidencia()
    {
        if ($this->auth->is_auth()) {
            $id_pds = $this->uri->segment(3);
            $id_inc = $this->uri->segment(4);
            $envio_mail=$this->uri->segment(5);



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


            $info_intervencion = $this->intervencion_model->get_info_intervencion($incidencia['intervencion']);
            $data['id_parte'] = $info_intervencion->operador->id_parte;



            $data['title'] = 'DOCUMENTACIÓN DE RESOLUCIÓN DE INCIDENCIA';

            // Salida PDF
            $html = $this->load->view('backend/imprimir_incidencia', $data, true);
            $filename_pdf = 'intervencion-'.$incidencia['intervencion'].'_incidencia-'.$incidencia['id_incidencia'];
            $created_pdf = pdf_create($html, $filename_pdf,FALSE);

            file_put_contents("uploads/intervenciones/".$filename_pdf.".pdf",$created_pdf);
            $attach =  "uploads/intervenciones/".$filename_pdf.".pdf";



            /** ENVIO DEL EMAIL al operador*/

            if($envio_mail === "notificacion")
            {


                $mail_operador = $info_intervencion->operador->email;
                $mail_cc = $info_intervencion->operador->email_cc;


                if (!empty($mail_cc)) {
                    $a_mail_cc = explode(",", $mail_cc);
                    foreach ($a_mail_cc as $key => $mail) {
                        $a_mail_cc[$key] = trim($mail);
                    }
                    $mail_cc = implode(",", $a_mail_cc);
                }


                if (empty($mail_operador)) {
                    $data['email_sent'] = FALSE;
                } else {
                    /**
                     * El asunto al ir en los headers del email, no puede pasar de 62 caracteres. Ahora hay margen pero si en el futuro el email
                     * se ve raramente, revisad que el asunto no haya crecido más de 62 chars, en primer lugar.
                     */
                    $subject = "DEMOREAL / SFID " . $sfid['reference'] . " / INC " . $incidencia['id_incidencia'] . " / INT " . $incidencia['intervencion'];

                    $message_operador = "Asunto: " . $subject . "\r\n\r\n";
                    $message_operador .= "En referencia a los datos indicados en Asunto, adjunto remitimos parte para la intervención." . "\r\n";
                    $message_operador .= "Recordamos los pasos principales del procedimiento:" . "\r\n\r\n";
                    $message_operador .= "1) Realizar intervención dentro de las 48h siguientes a la recepción del email." . "\r\n";
                    $message_operador .= "2) Enviar el presente parte rellenado y con la firma de la persona encargada de la tienda al email demoreal@focusonemotions.com." . "\r\n";
                    $message_operador .= "3) Preparar en bolsa independiente todo el material sobrante y defectuoso separado por incidencia." . "\r\n";
                    $message_operador .= "4) Enviar email con el material preparado a demoreal@focusonemotions.com." . "\r\n";
                    $message_operador .= "Demo Real" . "\r\n";
                    $message_operador .= "http://demoreal.focusonemotions.com/" . "\r\n\n";

                    $this->email->from('demoreal@focusonemotions.com', 'Demo Real');

                    if($mail_operador=="no-reply@altabox.net"){
                        // No enviamos mail, si el instalador es por defecto, y tiene la cuenta no-reply
                    }else {
                        $this->email->to($mail_operador);
                    }

                    if (!empty($mail_cc)) {
                        $this->email->cc($mail_cc);
                    }
                    /** COMENTADO AVISO COPIA */
                    $this->email->bcc('demoreal@focusonemotions.com');

                    $this->email->subject($subject);
                    $this->email->message($message_operador);

                    $this->email->attach($attach);

                    if ($this->email->send()) {
                        $data['email_sent'] = TRUE;
                    } else {
                        $data['email_sent'] = FALSE;
                    }
                    $this->email->clear();
                }
                /** FIN ENVIO DEL EMAIL al operador*/


            }else{
                $data['email_sent'] = NULL; // Descarga sin notificacion
            }
            // Paso a vista
            $data['title'] = 'Generación del parte para la incidencia ['.$incidencia['id_incidencia'].']';
            $data['filename_pdf'] = $filename_pdf;

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////

            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/descargar_parte', $data);
            $this->load->view('backend/footer');



        } else {
            redirect('admin', 'refresh');
        }
    }

    /**
     * Método llamado desde la vista de Impresión del parte de incidencia para descargar un PDF en el equipo.
     * @param $filename
     */
    public function descargar_parte($filename)
    {

        $f = $filename.".pdf";
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$f\"\n");
        $fp=fopen("uploads/intervenciones/$f", "r");
        fpassthru($fp);
    }

    public function insert_chat()
    {
        if ($this->auth->is_auth()) {
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
                $error = 'Ha fallado la carga de la foto.';
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////

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
        $status_ext = $this->uri->segment(7);


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

        /**
         * Botón Asignar material
         */
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



        $fecha_cierre = $this->input->post('fecha_cierre');

        if(empty($fecha_cierre)) { $fecha_cierre = date('Y-m-d H:i:s'); }

        /**
         * Botón resolver Incidencia : Recoge fecha de resolución y hace la operación
         */
        if ($status == 6)
        {

            $this->tienda_model->incidencia_update_cierre($id_inc, $fecha_cierre);

            if ($incidencia['fail_device'] == 1) {
                $this->tienda_model->incidencia_update_device_pds($incidencia['id_devices_pds'], 1);
            }

        }

        /**
         * CIERRE FORZOSO
         */
        if (($status == 8) AND ($status_ext == 'ext'))
        {

            if ($incidencia['fail_device'] == 1) {
                $this->tienda_model->incidencia_update_device_pds($incidencia['id_devices_pds'], 1,$id_inc);
            }
            $this->tienda_model->incidencia_update_cierre($id_inc, $fecha_cierre);
        }

        /**
         * Guardar incidcencia en el histórico
         */
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


        /**
         * Notificación a instalador
         */
        if ($status == 5)
        {
            $envio_mail = $this->uri->segment(7);

            // Se va a proceder a la notificación de la incidencia por lo que el material asignado ya será inamovible
            // Y por tanto deberá procesarse (procesado=1) y así verse en el histórico Diario de almacén.
            $this->tienda_model->procesar_historico_incidencia($id_inc);

            redirect('admin/imprimir_incidencia/'.$id_pds.'/'.$id_inc.'/'.$envio_mail, 'refresh');
        }
        else
        {
            redirect('admin/operar_incidencia/'.$id_pds.'/'.$id_inc, 'refresh');
        }


    }


    public function reset_incidencia_status()
    {
        if($this->auth->is_auth()) {

            $id_inc = $this->uri->segment(3);

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model', 'intervencion_model','incidencia_model'));


            $data["title"] = "Resetear estado de una incidencia";
            $data["mensaje_alerta"] = '¿Seguro que deseas resetear la incidencia Nº ##NUM_INC##? El proceso es irreversible.';

            if (empty($id_inc)) {
                // Aún no ha indicado el ID de incidencia a actualizar.



                $resetear_incidencia = $this->input->post('resetear_incidencia');

                $data["mensaje_error"] = ($resetear_incidencia === 'si') ? 'Debes introducir el Identificador de la incidencia a resetear' : '';

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
                $this->load->view('backend/header', $data);
                $this->load->view('backend/navbar', $data);
                $this->load->view('backend/reset_incidencia', $data);
                $this->load->view('backend/footer', $data);
            } else {



                $o_pds = $this->db->query("SELECT id_pds FROM incidencias WHERE id_incidencia = '$id_inc' ")->row_array();

                $id_pds = (isset($o_pds["id_pds"])) ? $o_pds["id_pds"] : NULL;

                $data["id_pds"] = $id_pds;
                $data["id_inc"] = $id_inc;


                // Ya tenemos el ID....
                // - Comprobamos que exista la incidencia y no esté finalizada
                // - Reseteamos el status y status_pds
                // - Eliminamos del histórico de estados las entradas...
                // - Eliminamos la intervención
                // - Eliminamos el material asignado.
                $mensaje_error = "";
                $mensaje_exito = "";

                $sql_check = ' SELECT id_incidencia FROM incidencias WHERE status NOT IN ("Cerrada","Cancelada","Resuelta") AND status_pds != "Finalizada" AND id_incidencia = "'.$id_inc.'" ';
                $query = $this->db->query($sql_check);
                $check = $query->row_array();


                if(empty($check))
                {
                    // La incidencia no existe, o no es reseteable...
                    $mensaje_error = "La incidencia no existe o no es reseteable por estar Finalizada.";
                }
                else
                {
                    // La incidencia exsite, seguimos con el proceso de reseteo...
                    // Reseteamos estado de la incidencia
                    $sql_status = 'UPDATE incidencias SET fecha_cierre = NULL, status="Nueva", status_pds = "Alta realizada" WHERE id_incidencia="'.$id_inc.'"';
                    $query = $this->db->query($sql_status);

                    // Sacar el ID de intervención asignada a la incidencia...
                    $sql_intervencion = 'SELECT id_intervencion FROM intervenciones_incidencias WHERE id_incidencia = "'.$id_inc.'"';
                    $query = $this->db->query($sql_intervencion);
                    $resultado_intervencion = $query->row_array();
                    if(!empty($resultado_intervencion)){

                        // Ahora borramos la relación de la incidencia con la intervención...
                        $id_intervencion = $resultado_intervencion["id_intervencion"];
                        $sql_int_inc = 'DELETE FROM intervenciones_incidencias WHERE id_incidencia = "'.$id_inc.'"';
                        $this->db->query($sql_int_inc);

                        // Si no hay otras incidencias relacionadas con la misma intervención, borramos la intervención...
                        $sql_int_inc = 'SELECT id_incidencia, id_intervencion FROM intervenciones_incidencias WHERE id_intervencion = "'.$id_intervencion.'"';
                        $query = $this->db->query($sql_int_inc);
                        $resultados = $query->result();

                        if(empty($resultados))
                        {
                            $this->db->query('DELETE FROM intervenciones WHERE id_intervencion ="'.$id_intervencion.'"');
                        }


                        // Eliminamos de la facturación la intervención...
                        $this->db->query('DELETE FROM facturacion WHERE id_intervencion = "'.$id_intervencion.'"');

                    }


                    // Borramos el histórico de estados
                    $this->db->query('DELETE FROM historico WHERE id_incidencia = "'.$id_inc.'"');


                    // Borramos el material asignado
                    $this->incidencia_model->desasignar_material($id_inc);

                    // Proceso finalizado
                    $mensaje_exito = "La incidencia Nº $id_inc se ha reseteado correctamente.";
                }


                $data["mensaje_error"] = $mensaje_error;
                $data["mensaje_exito"] = $mensaje_exito;

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
                $this->load->view('backend/header', $data);
                $this->load->view('backend/navbar', $data);
                $this->load->view('backend/reset_incidencia_ok', $data);
                $this->load->view('backend/footer', $data);
            }
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
            $this->tienda_model->update_dispositivos($this->input->post('dipositivo_almacen_1'),$this->input->post('imei_1'),$this->input->post('mac_1'),$this->input->post('serial_1'),$this->input->post('barcode_1'));
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
            $this->tienda_model->update_dispositivos($this->input->post('dipositivo_almacen_2'),$this->input->post('imei_2'),$this->input->post('mac_2'),$this->input->post('serial_2'),$this->input->post('barcode_2'));

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
            $this->tienda_model->update_dispositivos($this->input->post('dipositivo_almacen_3'),$this->input->post('imei_3'),$this->input->post('mac_3'),$this->input->post('serial_3'),$this->input->post('barcode_3'));
        }

        // RECEPCION DE 10 CAMPOS DE ALARMAS
        /*for($i = 1; $i <= 10; $i++)
        {
            if ($this->input->post('units_alarma_alamacen_'.$i) <> '' && $this->input->post('units_alarma_almacen_'.$i) <> '0')
            {
                $var_alarma = "alarma_almacen_".$i;
                $$var_alarma = array(
                    'fecha' => date('Y-m-d H:i:s'),
                    'id_incidencia' => $id_inc,
                    'id_pds' => $id_pds,
                    'id_alarm' => $this->input->post('alarma_almacen_'.$i),
                    'id_devices_almacen' => NULL,
                    'cantidad' => $this->input->post('units_alarma_almacen_'.$i)
                );

                $this->tienda_model->incidencia_update_material($$var_alarma);
                $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_'.$i),$this->input->post('units_alarma_almacen_'.$i));
            }
        }*/
        if ($this->input->post('units_alarma_almacen_1') <> '' && $this->input->post('units_alarma_almacen_1') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_1'),$this->input->post('units_alarma_almacen_1'));
        }

        if ($this->input->post('units_alarma_almacen_2') <> '' && $this->input->post('units_alarma_almacen_2') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_2'),$this->input->post('units_alarma_almacen_2'));
        }

        if ($this->input->post('units_alarma_almacen_3') <> '' && $this->input->post('units_alarma_almacen_3') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_3'),$this->input->post('units_alarma_almacen_3'));
        }

        if ($this->input->post('units_alarma_almacen_4') <> '' && $this->input->post('units_alarma_almacen_4') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_4'),$this->input->post('units_alarma_almacen_4'));
        }

        if ($this->input->post('units_alarma_almacen_5') <> '' && $this->input->post('units_alarma_almacen_5') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_5'),$this->input->post('units_alarma_almacen_5'));
        }

        if ($this->input->post('units_alarma_almacen_6') <> '' && $this->input->post('units_alarma_almacen_6') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_6'),$this->input->post('units_alarma_almacen_6'));
        }

        if ($this->input->post('units_alarma_almacen_7') <> '' && $this->input->post('units_alarma_almacen_7') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_7'),$this->input->post('units_alarma_almacen_7'));
        }

        if ($this->input->post('units_alarma_almacen_8') <> '' && $this->input->post('units_alarma_almacen_8') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_8'),$this->input->post('units_alarma_almacen_8'));
        }

        if ($this->input->post('units_alarma_almacen_9') <> '' && $this->input->post('units_alarma_almacen_9') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_9'),$this->input->post('units_alarma_almacen_9'));
        }

        if ($this->input->post('units_alarma_almacen_10') <> '' && $this->input->post('units_alarma_almacen_10') <> '0')
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
            $this->tienda_model->borrar_alarmas($this->input->post('alarma_almacen_10'),$this->input->post('units_alarma_almacen_10'));
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
        if ($this->auth->is_auth()) {
            $id_pds = $this->uri->segment(3);
            $id_inc = $this->uri->segment(4);
            $status_pds = $this->uri->segment(5);
            $status = $this->uri->segment(6);

            $xcrud = xcrud_get_instance();
            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model'));

            $sfid = $this->tienda_model->get_pds($id_pds);
            $data['pds'] = $sfid;
            $data['id_pds'] = 'ABX/PDS-' . $sfid['id_pds'];
            //$data['type_pds'] = $sfid['pds'];
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

            //$data['alarms_almacen'] = $this->tienda_model->get_alarms_almacen_reserva();

            // Sacamos los dueños, y para cada uno de ellos sus alarmas, si existen y lo pasamos a la vista
            $duenos_alarm = $this->tienda_model->get_duenos();

            $data['alarms_almacen'] = array();
            foreach($duenos_alarm as $key=>$dueno){
                $alarms_dueno = $this->tienda_model->get_alarms_almacen_reserva($dueno->id_client);
                if(count($alarms_dueno) > 0){
                    $data['alarms_almacen'][$dueno->client] = $alarms_dueno;
                }else{
                    unset($duenos_alarm[$key]); // Si no tiene alarmas, lo eliminamos del array de dueños, para que no salgan en la vista.
                }
            }

            $data['duenos_alarm'] = $duenos_alarm;
            $data['devices_almacen'] = $this->tienda_model->get_devices_almacen_reserva();

            $data['title'] = 'Operativa incidencia Ref. '.$data['id_inc_url'];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/operar_incidencia_materiales', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function desasignar_incidencia_materiales()
    {
        if ($this->auth->is_auth()) {
            $id_pds = $this->uri->segment(3);
            $id_inc = $this->uri->segment(4);
            $tipo_dispositivo = $this->uri->segment(5);
            $id_material_incidencia = $this->uri->segment(6);

            $xcrud = xcrud_get_instance();
            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','incidencia_model'));
            $sfid = $this->tienda_model->get_pds($id_pds);
            $this->incidencia_model->desasignar_material($id_inc,$tipo_dispositivo,$id_pds,$id_material_incidencia);



            $this->operar_incidencia();

        } else {
            redirect('admin', 'refresh');
        }
    }



    public function carga_datos_dispositivo()
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model', 'sfid_model'));

            $data['dispositivos']    =  $this->tienda_model->search_dispositivo($this->input->post('codigo'));
            $data['devices_almacen'] = $this->tienda_model->get_devices_almacen_reserva();

            $data['title'] = 'Carga datos dispositivo';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
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
        $xcrud->label('id_client','Identificador')->label('client', 'Empresa')->label('type_profile_client', 'Tipo')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('facturable', 'Facturable')->label('status', 'Estado');
        $xcrud->columns('id_client,client,type_profile_client,facturable');
        $xcrud->fields('client,type_profile_client,picture_url,description,facturable,status');
        $xcrud->order_by('client');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud->unset_remove();

        $data['title'] = 'Empresas';
        $data['content'] = $xcrud->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function incidencias()
    {
        if ($this->auth->is_auth()) {

            $xcrud_SQL = xcrud_get_instance();
            $xcrud_SQL->table_name('Incidencias Demo Real');
            $xcrud_SQL->query('SELECT
					count(incidencias.id_incidencia) AS "Número incidencias",
            		intervenciones_incidencias.id_intervencion AS "Intervención",
					incidencias.fecha AS Fecha,
            		type_pds.pds AS "Tipo Pds",
					pds.reference AS Referencia,
            		pds.commercial AS "Nombre comercial",
            		pds.address AS Dirección,
            		pds.zip AS CP,
            		pds.city AS Ciudad,
            		province.province AS Provincia,
            		county.county AS CCAA,
					display.display AS Mueble,
					device.device AS Dispositivo,
					incidencias.tipo_averia AS Tipo,
					count(incidencias.fail_device) AS "Fallo dispositivo",
					count(incidencias.alarm_display) "Alarma mueble",
					count(incidencias.alarm_device) "Alarma dispositivo",
					count(incidencias.alarm_garra) "Sistema de alarma",
					incidencias.description_1 AS "Comentarios",
					incidencias.description_2 AS "Comentarios SAT",
            		incidencias.description_3 AS "Comentarios Instalador",
					incidencias.contacto,
					incidencias.phone AS "Teléfono",
            		incidencias.status_pds AS "Estado tienda",
					incidencias.status AS "Estado SAT"
				FROM incidencias
				LEFT JOIN intervenciones_incidencias ON incidencias.id_incidencia = intervenciones_incidencias.id_incidencia
            	JOIN pds ON incidencias.id_pds = pds.id_pds
            	JOIN type_pds ON pds.type_pds = type_pds.id_type_pds
            	LEFT JOIN province ON pds.province = province.id_province
            	LEFT JOIN county ON pds.county = county.id_county
				JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
				JOIN display ON displays_pds.id_display = display.id_display
				LEFT JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
				LEFT JOIN device ON devices_pds.id_device = device.id_device
            	GROUP BY intervenciones_incidencias.id_intervencion');

            $data['title'] = 'Export incidencias';
            $data['content'] = $xcrud_SQL->render();

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/content', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function incidencias_exp()
    {
        if ($this->auth->is_auth()) {

            $xcrud_SQL = xcrud_get_instance();
            $xcrud_SQL->table_name('Incidencias Demo Real');
            $xcrud_SQL->query('SELECT
					incidencias.id_incidencia AS Incidencia,
					incidencias.fecha AS Fecha,
					incidencias.fecha_cierre AS Cierre,
					(SELECT MAX(fecha) FROM historico
					    WHERE status="Resuelta" AND historico.id_incidencia = incidencias.id_incidencia)
                    as Cierre_Historico,
					pds.reference AS Referencia,
            		pds.commercial AS "Nombre comercial",
            		pds.address AS Dirección,
            		pds.zip AS CP,
            		pds.city AS Ciudad,
            		province.province AS Provincia,
            		county.county AS CCAA,
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
				LEFT JOIN pds ON incidencias.id_pds = pds.id_pds
            	LEFT JOIN province ON pds.province = province.id_province
            	LEFT JOIN county ON pds.county = county.id_county
				LEFT JOIN displays_pds ON incidencias.id_displays_pds = displays_pds.id_displays_pds
				LEFT JOIN display ON displays_pds.id_display = display.id_display
				LEFT JOIN devices_pds ON incidencias.id_devices_pds = devices_pds.id_devices_pds
				LEFT JOIN device ON devices_pds.id_device = device.id_device');

            $data['title'] = 'Export incidencias';
            $data['content'] = $xcrud_SQL->render();

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/content', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    public function incidencias_master()
    {
        if ($this->auth->is_auth()) {

            $xcrud_SQL = xcrud_get_instance();
            $xcrud_SQL->table_name('Incidencias Demo Real Orange');
            $xcrud_SQL->query("SELECT
					incidencias.id_incidencia AS Incidencia,
					DATE_FORMAT(incidencias.fecha,'%d/%m/%Y') AS Fecha,
    				type_pds.pds AS 'Tipo Pds',
					pds.reference AS Referencia,
            		pds.commercial AS 'Nombre comercial',
            		pds.address AS Dirección,
            		pds.zip AS CP,
            		pds.city AS Ciudad,
            		province.province AS Provincia,
            		county.county AS CCAA,
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
            		incidencias.status_pds AS 'Estado tienda'
				FROM incidencias
				JOIN pds ON incidencias.id_pds = pds.id_pds
    			JOIN type_pds ON pds.type_pds = type_pds.id_type_pds
            	LEFT JOIN province ON pds.province = province.id_province
            	LEFT JOIN county ON pds.county = county.id_county
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
        $xcrud_1->order_by('type');

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
        $xcrud_2->label('id_contact','Identificador')->label('client_contact', 'Empresa')->label('type_profile_contact', 'Tipo')->label('contact', 'Contacto')->label('id_parte', 'Id. destino material')->label('type_via', 'Tipo vía')->label('address', 'Dirección')->label('zip', 'C.P.')->label('city', 'Ciudad')->label('province', 'Provincia')->label('county', 'CC.AA.')->label('schedule', 'Horario')->label('phone', 'Teléfono')->label('mobile', 'Móvil')->label('email', 'Email')->label('email_cc', 'Copia email')->label('status', 'Estado');
        $xcrud_2->columns('id_contact,client_contact,type_profile_contact,contact, id_parte,email');
        $xcrud_2->fields('client_contact,type_profile_contact,contact,id_parte,type_via,address,zip,city,province,county,schedule,phone,mobile,email,email_cc,status');
        $xcrud_2->order_by('contact');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_2->unset_remove();

        $data['title'] = 'Contactos';
        //$data['content'] = $xcrud_1->render();
        //$data['content'] = $data['content'].$xcrud_2->render();
        $data['content'] = $xcrud_2->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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
        $xcrud_1->label('brand', 'Fabricante')->label('id_brand_alarm','Identificador');
        $xcrud_1->columns('id_brand_alarm, brand');
        $xcrud_1->fields('brand');
        $xcrud_1->order_by('brand');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_1->unset_remove();


        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('type_alarm');
        $xcrud_2->table_name('Tipo');
        $xcrud_2->label('id_type_alarm', 'Identificador')->label('type', 'Tipo');
        $xcrud_2->columns('id_type_alarm,type');
        $xcrud_2->fields('type');
        $xcrud_2->order_by('type');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_2->unset_remove();

        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('alarm');
        $xcrud_3->table_name('Modelo');
        $xcrud_3->relation('client_alarm', 'client', 'id_client', 'client');
        $xcrud_3->relation('type_alarm', 'type_alarm', 'id_type_alarm', 'type');
        $xcrud_3->relation('brand_alarm', 'brand_alarm', 'id_brand_alarm', 'brand');
        $xcrud_3->change_type('picture_url', 'image');
        $xcrud_3->modal('picture_url');
        $xcrud_3->label('id_alarm', 'Identificador')->label('client_alarm', 'Cliente')->label('brand_alarm', 'Fabricante')->label('type_alarm', 'Tipo')->label('code', 'Código')->label('alarm', 'Modelo')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('units', 'Unidades')->label('status', 'Estado');
        $xcrud_3->columns('id_alarm,client_alarm,brand_alarm,type_alarm,code,alarm,picture_url,status');
        $xcrud_3->fields('client_alarm,brand_alarm,type_alarm,code,alarm,picture_url,description,status');
        $xcrud_3->order_by('code');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_3->unset_remove();

        /*
         *  NO USADO
         * $xcrud_4 = xcrud_get_instance();
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
        $xcrud_5->fields('client_type_pds,id_device,id_display,id_alarm,description,status');*/

        $data['title'] = 'Alarmas';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_3->render();
        /* NO USADO
        $data['content'] = $data['content'] . $xcrud_4->render();
        $data['content'] = $data['content'] . $xcrud_5->render();*/

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }



    public function alarmas_almacen()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('alarm');
        $xcrud->table_name('Sistemas de seguridad');
        $xcrud->relation('client_alarm', 'client', 'id_client', 'client');
        $xcrud->relation('type_alarm', 'type_alarm', 'id_type_alarm', 'type');
        $xcrud->relation('brand_alarm', 'brand_alarm', 'id_brand_alarm', 'brand');
        $xcrud->change_type('picture_url', 'image');
        $xcrud->modal('picture_url');

        $xcrud->label('client_alarm', 'Dueño')
            ->label('brand_alarm', 'Fabricante')
            ->label('type_alarm', 'Tipo')
            ->label('code', 'Código')
            ->label('alarm', 'Modelo')
            ->label('picture_url', 'Foto')
            ->label('description', 'Comentarios')
            ->label('units', 'Unidades')
            ->label('status', 'Estado');
        $xcrud->order_by('client_alarm');
        $xcrud->columns('client_alarm,brand_alarm,type_alarm,code,alarm,picture_url,units,status');
        $xcrud->fields('client_alarm,brand_alarm,type_alarm,code,alarm,picture_url,description,units,status');

        $xcrud->before_update("historico_io_alarmas_before_update","../libraries/diario_almacen.php");

        $data['title'] = 'Gestión Sistemas de seguridad';
        $data['content'] = $xcrud->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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
        $xcrud_1->label('id_brand_device', 'Identificador')->label('brand', 'Fabricante');
        $xcrud_1->columns('id_brand_device,brand');
        $xcrud_1->fields('brand');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_1->unset_remove();

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('type_device');
        $xcrud_2->table_name('Tipo');
        $xcrud_2->label('id_type_device', 'Identificador')->label('type', 'Tipo');
        $xcrud_2->columns('id_type_device,type');
        $xcrud_2->fields('type');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_2->unset_remove();

        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('device');
        $xcrud_3->table_name('Modelo');
        $xcrud_3->relation('type_device', 'type_device', 'id_type_device', 'type');
        $xcrud_3->relation('brand_device', 'brand_device', 'id_brand_device', 'brand');
        $xcrud_3->change_type('picture_url', 'image');
        $xcrud_3->modal('picture_url');
        $xcrud_3->label('id_device', 'Identificador')->label('brand_device', 'Fabricante')->label('type_device', 'Tipo')->label('device', 'Modelo')->label('brand_name', 'Modelo fabricante')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_3->columns('id_device,brand_device,type_device,device,picture_url,brand_name,status');
        $xcrud_3->fields('brand_device,type_device,device,brand_name,picture_url,description,status');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_3->unset_remove();

        $data['title'] = 'Dispositivos';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_3->render();


        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function muebles()
    {
        /*$xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('panelado');
        $xcrud_1->table_name('Panelado');
        $xcrud_1->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_1->relation('type_pds', 'type_pds', 'id_type_pds', 'pds');
        $xcrud_1->change_type('picture_url', 'image');
        $xcrud_1->label('id_panelado', 'Identificador')->label('client_panelado', 'Cliente')->label('type_pds', 'Tipo punto de venta')->label('panelado', 'Panelado Orange')->label('panelado_abx', 'REF.')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_1->columns('id_panelado,client_panelado,type_pds,panelado,panelado_abx,status');
        $xcrud_1->fields('client_panelado,type_pds,panelado,panelado_abx,picture_url,description,status');*/

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:


        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('displays_categoria');
        $xcrud_2->table_name('Muebles categoría');
        $xcrud_2->relation('client', 'client', 'id_client', 'client');

        $xcrud_2->relation('id_tipo', 'pds_tipo','id','titulo');
        $xcrud_2->relation('id_subtipo', 'pds_subtipo', 'id', 'titulo','','titulo ASC',false, '', false,'id_tipo','id_tipo');
        //$xcrud_2->fk_relation('Tipología','id_tipologia', 'pds_subtipo_tipologia', 'id_subtipo', 'id_tipologia', 'pds_tipologia', 'id', 'titulo');
        $xcrud_2->relation('id_segmento', 'pds_segmento','id', 'titulo');
        $xcrud_2->relation('id_tipologia', 'pds_tipologia','id', 'titulo');
        //id_tipo,id_subtipo,id_segmento,id_tipologia,

        $xcrud_2->relation('id_display', 'display', 'id_display', 'display','');

        $xcrud_2->label('id', 'Identificador')->label('client', 'Cliente')->label('id_display', 'Modelo')->label('id_tipo', 'Tipo PDS')
            ->label('id_subtipo', 'Subtipo PDS')->label('id_segmento', 'Segmento PDS')->label('id_tipologia', 'Tipología PDS')->label('position', 'Posición')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_2->columns('id,client,id_tipo,id_subtipo,id_segmento,id_tipologia,id_display,position,status');
        $xcrud_2->fields('client,id_tipo,id_subtipo,id_segmento,id_tipologia,id_display,position,status');
// Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_2->unset_remove();


        $xcrud_4 = xcrud_get_instance();
        $xcrud_4->table('devices_display');
        $xcrud_4->table_name('Dispositivos mueble');
        $xcrud_4->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_4->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_4->relation('id_device', 'device', 'id_device', 'device');
        $xcrud_4->label('id_devices_display', 'Identificador')->label('client_panelado', 'Cliente')->label('id_panelado', 'REF.')->label('id_display', 'Mueble')->label('id_device', 'Dispositivo')->label('position', 'Posición')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_4->columns('id_devices_display,client_panelado,id_display,id_device,position,status');
        $xcrud_4->fields('client_panelado,id_display,id_device,position,description,status');
        // $xcrud_4->where('status',array('Incidencia','Alta'));
        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_4->unset_remove();



        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('display');
        $xcrud_3->table_name('Modelo');
        $xcrud_3->relation('client_display', 'client', 'id_client', 'client');
        $xcrud_3->change_type('picture_url', 'image');
        $xcrud_3->change_type('canvas_url', 'file');
        $xcrud_3->modal('picture_url');
        $xcrud_3->label('id_display', 'Identificador')->label('client_display', 'Cliente')->label('display', 'Modelo')->label('picture_url', 'Foto')->label('canvas_url', 'SVG')->label('description', 'Comentarios')->label('positions', 'Posiciones')->label('status', 'Estado');
        $xcrud_3->columns('id_display,client_display,display,picture_url,positions,status');
        $xcrud_3->fields('client_display,display,picture_url,canvas_url,description,positions,status');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_3->unset_remove();


        $data['title'] = 'Muebles';

        $data['content'] =  $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_4->render();
        $data['content'] = $data['content'] . $xcrud_3->render();


        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }



    public function muebles_OLD()
    {
        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('panelado');
        $xcrud_1->table_name('Panelado');
        $xcrud_1->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_1->relation('type_pds', 'type_pds', 'id_type_pds', 'pds');
        $xcrud_1->change_type('picture_url', 'image');
        $xcrud_1->label('id_panelado', 'Identificador')->label('client_panelado', 'Cliente')->label('type_pds', 'Tipo punto de venta')->label('panelado', 'Panelado Orange')->label('panelado_abx', 'REF.')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_1->columns('id_panelado,client_panelado,type_pds,panelado,panelado_abx,status');
        $xcrud_1->fields('client_panelado,type_pds,panelado,panelado_abx,picture_url,description,status');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_1->unset_remove();

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('displays_panelado');
        $xcrud_2->table_name('Muebles panelado');
        $xcrud_2->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_2->relation('id_panelado', 'panelado', 'id_panelado', 'panelado_abx');
        $xcrud_2->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_2->label('id_displays_panelado', 'Identificador')->label('client_panelado', 'Cliente')->label('id_panelado', 'REF.')->label('id_display', 'Modelo')->label('position', 'Posición')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_2->columns('id_displays_panelado,client_panelado,id_panelado,id_display,position,status');
        $xcrud_2->fields('client_panelado,id_panelado,id_display,position,description,status');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_2->unset_remove();

        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('display');
        $xcrud_3->table_name('Modelo');
        $xcrud_3->relation('client_display', 'client', 'id_client', 'client');
        $xcrud_3->change_type('picture_url', 'image');
        $xcrud_3->change_type('canvas_url', 'file');
        $xcrud_3->modal('picture_url');
        $xcrud_3->label('id_display', 'Identificador')->label('client_display', 'Cliente')->label('display', 'Modelo')->label('picture_url', 'Foto')->label('canvas_url', 'SVG')->label('description', 'Comentarios')->label('positions', 'Posiciones')->label('status', 'Estado');
        $xcrud_3->columns('id_display,client_display,display,picture_url,positions,status');
        $xcrud_3->fields('client_display,display,picture_url,canvas_url,description,positions,status');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_3->unset_remove();

        $xcrud_4 = xcrud_get_instance();
        $xcrud_4->table('devices_display');
        $xcrud_4->table_name('Dispositivos mueble');
        $xcrud_4->relation('client_panelado', 'client', 'id_client', 'client');
        $xcrud_4->relation('id_display', 'display', 'id_display', 'display');
        $xcrud_4->relation('id_device', 'device', 'id_device', 'device');
        $xcrud_4->label('id_devices_display', 'Identificador')->label('client_panelado', 'Cliente')->label('id_panelado', 'REF.')->label('id_display', 'Mueble')->label('id_device', 'Dispositivo')->label('position', 'Posición')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_4->columns('id_devices_display,client_panelado,id_display,id_device,position,status');
        $xcrud_4->fields('client_panelado,id_display,id_device,position,description,status');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_4->unset_remove();

        $data['title'] = 'Muebles';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_3->render();
        $data['content'] = $data['content'] . $xcrud_4->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }



    public function puntos_de_venta()
    {
        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('pds_supervisor');
        $xcrud_1->table_name('Supervisores');
        $xcrud_1->label('id', 'Identificador')->label('titulo', 'Supervisor')->label('telefono', 'Teléfono');
        $xcrud_1->columns('id,titulo,telefono');
        $xcrud_1->fields('titulo,telefono');
        $xcrud_1->start_minimized(true);
        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_1->unset_remove();

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
//
        /*
         *
         *
        PRUEBA DE CATEGORIA CON CAMPOS VINCULADOS TOTALES EN XCRUD
        $xcrud_2->relation('id_tipo', 'pds_tipo','id','titulo');
        $xcrud_2->relation('id_subtipo', 'pds_subtipo', 'id', 'titulo','','titulo ASC',false, '', false,'id_tipo','id_tipo');

        $xcrud_2->relation('id_subtipo_tipologia', 'pds_subtipo_tipologia', 'id', 'id_tipologia','','id_tipologia ASC', false, '', false, 'id_subtipo','id_subtipo');
        $xcrud_2->relation('id_tipologia', 'pds_tipologia', 'id', 'titulo','','titulo ASC', false, '', false, 'id','id_subtipo_tipologia');

        $xcrud_2->relation('subtipo_listado', 'pds_subtipo', 'id', 'titulo','','titulo ASC','', false, '', false, 'id','id_tipo');
        $xcrud_2->relation('id_segmento', 'pds_segmento','id', 'titulo');
        */


        $xcrud_2->relation('id_tipo', 'pds_tipo','id','titulo');
        $xcrud_2->relation('id_subtipo', 'pds_subtipo', 'id', 'titulo','','titulo ASC',false, '', false,'id_tipo','id_tipo');
        //$xcrud_2->fk_relation('Tipología','id_tipologia', 'pds_subtipo_tipologia', 'id_subtipo', 'id_tipologia', 'pds_tipologia', 'id', 'titulo');
        $xcrud_2->relation('id_segmento', 'pds_segmento','id', 'titulo');
        $xcrud_2->relation('id_tipologia', 'pds_tipologia','id', 'titulo');


        //id_tipo,id_subtipo,id_segmento,id_tipologia,
        $xcrud_2->relation('id_supervisor', 'pds_supervisor','id', 'titulo');

        $xcrud_2->change_type('picture_url', 'image');
        $xcrud_2->modal('picture_url');
        //$xcrud_2->readonly('reference');
        $xcrud_2->disabled('reference','edit');
        //$xcrud_2->disabled('codigoSAT','edit');
        $xcrud_2->sum('m2_total', 'm2_fo', 'm2_bo');
        $xcrud_2->label('id_pds', 'Identificador')->label('client_pds', 'Cliente')->label('reference', 'SFID')->label('codigoSAT', 'Codigo SAT')->label('id_tipo', 'Tipo PDS')
            ->label('id_subtipo', 'Subtipo PDS')->label('id_segmento', 'Segmento PDS')->label('id_tipologia', 'Tipología PDS')
            ->label('territory', 'Territorio')->label('panelado_pds', 'Panelado')->label('dispo', 'Disposición')
            ->label('commercial', 'Nombre comercial')->label('cif', 'CIF')->label('picture_url', 'Foto')->label('m2_fo', 'M2 front-office')
            ->label('m2_bo', 'M2 back-office')->label('m2_total', 'M2 total')->label('type_via', 'Tipo vía')
            ->label('address', 'Dirección')->label('zip', 'C.P.')->label('city', 'Ciudad')->label('province', 'Provincia')->label('county', 'CC.AA.')->label('schedule', 'Horario')->label('phone', 'Teléfono')->label('mobile', 'Móvil')->label('email', 'Email')->label('contact_contact_person', 'Contacto')->label('contact_in_charge', 'Encargado')->label('id_supervisor', 'Supervisor')->label('status', 'Estado');

        $xcrud_2->columns('id_pds,client_pds,reference,codigoSAT,id_tipo,id_subtipo,id_segmento,id_tipologia,commercial,territory,status');
        $xcrud_2->fields('client_pds,reference,codigoSAT,id_tipo,id_subtipo,id_segmento,id_tipologia,commercial,cif,territory,picture_url,m2_fo,m2_bo,m2_total,type_via,address,zip,city,province,county,territory,schedule,phone,mobile,email,contact_contact_person,contact_in_charge,id_supervisor,status');

        $xcrud_2->validation_required('reference');
        $xcrud_2->validation_required('codigoSAT');
        $xcrud_2->validation_required('province');
        $xcrud_2->validation_required('territory');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_2->unset_remove();
        $xcrud_2->order_by(array("id_tipo"=>"desc","id_subtipo"=>"asc","id_segmento"=>"asc","id_tipologia"=>"asc"));
        $data['title'] = 'Puntos de venta';

        $data['content'] = $xcrud_1->render();
        $data['content'] .= $xcrud_2->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();

        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function puntos_de_venta_OLD()
    {
        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('type_pds');
        $xcrud_1->table_name('Tipo');
        $xcrud_1->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_1->label('id_type_pds', 'Identificador')->label('client_type_pds', 'Cliente')->label('pds', 'Tipo')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_1->columns('id_type_pds,client_type_pds,pds,status');
        $xcrud_1->fields('client_type_pds,pds,description,status');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_1->unset_remove();

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
        $xcrud_2->label('id_pds', 'Identificador')->label('client_pds', 'Cliente')->label('reference', 'SFID')->label('type_pds', 'Tipo')->label('territory', 'Territorio')->label('panelado_pds', 'Panelado')->label('dispo', 'Disposición')->label('commercial', 'Nombre comercial')->label('cif', 'CIF')->label('picture_url', 'Foto')->label('m2_fo', 'M2 front-office')->label('m2_bo', 'M2 back-office')->label('m2_total', 'M2 total')->label('type_via', 'Tipo vía')->label('address', 'Dirección')->label('zip', 'C.P.')->label('city', 'Ciudad')->label('province', 'Provincia')->label('county', 'CC.AA.')->label('schedule', 'Horario')->label('phone', 'Teléfono')->label('mobile', 'Móvil')->label('email', 'Email')->label('contact_contact_person', 'Contacto')->label('contact_in_charge', 'Encargado')->label('contact_supervisor', 'Supervisor')->label('status', 'Estado');
        $xcrud_2->columns('id_pds,client_pds,reference,type_pds,panelado_pds,commercial,territory,status');
        $xcrud_2->fields('client_pds,reference,type_pds,panelado_pds,dispo,commercial,cif,territory,picture_url,m2_fo,m2_bo,m2_total,type_via,address,zip,city,province,county,schedule,phone,mobile,email,contact_contact_person,contact_in_charge,contact_supervisor,status');

        $xcrud_2->validation_required('province');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud_2->unset_remove();

        $data['title'] = 'Puntos de venta';
        $data['content'] = $xcrud_1->render();
        $data['content'] = $data['content'] . $xcrud_2->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }



    public function categorias_pdv()
    {
        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('pds_tipo');
        $xcrud_1->table_name('Definir Tipos de PDS');
        // $xcrud_1->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_1->label('id', 'Id.')->label('titulo', 'Título');
        $xcrud_1->columns('id,titulo');
        $xcrud_1->fields('titulo');


      /* Agregando el campo orden*/
        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('pds_tipologia');
        $xcrud_2->table_name('Definir Tipologías de PDS');
        $xcrud_2->order_by('orden','asc');
        // $xcrud_1->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_2->label('id', 'Id.')->label('titulo', 'Título')->label('orden', 'Orden');
        $xcrud_2->columns('id,titulo,orden');
        $xcrud_2->columns('titulo');
        $xcrud_2->columns('orden');
        /* SIN EL CAMPO ORDEN
        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('pds_tipologia');
        $xcrud_2->table_name('Definir Tipologías de PDS');
        // $xcrud_1->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_2->label('id', 'Id.')->label('titulo', 'Título');
        $xcrud_2->columns('id,titulo');
        $xcrud_2->fields('titulo');
*/

        $xcrud_3 = xcrud_get_instance();
        $xcrud_3->table('pds_subtipo');
        $xcrud_3->table_name('Definir Subtipos de PDS y sus tipologías relacionadas');
        $xcrud_3->relation('id_tipo', 'pds_tipo', 'id', 'titulo');
        $xcrud_3->fk_relation('Tipologías','id','pds_subtipo_tipologia','id_subtipo','id_tipologia','pds_tipologia','id','titulo');
        //(label, field, fk_table, in_fk_field, out_fk_field, rel_tbl, rel_field, rel_name, rel_where, rel_orderby, rel_concat_separator, before, add_data

        $xcrud_3->label('id', 'Id.')->label('titulo', 'Título')->label('id_tipo','Tipo');
        $xcrud_3->order_by('id_tipo','asc');
        $xcrud_3->columns('id,id_tipo,titulo');
        $xcrud_3->columns('id,id_tipo,titulo,Tipologías');


        /*Agregando el campo orden */
        $xcrud_4 = xcrud_get_instance();
        $xcrud_4->table('pds_segmento');
        $xcrud_4->table_name('Definir Segmentos de PDS');
        $xcrud_4->order_by('orden','asc');
        $xcrud_4->label('id', 'Id.')->label('titulo', 'Título')->label('orden', 'Orden');
        $xcrud_4->columns('id,titulo,orden');
        $xcrud_4->columns('titulo');
        $xcrud_4->columns('orden');

        /* SIN EL CAMPO ORDEN
        $xcrud_4 = xcrud_get_instance();
        $xcrud_4->table('pds_segmento');
        $xcrud_4->table_name('Definir Segmentos de PDS');
        $xcrud_4->label('id', 'Id.')->label('titulo', 'Título');
        $xcrud_4->columns('id,titulo');
        $xcrud_4->fields('titulo');*/


        $data['title'] = 'Categorización de PDS: Tipo, Subtipo, Segmento, Tipología';
        $data['content'] = $xcrud_1->render();
        $data['content'] .= $xcrud_2->render();
        $data['content'] .= $xcrud_3->render();
        $data['content'] .= $xcrud_4->render();


        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');

    }

  /*  public function tipos_incidencia()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('type_incidencia');
        $xcrud->table_name('Tipos de incidencia');
        $xcrud->label('id_type_incidencia','Identificador')->label('title', 'Título');
        $xcrud->columns('id_type_incidencia,title');
        $xcrud->fields('title');
        $xcrud->order_by('id_type_incidencia');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud->unset_remove();

        $data['title'] = 'Tipos de incidencia';
        $data['content'] = $xcrud->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }*/

    public function razones_parada()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('type_incidencia');
        $xcrud->table_name('Razones de parada de incidencias');
        $xcrud->label('id_type_incidencia','Identificador')->label('title', 'Título');
        $xcrud->columns('id_type_incidencia,title');
        $xcrud->fields('title');
        $xcrud->order_by('id_type_incidencia');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud->unset_remove();

        $data['title'] = 'Razones de parada';
        $data['content'] = $xcrud->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }

    public function soluciones_ejecutadas()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('solucion_incidencia');
        $xcrud->table_name('Soluciones para incidencias');
        $xcrud->label('id_solucion_incidencia','Identificador')->label('title', 'Título');
        $xcrud->columns('id_solucion_incidencia,title');
        $xcrud->fields('title');
        $xcrud->order_by('id_solucion_incidencia');

        // Ocultar el botón de borrar para evitar borrados accidentales mientras no existan constraints en BD:
        $xcrud->unset_remove();

        $data['title'] = 'Soluciones';
        $data['content'] = $xcrud->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/descripcion', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function inventarios_OLD()
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/inventario', $data);
        $this->load->view('backend/footer');
    }

    public function inventarios()
    {
        $this->load->model('tienda_model');


        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('displays_pds');
        $xcrud_1->table_name('Inventario muebles');
        $xcrud_1->relation('client_type_pds', 'client', 'id_client', 'client');
        $xcrud_1->relation('id_pds', 'pds', 'id_pds', 'reference');


        $xcrud_1->relation('id_display', 'display', 'id_display', 'display');

        $xcrud_1->relation('id_tipo', 'pds_tipo','id','titulo');
        $xcrud_1->relation('id_subtipo', 'pds_subtipo', 'id', 'titulo','','titulo ASC',false, '', false,'id_tipo','id_tipo');
        //$xcrud_2->fk_relation('Tipología','id_tipologia', 'pds_subtipo_tipologia', 'id_subtipo', 'id_tipologia', 'pds_tipologia', 'id', 'titulo');
        $xcrud_1->relation('id_segmento', 'pds_segmento','id', 'titulo');
        $xcrud_1->relation('id_tipologia', 'pds_tipologia','id', 'titulo');
        //id_tipo,id_subtipo,id_segmento,id_tipologia,

        $xcrud_1->label('client_type_pds', 'Cliente')->label('id_displays_pds', 'REF.')->label('id_type_pds', 'Tipo')->label('id_pds', 'SFID')->label('id_panelado', 'Panelado')->label('id_display', 'Mueble')->label('position', 'Posición Orange')->label('description', 'Comentarios')->label('status', 'Estado');
        $xcrud_1->columns('client_type_pds,id_displays_pds,id_pds,id_tipo,id_subtipo,id_segmento,id_tipologia,id_display,position,status');
        $xcrud_1->fields('client_type_pds,id_displays_pds,id_pds,id_tipo,id_subtipo,id_segmento,id_tipologia,id_pds,id_display,position,description,status');
        $xcrud_1->where('status',array('Alta','Incidencia'));
        $xcrud_1->order_by('id_pds', 'asc');
        $xcrud_1->order_by('position', 'asc');
        $xcrud_1->show_primary_ai_column(true);
        $xcrud_1->unset_numbers();
        $xcrud_1->start_minimized(false);

        /*$xcrud_2 = xcrud_get_instance();
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
        $xcrud_2->start_minimized(true);*/

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

         $xcrud_3->where('status',array('Alta','Incidencia'));
        $xcrud_3->order_by('id_pds', 'asc');
        $xcrud_3->order_by('id_displays_pds', 'asc');
        $xcrud_3->order_by('position', 'asc');
        $xcrud_3->show_primary_ai_column(true);
        $xcrud_3->unset_numbers();
        $xcrud_3->start_minimized(false);

        /*$xcrud_4 = xcrud_get_instance();
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
        $xcrud_4->start_minimized(true);*/

        $data['title'] = 'Inventarios tiendas';
        $data['content'] = $xcrud_1->render();
        //$data['content'] = $data['content'] . $xcrud_2->render();
        $data['content'] = $data['content'] . $xcrud_3->render();
        ///$data['content'] = $data['content'] . $xcrud_4->render();

        $data['displays'] = $this->tienda_model->get_displays_total();
        $data['devices'] = $this->tienda_model->get_devices_total();


        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/inventario', $data);
        $this->load->view('backend/footer');
    }

    /**
     * Método de entrada a Diario Almacén (Histórico de E/S de Alarmas).
     *  Por una parte, existe histórico de alarmas masivas
     *  Por otra, histórico sobre las alarmas asignadas para incidencias.
     */
    public function diario_almacen()
    {
        $this->load->model('tienda_model');

        /*$data['displays'] = $this->tienda_model->get_displays_total();
        $data['devices'] = $this->tienda_model->get_devices_total();*/
        $data["title"] = "Diario de almacén";

        $xcrud_1 = xcrud_get_instance();
        $xcrud_1->table('historico_io');
        $xcrud_1->table_name('Histórico de dispositivos');
        $xcrud_1->relation('id_client', 'client', 'id_client', 'client');
        $xcrud_1->relation('id_device', 'device', 'id_device', 'device');



        $xcrud_1->label('id_historico_almacen','Ref.')->label('id_device', 'Dispositivo maestro')->label('id_devices_almacen', 'Id. Dispositivo almacén')->label('id_client', 'Dueño')->label('fecha', 'Fecha')
            ->label('unidades', 'Unidades');
        $xcrud_1->columns('id_historico_almacen,id_device,id_devices_almacen,id_client,fecha,unidades');
        $xcrud_1->where('procesado',1);
        $xcrud_1->where('id_alarm IS NULL');

        $xcrud_1->order_by('fecha', 'desc');
        $xcrud_1->show_primary_ai_column(true);
        $xcrud_1->unset_add();
        $xcrud_1->unset_edit();
        $xcrud_1->unset_view();
        $xcrud_1->unset_remove();
        $xcrud_1->unset_numbers();
        $xcrud_1->start_minimized(false);

        $data["content_dispositivos"] = $xcrud_1->render();


        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('historico_io');
        $xcrud_2->table_name('Histórico de alarmas');
        $xcrud_2->relation('id_alarm', 'alarm', 'id_alarm', 'alarm');
        $xcrud_2->relation('id_client', 'client', 'id_client', 'client');

        $xcrud_2->label('id_historico_almacen','Ref.')->label('id_alarm', 'Alarma')->label('id_client', 'Dueño')->label('fecha', 'Fecha')->label('unidades', 'Unidades');
        $xcrud_2->columns('id_historico_almacen,id_alarm,id_client,fecha,unidades');
        $xcrud_2->where('procesado',1);
        $xcrud_2->where('id_devices_almacen IS NULL');
        $xcrud_2->where('id_device IS NULL');

        $xcrud_2->order_by('fecha', 'desc');
        $xcrud_2->show_primary_ai_column(true);
        $xcrud_2->unset_add();
        $xcrud_2->unset_edit();
        $xcrud_2->unset_view();
        $xcrud_2->unset_remove();
        $xcrud_2->unset_numbers();
        $xcrud_2->start_minimized(false);


        $data["content_alarmas"] = $xcrud_2->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/diario_almacen', $data);
        $this->load->view('backend/footer');

    }


    /**
     * Método que crea un listado del inventario de dispositivos que habrá en una tienda de SFID
     * pasado como parámetro como el tercer segmento de la URL.
     *
     * @return bool
     *
     */
    public function get_inventarios_sfid($sfid=NULL,$accion=NULL,$id_pds=NULL)
    {
        $this->load->model(array('tienda_model','sfid_model'));


        if($accion == "alta"){
            // INFORME ALTA
            $pds = $this->tienda_model->get_sfid($sfid,"object");
            $this->get_inventarios_sfid_alta($pds);
        }else{
            // INFORME BAJA

            if(is_null($id_pds) || empty($id_pds)) {
                // El  SFID ya ha sido cerrado, buscar el id_pds en el historico de cierres de SFID
                $pds = $this->sfid_model->get_historico_cierre_sfid($sfid, "object");
                $id_pds = $pds->id_pds;
            }



                if (!empty($id_pds)) {
                    $query = $this->db->select('*')->where('id_pds', $id_pds)->get('pds');
                    $pds = $query->row();
                    $this->get_inventarios_sfid_baja($pds);
                }

            }

    }




    public function get_inventarios_sfid_alta($pds='')
    {
        $this->load->helper(array('dompdf','file'));
        if(!empty($pds))
        {
            $data = $this->get_info_inventarios_pds($pds);
            $data['title'] = "Alta Inventario dispositivos SFID [" . $pds->reference . "]";
            $data['content'] = $this->get_inventarios($pds);
            $html = $this->load->view('backend/imprimir_inventario_alta', $data, TRUE);
            $this->load->view('backend/imprimir_inventario_alta', $data);

            $filename_pdf = "ALTA_SFID_" . $pds->reference;
            pdf_create($html, $filename_pdf,true);
        }

    }

    public function get_inventarios_sfid_baja($pds='')
    {
        $this->load->helper(array('dompdf','file'));

        if(!empty($pds))
        {
            $data = $this->get_info_inventarios_pds($pds);
            $data['title'] = "Baja Inventario dispositivos SFID [" . $pds->reference . "]";
            $data['content'] = $this->get_inventarios($pds);
            $html = $this->load->view('backend/imprimir_inventario_baja', $data, TRUE);
            $filename_pdf = "BAJA_SFID_" . $pds->reference;
            pdf_create($html, $filename_pdf,true);
        }

    }


    public function get_info_inventarios_pds($pds)
    {


        $data['id_pds'] = 'ABX/PDS-' . $pds->id_pds;
        $data['commercial'] = $pds->commercial;

        $data['reference'] = $pds->reference;
        $data['address'] = $pds->address;
        $data['zip'] = $pds->zip;
        $data['city'] = $pds->city;
        $data['province'] = $pds->province;
        $data['phone_pds'] = $pds->phone;

        return $data;
    }

    public function get_inventarios($pds){

        if(!empty($pds)) {
            $xcrud = xcrud_get_instance();
            $xcrud->table('devices_pds');
            $xcrud->table_name(' ');
            $xcrud->relation('client_type_pds', 'client', 'id_client', 'client');
            $xcrud->relation('id_pds', 'pds', 'id_pds', 'reference');
            $xcrud->relation('id_displays_pds', 'displays_pds', 'id_displays_pds', 'id_displays_pds');
            $xcrud->relation('id_display', 'display', 'id_display', 'display');
            $xcrud->relation('id_device', 'device', 'id_device', 'device');
            $xcrud->relation('id_color_device', 'color_device', 'id_color_device', 'color_device');
            $xcrud->relation('id_complement_device', 'complement_device', 'id_complement_device', 'complement_device');
            $xcrud->relation('id_status_device', 'status_device', 'id_status_device', 'status_device');
            $xcrud->relation('id_status_packaging_device', 'status_packaging_device', 'id_status_packaging_device', 'status_packaging_device');
            $xcrud->label('client_type_pds', 'Cliente')->label('id_devices_pds', 'REF.')->label('id_pds', 'SFID')->label('id_displays_pds', 'Cod. mueble')->label('id_display', 'Mueble')->label('alta', 'Fecha de alta')->label('position', 'Posición')->label('id_device', 'Dispositivo')->label('IMEI', 'IMEI')->label('mac', 'MAC')->label('serial', 'Nº de serie')->label('barcode', 'Código de barras')->label('id_color_device', 'Color')->label('id_complement_device', 'Complementos')->label('id_status_device', 'Estado dispositivo')->label('id_status_packaging_device', 'Estado packaging')->label('picture_url_1', 'Foto #1')->label('picture_url_2', 'Foto #2')->label('picture_url_3', 'Foto #3')->label('description', 'Comentarios')->label('status', 'Estado');
            $xcrud->columns('client_type_pds,id_devices_pds,id_pds,id_displays_pds,id_display,id_device,position,IMEI, serial ,mac');
            $xcrud->fields('client_type_pds,id_devices_pds,id_pds,id_displays_pds,id_display,alta,id_device,position,serial,IMEI,mac,barcode,id_color_device,id_complement_device,id_status_device,id_status_packaging_device,picture_url_1,picture_url_2,picture_url_3,description');
            $xcrud->where('id_pds', $pds->id_pds);
            $xcrud->order_by('id_displays_pds', 'asc');
            $xcrud->order_by('position', 'asc');

            $xcrud->limit('all');
            $xcrud->show_primary_ai_column(true);
            $xcrud->unset_numbers();
            $xcrud->start_minimized(false);
            $xcrud->unset_add();
            $xcrud->unset_edit();
            $xcrud->unset_view();
            $xcrud->unset_csv();
            $xcrud->unset_print();
            $xcrud->unset_search();
            $xcrud->unset_remove();
            $xcrud->unset_pagination();
            $xcrud->unset_limitlist();

            return ($xcrud->render());

        }

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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        //$data['displays'] = $this->tienda_model->get_displays();
        $data['displays'] = $this->tienda_model->get_displays_demoreal();
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/inventario_planogramas', $data);
        $this->load->view('backend/footer');
    }

    public function almacen()
    {
        $this->load->model('tienda_model');

        $data['devices'] = $this->tienda_model->get_devices_almacen();
        $data['alarmas'] = $this->tienda_model->get_alarms_almacen_reserva();

        $xcrud = xcrud_get_instance();
        $xcrud->table('devices_almacen');
        $xcrud->table_name('Inventario dispositivos');
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

        $xcrud_2 = xcrud_get_instance();
        $xcrud_2->table('alarm');
        $xcrud_2->table_name('Inventario alarmas');
        $xcrud_2->relation('type_alarm', 'type_alarm', 'id_type_alarm', 'type');
        $xcrud_2->relation('brand_alarm', 'brand_alarm', 'id_brand_alarm', 'brand');
        $xcrud_2->change_type('picture_url', 'image');
        $xcrud_2->modal('picture_url');
        $xcrud_2->label('brand_alarm', 'Fabricante')->label('type_alarm', 'Tipo')->label('code', 'Código')->label('alarm', 'Modelo')->label('picture_url', 'Foto')->label('description', 'Comentarios')->label('units', 'Unidades')->label('status', 'Estado');
        $xcrud_2->columns('brand_alarm,type_alarm,code,alarm,picture_url,units,status');
        $xcrud_2->fields('brand_alarm,type_alarm,code,alarm,picture_url,description,units,status');
        $xcrud_2->order_by('code', 'asc');
        $xcrud_2->order_by('alarm', 'asc');
        $xcrud_2->unset_numbers();
        $xcrud_2->start_minimized(true);

        $data['title'] = 'Inventario';
        $data['content'] = $xcrud->render();
        $data['content'] = $data['content'] . $xcrud_2->render();

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/almacen', $data);
        $this->load->view('backend/footer');
    }



    /**
     * Método del controlador, que invoca al modelo para generar un CSV con el inventario de dispositivos en almacén.
     */
    public function exportar_dispositivos_almacen($formato=NULL)
    {
        if($this->auth->is_auth())
        {
            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $this->load->model('tienda_model');
            $data['stocks'] = $this->tienda_model->exportar_dispositivos_almacen($ext);
        }
        else
        {
            redirect('admin','refresh');
        }
    }

    /**
     * Método del controlador, que invoca al modelo para generar un CSV con el inventario de alarmas en almacén.
     */
    public function exportar_alarmas_almacen($formato=NULL)
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $this->load->model('tienda_model');
            $data['stocks'] = $this->tienda_model->exportar_alarmas_almacen($ext);

        }
        else
        {
            redirect('admin','refresh');
        }
    }


    public function alta_dispositivos_almacen()
    {
        if ($this->auth->is_auth())
        {
            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');

            $data['devices'] = $this->tienda_model->get_devices();

            $data['title'] = 'Alta masiva dispositivos';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/alta_dispositivos_almacen', $data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }


    public function alta_dispositivos_almacen_update()
    {
        if ($this->auth->is_auth())
        {
            $this->load->model('tienda_model');

            $data = array(
                'id_device' => $this->input->post('dipositivo_almacen'),
                'alta' => date('Y-m-d H:i:s'),
                'IMEI' => NULL,
                'mac' => NULL,
                'serial' => NULL,
                'barcode' => NULL,
                'id_color_device' => NULL,
                'id_complement_device' => NULL,
                'id_status_device' => NULL,
                'id_status_packaging_device' => NULL,
                'picture_url_1' => NULL,
                'picture_url_2' => NULL,
                'picture_url_3' => NULL,
                'description' => NULL,
                'owner' => $this->input->post('owner_dipositivo_almacen'),
                'status' => 1,
            );

            $i = 0;

            while ($i < $this->input->post('units_dipositivo_almacen'))
            {
                $this->tienda_model->alta_dispositivos_almacen_update($data,$this->input->post('units_dipositivo_almacen'));
                $i = $i + 1;
            }





            $this->session->set_flashdata("id_device",$this->input->post('dipositivo_almacen'));
            $this->session->set_flashdata("num",$this->input->post('units_dipositivo_almacen'));

            redirect('admin/alta_dispositivos_ok', 'refresh');
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }


    public function alta_dispositivos_ok()
    {
        if ($this->auth->is_auth())
        {
            $this->load->model('tienda_model');
            $xcrud = xcrud_get_instance();

            $id_device = $this->session->flashdata("id_device");
            $num = $this->session->flashdata("num");


            if(!empty($id_device) && !empty($num)) {
                $device = $this->tienda_model->get_device($id_device);

                $data["title"] = "Alta masiva dispositivos";

                $data["num"] = $num;
                $data["modelo"] = $device["device"];

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
                $this->load->view('backend/header', $data);
                $this->load->view('backend/navbar', $data);
                $this->load->view('backend/alta_dispositivos_almacen_ok', $data);
                $this->load->view('backend/footer');
            }else{
                redirect('admin/alta_dispositivos_almacen', 'refresh');
            }
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }

    public function baja_dispositivos_almacen()
    {
        if ($this->auth->is_auth())
        {
            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');

            $data['devices'] = $this->tienda_model->get_devices();

            $data['title'] = 'Baja masiva dispositivos';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/baja_dispositivos_almacen', $data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }

    public function baja_dispositivos_almacen_update()
    {
        if ($this->auth->is_auth())
        {
            $this->load->model('tienda_model');

            $num = $this->tienda_model->baja_dispositivos_almacen_update($this->input->post('dipositivo_almacen'),$this->input->post('owner_dipositivo_almacen'),$this->input->post('units_dipositivo_almacen'));

            $this->session->set_flashdata("id_device", $this->input->post('dipositivo_almacen'));



            if($num >= 0) {
                $this->session->set_flashdata("num", $num);
                redirect('admin/baja_dispositivos_ok', 'refresh');
            }else{
                $this->session->set_flashdata("num", $this->input->post('units_dipositivo_almacen'));
                redirect('admin/baja_dispositivos_ko', 'refresh');
            }
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }



    public function baja_dispositivos_ok()
    {
        if ($this->auth->is_auth())
        {
            $this->load->model('tienda_model');
            $xcrud = xcrud_get_instance();


            $id_device = $this->session->flashdata("id_device");
            $num = $this->session->flashdata("num");


            if(!empty($id_device) && !empty($num)) {
                $device = $this->tienda_model->get_device($id_device);

                $data["title"] = "Baja masiva dispositivos";

                $data["num"] = $num;
                $data["modelo"] = $device["device"];

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
                $this->load->view('backend/header', $data);
                $this->load->view('backend/navbar', $data);
                $this->load->view('backend/baja_dispositivos_almacen_ok', $data);
                $this->load->view('backend/footer');
            }else{
                redirect('admin/alta_dispositivos_almacen', 'refresh');
            }
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }


    public function baja_dispositivos_ko()
    {
        if ($this->auth->is_auth())
        {
            $this->load->model('tienda_model');
            $xcrud = xcrud_get_instance();


            $id_device = $this->session->flashdata("id_device");
            $num = $this->session->flashdata("num");


            if(!empty($id_device) && !empty($num)) {
                $device = $this->tienda_model->get_device($id_device);

                $data["title"] = "Baja masiva dispositivos";

                $data["num"] = $num;
                $data["modelo"] = $device["device"];

                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                /////
                $this->load->view('backend/header', $data);
                $this->load->view('backend/navbar', $data);
                $this->load->view('backend/baja_dispositivos_almacen_ko', $data);
                $this->load->view('backend/footer');
            }else{
                redirect('admin/alta_dispositivos_almacen', 'refresh');
            }
        }
        else
        {
            redirect('admin', 'refresh');
        }
    }

    public function listado_incidencias()
    {
        $xcrud = xcrud_get_instance();

        $data['title'] = 'Listado de incidencias';
        $data['content'] = 'En construcción.';

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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


        $ahora = date("Y-m-d H:i:s");

        $data = array(
            'fecha' => date('Y-m-d H:i:s'),
            'fecha_cierre'    	=> NULL,
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
            'last_updated' => $ahora,
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
            //$this->email->send();

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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
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

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
    }


    public function facturacion()
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model','sfid_model'));

            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin    = $this->input->post('fecha_fin');
            $instalador = $this->input->post('instalador'); $instalador = (empty($instalador)) ? 0 : $instalador;
            $dueno = $this->input->post('dueno');   $dueno = (empty($dueno)) ? 0 : $dueno;

            $instaladores = $this->db->query("SELECT id_contact, contact FROM contact WHERE type_profile_contact = 1")->result();
            $duenos = $this->db->query("SELECT id_client, client FROM client WHERE status='Alta' AND facturable = 1")->result();




            $data['facturacion'] = $this->tienda_model->facturacion_estado($fecha_inicio,$fecha_fin,$instalador,$dueno);

            $data['fecha_inicio'] = $fecha_inicio;
            $data['fecha_fin']   = $fecha_fin;

            $data['select_instaladores'] = $instaladores;
            $data['instalador'] = $instalador;

            $data['select_duenos'] = $duenos;
            $data['dueno'] = $dueno;

            $data['title'] = 'Facturación de intervenciones';
            $data['accion'] = 'admin/facturacion';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/facturacion/facturacion', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }



    public function exportar_facturacion($fecha_inicio=NULL,$fecha_fin=NULL,$instalador=NULL,$dueno=NULL,$formato=NULL)
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model','sfid_model'));

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $data['facturacion_csv'] = $this->tienda_model->exportar_facturacion($ext,$fecha_inicio,$fecha_fin,$instalador,$dueno);

        } else {
            redirect('admin', 'refresh');
        }
    }

    public function facturacion_intervencion()
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model','sfid_model'));


            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin    = $this->input->post('fecha_fin');
            $instalador = $this->input->post('instalador'); $instalador = (is_null($instalador) || empty($instalador)) ? 0 : $instalador;
            $dueno = $this->input->post('dueno');           $dueno = (is_null($dueno) || empty($dueno)) ? 0 : $dueno;




            $instaladores = $this->db->query("SELECT id_contact, contact FROM contact WHERE type_profile_contact = 1")->result();
            $duenos = $this->db->query("SELECT id_client, client FROM client WHERE status='Alta' AND facturable = 1")->result();




            $data['facturacion'] = $this->tienda_model->facturacion_estado_intervencion($fecha_inicio,$fecha_fin,$instalador,$dueno);

            $data['fecha_inicio'] = $fecha_inicio;
            $data['fecha_fin']   = $fecha_fin;

            $data['select_instaladores'] = $instaladores;
            $data['instalador'] = $instalador;

            $data['select_duenos'] = $duenos;
            $data['dueno'] = $dueno;

            $data['title'] = 'Facturación de proveedores';
            $data['accion'] = 'admin/facturacion_intervencion';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/facturacion/facturacion_intervencion', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function exportar_intervenciones_facturacion($fecha_inicio,$fecha_fin,$instalador=NULL,$dueno=NULL,$formato=NULL)
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');
            $this->load->model(array('tienda_model','sfid_model'));

            $xcrud = xcrud_get_instance();

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG

            $data['facturacion_csv'] = $this->tienda_model->exportar_intervenciones_facturacion($ext,$fecha_inicio,$fecha_fin,$instalador,$dueno);

        } else {
            redirect('admin', 'refresh');
        }
    }

    public function facturacion_fabricanteM()
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model','sfid_model'));

            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin    = $this->input->post('fecha_fin');
            $fabricante = $this->input->post('fabricante');   $fabricante = (empty($fabricante)) ? 0 : $fabricante;

            $fabricantes = $this->db->query("SELECT id_client, client FROM client WHERE status='Alta' AND facturable = 1 and type_profile_client=2")->result();

            $data['facturacion'] = $this->tienda_model->facturacion_fabricanteM($fecha_inicio,$fecha_fin,$fabricante);

            //$material_dispositivos = $this->tienda_model->get_material_dispositivos($incidencia['id_incidencia']);
            $data['fecha_inicio'] = $fecha_inicio;
            $data['fecha_fin']   = $fecha_fin;
            $data['select_fabricantes'] = $fabricantes;
            $data['fabricante'] = $fabricante;
            $data['title'] = 'Facturación de fabricante';
            $data['accion'] = 'admin/facturacion_fabricanteM';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/facturacion/facturacion_fabricanteM', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    public function exportar_facturacion_fabricanteM($fecha_inicio=NULL,$fecha_fin=NULL,$fabricante=NULL,$formato=NULL)
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();
            $this->load->model(array('tienda_model','sfid_model'));

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $data['facturacion_csv'] = $this->tienda_model->exportar_facturacion_fabricanteM($ext,$fecha_inicio,$fecha_fin,$fabricante);

        } else {
            redirect('admin', 'refresh');
        }
    }

    public function operaciones()
    {
        $xcrud = xcrud_get_instance();

        $data['title'] = 'Operaciones';
        $data['content'] = 'En construcción.';

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/content', $data);
        $this->load->view('backend/footer');
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

            $codigoSAT = "";

            $data["codigoSAT"] = $codigoSAT;

            $data["generado"] = FALSE;

            $data["resultados"] = $resultados;

            $data["muebles"] = $this->tienda_model->get_displays_demoreal();

            $data["pds_tipos"] = $this->categoria_model->get_tipos_pds();
            $data["pds_subtipos"] = $this->categoria_model->get_subtipos_pds();
            $data["pds_segmentos"] = $this->categoria_model->get_segmentos_pds();
            $data["pds_tipologias"] = $this->categoria_model->get_tipologias();

            $data["terminales"] = $this->tienda_model->get_devices_demoreal();
            /* LISTADO DE TERRITORIOS PARA EL SELECT */
            $data["territorios"] = $this->tienda_model->get_territorios();
            /* LISTADO DE FABRICANTES PARA EL SELECT */
            $data["fabricantes"] = $this->tienda_model->get_fabricantes();

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/informes/bloom/pdv/informe_puntos_venta_form', $data);
            $this->load->view('backend/informes/bloom/pdv/informe_puntos_venta', $data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin','refresh');
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
                "brand_device" => '',
                "codigoSAT" =>''
            );

            foreach ($arr_campos as $campo => $valor) {
                $$campo = $valor;
                $data[$campo] = $valor;
            }

            $campo_orden = NULL;
            $ordenacion = NULL;

            $total_registros = 0;

            $controlador_origen = "admin"; //  Controlador por defecto


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

                // CODIGO SAT
                $codigoSAT ="";
                $campos_sess_informe["codigoSAT"] = NULL;
                if (is_array($this->input->post("codigoSAT_multi"))) {
                    foreach ($this->input->post("codigoSAT_multi") as $tt) $codigoSAT = $tt;
                    $campos_sess_informe["codigoSAT"] = $codigoSAT;
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
                $data["codigoSAT"] = $codigoSAT;


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
                //print_r($resultados); exit;
                $data["total_registros"] = count($resultados);
                $data["resultados"] = $resultados;

                $resp = $this->load->view('backend/informes/bloom/pdv/informe_puntos_venta_ajax', $data, TRUE);
                echo $resp;

            }
            // Informe CSV
            else
            {

                $this->informe_model->exportar_informe_pdv($data,$ext,$controlador_origen);
            }

        }
    }



    public function informe_pdv_exportar_OLD()
    {
        if($this->auth->is_auth()) {
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
            redirect('admin','refresh');
        }
    }

    /**
     * Punto de entrada del Informe sobre planogramas.
     * Mostrará la vista principal con el formulario de filtrado, y recogerá los datos enviados y los procesará
     * como corresponda.
     */
    public function informe_planogramas()
    {
        if ($this->auth->is_auth()) {

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
                            //echo $display->devices_count."-";
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
                    $data['displays'] = $this->tienda_model->get_displays();
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

                redirect("admin/informe_planogramas", "refresh");

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
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/informes/informe_planograma_form', $data);

            switch ($vista) {
                case 1:
                    $this->load->view('backend/informes/informe_planograma_mueble_sfid',$data);
                    break;
                case 2:
                    $this->load->view('backend/informes/informe_planograma_mueble', $data);
                    break;
                case 3:
                    $this->load->view('backend/informes/informe_planograma_sfid', $data);
                    break;
                default:
                    $this->load->view('backend/informes/informe_planograma', $data);

            }


            $this->load->view('backend/footer');


        }
        else
        {
            redirect('admin','refresh');
        }
    }



    public function informe_planograma_mueble_pds($id_pds, $id_dis){
        if($this->auth->is_auth())
        {


            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');
            $this->load->model('sfid_model');

            $sfid = $this->tienda_model->get_pds($id_pds);

            $data["generado_planograma"] = FALSE;


            $data['id_pds']     = 'ABX/PDS-'.$sfid['id_pds'];
            $data['commercial'] = $sfid['commercial'];
            $data['territory']  = $sfid['territory'];
            $data['reference']  = $sfid['reference'];
            $data['address']    = $sfid['address'];
            $data['zip']        = $sfid['zip'];
            $data['city']       = $sfid['city'];

            $display = $this->sfid_model->get_display($id_dis);


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
            $muebles = $this->tienda_model->get_displays();
            $data["muebles"] = $muebles;
            $data['title'] = 'Planograma tienda';
            $data['subtitle'] = 'Planograma tienda [SFID-'.$data['reference'].'] - '.$data['display'];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header',$data);
            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/informes/informe_planograma_form',$data);
            $this->load->view('backend/informes/informe_planograma_ficha_mueble',$data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin','refresh');
        }

    }


    public function informe_planograma_terminal(){
        if($this->auth->is_auth())
        {
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
            $muebles = $this->tienda_model->get_displays();
            $data["muebles"] = $muebles;
            $data['title'] = 'Planograma tienda [SFID-'.$data['reference'].']';
            $data['subtitle'] = $data["display"] .' - '. $data["device"];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header',$data);
            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/informes/informe_planograma_form',$data);
            $this->load->view('backend/informes/informe_planograma_ficha_terminal',$data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin','refresh');
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
                redirect("admin/informe_visual", "refresh");
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
                        //print_r($displays);

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
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_form', $data);

            switch ($vista) {
                case 2 :
                    $this->load->view('backend/informes/bloom/visual/informe_visual_panelado',$data);
                    break;
                case 1 :
                    $this->load->view('backend/informes/bloom/visual/informe_visual_sfid', $data);
                    break;
                default:
                    $this->load->view('backend/informes/bloom/visual/informe_visual', $data);

            }

            $this->load->view('backend/footer');




        }
        else
        {
            redirect('admin','refresh');
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
            $this->load->view('backend/header',$data);
            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_form',$data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_maestro_mueble',$data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin','refresh');
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
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_form', $data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_terminal', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
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
            $this->load->view('backend/header',$data);
            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_form',$data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_mueble_sfid',$data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin','refresh');
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
            $this->load->view('backend/header',$data);
            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_form',$data);
            $this->load->view('backend/informes/bloom/visual/informe_visual_ficha_terminal',$data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin','refresh');
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
            $this->load->view('/backend/header', $data);
            $this->load->view('/backend/navbar', $data);
            $this->load->view('/backend/informes/tiendas_tipologia/list', $data);
            $this->load->view('/backend/footer');
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
            $this->load->view('/backend/header', $data);
            $this->load->view('/backend/navbar', $data);
            $this->load->view('/backend/informes/tiendas_fabricante/list', $data);
            $this->load->view('/backend/footer');
        }
        else
        {
            redirect($entrada,"refresh");
        }
    }

    /**
     * Muestra por mes el consumo de los diferentes modelos de alarmas
     * Tomando el estado de las incidencias "En visita" y la fecha en la cual la incidencia se puso en ese estado
     */
    public function informe_sistemas_seguridad()
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
            $this->load->view('/backend/header', $data);
            $this->load->view('/backend/navbar', $data);
            $this->load->view('/backend/informe_sistemas_seguridad.php', $data);
            $this->load->view('/backend/footer');
        } else {
            redirect('master', 'refresh');
        }
    }

    /**
     * Funcion que exporta los datos de las alarmas utilizadas en un año
     */
    public function exportar_sistemas_seguridad($anio,$formato=NULL) {
        if ($this->auth->is_auth())
        {
            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $this->load->model('alarma_model');
            $data['alarmas'] = $this->alarma_model->exportar_sistemas_seguridad($anio,$ext);
        }
        else
        {
            redirect('master','refresh');
        }
    }

    public function ayuda($tipo)
    {
        if ($this->auth->is_auth()) {
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
                case 5:
                    redirect('admin/manuales','refresh');
                    break;
                case 6:
                    redirect('admin/muebles_fabricantes','refresh');
                    break;
                default:
                    $data['video'] = "ver_incidencias.mp4";
                    $data['ayuda_title'] = "Mis solicitudes";
            }

            $data['title'] = 'Ayuda';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header', $data);
            $this->load->view('backend/navbar', $data);
            $this->load->view('backend/ayuda', $data);
            $this->load->view('backend/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function manuales()
    {
        if ($this->auth->is_auth()) {
            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $xcrud = xcrud_get_instance();

            $data['title']       = 'Ayuda';
            $data['ayuda_title'] = 'Manuales';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header',$data);
            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/manuales',$data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin','refresh');
        }
    }

    public function muebles_fabricantes()
    {
        if($this->auth->is_auth())
        {
            $xcrud = xcrud_get_instance();

            $data['title']       = 'Ayuda';
            $data['ayuda_title'] = 'Muebles fabricantes';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('backend/header',$data);
            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/muebles_fabricantes',$data);
            $this->load->view('backend/footer');
        }
        else
        {
            redirect('admin','refresh');
        }
    }


    public function logout()
    {
        if ($this->session->userdata('logged_in')) {
            $this->session->unset_userdata('logged_in');
            $this->session->sess_destroy();
        }
        redirect('admin', 'refresh');
    }



    public function informe_backup()
    {
        if($this->auth->is_auth()) {
            $xcrud = xcrud_get_instance();
            $opcion = NULL;
            $this->load->model("backup_model");


            $data['title'] = 'Realizar backup';

            $generar_backup = $this->input->post("generar_backup");

            if($generar_backup=="si") {
                $opcion = $this->input->post("opcion");
                $sfids = $this->input->post("sfids");
                $array_sfids = explode("\n", $sfids);
            }



            if (is_null($opcion)) {

                /////
                // Form de backup
                /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
                $this->data->add($data);
                $data = $this->data->getData();
                $this->load->view('backend/header', $data);
                $this->load->view('backend/navbar', $data);
                $this->load->view('backend/backup/form', $data);
                $this->load->view('backend/footer');

            } else {
                // Tratamiento del backup
                switch ($opcion) {
                    case "planograma":
                        $this->backup_model->set_sfids($array_sfids);
                        $resultado = $this->backup_model->exportar_planogramas();

                        $data["resultado"] = $resultado;

                        $this->data->add($data);
                        $data = $this->data->getData();
                        $this->load->view('backend/header', $data);

                        $this->load->view('backend/backup/planograma', $data);
                        $this->load->view('backend/footer');
                        break;
                }


            }
        }
    }

    public function anadir_mueble_sfid()
    {
        if($this->auth->is_auth()) {
            $xcrud = xcrud_get_instance();
            $this->load->model("tienda_model");

            $anadir_mueble = $this->input->post("anadir_mueble");
            $data["title"] = "Añadir mueble a SFIDs";
            $data["anadiendo_mueble"] = FALSE;

            $id_display = NULL;
            $sfids = "";
            $arr_sfids = array();

            // Venimos del form de añadir mueble. Añadir
            if($anadir_mueble == "si")
            {


                $sfids = $this->input->post("sfids");
                $arr_sfids = explode("\n", $sfids);
                $data["arr_sfids"] = $arr_sfids;
                $id_display = $this->input->post("id_display");


                $position = $this->input->post("position"); $position = (empty($position)) ? NULL : $position;

            }

            // Validamos y añadimos
            if(!empty($sfids) && !empty($id_display))
            {
                $data["anadiendo_mueble"] = TRUE;

                $display = $this->tienda_model->get_display($id_display,"object");
                $data["mueble"] = $display->display;
                $data["position"] = $position;


                $devices_display = $this->tienda_model->get_devices_display($id_display);

                // Validamos el array de SFIDs, y creamos un nuevo array que guarde NULL si el SFID no se encuentra
                // y en caso contrario guarde el objeto PDS asociado.
                $checked_sfids = array();
                foreach($arr_sfids as $sfid)
                {
                    if(!empty($sfid))
                    {
                        $check_sfid = $this->tienda_model->get_sfid($sfid,"object");
                        if (!empty($check_sfid)){// SFID ENCONTRADO
                            $checked_sfids[$sfid] = $check_sfid;

                            $this->tienda_model->anadir_mueble_sfid($display,$check_sfid,$position);


                        }
                        else $checked_sfids[$sfid] = NULL;
                    }
                }
                $data["checked_sfids"] = $checked_sfids;


            }


            $this->data->add($data);
            $data = $this->data->getData();
            $this->load->view('backend/header', $data);

            $muebles = $this->tienda_model->get_muebles();
            $data["muebles"] = $muebles;
            $data["sfids"] = $sfids;
            $data["id_display"]  = $id_display;

            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/masivas/anadir_mueble_sfid/formulario', $data);
            $this->load->view('backend/masivas/anadir_mueble_sfid/resultado', $data);
            $this->load->view('backend/footer');
        }
    }

    public function mantenimiento()
    {

        $data['bg_image'] = "bg-admin.jpg";
        $data['title'] = 'Parada por mantenimiento';

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('backend/header', $data);
        $this->load->view('common/mantenimiento', $data);
        $this->load->view('backend/footer');
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
            $this->load->view('backend/header',$data);
            $this->load->view('backend/navbar',$data);
            $this->load->view('backend/cdm_incidencias',$data);
            $this->load->view('backend/footer');
        } else {
            redirect('master', 'refresh');
        }
    }
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */