<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 09/10/2015
 * Time: 10:12
 */

/**
 * Modelo para la nueva categorización de PDS en DemoReal
 * Class Categoria_Model
 */
class Categoria_Model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * @param $id_tipo
     * @return null
     */
    public function get_tipos_pds($id_tipo=NULL)
    {

        $query = $this->db->select('*');
        $unico = false;
        if(!empty($id_tipo) && !is_null($id_tipo))
        {
            $query = $this->db->where('id',$id_tipo);
            $unico = true;
        }
        $query = $this->db->get('pds_tipo');

        return $query->result_array();
    }
    /**
     * Devuelve un array de SUBTIPOS de PDS.
     * Si se le pasa un TIPO, devuelve los SUBTIPOS que le pertenecen.  Y si no, devuelve todos los SUBTIPOS existentes.
     * @param $array_tipos
     * @return mixed
     */
    public function get_subtipos_pds($id_tipo=NULL,$id_subtipo=NULL)
    {
        $query = $this->db->select('*');
        if(!empty($id_tipo))
        {
            $query = $this->db->where('id_tipo',$id_tipo);
        }

        if(!empty($id_subtipo))
        {
            $query = $this->db->where("id",$id_subtipo);
        }
        $query = $this->db->order_by('pds_subtipo.titulo');
        $query = $this->db->get('pds_subtipo');
        

        return $query->result_array();
    }


    /**
     * Devuelve un array de SEGMENTOS de PDS
     * @return mixed
     */
    public function get_segmentos_pds($id_segmento=NULL)
    {
        $query = $this->db->select('*')->order_by('titulo')->get('pds_segmento');

        return $query->result_array();
    }
    /**
     * Devuelve un array de TIPOLOGIAS de PDS
     * @return mixed
     */
    public function get_tipologias($id_tipologia=NULL, $id_subtipo= NULL)
    {
        $query = $this->db->select('id_subtipo, id_tipologia as id, pds_tipologia.titulo');
        if(empty($id_subtipo) && is_null($id_subtipo))
        {            
            $query =$this->db->join('pds_subtipo_tipologia','pds_subtipo_tipologia.id_tipologia = pds_tipologia.id');
        }
        else
        {
            $query = $this->db->select('id_subtipo, id_tipologia as id, pds_tipologia.titulo');
            $query = $this->db->join('pds_subtipo_tipologia','pds_subtipo_tipologia.id_tipologia = pds_tipologia.id')
                    ->where('pds_subtipo_tipologia.id_subtipo = '.$id_subtipo);
        }        
        if(!empty($id_tipologia) && !is_null($id_tipologia))
        {
            $query = $this->db->where('pds_subtipo_tipologia.id_tipologia',$id_tipologia);
        }
        $query= $this->db->distinct();
        $query = $this->db->group_by('id_tipologia');
        $query = $this->db->get('pds_tipologia');
         
        return $query->result_array();
    }
    
     public function get_tipologias_pds($id_tipologia=NULL, $id_subtipo= NULL)
    {
        if(empty($id_subtipo) && is_null($id_subtipo))
        {
            if(empty($id_subtipo) && is_null($id_subtipo))
            {
                $query = $this->db->select('*');
            }
            else
            {
                $query = $this->db->where('id= '.$id_tipologia);
            }
            $query = $this->db->order_by('pds_tipologia.titulo');
            $query = $this->db->get('pds_tipologia');
        }
        else
        {
            $query = $this->db->select('*')
                ->join('pds_subtipo_tipologia','pds_subtipo_tipologia.id_tipologia = pds_tipologia.id')
                ->where('pds_subtipo_tipologia.id_subtipo = '.$id_subtipo)
                ->get('pds_tipologia');
        }
        return $query->result_array();
    }
    

    public function get_tipologias_filtradas($id_tipo=NULL, $id_subtipo= NULL)
    {

            $query = $this->db->select('pds_tipologia.*')
                ->join('pds_subtipo_tipologia','pds_subtipo_tipologia.id_tipologia = pds_tipologia.id')
                ->join ('pds_subtipo','pds_subtipo_tipologia.id_subtipo=pds_subtipo.id')
                ->join('pds_tipo','pds_tipo.id = pds_subtipo.id_tipo');


            if(!is_null($id_subtipo)) $query = $this->db->where('pds_subtipo_tipologia.id_subtipo = '.$id_subtipo);
            if(!is_null($id_tipo))    $query = $this->db->where('pds_tipo.id = '.$id_tipo);

            $query = $this->db->get('pds_tipologia');


        return $query->result_array();
    }


    public function existe_mobiliario($id_tipo,$id_subtipo,$id_segmento,$id_tipologia)
    {
        $respuesta = FALSE;


        if(!is_null($id_tipo) && !is_null($id_subtipo) && !is_null($id_segmento) && !is_null($id_tipologia)) {
            // Sacamos los muebles
            $muebles = $this->get_displays_categoria($id_tipo, $id_subtipo, $id_segmento, $id_tipologia);
            if (!empty($muebles)) $respuesta = TRUE;
        }
        return $respuesta;
    }


    public function get_displays_categoria($id_tipo,$id_subtipo,$id_segmento,$id_tipologia)
    {
        $query = $this->db->select ('displays_categoria.*, display.*')
                ->join('display','displays_categoria.id_display = display.id_display')
                ->where('id_tipo',$id_tipo)
                ->where('id_subtipo',$id_subtipo)
                ->where('id_segmento',$id_segmento)
                ->where('id_tipologia',$id_tipologia)
                ->where('displays_categoria.status','Alta')
                ->get('displays_categoria');
        
        return $query->result();
    }

    /**
     * Insertar una categoría
     * @return mixed
     */
    public function insertar_categoria($data= NULL)
    {
        $this->db->insert('pds_categoria',$data);
        $id=$this->db->insert_id();
        return array('add' => (isset($id)) ? $id : FALSE, 'id' => $id);
    }

}