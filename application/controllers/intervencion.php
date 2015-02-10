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
        $this->load->model('intervencion_model');
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
        $intervenciones = $this->intervencion_model->get_intervenciones();
        $this->data['data'] = $intervenciones;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function getInfoIntervercion()
    {
        if (isset($_POST["intervencion_id"])) {
            $intervencion_id = $_POST["intervencion_id"];
            $intervencion = $this->intervencion_model->get_info_intervencion($intervencion_id);
            $this->data['data'] = $intervencion;
        } else {
            $this->data['data'] = null;
        }
        $this->load->view('backend/dataJSON', $this->data);
    }

    function test_getInfoIntervercion($intervencion_id)
    {
        $intervencion = $this->intervencion_model->get_info_intervencion($intervencion_id);
        $this->data['data'] = $intervencion;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function getIntervencionesPDS(){
        if(isset($_POST['id_pds'])){
            $id_pds=$_POST['id_pds'];
            $intervenciones = $this->intervencion_model->get_intervenciones_pds($id_pds);
            $this->data['data']=$intervenciones;
        }
        else{
            $this->data['data']=null;
        }
        $this->load->view('backend/dataJSON', $this->data);
    }

    function createIntervencion(){

        if ((isset($_POST['incidencia_id'])) && (isset($_POST['pds_id']))
            &&  (isset($_POST['description'])) && (isset($_POST['operador_id'])) ){
            $this->load->model('VO/ContactVO');
            $this->load->model('VO/IntervencionVO');
            $this->load->model('VO/PdsVO');
            $this->load->model('VO/IncidenciaVO');
            $incidencia_id=$_POST['incidencia_id'];
            $pds_id=$_POST['pds_id'];
            $description=$_POST['description'];
            $operador_id=$_POST['operador_id'];
            $c = new ContactVO();
            $i = new IntervencionVO();
            $p = new PdsVO();
            $incidencia = new IncidenciaVO();
            $incidencia->__set('id_incidencia',$incidencia_id);
            $incidencia->__set('status',4);
            $p->__set('id_pds',$pds_id);
            $c->__set('id_contact',$operador_id);
            $i->__set('description',$description);
            $i->__set('operador',$c);
            $i->__set('status',1);
            $i->__set('pds',$p);

            if (!$this->intervencion_model->check_incidencia_asignada_to_intervencion($incidencia->id_incidencia)) {
                $this->data['data']="Incidencia ya asignada a una intervencion";
            } else {
                $i->__set('id_intervencion',$this->intervencion_model->create_intervencion($i));
                $result=$this->intervencion_model->add_incidencia_to_intervencion($incidencia->id_incidencia,$i->id_intervencion);
                if($result==true) {
                    //cambiamos el estado de la incidencia a asignada
                    $status = 4;
                    $result = $this->intervencion_model->change_status_incidencia($incidencia_id, $status);
                    $this->data['data'] = $result;
                }
                $this->data['data']=false;
            }
        }
        else{
            $this->data['data']=false;
        }
        $this->load->view('backend/dataJSON', $this->data);
    }

    function addIncidenciaToIntervencion(){
        if (isset($_POST['incidencia_id']) && isset($_POST['intervencion_id'])) {
            $incidencia_id = $_POST['incidencia_id'];
            $intervencion_id = $_POST['intervencion_id'];
            $result=$this->intervencion_model->add_incidencia_to_intervencion($incidencia_id,$intervencion_id);
            if($result==0){
                $this->data['data']="Incidencia ya asignada";
            }
            else if($result==true){
                //cambiamos el estado de la incidencia a asignada
                $status=4;
                $result=$this->intervencion_model->change_status_incidencia($incidencia_id,$status);
                $this->data['data']=$result;
            }
            else{
                $this->data['data']=$result;
            }
        }
        else{

            $this->data['data']=false;
        }
        $this->load->view('backend/dataJSON', $this->data);
    }

    function getOperadoresIntervencion(){
        $operadores = $this->intervencion_model->get_operadores();
        $this->data['data']=$operadores;
        $this->load->view('backend/dataJSON', $this->data);
    }

}

?>