<?php

/**
 * Created by PhpStorm.
 * User: dani
 * Date: 9/2/15
 * Time: 17:02
 */
class Operacion extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('xcrud', 'text');
        $this->load->library(array('email', 'form_validation','encrypt', 'form_validation', 'session'));
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
            $intervenciones = $this->intervencion_model->get_intervenciones_pds_nuevas($id_pds);
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
                $this->intervencion_model->add_incidencia_to_intervencion($incidencia->id_incidencia, $i->id_intervencion);
                //cambiamos el estado de la incidencia a asignada
                $status = 4;
                $result = $this->intervencion_model->change_status_incidencia($incidencia_id, $status);
                $this->data['data'] = $result;
            }
        }
        else{
            $this->data['data']=0;
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

    function cancelIntervencion(){
        if (isset($_POST['intervencion_id'])) {
            $this->load->model('VO/IntervencionVO');
            $this->load->model('VO/IncidenciaVO');
            $intervencion_id=$_POST['intervencion_id'];
            //buscamos las incidencias de la intervencion y las pasamos al estado anterior
            $intervencion=new IntervencionVO();
            $intervencion->__set('id_intervencion',$intervencion_id);
            $incidencias = $this->intervencion_model->get_incidencias_by_intervencion($intervencion);
            $status_incidencia=3;
            foreach($incidencias as $incidencia){
                $this->intervencion_model->change_status_incidencia($incidencia->id_incidencia,$status_incidencia);
            }
            //desasignamos las incidencias de la intervencion
            $this->intervencion_model->delete_incidencias_intervencion($intervencion_id);
            //cambiamos el estado de la intervencion a cancelada
            $status=4;
            $result=$this->intervencion_model->change_status_intervencion($intervencion_id,$status);
            $this->data['data']=$result;
        }
        else
            $this->data['data']=false;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function generateDocIntervencion(){
        if (isset($_POST['intervencion_id'])) {
            //Cambiamos el estado de las incidencias a comunicadas
            $this->load->model('VO/IntervencionVO');
            $this->load->model('VO/IncidenciaVO');
            $intervencion_id=$_POST['intervencion_id'];
            //buscamos las incidencias de la intervencion y las pasamos al estado comunicada
            $intervencion=new IntervencionVO();
            $intervencion->__set('id_intervencion',$intervencion_id);
            $incidencias = $this->intervencion_model->get_incidencias_by_intervencion($intervencion);
            $status_incidencia=5;
            foreach($incidencias as $incidencia){
                $this->intervencion_model->change_status_incidencia($incidencia->id_incidencia,$status_incidencia);
            }
            //GENERAMOS TODA LA DOCUMENTACION NECESARIA
            //TODO
            //cambiamos el estado de la intervencion a comunicada
            $status=2;
            $result=$this->intervencion_model->change_status_intervencion($intervencion_id,$status);
            $this->data['data']=$result;
        }
        else
            $this->data['data']=false;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function cerrarIntervencion(){
        if (isset($_POST['intervencion_id'])) {
            //Cambiamos el estado de las incidencias a resueltas
            $this->load->model('VO/IntervencionVO');
            $this->load->model('VO/IncidenciaVO');
            $intervencion_id=$_POST['intervencion_id'];
            //buscamos las incidencias de la intervencion y las pasamos al estado resuelto
            $intervencion=new IntervencionVO();
            $intervencion->__set('id_intervencion',$intervencion_id);
            $incidencias = $this->intervencion_model->get_incidencias_by_intervencion($intervencion);
            $status_incidencia=6;
            foreach($incidencias as $incidencia){
                $this->intervencion_model->change_status_incidencia($incidencia->id_incidencia,$status_incidencia);
            }
            //cambiamos el estado de la intervencion a cerrada
            $status=3;
            $result=$this->intervencion_model->change_status_intervencion($intervencion_id,$status);
            $this->data['data']=$result;
        }
        else
            $this->data['data']=false;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function deleteIncidenciaIntervencion(){
        if (isset($_POST['incidencia_id']) && isset($_POST['intervencion_id'])) {
            $incidencia_id=$_POST['incidencia_id'];
            $intervencion_id=$_POST['intervencion_id'];
            //sacamos la incidencia de la intervención
            $result=$this->intervencion_model->delete_incidencia_intervencion($incidencia_id);
            if($result){
                //comprobamos el numero de incidencias para la intervencion y si es 0, la pasamos a cancelada
                $count=$this->intervencion_model->get_count_incidencias_in_intervencion($intervencion_id);
                if($count==0){
                    $status=4;
                    $this->intervencion_model->change_status_intervencion($intervencion_id,$status);
                }
                //cambiamos el estado de la incidencia a asiganado material
                $status=3;
                $result=$this->intervencion_model->change_status_incidencia($incidencia_id,$status);
                $this->data['data']=$result;
            }
            else
                $this->data['data']=$result;

        }
        else
            $this->data['data']=false;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function getIncidenciasTiendaSinIntervencion(){
        $incidencias=array();
        if (isset($_POST['id_pds'])) {
            $id_pds=$_POST['id_pds'];
            $incidencias=$this->intervencion_model->get_incidencias_material_asignado_pds_sin_intervencion($id_pds);
        }
        $this->data['data']=$incidencias;
        $this->load->view('backend/dataJSON', $this->data);
    }

    function addIncidenciasIntervencion(){
        if(isset($_POST['incidencias']) && isset($_POST['id_intervencion'])){
            $incidencias = json_decode(stripslashes($_POST['incidencias']));
            $intervencion_id=$_POST['id_intervencion'];
            foreach($incidencias as $incidencia_id){
                if($this->intervencion_model->add_incidencia_to_intervencion($incidencia_id, $intervencion_id)){
                    //cambiamos el estado de la incidencia a asignada
                    $status=4;
                    $this->intervencion_model->change_status_incidencia($incidencia_id,$status);
                }
            }
            $this->data['data']=true;
        }
        else
            $this->data['data']=false;
        $this->load->view('backend/dataJSON', $this->data);
    }

}

?>