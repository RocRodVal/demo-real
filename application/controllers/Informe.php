<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Informe extends CI_Controller {

    private $controlador;

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('email','text','xcrud'));
		$this->load->library(array('email','encrypt','form_validation','session'));
        ## CI Deprecated - $this->load->library('uri');

    }

    public function auth()
    {
        $perfiles_permitidos = array(9,10);
        return ($this->session->userdata('logged_in') && (in_array($this->session->userdata('type'),$perfiles_permitidos)));

    }
	
	public function index()
	{
		$xcrud = xcrud_get_instance();
		$this->load->model('user_model');
	
		$this->form_validation->set_rules('sfid','SFID','required');
		$this->form_validation->set_rules('password','password','required');

		if ($this->form_validation->run() == true){
			$data = array(
                'sfid' 	   => strtolower($this->input->post('sfid')),
                'password' => $this->input->post('password'),
			);
		}
	
		if ($this->form_validation->run() == true)
		{
            $this->form_validation->set_rules('sfid','SFID','callback_do_login');

            if($this->form_validation->run() == true){
                redirect('master/estado_incidencias/abiertas');
            }else{
                $data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));
            }
		}

        $data['message'] = (validation_errors() ? validation_errors() : ($this->session->flashdata('message')));

        $data['title'] = 'Login';

        $this->load->view('master/header',$data);
        $this->load->view('master/login',$data);
        $this->load->view('master/footer');

	}


    public function do_login(){

        $this->load->model('user_model');
        $data = array(
            'sfid' => strtolower($this->input->post('sfid')),
            'password' => sha1($this->input->post('password')),
        );
        if($this->user_model->login_master($data)){
            return true;
        }else{
            $this->form_validation->set_message('do_login','"Username" or "password" are incorrect.');
            return false;
        }
    }

    /**
     * Funci??n que guarda en sesi??n el valor de los filtros del POST, al venir de un form de filtrado
     * @param $array_filtros
     */
    public function set_filtros($array_filtros){
        $array_valores = NULL;
        if(is_array($array_filtros))
        {
            $array_valores = array();
            foreach ($array_filtros as $filter)
            {
                $valor_filter = $this->input->post($filter);
                $this->session->set_userdata($filter, $valor_filter);
                $array_valores[$filter] = $valor_filter;
            }
        }
        return $array_valores;
    }


    /**
     * M??todo que borra de la sesi??n, X variables, pasado sus nombres en un array
     * Si el par??metro es un array (de variables), lo recorremos y eliminamos de la sesi??n cualquier valor que tenga
     * la variable de sesi??n de ese nombre
     */
    public function delete_filtros($array_filtros){
        if(is_array($array_filtros)){
            foreach($array_filtros as $filtro){
                $this->session->unset_userdata($filtro);
            }
        }
    }

    /**
     * Recibe el array de filtros (campos del buscador/filtrador) y buscar?? su valor en la sesi??n, y cargar?? otro array
     * con los pares VARIABLE=>VALOR SESION.
     * @param $array_filtros
     * @return array|null
     */
    public function get_filtros($array_filtros){
        $array_session = NULL;

        if(is_array($array_filtros)){
            $array_session = array();
            foreach($array_filtros as $filter){

                $sess_filter = $this->session->userdata($filter);
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


    public function get_orden() {
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


    public function subtipos_tienda($id_tipo, $id_subtipo=NULL)
    {
        /* Incluir los modelos */
        $xcrud = xcrud_get_instance();
        $this->load->model('sfid_model');
        $this->load->model('tienda_model');
        $this->load->model('categoria_model');


        $subtipos = $this->categoria_model->get_subtipos_pds($id_tipo);

        $resp = '<option value="" selected="selected">Escoge la tipolog??a...</option>';
        foreach($subtipos as $subtipo){
            $s_selected = (!is_null($id_subtipo) && $id_subtipo == $subtipo["id"]) ? ' selected="selected" ' : '';
            $resp .= '<option value="'.$subtipo["id"].'" '.$s_selected.'>'.$subtipo["titulo"].'</option>';
        }
        echo $resp;
    }


    public function tipologias_tienda($id_subtipo, $id_tipologia=NULL)
    {
        /* Incluir los modelos */
        $xcrud = xcrud_get_instance();
        $this->load->model('sfid_model');
        $this->load->model('tienda_model');
        $this->load->model('categoria_model');


        $tipologias = $this->categoria_model->get_tipologias_pds($id_tipologia,$id_subtipo);
        
        $resp = '<option value="" selected="selected">Escoge la categorizaci??n...</option>';
        foreach($tipologias as $tipologia){
            $s_selected = (!is_null($id_tipologia) && $id_tipologia == $tipologia["id_tipologia"]) ? ' selected="selected" ' : '';
            $resp .= '<option value="'.$tipologia["id_tipologia"].'" '.$s_selected.'>'.$tipologia["titulo"].'</option>';
        }
        echo $resp;
    }

    /*
     * Funcion que nos devuelve los devices que tenemos en el almacen de un tipo determinado
     */
    public function devices_almacen($id_device)
    {
        /* Incluir los modelos */
        $xcrud = xcrud_get_instance();
        $this->load->model('tienda_model');

        $devices_almacen = $this->tienda_model->get_devicesAlmacen_Tipo($id_device);
        $resp = '<option value="" selected="selected">Escoge uno...</option>';
        foreach($devices_almacen as $device){
            $titulo = $device->device;
            if(!is_null($device->IMEI))
                $titulo.='['.$device->IMEI.']';
            else
                $titulo.='[]';
            $resp .= '<option value="'.$device->id_devices_almacen.'">'.$titulo.'</option>';
        }
        echo $resp;
    }

    /**
     * M??todo que genera XLS en Cuadros de Mando > Estado incidencias.
     * Recibe el mes
     * @param $i_mes
     * @param null $idx_status
     * @param null $menos_72h
     */
    public function exportar_cdm_incidencias($i_anio= NULL,$i_mes=NULL,$idx_status=NULL,$menos_72h = NULL)
    {
        $this->load->model("informe_model");

        if(is_null($i_mes)) $i_mes = 1;
        if(is_null($i_anio)) $i_anio = date("Y");

        $this->informe_model->exportar_cdm_incidencias($i_anio,$i_mes,$idx_status,$menos_72h);
    }


    public function exportar_cdm_incidencias_finalizadas($i_anio= NULL,$i_mes=NULL,$menos_72h = NULL) {
        $this->load->model("informe_model");

        if(is_null($i_mes)) $i_mes = 1;
        if(is_null($i_anio)) $i_anio = date("Y");

        $this->informe_model->exportar_cdm_incidencias_finalizadas($i_anio,$i_mes,$menos_72h);
    }
}
/* End of file admin.php */
/* Location: ./application/controllers/admin.php */