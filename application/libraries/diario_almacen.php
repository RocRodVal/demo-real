    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 24/06/2015
 * Time: 16:25
 */



    /**
     * Callback para el xcrud, previo a actualización de alarma masiva.
     */
    function historico_io_alarmas_before_update($postdata, $primary)

    {
        $id_alarm = $primary;      // Clave primaria del XCrud
        $id_client = $postdata->get("client_alarm");

        $CI =& get_instance();
        $query = $CI->db->query("SELECT units as unidades_previas FROM alarm WHERE id_alarm=$id_alarm");

        $unidades_actuales = $postdata->get('units');
        $unidades_previas = $query->row()->unidades_previas;


        $incremento = $unidades_actuales - $unidades_previas; // Valor positivo: entrada, Valor negativo: salida.

        $fecha = time();
        $sql = "INSERT INTO historico_io (id_alarm, id_client, unidades) VALUES($id_alarm,$id_client,$incremento)";
        $CI->db->query($sql);

    }

    /*
 * funcion para que inserte en el historico_io si hay un cambio de estado
 * $xcrud guarda el ID del elemento a actualziar
   $postdata tenemos los datos para actualizar el dispositivo
 */
    function inventario_dispositivos_historicoIO($postdata,  $xcrud){

        $CI =& get_instance();
        /*consultamos el estado anterior del dispositivo*/
        $result = $CI->db->select('*')
            ->where("id_devices_almacen",$xcrud)
            ->get('devices_almacen')->row_array();
        $estado_anterior=$result['status'];

        switch ($postdata->get('status')){
            case 'En stock':    $unidades=1; break;
            case "Reservado":   $unidades=-1; break;
            case "Baja":        $unidades=-1; break;
            case "Transito":    $unidades=-1; break;
            case "Televenta":   $unidades=-1; break;
            case "RMA":         $unidades=-1; break;
        }

        $elemento = array(
            'id_devices_almacen'        => $xcrud,
            'id_device'                 => ($postdata->get('id_device') ? $postdata->get('id_device') : NULL),
            'id_alarm'                  => NULL,
            'id_client'                 => ($postdata->get('id_cliente') ? $postdata->get('id_client') : NULL),
            'fecha'                     => date("Y-m-d H:i:s"),
            'unidades'                  => $unidades, // En negativo porque luego la función lo multiplica por -1
            'id_incidencia'             => NULL,
            'procesado'                 => 1,
            'id_material_incidencia'    => ($postdata->get('id_material_incidencia') ? $postdata->get('id_material_incidencia'): NULL),
            'status'                    => ($postdata->get('status') ? $postdata->get('status') : NULL)
        );

        if(!empty($estado_anterior)) {
            if ($estado_anterior != $postdata->get('status')) {
                $CI->db->insert('historico_io', $elemento);
            }
        }

    }
