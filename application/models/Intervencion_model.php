<?php

/**
 * Created by PhpStorm.
 * User: dani
 * Date: 9/2/15
 * Time: 17:53
 */
class Intervencion_model extends MY_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->model('VO/IntervencionVO');
    }

    public function get_intervenciones()
    {
        $this->load->model('VO/ContactVO');
        $this->load->model('VO/PdsVO');
        $this->db->select('intervenciones.*,
                contact.id_contact, contact.contact,
                contact.email, contact.phone,
                pds.id_pds, pds.reference, pds.commercial');
        $this->db->from('intervenciones');
        $this->db->join('contact', 'intervenciones.id_operador=contact.id_contact');
        $this->db->join('pds', 'intervenciones.id_pds = pds.id_pds');

        $query = $this->db->get();
        $intervenciones = array();
        foreach ($query->result_array() as $row) {
            $i = new IntervencionVO();
            $i->__set('id_intervencion', $row['id_intervencion']);
            $i->__set('description', $row['description']);
            $i->__set('fecha', $row['fecha']);
            $i->__set('status', $row['status']);
            $c = new ContactVO();
            $c->__set('id_contact', $row['id_contact']);
            $c->__set('email', $row['email']);
            $c->__set('contact', $row['contact']);
            $c->__set('phone', $row['phone']);
            $i->__set('operador', $c);
            $pds = new PdsVO();
            $pds->__set('id_pds', $row['id_pds']);
            $pds->__set('reference', $row['reference']);
            $pds->__set('commercial', $row['commercial']);
            $i->__set('pds', $pds);
            $i->__set('incidencias',$this->get_incidencias_by_intervencion($i));

            $intervenciones[] = $i;
        }
        return $intervenciones;

    }

    public function get_info_intervencion($intervencion_id)
    {
        $this->load->model('VO/ContactVO');
        $this->load->model('VO/PdsVO');
        $this->db->select('intervenciones.*,
                contact.id_contact,contact.email,contact.email_cc, contact.contact,contact.id_parte,contact.phone,
                pds.id_pds,pds.reference, pds.commercial');
        $this->db->from('intervenciones');
        $this->db->join('contact', 'intervenciones.id_operador=contact.id_contact');
        $this->db->join('pds', 'intervenciones.id_pds = pds.id_pds');
        $this->db->where('id_intervencion', $intervencion_id);
        $query = $this->db->get();
        $intervencion = new IntervencionVO();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $intervencion->__set('id_intervencion', $row->id_intervencion);
            $intervencion->__set('description', $row->description);
            $intervencion->__set('fecha', $row->fecha);
            $intervencion->__set('status', $row->status);
            $intervencion->__set('incidencias', $this->get_incidencias_by_intervencion($intervencion));
            $c = new ContactVO();
            $c->__set('id_contact', $row->id_contact);
            $c->__set('email', $row->email);
            $c->__set('email_cc', $row->email_cc);
            $c->__set('contact', $row->contact);
            $c->__set('id_parte', $row->id_parte);
            $c->__set('phone', $row->phone);
            $intervencion->__set('operador', $c);
            $pds = new PdsVO();
            $pds->__set('id_pds', $row->id_pds);
            $pds->__set('reference', $row->reference);
            $pds->__set('commercial', $row->commercial);

            $intervencion->__set('pds', $pds);
        }

        return $intervencion;

    }

    public function get_intervencion_incidencia($id_incidencia){
        $this->db->select('id_intervencion');
        $this->db->from('intervenciones_incidencias');
        $this->db->where('id_incidencia',$id_incidencia);
        $query=$this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id_intervencion;
        }
        else
            return 0;
    }

    /*Devuelve el listado de intervenciones de un PDV en el cual las incidencias no estan Finzalizadas*/
 /*   public function get_intervenciones_pds_nuevas($id_pds)
    {
        /*
         * SELECT distinct(intervenciones.id_intervencion), contact.id_contact, contact.id_parte
FROM intervenciones_incidencias
JOIN intervenciones ON intervenciones_incidencias.id_intervencion=intervenciones.id_intervencion
JOIN contact ON intervenciones.id_operador=contact.id_contact
JOIN incidencias ON incidencias.id_incidencia=intervenciones_incidencias.id_incidencia
WHERE incidencias.id_pds=2822 and (incidencias.status_pds='En proceso' OR incidencias.status_pds='En visita' )

       // $wbere="(`incidencias`.`status_pds` = 'En proceso' OR `incidencias`.`status_pds` = 'En visita') ";
        $wbere="(`incidencias`.`status_pds` != 'Finalizada') ";
        $this->load->model('VO/ContactVO');
        $this->db->select('distinct(intervenciones.id_intervencion),intervenciones.fecha,contact.id_parte,contact.id_contact');
        $this->db->from('intervenciones_incidencias');
        $this->db->join('intervenciones', 'intervenciones_incidencias.id_intervencion=intervenciones.id_intervencion');
        $this->db->join('contact', 'contact.id_contact=intervenciones.id_operador');
        $this->db->join('incidencias', 'incidencias.id_incidencia=intervenciones_incidencias.id_incidencia');
        $this->db->where($wbere);
        $this->db->where('intervenciones.id_pds', $id_pds);
        //$this->db->where('intervenciones.status', 1);
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        $intervenciones = array();
        foreach ($query->result_array() as $row) {
            $intervencion = new IntervencionVO();
            $intervencion->__set('id_intervencion', $row['id_intervencion']);
            $intervencion->__set('fecha', $row['fecha']);
            //$intervencion->__set('status', $row['status']);
            $c = new ContactVO();
            $c->__set('id_contact', $row['id_contact']);
            //$c->__set('email', $row['email']);
            //$c->__set('contact', $row['contact']);
            $c->__set('contact', $row['id_parte']);
            //$c->__set('phone', $row['phone']);
            $intervencion->__set('operador', $c);

            $intervenciones[] = $intervencion;
        }
        return $intervenciones;
    }*/

    public function get_intervenciones_pds_nuevas($id_pds)
    {
        $this->load->model('VO/ContactVO');
        $this->db->select('*');
        $this->db->from('intervenciones');
        $this->db->join('contact', 'contact.id_contact=intervenciones.id_operador');
        $this->db->where('intervenciones.id_pds', $id_pds);
        $this->db->where('intervenciones.status', 1);
        $this->db->order_by('intervenciones.fecha', 'desc');
        $query = $this->db->get();
        $intervenciones = array();
        foreach ($query->result_array() as $row) {
            $intervencion = new IntervencionVO();
            $intervencion->__set('id_intervencion', $row['id_intervencion']);
            $intervencion->__set('fecha', $row['fecha']);
            $intervencion->__set('status', $row['status']);
            $c = new ContactVO();
            $c->__set('id_contact', $row['id_contact']);
            $c->__set('email', $row['email']);
            $c->__set('contact', $row['contact']);
            $c->__set('contact', $row['id_parte']);
            $c->__set('phone', $row['phone']);
            $intervencion->__set('operador', $c);

            $intervenciones[] = $intervencion;
        }
        return $intervenciones;
    }

    public function get_incidencias_by_intervencion($intervencion)
    {
        $this->load->model('VO/IncidenciaVO');
        $this->load->model('VO/PdsVO');
        $this->db->select('incidencias.*,pds.id_pds, pds.reference, pds.commercial, display.display, device.device');
        //$this->db->select('incidencias.*,pds.id_pds, pds.reference, pds.address,device.device');
        $this->db->from('intervenciones_incidencias');
        $this->db->join('incidencias', 'incidencias.id_incidencia=intervenciones_incidencias.id_incidencia');
        $this->db->join('pds', 'incidencias.id_pds = pds.id_pds');
        $this->db->join('devices_pds', 'incidencias.id_devices_pds=devices_pds.id_devices_pds','left');
        $this->db->join('device', 'devices_pds.id_device=device.id_device','left');
        $this->db->join('displays_pds', 'displays_pds.id_displays_pds=incidencias.id_displays_pds');
        $this->db->join('display', 'displays_pds.id_display=display.id_display');
        $this->db->where('id_intervencion', $intervencion->id_intervencion);
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        $incidencias = array();
        foreach ($query->result_array() as $row) {
            $i = new IncidenciaVO();
            $i->__set('id_incidencia', $row['id_incidencia']);
            $i->__set('description', $row['description_1']);
            $i->__set('fecha', $row['fecha']);
            $i->__set('denuncia', $row['denuncia']);
            $i->__set('status', $row['status']);
            $i->__set('foto_url', $row['foto_url']);
            $i->__set('device', $row['device']);
            $i->__set('display', $row['display']);
            $pds = new PdsVO();
            $pds->__set('id_pds', $row['id_pds']);
            $pds->__set('reference', $row['reference']);
            $pds->__set('commercial', $row['commercial']);
            $i->__set('pds', $pds);
            $incidencias[] = $i;
        }
        return $incidencias;
    }

    public function get_operadores()
    {
        $this->load->model('VO/ContactVO');
        $query = $this->db->from('contact')
        ->where('type_profile_contact' , 1)->where('status' , 'Alta')
        ->order_by('id_parte','asc')->get();

        $operadores = array();
        foreach ($query->result_array() as $row) {
            $c = new ContactVO();
            $c->__set('id_contact', $row['id_contact']);
            $c->__set('contact', $row['contact']);

            $operadores[] = $c;
        }
        return $operadores;
    }

    public function check_incidencia_asignada_to_intervencion($incidencia_id)
    {
        $query = $this->db->get_where('intervenciones_incidencias', array('id_incidencia' => $incidencia_id));
        if ($query->num_rows() > 0) {
            return false;
        }
        return true;
    }

    public function add_incidencia_to_intervencion($incidencia_id, $intervencion_id)
    {

        if (!$this->check_incidencia_asignada_to_intervencion($incidencia_id)) {
            return 0;
        } else {
            $data = array(
                "id_intervencion" => $intervencion_id,
                "id_incidencia" => $incidencia_id
            );
            $this->db->insert('intervenciones_incidencias', $data);


            return true;
        }
    }

    public function create_intervencion($intervencion)
    {
        $data=array(
            "description"=>$intervencion->description,
            "id_operador"=>$intervencion->operador->id_contact,
            "status"=>$intervencion->status,
            "id_pds"=>$intervencion->pds->id_pds,
            "description"=>$intervencion->description
            );
        $this->db->insert('intervenciones', $data);
        return $this->db->insert_id();
    }

    public function change_status_incidencia($incidencia_id, $status)
    {

        // Actualizamos el campo last_updated de la incidencia
        $ahora = date("Y-m-d H:i:s");

        $data = array(
            "status" => $status,
            "last_updated"=> $ahora
        );


        $this->db->where('id_incidencia', $incidencia_id);
        $this->db->update('incidencias', $data);

        if ($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }

    public function change_status_intervencion($intervencion_id, $status)
    {
        $data = array("status" => $status);
        $this->db->where('id_intervencion', $intervencion_id);
        $this->db->update('intervenciones', $data);
        if ($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }

    public function delete_incidencias_intervencion($intervencion_id){
        $this->db->where('id_intervencion', $intervencion_id);
        return $this->db->delete('intervenciones_incidencias');
    }

    public function delete_incidencia_intervencion($incidencia_id){
        $this->db->where('id_incidencia', $incidencia_id);
        return $this->db->delete('intervenciones_incidencias');
    }

    public function get_count_incidencias_in_intervencion($intervencion_id){
        $query=$this->db->get_where('intervenciones_incidencias',array("id_intervencion"=>$intervencion_id));
        return $query->num_rows();
    }

    public function get_incidencias_material_asignado_pds_sin_intervencion($id_pds){
        $this->load->model('VO/IncidenciaVO');
        $consulta="select * from incidencias where id_pds = $id_pds and status=3 and incidencias.id_incidencia
                  NOT IN (select id_incidencia from intervenciones_incidencias)";
        $query=$this->db->query($consulta);
        $incidencias=array();
        foreach ($query->result_array() as $row) {
            $incidencia = new IncidenciaVO();
            $incidencia->__set('id_incidencia', $row['id_incidencia']);
            $incidencia->__set('fecha', $row['fecha']);
            $incidencia->__set('tipo_averia', $row['tipo_averia']);
            $incidencia->__set('description', $row['description']);

            $incidencias[] = $incidencia;
        }
        return $incidencias;

    }




    /**
     * Funci??n que crea una tabla temporal de las intervenciones entre dos fechas, y el numero de incidencias asociadas
     * y cu??ntas est??n cerradas.
     */
    function crear_temporal_estado_incidencias($fecha_ini=NULL, $fecha_fin= NULL)
    {
        $this->load->database();


        echo $this->db->last_query();

    }

}