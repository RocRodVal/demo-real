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

        $sql = "SELECT pds.reference as sfid,
                       type_pds.pds as tipo_tienda,
                       type_pds.id_type_pds as id_type_pds,
                       panelado.panelado as panelado,
                       panelado.id_panelado as id_panelado,
                       devices_pds.id_displays_pds as id_displays_pds,
                       display.display as mueble,
                       device.device as terminal,
                       devices_pds.position as position
                FROM pds
                LEFT JOIN type_pds 		ON pds.type_pds 			= type_pds.id_type_pds
                LEFT JOIN panelado 		ON panelado.id_panelado 	= pds.panelado_pds
                LEFT JOIN devices_pds 	ON devices_pds.id_pds 		= pds.id_pds
                LEFT JOIN display 		ON devices_pds.id_display 	= display.id_display
                LEFT JOIN device 		ON devices_pds.id_device 	= device.id_device
                LEFT JOIN displays_panelado ON devices_pds.id_display = displays_panelado.id_display
                WHERE 1=1 AND devices_pds.status = 'Alta'
                ";

        if(!is_null($tipo_tienda)) $sql .= (" AND pds.type_pds ='$tipo_tienda' ");
        if(!is_null($panelado)) $sql .= (" AND displays_panelado.id_panelado ='$panelado' ");
        if(!is_null($mueble)) $sql .= (" AND display.id_display ='$mueble' ");
        if(!is_null($terminal)) $sql .= (" AND device.id_device ='$terminal' ");
        if(!is_null($sfid)) $sql .= (" AND pds.reference LIKE '$sfid' ");

        if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($ordenacion) && !empty($ordenacion)){
            $sql .= " ORDER BY $campo_orden $ordenacion ";
        }else {
            $sql .= (" ORDER BY sfid ASC, id_type_pds ASC, id_panelado ASC, id_displays_pds ASC, position ASC ");
        }

            $offset = (($page-1) * $cfg_pag["per_page"]);
            $sql .= ("LIMIT ".$offset.",".$cfg_pag["per_page"]);


        $query = $this->db->query($sql);

        return $query->result();
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

        $sql = "SELECT pds.reference as sfid,
                       type_pds.pds as tipo_tienda,
                       type_pds.id_type_pds as id_type_pds,
                       panelado.panelado as panelado,
                       panelado.id_panelado as id_panelado,
                       devices_pds.id_displays_pds as id_displays_pds,
                       display.display as mueble,
                       device.device as terminal,
                       devices_pds.position as position
                FROM pds
                LEFT JOIN type_pds 		ON pds.type_pds 			= type_pds.id_type_pds
                LEFT JOIN panelado 		ON panelado.id_panelado 	= pds.panelado_pds
                LEFT JOIN devices_pds 	ON devices_pds.id_pds 		= pds.id_pds
                LEFT JOIN display 		ON devices_pds.id_display 	= display.id_display
                LEFT JOIN device 		ON devices_pds.id_device 	= device.id_device
                LEFT JOIN displays_panelado ON devices_pds.id_display = displays_panelado.id_display
                WHERE 1=1 AND devices_pds.status = 'Alta'
                ";

        if(!is_null($tipo_tienda)) $sql .= (" AND pds.type_pds ='$tipo_tienda' ");
        if(!is_null($panelado)) $sql .= (" AND displays_panelado.id_panelado ='$panelado' ");
        if(!is_null($mueble)) $sql .= (" AND display.id_display ='$mueble' ");
        if(!is_null($terminal)) $sql .= (" AND device.id_device ='$terminal' ");
        if(!is_null($sfid)) $sql .= (" AND pds.reference LIKE '$sfid' ");

        /*if(!is_null($campo_orden) && !empty($campo_orden) && !is_null($ordenacion) && !empty($ordenacion)){
            $sql .= " ORDER BY $campo_orden $ordenacion ";
        }else {*/
            $sql .= (" ORDER BY sfid ASC, id_type_pds ASC, id_panelado ASC, id_displays_pds ASC, position ASC ");
        //}


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


        $sql = "SELECT pds.reference as sfid,
                       type_pds.pds as tipo_tienda,
                       type_pds.id_type_pds as id_type_pds,
                       panelado.panelado as panelado,
                       panelado.id_panelado as id_panelado,
                       devices_pds.id_displays_pds as id_displays_pds,
                       display.display as mueble,
                       device.device as terminal,
                       devices_pds.position as position
                FROM pds
                LEFT JOIN type_pds 		ON pds.type_pds 			= type_pds.id_type_pds
                LEFT JOIN panelado 		ON panelado.id_panelado 	= pds.panelado_pds
                LEFT JOIN devices_pds 	ON devices_pds.id_pds 		= pds.id_pds
                LEFT JOIN display 		ON devices_pds.id_display 	= display.id_display
                LEFT JOIN device 		ON devices_pds.id_device 	= device.id_device
                LEFT JOIN displays_panelado ON devices_pds.id_display = displays_panelado.id_display
                WHERE 1=1 AND devices_pds.status = 'Alta'
                ";

        if(!is_null($tipo_tienda)) $sql .= (" AND pds.type_pds ='$tipo_tienda' ");
        if(!is_null($panelado)) $sql .= (" AND displays_panelado.id_panelado ='$panelado' ");
        if(!is_null($mueble)) $sql .= (" AND display.id_display ='$mueble' ");
        if(!is_null($terminal)) $sql .= (" AND device.id_device ='$terminal' ");
        if(!is_null($sfid)) $sql .= (" AND pds.reference LIKE '$sfid' ");



        $query = $this->db->query($sql);



        return count($query->result());
    }

}