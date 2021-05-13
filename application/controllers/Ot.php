<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ot extends MY_Controller {

	function __construct()
	{
        parent::__construct();

        // Ctrl configuration
        $this->setController('ot');
        $this->setViewsFolder('ot');
        $this->setEnvironmentProfile('ot');
        $this->setHomeAction('cdm_dispositivos_balance');

        // Load Auth and check Entorno
        $this->setUserType(11);
        $this->load->library('auth',array(11));
        $this->auth->check_entorno('ot');
    }

    /**
     * Callback para el login
     * @param int $type
     */
    public function do_login()
    {
        return parent::do_login();
    }

    /*
     * Depósito - Inventario de dispositivos para Oferta Táctica
     */
   /* public function cdm_dispositivos()
    {
        if($this->auth->is_auth())
        {
            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');
            $data['stocks'] = $this->tienda_model->get_stock_cruzado();


            $data['stocks_dispositivos']  = $this->tienda_model->get_cdm_dispositivos();

            $data['title']   = 'Dispositivos';


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('ot/header',$data);
            $this->load->view('ot/navbar',$data);
            $this->load->view('ot/cdm_dispositivos',$data);
            $this->load->view('master/footer');
        }
        else
        {
            redirect('master','refresh');
        }
    }*/
    /*
     * Depósito - Inventario de dispositivos para Oferta Táctica
     */

    public function cdm_dispositivos_balance()
    {
        if($this->auth->is_auth())
        {
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
            $borrar_busqueda = $this->uri->segment(3);
            if($borrar_busqueda === "borrar_busqueda")
            {
                $this->delete_filtros($array_filtros);
                redirect(site_url("/ot/cdm_dispositivos_balance"),'refresh');
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
            $data['title']   = 'Dispositivos';

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('ot/header',$data);
            $this->load->view('ot/navbar',$data);
            $this->load->view('ot/cdm_dispositivos_balance',$data);
            $this->load->view('ot/footer');
        }
        else
        {
            redirect('ot','refresh');
        }
    }

    /*
    * Depósito - Incidencias de dispositivos para Oferta Táctica
    */
    public function cdm_dispositivos_incidencias()
    {
        if($this->auth->is_auth())
        {
            $xcrud = xcrud_get_instance();
            $this->load->model('tienda_model');
            //$data['stocks'] = $this->tienda_model->get_stock_cruzado();


            $data['stocks_dispositivos']  = $this->tienda_model->get_cdm_dispositivos();

            $data['title']   = 'Dispositivos';


            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('ot/header',$data);
            $this->load->view('ot/navbar',$data);
            $this->load->view('ot/cdm_dispositivos_incidencias',$data);
            $this->load->view('ot/footer');
        }else{
            redirect('ot','refresh');
        }
    }

    /**
     * Método del controlador, que invoca al modelo para generar un CSV con el balance de activos.
     */
    public function exportar_balance_activos($formato=NULL)
    {
        if($this->auth->is_auth())
        {
            $this->load->model('tienda_model');
            $ext = (!is_null($formato) ? $formato : $this->ext);    // Formato para exportaciones, especficiado o desde CFG

            /** Crear los filtros           */
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
                redirect(site_url("/ot/cdm_dispositivos_balance"),'refresh');
            }

            if($this->input->post('do_busqueda')==="si") $array_sesion = $this->set_filtros($array_filtros);

            /* Creamos al vuelo las variables que vienen de los filtros */
            foreach($array_filtros as $filtro=>$value){
                $$filtro = $array_sesion[$filtro];
                $data[$filtro] = $array_sesion[$filtro]; // Pasamos los valores a la vista.
            }

            $data['stocks'] = $this->tienda_model->exportar_stock_cruzado($ext,$this->session->userdata('sfid'),$array_sesion);
        }else{
            redirect('ot','refresh');
        }
    }

    /*
     * Depósito - Inventario para Oferta Táctica
     */
	/*public function cdm_inventario()
	{
        if($this->auth->is_auth())
        {
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
			$this->load->view('ot/header',$data);
			$this->load->view('ot/navbar',$data);
			$this->load->view('ot/inventario',$data);
			$this->load->view('ot/footer');
		}
		else
		{
			redirect('ot','refresh');
		}
	}*/

    /**
     * Método del controlador, que invoca al modelo para generar un CSV con el balance de activos.
     */
    public function cdm_balance_activos_csv()
    {
        if($this->auth->is_auth())
        {
            $this->load->model('tienda_model');
            $data['stocks'] = $this->tienda_model->get_stock_cruzado_csv();
        }
        else
        {
            redirect('ot','refresh');
        }
    }
    /*
     * Informe de puntos de venta
     */
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

           // $data["codigoSAT"] = $codigoSAT;

            $data["generado"] = FALSE;

            $data["resultados"] = $resultados;

            $data["muebles"] = $this->tienda_model->get_displays_demoreal();
            $data["mueblesdisplay"] = $this->tienda_model->get_mueblesdisplay_demoreal();

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
            $this->load->view('ot/header', $data);
            $this->load->view('ot/navbar', $data);
            $this->load->view('ot/informes/pdv/informe_puntos_venta_form', $data);
            $this->load->view('ot/informes/pdv/informe_puntos_venta', $data);
            $this->load->view('ot/footer');
        }
        else
        {
            redirect('ot','refresh');
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
                "id_muebledisplay" => '',
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

            $controlador_origen = "ot"; //  Controlador por defecto


            if ($this->input->post("generar_informe") === "si") {


                $controlador_origen = $this->input->post("controlador");
                $data["controlador"] = $controlador_origen;

                $campos_sess_informe = array();
                // CANAL - TIPO TIENDA
                $id_tipo = array();
                $campos_sess_informe["id_tipo"] = NULL;
                if (is_array($this->input->post("id_tipo_multi"))) {
                    foreach ($this->input->post("id_tipo_multi") as $tt) $id_tipo[] = $tt;
                    $campos_sess_informe["id_tipo"] = $id_tipo;
                }

                // TIPOLOGIA - SUBTIPO TIENDA
                $id_subtipo = array();
                $campos_sess_informe["id_subtipo"] = NULL;
                if (is_array($this->input->post("id_subtipo_multi"))) {
                    foreach ($this->input->post("id_subtipo_multi") as $tt) $id_subtipo[] = $tt;
                    $campos_sess_informe["id_subtipo"] = $id_subtipo;
                }

                // CONCEPTO - SEGMENTO TIENDA
                $id_segmento = array();
                $campos_sess_informe["id_segmento"] = NULL;
                if (is_array($this->input->post("id_segmento_multi"))) {
                    foreach ($this->input->post("id_segmento_multi") as $tt) $id_segmento[] = $tt;
                    $campos_sess_informe["id_segmento"] = $id_segmento;
                }

                // CATEGORIZACION - TIPOLOGIA TIENDA
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
                  // MUEBLE DISPLAY
                  $id_muebledisplay = array();
                  $campos_sess_informe["id_muebledisplay"] = NULL;
                  if (is_array($this->input->post("id_muebledisplay_multi"))) {
                      foreach ($this->input->post("id_muebledisplay_multi") as $tt) $id_muebledisplay[] = $tt;
                      $campos_sess_informe["id_muebledisplay"] = $id_muebledisplay;
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
                //print_r($resultados); exit;
                $data["total_registros"] = count($resultados);
                $data["resultados"] = $resultados;

                $resp = $this->load->view('ot/informes/pdv/informe_puntos_venta_ajax', $data, TRUE);
                echo $resp;

            }
            // Informe CSV
            else
            {
                if ($exportar=="exportarT") {
                    $data=array();
                }

                $this->informe_model->exportar_informe_pdv($data,$ext);
            }

        }
    }

    /**
     * Punto de entrada del Informe sobre planogramas.
     * Mostrará la vista principal con el formulario de filtrado, y recogerá los datos enviados y los procesará
     * como corresponda.
     */
    public function informe_planogramas()
    {
        if($this->auth->is_auth())
        {

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


                    $tiendas = $this->tienda_model->search_pds($sfid_plano,"Alta");
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
                    $tiendas = $this->tienda_model->search_pds($sfid_plano,"Alta");



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

            redirect("ot/informe_planogramas", "refresh");

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
        $this->load->view('ot/header', $data);
        $this->load->view('ot/navbar', $data);
        $this->load->view('ot/informes/planogramas/informe_planograma_form', $data);

        switch ($vista) {
            case 1:
                $this->load->view('ot/informes/planogramas/informe_planograma_mueble_sfid',$data);
                break;
            case 2:
                $this->load->view('ot/informes/planogramas/informe_planograma_mueble', $data);
                break;
            case 3:
                $this->load->view('ot/informes/planogramas/informe_planograma_sfid', $data);
                break;
            default:
                $this->load->view('ot/informes/planogramas/informe_planograma', $data);

        }


        $this->load->view('ot/footer');


    }
        else
        {
            redirect('ot','refresh');
        }
    }

    public function informe_planograma_mueble_pds(){
        if($this->auth->is_auth())
        {
            $id_pds   = $this->uri->segment(3);
            $id_dis   = $this->uri->segment(4);

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
            $muebles = $this->tienda_model->get_displays();
            $data["muebles"] = $muebles;
            $data['title'] = 'Planograma tienda';
            $data['subtitle'] = 'Planograma tienda [SFID-'.$data['reference'].'] - '.$data['display'];

            /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
            $this->data->add($data);
            $data = $this->data->getData();
            /////
            $this->load->view('ot/header',$data);
            $this->load->view('ot/navbar',$data);
            $this->load->view('ot/informes/planogramas/informe_planograma_form',$data);
            $this->load->view('ot/informes/planogramas/informe_planograma_ficha_mueble',$data);
            $this->load->view('ot/footer');
        }
        else
        {
            redirect('ot','refresh');
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
        $this->load->view('ot/header',$data);
        $this->load->view('ot/navbar',$data);
        $this->load->view('ot/informes/planogramas/informe_planograma_form',$data);
        $this->load->view('ot/informes/planogramas/informe_planograma_ficha_terminal',$data);
        $this->load->view('ot/footer');
        }
        else
        {
            redirect('ot/informe_planogramas','refresh');
        }
}

	public function panelado_tienda($tipo, $id_panelado=NULL)
    {
        // Incluir los modelos
        $xcrud = xcrud_get_instance();
        $this->load->model('sfid_model');
        $this->load->model('tienda_model');
        $this->load->model('informe_model');

        $panelados = $this->tienda_model->get_panelados_maestros_demoreal($tipo);

        $resp = '<option value="" selected="selected">Escoge el panelado...</option>';
        foreach($panelados as $panel){

            $s_selected = (!is_null($id_panelado) && $id_panelado == $panel->id_panelado) ? ' selected="selected" ' : '';
            $resp .= '<option value="'.$panel->id_panelado.'" '.$s_selected.'>'.$panel->panelado.'</option>';
        }
        echo $resp;
    }

	public function logout()
	{
		//if($this->session->userdata('logged_in'))
        if($this->auth->is_auth())
		{		
			$this->session->unset_userdata('logged_in');
            $this->session->sess_destroy();
		}	
		redirect('ot','refresh');
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