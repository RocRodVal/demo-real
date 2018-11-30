<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 20/10/2015
 * Time: 11:26
 */


class Backup_Model extends CI_Model {

    private $sfids = array();

    function __construct()
    {
        $this->load->database();
        $this->load->model("incidencia_model");
        $this->load->model("intervencion_model");


    }

    function set_sfids($array_sfids)
    {
        $this->sfids = $array_sfids;
    }

    function exportar_planograma()
    {
        $sfids = implode(",",$this->sfids);
        $sfids = trim($sfids,",");

        $result = NULL;

        if(!empty($sfids)){
            $query = $this->db->query(" SELECT pds.reference, pd.*, display.*  FROM displays_pds pd
                                        JOIN display ON display.id_display = pd.id_display
                                        JOIN pds ON pds.id_pds = pd.id_pds
                                        WHERE pds.reference IN( ".$sfids." )
                                        ORDER BY pd.id_pds  ASC, pd.position  ASC ");

            $result = $query->result();


            foreach($result as $clave=>$mueble)
            {
                $id_displays_pds = $mueble->id_displays_pds;
                $query_devices = $this->db->query(" SELECT dp.id_devices_pds, dp.client_type_pds, dp.id_pds, dp.id_displays_pds, dp.id_display, display.display,
                                                    dp.alta, dp.position, dp.id_device, device.device, dp.IMEI, dp.mac, dp.serial, dp.owner, dp.status
                                        FROM devices_pds dp
                                        JOIN display ON display.id_display = dp.id_display
                                        JOIN device ON device.id_device = dp.id_device

                                        WHERE dp.id_displays_pds = ".$id_displays_pds."
                                        ORDER BY dp.id_display  ASC, dp.position  ASC ");

                    $mueble->devices = $query_devices->result();

            }
        }


        return $result;

    }


    function exportar_planogramas()
    {
        $sfids = implode(",",$this->sfids);
        $sfids = trim($sfids,",");

        $result = NULL;

        if(!empty($sfids)){
            $query = $this->db->query("
                SELECT pds.reference, dp.id_devices_pds, dp.client_type_pds, pd.position, pds.id_pds, dp.id_displays_pds, dp.id_display, display.display,
                           dp.alta, dp.position as position_device, dp.id_device, device.device, dp.IMEI, dp.mac, dp.serial, dp.owner, dp.status
                           FROM devices_pds dp
                           JOIN displays_pds pd ON pd.id_displays_pds = dp.id_displays_pds
                           JOIN display ON display.id_display = dp.id_display
                           JOIN device ON device.id_device = dp.id_device
                           JOIN pds ON pds.id_pds = dp.id_pds
                           WHERE pds.reference IN( ".$sfids." )
                           ORDER BY pds.reference ASC, pd.position ASC, dp.position  ASC
                ");

            $result = $query->result();
        }


        return $result;

    }

    function get_id_incidencia($id_devices_pds,$estado)
    {
        $result = NULL;

        if(!empty($id_devices_pds)){
            switch ($estado){
                case 'Incidencia':
                    $sql="SELECT id_incidencia FROM incidencias where id_devices_pds = ".$id_devices_pds." and status_pds in
                    ('En proceso','En visita','Alta realizada')";
                    break;
                case 'RMA':
                    $sql="SELECT id_incidencia FROM incidencias where id_devices_pds = ".$id_devices_pds." and status in
                    ('Sustituido','Resuelta','Pendiente recogida')";
                    break;

            }
            $query = $this->db->query($sql);
            $result = $query->result();
        }


        return $result;

    }



}