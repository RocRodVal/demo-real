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



    /*
     * Segun el mueble obtiene los datos de las tiendas indicadas para todas las posiciones siempre que el estado sea Alta
     */
    function generar_tabla_masiva($mueble)
    {
        $sfids = implode(",",$this->sfids);
        $sfids = trim($sfids,",");

        $result = NULL;

        if(!empty($sfids)){
            $query = $this->db->query(" SELECT pds.reference, dp.*, display.* , device.device 
                                        FROM devices_pds dp
                                        JOIN display ON display.id_display = dp.id_display
                                        JOIN device ON device.id_device=dp.id_device 
                                        JOIN pds ON pds.id_pds = dp.id_pds
                                        WHERE pds.reference IN( ".$sfids." ) AND dp.id_display=$mueble
                                        AND dp.status='Alta'
                                        ORDER BY dp.id_pds  ASC, dp.position  ASC ");

            $result = $query->result();
           //echo $this->db->last_query()."<br>";exit;

            //var_dump($result);exit;
            $sfid="";
            $anterior="";
            $resultado=[];
            $elementos=[];

            $indice=0;
            $posiciones=1;
            foreach($result as $position)
            {
                $posiciones = $position->positions;
                //$elemento=null;
            /*    print_r($position);
                echo "<br>";*/
                $sfid=$position->reference;
                //echo "ACTUAL ".$sfid." ANTERIOR ".$anterior."<br>";
                if ($indice==0){
                    $resultado[$sfid]=[];
                    $elementos=[];

                    $elemento=array("posicion"=>$position->position, "imei"=>$position->IMEI,"device"=>$position->device);
                    array_push($elementos,$elemento);
                   // $anterior = $sfid;
                } else {
                    if ($sfid == $anterior) {
                        $elemento=array("posicion"=>$position->position, "imei"=>$position->IMEI,"device"=>$position->device);
                        //  echo "ELEMENTO ";print_r($elemento);
                        array_push($elementos, $elemento);
                    } else {

                        array_push($resultado[$anterior], $elementos);

                        $resultado[$sfid] = [];
                        $elementos = [];

                        $elemento =array("posicion"=>$position->position, "imei"=>$position->IMEI,"device"=>$position->device);
                        array_push($elementos, $elemento);

                    }
                }
                $anterior = $sfid;
                $indice++;
                if ($indice==count($result)){
                    array_push($resultado[$anterior], $elementos);
                }

            }
            $resultado["posiciones"]=$posiciones;

        }
//var_dump($resultado);

        return $resultado;

    }

    /*
     * Funcion que guarda los datos indicados para la actualización masiva
     */
    function update_actualizacion_masiva($id_mueble,$sfids,$imeis,$devices,$posiciones){
        $resultado=true;
        return $resultado;
    }
}