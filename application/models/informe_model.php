<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 01/07/2015
 * Time: 12:01
 */



class Informe_model extends CI_Model
{
    public $num_registros = 0;

    /**
     * @return int
     */
    public function getNumRegistros()
    {
        return $this->num_registros;
    }

    /**
     * @param int $num_registros
     */
    public function setNumRegistros($num_registros)
    {
        $this->num_registros = $num_registros;
    }

    public function __construct()
    {
        $this->load->database();
    }







    public function tabla_temporal($data,$limit=NULL)
    {



    }

    public function get_sql_informe_pdv_OLD($data,$limit=NULL)
    {
        $tipo_tienda = (isset($data["tipo_tienda"]) && !empty($data["tipo_tienda"])) ? $data["tipo_tienda"] : NULL;
        $panelado = (isset($data["panelado"])  && !empty($data["panelado"])) ? $data["panelado"] : NULL;
        $territory = (isset($data["territory"])  && !empty($data["territory"])) ? $data["territory"] : NULL;

        $id_display = (isset($data["id_display"])  && !empty($data["id_display"])) ? $data["id_display"] : NULL;

        $id_device = (isset($data["id_device"])  && !empty($data["id_device"])) ? $data["id_device"] : NULL;
        $brand_device = (isset($data["brand_device"])  && !empty($data["brand_device"])) ? $data["brand_device"] : NULL;

        if(is_null($tipo_tienda) && is_null($panelado) && is_null($id_display) && is_null($id_device) && is_null($territory) && is_null($brand_device))
        {
            return NULL;
        }

        /*$this->db->select("pds.id_pds as id_pds, pds.reference as reference, panelado.panelado_abx as panelado_abx, type_pds.pds as tipo_pds,
                        pds.territory as territory, territory.territory as territorio, pds.panelado_pds as panelado_pds, commercial, pds.type_via as type_via, type_via.via as tipo_via,
                        address, zip, city")*/

        $aQuery = array();

        $aQuery["fields"] = array(
                    "pds.reference as reference",
                    "type_pds.pds as tipo_pds",
                    "panelado.panelado_abx as panelado_abx",
                    "pds.territory as territory",
                    "territory.territory as territorio",
                    "pds.panelado_pds as panelado_pds",
                    "commercial",
                    "pds.type_via as type_via",
                    "type_via.via as tipo_via",
                    "address", "zip", "city");

        $aQuery["table"] = " pds ";
        $aQuery["joins"] = array();

        //$this->db->select($sCampos);

        $aQuery["joins"]["type_pds"] = "type_pds.id_type_pds = pds.type_pds";
        $aQuery["joins"]["panelado"] = "panelado.id_panelado=pds.panelado_pds";
        $aQuery["joins"]["territory"] = "territory.id_territory=pds.territory";
        $aQuery["joins"]["type_via"] = "type_via.id_type_via = pds.type_via";


        if(!is_null($id_display)){
            $aQuery["joins"]["displays_pds"]  = "displays_pds.id_pds = pds.id_pds";
            $aQuery["joins"]["display"]  = "display.id_display = displays_pds.id_display";
        }

        if(!is_null($id_device) || !is_null($brand_device)){
            $aQuery["joins"]["devices_pds"]  = "devices_pds.id_pds=pds.id_pds";
            $aQuery["joins"]["device"]  = "device.id_device=devices_pds.id_device";
        }


        if(!is_null($tipo_tienda))  $aQuery["where_in"]["pds.type_pds"] = $tipo_tienda;
        if(!is_null($panelado))     $aQuery["where_in"]["pds.panelado_pds"] = $panelado;
        if(!is_null($id_display))   $aQuery["where_in"]["displays_pds.id_display"] = $id_display;
        if(!is_null($id_device))    $aQuery["where_in"]["devices_pds.id_device"] = $id_device;
        if(!is_null($territory))    $aQuery["where_in"]["pds.territory"] = $territory;
        if(!is_null($brand_device)) $aQuery["where_in"]["device.brand_device"] = $brand_device;


        $aQuery["order_by"]["type_pds.pds"] = "asc";
        $aQuery["order_by"]["panelado.panelado_abx"] = "asc";
        $aQuery["order_by"]["territory.territory"] = "asc";
        $aQuery["order_by"]["pds.reference"] = "asc";

        $aQuery["group_by"] = "reference";




       if(!is_null($limit))
        {
            $aQuery["limit"] = array("ini"=> $limit["ini"], "offset"=>$limit["offset"]);
        }



        return $aQuery;
    }




    public function get_sql_informe_pdv($data,$limit=NULL)
    {
        $id_tipo = (isset($data["id_tipo"]) && !empty($data["id_tipo"])) ? $data["id_tipo"] : NULL;
        $id_subtipo = (isset($data["id_subtipo"]) && !empty($data["id_subtipo"])) ? $data["id_subtipo"] : NULL;
        $id_segmento = (isset($data["id_segmento"]) && !empty($data["id_segmento"])) ? $data["id_segmento"] : NULL;
        $id_tipologia = (isset($data["id_tipologia"]) && !empty($data["id_tipologia"])) ? $data["id_tipologia"] : NULL;


        $territory = (isset($data["territory"])  && !empty($data["territory"])) ? $data["territory"] : NULL;

        $id_display = (isset($data["id_display"])  && !empty($data["id_display"])) ? $data["id_display"] : NULL;

        $id_device = (isset($data["id_device"])  && !empty($data["id_device"])) ? $data["id_device"] : NULL;
        $brand_device = (isset($data["brand_device"])  && !empty($data["brand_device"])) ? $data["brand_device"] : NULL;

        if(is_null($id_tipo) && is_null($id_subtipo) && is_null($id_segmento) && is_null($id_tipologia)
            && is_null($id_display) && is_null($id_device) && is_null($territory) && is_null($brand_device))
        {
            return NULL;
        }

        /*$this->db->select("pds.id_pds as id_pds, pds.reference as reference, panelado.panelado_abx as panelado_abx, type_pds.pds as tipo_pds,
                        pds.territory as territory, territory.territory as territorio, pds.panelado_pds as panelado_pds, commercial, pds.type_via as type_via, type_via.via as tipo_via,
                        address, zip, city")*/

        $aQuery = array();

        $aQuery["fields"] = array(
            "pds.reference as reference",

            "pds_tipo.titulo as tipo",
            "pds_subtipo.titulo as subtipo",
            "pds_segmento.titulo as segmento",
            "pds_tipologia.titulo as tipologia",

            "pds.territory as territory",
            "territory.territory as territorio",

            "commercial",
            "pds.type_via as type_via",
            "type_via.via as tipo_via",
            "address", "zip", "city","province.province as provincia");

        $aQuery["table"] = " pds ";
        $aQuery["joins"] = array();

        //$this->db->select($sCampos);

        $aQuery["joins"]["pds_tipo"]        = "pds_tipo.id = pds.id_tipo";
        $aQuery["joins"]["pds_subtipo"]     = "pds_subtipo.id = pds.id_subtipo";
        $aQuery["joins"]["pds_segmento"]    = "pds_segmento.id = pds.id_segmento";
        $aQuery["joins"]["pds_tipologia"]   = "pds_tipologia.id = pds.id_tipologia";


        $aQuery["joins"]["territory"] = "territory.id_territory=pds.territory";
        $aQuery["joins"]["type_via"] = "type_via.id_type_via = pds.type_via";
        $aQuery["joins"]["province"] = "province.id_province = pds.province";


        if(!is_null($id_display)){
            $aQuery["joins"]["displays_pds"]  = "displays_pds.id_pds = pds.id_pds";
            $aQuery["joins"]["display"]  = "display.id_display = displays_pds.id_display";
        }

        if(!is_null($id_device) || !is_null($brand_device)){
            $aQuery["joins"]["devices_pds"]  = "devices_pds.id_pds=pds.id_pds";
            $aQuery["joins"]["device"]  = "device.id_device=devices_pds.id_device";
        }


        if(!is_null($id_tipo))      $aQuery["where_in"]["pds.id_tipo"] = $id_tipo;
        if(!is_null($id_subtipo))   $aQuery["where_in"]["pds.id_subtipo"] = $id_subtipo;
        if(!is_null($id_segmento))  $aQuery["where_in"]["pds.id_segmento"] = $id_segmento;
        if(!is_null($id_tipologia)) $aQuery["where_in"]["pds.id_tipologia"] = $id_tipologia;


        if(!is_null($id_display))   $aQuery["where_in"]["displays_pds.id_display"] = $id_display;
        if(!is_null($id_device))    $aQuery["where_in"]["devices_pds.id_device"] = $id_device;
        if(!is_null($territory))    $aQuery["where_in"]["pds.territory"] = $territory;
        if(!is_null($brand_device)) $aQuery["where_in"]["device.brand_device"] = $brand_device;


        $aQuery["order_by"]["pds_tipo.titulo"] = "asc";
        $aQuery["order_by"]["pds_subtipo.titulo"] = "asc";
        $aQuery["order_by"]["pds_segmento.titulo"] = "asc";
        $aQuery["order_by"]["pds_tipologia.titulo"] = "asc";

        $aQuery["order_by"]["territory.territory"] = "asc";
        $aQuery["order_by"]["pds.reference"] = "asc";

        $aQuery["group_by"] = "reference";




        if(!is_null($limit))
        {
            $aQuery["limit"] = array("ini"=> $limit["ini"], "offset"=>$limit["offset"]);
        }



        return $aQuery;
    }


    public function get_informe_pdv_OLD($data,$ci_pagination=NULL)
    {
        $limit = NULL;
        if(count($data) > $ci_pagination["per_page"])
        {
            $limit["ini"] =  $ci_pagination["n_inicial"];
            $limit["offset"] = $ci_pagination["n_final"];
        }
        $aQuery = $this->get_sql_informe_pdv($data,$limit);
        if(empty($aQuery)) return NULL;

        $query = $this->get_active_record_result($aQuery);

        return $query;
    }


    public function get_informe_pdv($data,$ci_pagination=NULL)
    {
        $limit = NULL;
        if(count($data) > $ci_pagination["per_page"])
        {
            $limit["ini"] =  $ci_pagination["n_inicial"];
            $limit["offset"] = $ci_pagination["n_final"];
        }
        $aQuery = $this->get_sql_informe_pdv($data,$limit);
        if(empty($aQuery)) return NULL;

        $query = $this->get_active_record_result($aQuery);

        return $query;
    }



    public function get_informe_pdv_quantity_OLD($data)
    {
        $query = $this->get_sql_informe_pdv($data);
        if($query === NULL) return 0;
        return count($query->result());
    }


    public function get_informe_pdv_quantity($data)
    {
        $query = $this->get_sql_informe_pdv($data);
        if($query === NULL) return 0;
        return count($query->result());
    }

    public function panelado_tienda($tipo, $id_panelado=NULL)
    {
        /* Incluir los modelos */
        $xcrud = xcrud_get_instance();
        $this->load->model('sfid_model');
        $this->load->model('tienda_model');
        $this->load->model('tienda_model');

        $panelados = $this->tienda_model->get_panelados_maestros_demoreal($tipo);

        $resp = '<option value="" selected="selected">Escoge el panelado...</option>';
        foreach($panelados as $panel){

            $s_selected = (!is_null($id_panelado) && $id_panelado == $panel->id_panelado) ? ' selected="selected" ' : '';
            $resp .= '<option value="'.$panel->id_panelado.'" '.$s_selected.'>'.$panel->panelado.'</option>';
        }
        echo $resp;
    }




    public function exportar_informe_pdv($data,$formato="csv")
    {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('csv');
        $this->load->helper('download');



        // Array de títulos y exclusiones de campo para la exportación XLS/CSV
        $arr_titulos = array('SFID','Tipo','Panelado','Territorio','Nombre','Tipo Vía','Dirección','CP','Localidad','Provincia');
        $excluir = array('territory','panelado_pds','type_via','');


        $aQuery = $this->get_sql_informe_pdv($data);


        $limit = NULL;
        if(empty($aQuery)) return NULL;

        $query = $this->get_sql_result($aQuery);



        $datos = preparar_array_exportar($query->result(),$arr_titulos,$excluir);

        exportar_fichero($formato,$datos,'Informe_PDV-'.date("d-m-Y").'T'.date("H:i:s"));


    }


    /**
     * Genera una consulta con Active Record en base a un array pasado como parametro, formado por las subpartes de una
     * consulta.
     * @param $aQuery
     *
     */
    function get_active_record_result($aQuery)
    {
        $xcrud = xcrud_get_instance();


        $sCampos = implode(",",$aQuery["fields"]);
        $this->db->select($sCampos);

        if(isset($aQuery["joins"]) && !empty($aQuery["joins"]))         foreach($aQuery["joins"] as $tabla_sec=>$on)        $this->db->join($tabla_sec,$on);
        if(isset($aQuery["where_in"]) && !empty($aQuery["where_in"]))   foreach($aQuery["where_in"] as $campo=>$valores)    $this->db->where_in($campo,$valores);
        if(isset($aQuery["order_by"]) && !empty($aQuery["order_by"]))   foreach($aQuery["order_by"] as $campo=>$orden)      $this->db->order_by($campo,$orden);

        if(isset($aQuery["group_by"]) && !empty($aQuery["group_by"]))   $this->db->group_by($aQuery["group_by"]);

        if(isset($aQuery["limit"])){
            $limites = $aQuery["limit"];
            $ini = (empty($limites["ini"])) ? NULL : $limites["ini"];
            $offset = (empty($limites["offset"])) ? NULL : $limites["offset"];
            $query = $this->db->get($aQuery["table"],$ini,$offset);
        }else{
            $query = $this->db->get($aQuery["table"]);
        }


        return $query->result();

    }



    /**
     * Genera una consulta con Active Record en base a un array pasado como parametro, formado por las subpartes de una
     * consulta.
     * @param $aQuery
     *
     */
    function get_sql_result($aQuery)
    {
        $xcrud = xcrud_get_instance();

        $sSQL = "SELECT ".implode(",",$aQuery["fields"]);
        $sSQL .= (" FROM ".$aQuery["table"]);

        if(isset($aQuery["joins"]) && !empty($aQuery["joins"]))         foreach($aQuery["joins"] as $tabla_sec=>$on)        $sSQL .= (" JOIN ".$tabla_sec." ON ".$on);

        $sSQL .= " WHERE 1=1 ";

        if(isset($aQuery["where_in"]) && !empty($aQuery["where_in"]))   foreach($aQuery["where_in"] as $campo=>$valores)    $sSQL .= (" AND ".$campo .  " IN (". implode(",",$valores).") ");

        if(isset($aQuery["group_by"]) && !empty($aQuery["group_by"]))   $sSQL  .= (" GROUP BY ".$aQuery["group_by"]);

        if(isset($aQuery["order_by"]) && !empty($aQuery["order_by"]))
        {
            $sSQL .= " ORDER BY ";
            foreach($aQuery["order_by"] as $campo=>$orden) $sSQL .= ($campo . " ".$orden.",");

            $sSQL = trim($sSQL,",");
        }


        if(isset($aQuery["limit"])){
            $limites = $aQuery["limit"];
            $ini = (empty($limites["ini"])) ? NULL : $limites["ini"];
            $offset = (empty($limites["offset"])) ? NULL : $limites["offset"];

            if(!is_null($ini) && !is_null($offset)){
                $sSQL .= ("LIMIT ".$ini.",".$offset);
            }
        }

        $query = $this->db->query($sSQL);
        return $query;

    }





}