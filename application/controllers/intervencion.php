<?php

/**
 * Created by PhpStorm.
 * User: dani
 * Date: 9/2/15
 * Time: 17:02
 */
class Intervencion extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('xcrud', 'text');
        $this->load->library(array('email', 'form_validation', 'ion_auth', 'encrypt', 'form_validation', 'session'));
        $this->load->helper('text');
        $this->load->database();
    }

    function index()
    {
        $session_data = $this->session->userdata('logged_in');
        $data['sfid'] = $this->session->userdata('sfid');
        $data['agent_id'] = $this->session->userdata('agent_id');
        $data['type'] = $this->session->userdata('type');

        $xcrud = xcrud_get_instance();

        $data['title'] = 'Intervenciones';
        $this->load->view('backend/header', $data);
        $this->load->view('backend/navbar', $data);
        $this->load->view('backend/intervenciones/listar', $data);
        $this->load->view('backend/footer');
    }

    function listar_intervenciones()
    {
        $this->load->model('intervencion_model');
        $intervenciones = $this->intervencion_model->get_intervenciones();
        $this->data['data'] = $intervenciones;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function getInfoIntervercion()
    {
        if (isset($_POST["intervencion_id"])) {
            $intervencion_id = $_POST["intervencion_id"];
            $this->load->model('intervencion_model');
            $intervencion = $this->intervencion_model->get_info_intervencion($intervencion_id); //TODO
            $this->data['data'] = $intervencion;
        } else {
            $this->data['data'] = null;
            var_dump("ERROR");
        }
        $this->load->view('backend/dataJSON', $this->data);
    }

    function test_getInfoIntervercion($intervencion_id)
    {
        $this->load->model('intervencion_model');
        $intervencion = $this->intervencion_model->get_info_intervencion($intervencion_id); //TODO
        $this->data['data'] = $intervencion;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function crear_intervencion()
    {

    }

    function asignar_incidencia()
    {
        if (isset($_POST['incidencia_id']))
            $incidencia_id = $_POST['incidencia_id'];
    }

}

?>