<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ttpp extends MY_Controller {

	function __construct()
	{
        parent::__construct();

        // Ctrl configuration
        $this->setController('ttpp');
        $this->setViewsFolder('ttpp');
        $this->setEnvironmentProfile('ttpp');
        $this->setHomeAction('estado_incidencias/abiertas');

        // Load Auth and check Entorno
        $this->setUserType(13);
        $this->load->library('auth',array(13));
        $this->auth->check_entorno('ttpp');
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

        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $data['id_pds'] = $this->session->userdata('id_pds');
            $data['sfid'] = $this->session->userdata('sfid');

            $limpiar_filtros = FALSE;
            if($this->session->userdata('estado_incidencias')=="" || $this->session->userdata('estado_incidencias')!= $tipo)
            {
                $this->session->set_userdata('estado_incidencias', $tipo);
                $limpiar_filtros = TRUE;
            }

            $xcrud = xcrud_get_instance();

            $controlador = $this->data->get("controlador");

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

                'id_tipo'=> '4',
                'id_subtipo'=>'10',
                'id_segmento'=>'',
                'id_tipologia'=>'',
                'id_tipo_incidencia'=>''
            );



            /* BORRAR BUSQUEDA */


            $borrar_busqueda = ($limpiar_filtros) ? "borrar_busqueda" : "";

            if($borrar_busqueda == "borrar_busqueda")
            {
                $this->delete_filtros($array_filtros,array('id_tipo'));
                $array_sesion = $this->set_filtros($array_filtros);
                redirect(site_url("/$controlador/estado_incidencias/".$tipo),'refresh');
            }

            if($this->input->post('do_busqueda')==="si") $array_sesion = $this->set_filtros($array_filtros);


            // Consultar a la session si ya se ha buscado algo y guardado allí.
            $array_sesion = $this->get_filtros($array_filtros);
            // Buscar en el POST si hay busqueda, y si la hay usarla y guardarla además en sesion

            /* Creamos al vuelo las variables que vienen de los filtros */
            foreach($array_filtros as $filtro=>$value){
                $$filtro = $array_sesion[$filtro];
                $data[$filtro] = $array_sesion[$filtro]; // Pasamos los valores a la vista.
            }


            // viene del form de ordenacion
          //  $do_orden = $this->input->post('ordenar');
           // if($do_orden==='true') {
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
            $cfg_pagination = $this->paginationlib->init_pagination($controlador."/estado_incidencias/$tipo/page/",$total_incidencias,$per_page,$segment);


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
                //$incidencia->device = $this->sfid_model->get_device($incidencia->id_devices_pds);
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
            $data["tipos"] = $this->categoria_model->get_tipos_pds(4);
            $data["subtipos"] = $this->categoria_model->get_subtipos_pds(4);
            $data["segmentos"] = $this->categoria_model->get_segmentos_pds();
            $data["tipologias"] = $this->categoria_model->get_tipologias_filtradas(4,10);

            /* SELECTOR DEL TIPO DE INCIDENCIA*/
            $data['tipos_incidencia'] = $this->tienda_model->get_tipos_incidencia();

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////

            $this->load->view('ttpp/header', $data);
            $this->load->view($controlador.'/navbar', $data);
            $this->load->view($controlador.'/estado_incidencias/'.$tipo, $data);
            $this->load->view('ttpp/footer');
        } else {
            redirect('ttpp', 'refresh');
        }
    }



    public function exportar_incidencias($tipo="abiertas",$formato=NULL)
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

                'id_tipo'=>'4',
                'id_subtipo'=>'10',
                'id_tipo_incidencia'=>''
            );
            $array_sesion = $this->get_filtros($array_filtros);

            // Obtener el campo a ordenar, primero de Session y despues del post, si procede..
            $array_orden = $this->get_orden();


            $this->incidencia_model->exportar_incidencias($array_orden, $array_sesion, $tipo,$ext);



        } else {
            redirect('ttpp', 'refresh');
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
				redirect('ttpp','refresh');
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

                $controlador = $this->data->get("controlador");

				$this->load->view('ttpp/header',$data);
				$this->load->view($controlador.'/navbar',$data);
				$this->load->view($controlador.'/detalle_incidencia',$data);
				$this->load->view('ttpp/footer');
			}				
		}
		else
		{
			redirect('ttpp','refresh');
		}
	}



	/*Funcion que ya no se usa
	 * public function inventarios_planogramas()
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
			$this->load->model('tienda_model');
            $controlador = $this->data->get("controlador");


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
			$this->load->view('ttpp/header',$data);
			$this->load->view($controlador.'/navbar',$data);
			$this->load->view('ttpp/inventario_planogramas',$data);
			$this->load->view('ttpp/footer');
		}
		else
		{
			redirect('ttpp','refresh');
		}
	}*/



    /**
     * Punto de entrada del Informe sobre planogramas.
     * Mostrará la vista principal con el formulario de filtrado, y recogerá los datos enviados y los procesará
     * como corresponda.
     */
    public function informe_planogramas()
    {
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $controlador = $this->data->get("controlador");

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


                    $tiendas = $this->tienda_model->search_pds($sfid_plano);
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
                    $tiendas = $this->tienda_model->search_pds($sfid_plano);



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

            redirect("ttpp/informe_planogramas", "refresh");

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
        $this->load->view('ttpp/header', $data);
        $this->load->view($controlador.'/navbar', $data);
        $this->load->view('ttpp/informes/informe_planograma_form', $data);

        switch ($vista) {
            case 1:
                $this->load->view('ttpp/informes/informe_planograma_mueble_sfid',$data);
                break;
            case 2:
                $this->load->view('ttpp/informes/informe_planograma_mueble', $data);
                break;
            case 3:
                $this->load->view('ttpp/informes/informe_planograma_sfid', $data);
                break;
            default:
                $this->load->view('ttpp/informes/informe_planograma', $data);

        }


        $this->load->view('ttpp/footer',$data);


    }
        else
        {
            redirect('ttpp','refresh');
        }
    }



    public function informe_planograma_mueble_pds(){

        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

            $controlador = $this->data->get("controlador");

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
            $this->load->view('ttpp/header',$data);
            $this->load->view($controlador.'/navbar',$data);
            $this->load->view('ttpp/informes/informe_planograma_form',$data);
            $this->load->view('ttpp/informes/informe_planograma_ficha_mueble',$data);
            $this->load->view('ttpp/footer');
        }
        else
        {
            redirect('ttpp/informe_planogramas','refresh');
        }

    }


    public function informe_planograma_terminal(){
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

        $data["generado_planograma"] = FALSE;
        $controlador = $this->data->get("controlador");

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
        $this->load->view('ttpp/header',$data);
        $this->load->view($controlador.'/navbar',$data);
        $this->load->view('ttpp/informes/informe_planograma_form',$data);
        $this->load->view('ttpp/informes/informe_planograma_ficha_terminal',$data);
        $this->load->view('ttpp/footer');
        }
        else
        {
            redirect('ttpp','refresh');
        }
}




	public function ayuda($tipo)
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
            $controlador = $this->data->get("controlador");

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
					redirect('ttpp/manuales','refresh');
					break;	
				case 6:
					redirect('ttpp/muebles_fabricantes','refresh');
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
			$this->load->view('ttpp/header',$data);
			$this->load->view($controlador.'/navbar',$data);
			$this->load->view('ttpp/ayuda',$data);
			$this->load->view('ttpp/footer');
		}
		else
		{
			redirect('ttpp','refresh');
		}
	}


	public function manuales()
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor

			$xcrud = xcrud_get_instance();
            $controlador = $this->data->get("controlador");

			$data['title']       = 'Ayuda';
			$data['ayuda_title'] = 'Manuales';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
			$this->load->view('ttpp/header',$data);
			$this->load->view($controlador.'/navbar',$data);
			$this->load->view('ttpp/manuales',$data);
			$this->load->view('ttpp/footer');
		}
		else
		{
			redirect('ttpp','refresh');
		}
	}

	public function muebles_fabricantes()
	{
        if($this->auth->is_auth()){ // Control de acceso según el tipo de agente. Permiso definido en constructor
			$xcrud = xcrud_get_instance();
	        $controlador = $this->data->get("controlador");

			$data['title']       = 'Ayuda';
			$data['ayuda_title'] = 'Muebles fabricantes';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
			$this->load->view('ttpp/header',$data);
			$this->load->view($controlador.'/navbar',$data);
			$this->load->view('ttpp/muebles_fabricantes',$data);
			$this->load->view('ttpp/footer');
		}
		else
		{
			redirect('ttpp','refresh');
		}
	}	
	
	public function logout()
	{
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {
			$this->session->unset_userdata('logged_in');
            $this->session->sess_destroy();
		}	
		redirect('ttpp','refresh');
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
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */