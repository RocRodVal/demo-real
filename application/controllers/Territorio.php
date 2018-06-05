<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Territorio extends MY_Controller {

	function __construct()
	{

        parent::__construct();

        // Ctrl configuration
        $this->setController('territorio');
        $this->setViewsFolder('territorio');
        $this->setEnvironmentProfile('territorio');
        $this->setHomeAction('estado_incidencias/abiertas');

        // Load Auth and check Entorno
        $this->setUserType(12);
        $this->load->library('auth',array(12));
        $this->auth->check_entorno('territorio');

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
                    'status_pds' => '',
                    'territory' => '',
                    'brand_device' => '',
                    'id_display'=>'',
                    'id_device'=>'',

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
        //if ($this->session->userdata('logged_in') && ($this->session->userdata('type') == 12)) {
        if ($this->auth->is_auth()) {
            $xcrud = xcrud_get_instance();

            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG
            $this->load->model(array('intervencion_model', 'tienda_model', 'sfid_model','chat_model'));
            $tipo = $this->uri->segment(3); // TIPO DE INCIDENCIA

            // Filtros
            $array_filtros = array(
                'status_pds'=>'',
                'territory'=>'',
                'brand_device'=>'',
                'id_display'=>'',
                'id_device'=>'',
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
            $this->incidencia_model->exportar_incidencias($array_orden, $array_sesion, $tipo,$ext);
        } else {
            redirect('territorio', 'refresh');
        }
    }

    public function detalle_incidencia($id_incidencia,$id_pds)
	{
		//if($this->session->userdata('logged_in') && ($this->session->userdata('type') == 12))
        if ($this->auth->is_auth()) {
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
                $data['foto_url']        = $incidencia['foto_url'];
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
		//if($this->session->userdata('logged_in'))
        if ($this->auth->is_auth()) {
			$this->session->unset_userdata('logged_in');
            $this->session->sess_destroy();
		}	
		redirect('territorio','refresh');
	}


    public function mantenimiento()
    {

        $data['bg_image'] = "bg-master.jpg";
        $data['title'] = 'Parada por mantenimiento';

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view('territorio/header', $data);
        $this->load->view('common/mantenimiento', $data);
        $this->load->view('territorio/footer');
    }
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */