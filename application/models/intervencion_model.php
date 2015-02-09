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

    public function get_intervenciones(){
        $this->load->model('VO/ContactVO');
        $this->load->model('VO/PdsVO');
        $this->db->select('intervenciones.*,
                contact.id_contact, contact.contact,
                contact.email, contact.phone,
                pds.id_pds, pds.reference, pds.address');
        $this->db->from('intervenciones');
        $this->db->join('contact','intervenciones.id_operador=contact.id_contact');
        $this->db->join('pds','intervenciones.id_pds = pds.id_pds');
        $query=$this->db->get();
        $intervenciones = array();
        foreach($query->result_array() as $row ) {
            $i = new IntervencionVO();
            $i->__set('id_intervencion', $row['id_intervencion']);
            $i->__set('description', $row['description']);
            $i->__set('fecha', $row['fecha']);
            $i->__set('status', $row['status']);
            $c = new ContactVO();
            $c->__set('id_contact',$row['id_contact']);
            $c->__set('email',$row['email']);
            $c->__set('contact',$row['contact']);
            $c->__set('phone',$row['phone']);
            $i->__set('operador',$c);
            $pds = new PdsVO();
            $pds->__set('id_pds',$row['id_pds']);
            $pds->__set('reference',$row['reference']);
            $pds->__set('address',$row['address']);
            $i->__set('pds',$pds);

            $intervenciones[] = $i;
        }
        return $intervenciones;

    }

    public function get_info_intervencion($intervencion_id){
        $this->load->model('VO/ContactVO');
        $this->load->model('VO/PdsVO');
        $this->db->select('*');
        $this->db->from('intervenciones');
        $this->db->join('contact','intervenciones.id_operador=contact.id_contact');
        $this->db->join('pds','intervenciones.id_pds = pds.id_pds');
        $this->db->where('id_intervencion',$intervencion_id);
        $query = $this->db->get();
        $intervencion = new IntervencionVO();
        if($query->num_rows > 0) {
            $row=$query->row();
            $intervencion->__set('id_intervencion', $row->id_intervencion);
            $intervencion->__set('description', $row->description);
            $intervencion->__set('fecha', $row->fecha);
            $intervencion->__set('status', $row->status);
            $intervencion->__set('incidencias',$this->get_incidencias_by_intervencion($intervencion));
            $c = new ContactVO();
            $c->__set('id_contact',$row->id_contact);
            $c->__set('email',$row->email);
            $c->__set('contact',$row->contact);
            $c->__set('phone',$row->phone);
            $intervencion->__set('operador',$c);
            $pds = new PdsVO();
            $pds->__set('id_pds',$row->id_pds);
            $pds->__set('reference',$row->reference);
            $pds->__set('address',$row->address);

            $intervencion->__set('pds',$pds);
        }
        return $intervencion;

    }

    public function get_incidencias_by_intervencion($intervencion){
        $this->load->model('VO/IncidenciaVO');
        $this->db->select('incidencias.*,pds.id_pds, pds.reference, pds.address');
        $this->db->from('intervenciones_incidencias');
        $this->db->join('incidencias','incidencias.id_incidencia=intervenciones_incidencias.id_incidencia');
        $this->db->join('pds','incidencias.id_pds = pds.id_pds');
        $this->db->where('id_intervencion',$intervencion->id_intervencion);
        $query=$this->db->get();
        $incidencias = array();
        foreach($query->result_array() as $row){
            $i = new IncidenciaVO();
            $i->__set('id_incidencia', $row['id_incidencia']);
            $i->__set('description', $row['description']);
            $i->__set('fecha', $row['fecha']);
            $i->__set('denuncia', $row['denuncia']);
            $i->__set('status', $row['status']);
            $i->__set('foto_url', $row['foto_url']);
            $pds = new PdsVO();
            $pds->__set('id_pds',$row['id_pds']);
            $pds->__set('reference',$row['reference']);
            $pds->__set('address',$row['address']);
            $i->__set('pds',$pds);
            $incidencias[] = $i;
        }
        return $incidencias;
    }
}