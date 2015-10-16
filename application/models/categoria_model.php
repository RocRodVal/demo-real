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
     * Devuelve un array de TIPOS de PDS
     * @return mixed
     */
    /*public function get_tipos_pds()
    {
        $query = $this->db->select('*')->get('pds_tipo');
        return $query->result_array();
    }*/

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
            $query = $this->db->where(' id_tipo = "'.$id_tipo.'" ');
            $unico = true;
        }
        $query = $this->db->get('pds_tipo');

        return (($unico) ? $query->array_row() : $query->result_array());
    }
    /**
     * Devuelve un array de SUBTIPOS de PDS.
     * Si se le pasa un TIPO, devuelve los SUBTIPOS que le pertenecen.  Y si no, devuelve todos los SUBTIPOS existentes.
     * @param $array_tipos
     * @return mixed
     */
    public function get_subtipos_pds($id_tipo=NULL)
    {
        $query = $this->db->select('*');
        if(!empty($id_tipo))
        {
            $query = $this->db->where('id_tipo',$id_tipo);
        }
        $query = $this->db->get('pds_subtipo');

        return $query->result_array();
    }


    /**
     * Devuelve un array de SEGMENTOS de PDS
     * @return mixed
     */
    public function get_segmentos_pds($id_segmento=NULL)
    {
        $query = $this->db->select('*')->get('pds_segmento');
        return $query->result_array();
    }
    /**
     * Devuelve un array de TIPOLOGIAS de PDS
     * @return mixed
     */
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