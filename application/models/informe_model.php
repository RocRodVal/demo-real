<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 01/07/2015
 * Time: 12:01
 */



class Informe_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function get_informe_pdv($page,$cfg_pag,$campo_orden=NULL,$ordenacion=NULL,$data)
    {
        $tipo_tienda = (isset($data["tipo_tienda"]) && !empty($data["tipo_tienda"])) ? $data["tipo_tienda"] : NULL;
        $panelado = (isset($data["panelado"])  && !empty($data["panelado"])) ? $data["panelado"] : NULL;
        $mueble = (isset($data["mueble"])  && !empty($data["mueble"])) ? $data["mueble"] : NULL;
        $terminal = (isset($data["terminal"])  && !empty($data["terminal"])) ? $data["terminal"] : NULL;
        $sfid = (isset($data["sfid"])  && !empty($data["sfid"])) ? $data["sfid"] : NULL;


        if(is_null($tipo_tienda) && is_null($panelado) && is_null($mueble) && is_null($terminal) && is_null($sfid)){
            return NULL;
        }



        $sql_campos = "";
        $sql_where = "";
        $sql_join = "";
        $sql_order = " ";
        $sql_group = " GROUP BY pds.reference  ";

        if(!is_null($tipo_tienda)){
            $sql_where .= (" AND pds.type_pds ='$tipo_tienda' ");
        }
        if(!is_null($panelado)){
            $sql_where .= (" AND pds.panelado_pds ='$panelado' ");
        }
        if(!is_null($mueble)){
            $sql_where .= (" AND display.id_display ='$mueble' ");
        }
        if(!is_null($terminal)){
            $sql_where .= (" AND device.id_device ='$terminal' ");
        }
        if(!is_null($sfid)) {
            $sql_where = (" AND pds.reference LIKE '$sfid' ");
            $sql_group = "";
        }


        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($ordenacion) && !empty($ordenacion)){
            $sql_order = " ORDER BY $campo_orden $ordenacion ";
        }else {
            $sql_order = ("  ORDER BY  sfid ASC, id_type_pds ASC, id_panelado ASC, id_displays_pds ASC, position ASC $sql_order  ");
        }

        $offset = (($page-1) * $cfg_pag["per_page"]);
        $sql_limit = ("LIMIT ".$offset.",".$cfg_pag["per_page"]);


        if(is_null($sfid)) {
            $sql = "SELECT  pds.reference as sfid,
                        type_pds.pds as tipo_tienda,
                        type_pds.id_type_pds as id_type_pds,
                        panelado.panelado as panelado,
                        pds.panelado_pds as id_panelado,
                        display.display as mueble,
                        devices_pds.id_displays_pds as id_displays_pds,
                        ''  as position,
                        '' as terminal
                FROM pds

                    INNER JOIN devices_pds ON pds.id_pds = devices_pds.id_pds
                    INNER JOIN device ON devices_pds.id_device = device.id_device

                    INNER JOIN displays_pds ON pds.id_pds = displays_pds.id_pds
                    INNER JOIN display ON displays_pds.id_display = display.id_display

                    INNER JOIN panelado ON pds.panelado_pds = panelado.id_panelado
                    INNER JOIN type_pds ON pds.type_pds = type_pds.id_type_pds

                WHERE 1=1 AND devices_pds.status ='Alta'
                $sql_where
                $sql_group
                $sql_order
                $sql_limit                ";
        }else{


            $sql= "SELECT pds.reference AS sfid,
                          type_pds.pds AS tipo_tienda,
                          CONCAT(display.display,' ',displays_pds.id_displays_pds) AS mueble,
                          panelado.panelado AS panelado,
                          devices_pds.id_device AS id_terminal,
                          displays_pds.position AS position_mueble,
                          devices_pds.position AS position,
                          device.device AS terminal


                    FROM pds, display, displays_pds, type_pds, panelado, device, devices_pds

                    WHERE display.id_display = displays_pds.id_display

                    AND displays_pds.id_displays_pds IN (SELECT id_displays_pds FROM displays_pds WHERE id_pds = pds.id_pds AND id_panelado = pds.panelado_pds AND status='Alta')
                    AND device.id_device = devices_pds.id_device

                    AND devices_pds.id_pds = pds.id_pds
                    AND devices_pds.id_displays_pds = displays_pds.id_displays_pds
                    AND devices_pds.id_display = displays_pds.id_display
                    AND devices_pds.id_device = device.id_device
                    ANd devices_pds.status = 'Alta'

                    AND type_pds.id_type_pds = pds.type_pds
                    AND panelado.id_panelado = pds.panelado_pds



                $sql_where

                ORDER BY sfid ASC, tipo_tienda ASC,  panelado ASC, position_mueble ASC, mueble ASC, position ASC
                $sql_limit    ";

        }


        $query = $this->db->query($sql);

        return $query->result();
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

    public function get_informe_csv($campo_orden=NULL,$ordenacion=NULL,$data)
    {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        $tipo_tienda = (isset($data["tipo_tienda"]) && !empty($data["tipo_tienda"])) ? $data["tipo_tienda"] : NULL;
        $panelado = (isset($data["panelado"])  && !empty($data["panelado"])) ? $data["panelado"] : NULL;
        $mueble = (isset($data["mueble"])  && !empty($data["mueble"])) ? $data["mueble"] : NULL;
        $terminal = (isset($data["terminal"])  && !empty($data["terminal"])) ? $data["terminal"] : NULL;
        $sfid = (isset($data["sfid"])  && !empty($data["sfid"])) ? $data["sfid"] : NULL;



        $sTitleFilename = "Informe_PDV-";

        $sFiltrosFilename = "";
        $sFiltrosFilename .= (!is_null($tipo_tienda)) ? ($tipo_tienda."-") : "";
        $sFiltrosFilename .= (!is_null($panelado)) ? ($panelado."-") : "";
        $sFiltrosFilename .= (!is_null($mueble)) ? ($mueble."-") : "";
        $sFiltrosFilename .= (!is_null($terminal)) ? ($terminal."-") : "";
        $sFiltrosFilename .= (!is_null($sfid)) ? ($sfid."-") : "";



        if(is_null($tipo_tienda) && is_null($panelado) && is_null($mueble) && is_null($terminal) && is_null($sfid)){
            return NULL;
        }

        $sql_campos = "";
        $sql_where = "";
        $sql_join = "";
        $sql_order = " ";
        $sql_group = " GROUP BY pds.reference  ";

        if(!is_null($tipo_tienda)){
            $sql_where .= (" AND pds.type_pds ='$tipo_tienda' ");
        }
        if(!is_null($panelado)){
            $sql_where .= (" AND pds.panelado_pds ='$panelado' ");
        }
        if(!is_null($mueble)){
            $sql_where .= (" AND display.id_display ='$mueble' ");
        }
        if(!is_null($terminal)){
            $sql_where .= (" AND device.id_device ='$terminal' ");
        }
        if(!is_null($sfid)) {
            $sql_where = (" AND pds.reference LIKE '$sfid' ");
            $sql_group = "";
        }


        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($ordenacion) && !empty($ordenacion)){
            $sql_order = " ORDER BY $campo_orden $ordenacion ";
        }else {
            $sql_order = ("  ORDER BY  sfid ASC, id_type_pds ASC, id_panelado ASC, id_displays_pds ASC, position ASC $sql_order  ");
        }




        if(is_null($sfid)) {
            $sql = "SELECT  pds.reference as sfid,
                        type_pds.pds as tipo_tienda,
                        type_pds.id_type_pds as id_type_pds,
                        panelado.panelado as panelado,
                        pds.panelado_pds as id_panelado,
                        display.display as mueble,
                        devices_pds.id_displays_pds as id_displays_pds,
                        '' as position,
                       '' as terminal
                FROM pds

                    INNER JOIN devices_pds ON pds.id_pds = devices_pds.id_pds
                    INNER JOIN device ON devices_pds.id_device = device.id_device

                    INNER JOIN displays_pds ON pds.id_pds = displays_pds.id_pds
                    INNER JOIN display ON displays_pds.id_display = display.id_display

                    INNER JOIN panelado ON pds.panelado_pds = panelado.id_panelado
                    INNER JOIN type_pds ON pds.type_pds = type_pds.id_type_pds

                WHERE 1=1 AND devices_pds.status ='Alta'
                $sql_where
                $sql_group
                $sql_order
                                ";
        }else{


            $sql= "SELECT pds.reference AS sfid,
                          type_pds.pds AS tipo_tienda,
                          CONCAT(display.display,' ',displays_pds.id_displays_pds) AS mueble,
                          panelado.panelado AS panelado,
                          devices_pds.id_device AS id_terminal,
                          displays_pds.position AS position_mueble,
                          devices_pds.position AS position,
                          device.device AS terminal


                    FROM pds, display, displays_pds, type_pds, panelado, device, devices_pds

                    WHERE display.id_display = displays_pds.id_display

                    AND displays_pds.id_displays_pds IN (SELECT id_displays_pds FROM displays_pds WHERE id_pds = pds.id_pds AND id_panelado = pds.panelado_pds AND status='Alta')
                    AND device.id_device = devices_pds.id_device

                    AND devices_pds.id_pds = pds.id_pds
                    AND devices_pds.id_displays_pds = displays_pds.id_displays_pds
                    AND devices_pds.id_display = displays_pds.id_display
                    AND devices_pds.id_device = device.id_device
                    ANd devices_pds.status = 'Alta'

                    AND type_pds.id_type_pds = pds.type_pds
                    AND panelado.id_panelado = pds.panelado_pds



                $sql_where

                ORDER BY sfid ASC, tipo_tienda ASC,  panelado ASC, position_mueble ASC, mueble ASC, position ASC
                ";

        }


        $query = $this->db->query($sql);


        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download('Demo_Real-'.$sTitleFilename.$sFiltrosFilename.date("d-m-Y").'T'.date("H:i:s").'.csv', $data);


    }





    public function get_informe_pdv_quantity($data)
    {
        $tipo_tienda = (isset($data["tipo_tienda"]) && !empty($data["tipo_tienda"])) ? $data["tipo_tienda"] : NULL;
        $panelado = (isset($data["panelado"])  && !empty($data["panelado"])) ? $data["panelado"] : NULL;
        $mueble = (isset($data["mueble"])  && !empty($data["mueble"])) ? $data["mueble"] : NULL;
        $terminal = (isset($data["terminal"])  && !empty($data["terminal"])) ? $data["terminal"] : NULL;
        $sfid = (isset($data["sfid"])  && !empty($data["sfid"])) ? $data["sfid"] : NULL;


        if(is_null($tipo_tienda) && is_null($panelado) && is_null($mueble) && is_null($terminal) && is_null($sfid)){
            return NULL;
        }



        $sql_campos = "";
        $sql_where = "";
        $sql_join = "";
        $sql_order = " ";
        $sql_group = "  GROUP BY sfid ";

        $sql_where = "";
        if(!is_null($tipo_tienda)){
            $sql_where .= (" AND pds.type_pds ='$tipo_tienda' ");
        }
        if(!is_null($panelado)){
            $sql_where .= (" AND pds.panelado_pds ='$panelado' ");
        }
        if(!is_null($mueble)){
            $sql_where .= (" AND display.id_display ='$mueble' ");
        }
        if(!is_null($terminal)){
            $sql_where .= (" AND device.id_device ='$terminal' ");
        }
        if(!is_null($sfid)) {
            $sql_where = (" AND pds.reference LIKE '$sfid' ");
            $sql_group = "";
        }



        if(is_null($sfid)) {
            $sql = "SELECT  pds.reference as sfid,
                        type_pds.pds as tipo_tienda,
                        type_pds.id_type_pds as id_type_pds,
                        panelado.panelado as panelado,
                        pds.panelado_pds as id_panelado,
                        display.display as mueble,
                        devices_pds.id_displays_pds as id_displays_pds,
                        devices_pds.position  as position,
                        device.device as terminal
                FROM pds

                    INNER JOIN devices_pds ON pds.id_pds = devices_pds.id_pds
                    INNER JOIN device ON devices_pds.id_device = device.id_device

                    INNER JOIN displays_pds ON pds.id_pds = displays_pds.id_pds
                    INNER JOIN display ON displays_pds.id_display = display.id_display

                    INNER JOIN panelado ON pds.panelado_pds = panelado.id_panelado
                    INNER JOIN type_pds ON pds.type_pds = type_pds.id_type_pds

                WHERE 1=1 AND devices_pds.status ='Alta'
                $sql_where
                $sql_group
                $sql_order
                                ";
        }else{


            $sql= "SELECT pds.reference AS sfid,
                          type_pds.pds AS tipo_tienda,
                          CONCAT(display.display,' ',displays_pds.id_displays_pds) AS mueble,
                          panelado.panelado AS panelado,
                          devices_pds.id_device AS id_terminal,
                          displays_pds.position AS position_mueble,
                          devices_pds.position AS position,
                          device.device AS terminal


                    FROM pds, display, displays_pds, type_pds, panelado, device, devices_pds

                    WHERE display.id_display = displays_pds.id_display

                    AND displays_pds.id_displays_pds IN (SELECT id_displays_pds FROM displays_pds WHERE id_pds = pds.id_pds AND id_panelado = pds.panelado_pds AND status='Alta')
                    AND device.id_device = devices_pds.id_device

                    AND devices_pds.id_pds = pds.id_pds
                    AND devices_pds.id_displays_pds = displays_pds.id_displays_pds
                    AND devices_pds.id_display = displays_pds.id_display
                    AND devices_pds.id_device = device.id_device
                    ANd devices_pds.status = 'Alta'

                    AND type_pds.id_type_pds = pds.type_pds
                    AND panelado.id_panelado = pds.panelado_pds



                $sql_where


                ";

        }



        $query = $this->db->query($sql);
        return count($query->result());
    }

}