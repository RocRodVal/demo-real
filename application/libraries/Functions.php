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
        $unidades=-1;
        switch ($postdata->get('status')){
            case 'En stock':    $unidades=1; break;
            /*case "Reservado":   $unidades=-1; break;
            case "Baja":        $unidades=-1; break;
            case "Transito":    $unidades=-1; break;
            case "RMA":         $unidades=-1; break;*/
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

    /*
    * funcion para que cuando se vaya a insertar el dispositivo automaticamente se busque el id_displays_pds segun el
    * mueble seleccionado
    * $xcrud guarda el ID del elemento a actualziar
    $postdata tenemos los datos para actualizar el dispositivo
    */
    function inventario_dispositivos_codigoMueble($postdata,  $xcrud){

        $CI =& get_instance();
      //  print_r($postdata);
        /*consultamos el estado anterior del dispositivo*/
        $result = $CI->db->select('id_displays_pds')
        //    ->join("pds","id_pds=".$postdata->get('id_pds'))
            ->where("id_display",$postdata->get('id_display'))
            ->where("id_pds",$postdata->get('id_pds'))
            ->get('displays_pds')->row_array();
        //echo $CI->db->last_query();
//print_r($result);
        if (!empty($result)) {
            $elemento = array(
                'id_displays_pds' => ($result['id_displays_pds'])
            );
            $CI->db->where('id_devices_pds',$xcrud)
                    ->update('devices_pds', $elemento);
            //return true;
        }
        else {
            $CI->db->where('id_devices_pds',$xcrud)
                    ->delete('devices_pds');
            echo "<text style='color: red; font-size: 18px '>ERROR - no se ha podido agregar el dispositivo porque el 
            mueble seleccionado no pertenece a la tienda</text>";
            //return false;
            //$postdata->set("error","Ese mueble no pertenece a la tienda");
        }
    }

    /*
   * funcion para que cuando se vaya a insertar el dispositivo automaticamente se busque el id_displays_pds segun el
   * mueble seleccionado
   * $xcrud guarda el ID del elemento a actualziar
   $postdata tenemos los datos para actualizar el dispositivo
   */
    function update_inventario_dispositivos_codigoMueble($postdata,  $xcrud){

        $CI =& get_instance();
        /*guardamos los datos anteriores del dispositivo en relacion al mueble y posicion del mismo*/
        $result = $CI->db->select('*')
            ->where("id_devices_pds",$xcrud)
            ->get('devices_pds')->row_array();
        $datosAntes=$result;

        /*Comprobamos si el mueble seleccionado existe en la tienda*/
        $result = $CI->db->select('id_displays_pds')
            ->where("id_display",$postdata->get('id_display'))
            ->where("id_pds",$postdata->get('id_pds'))
            ->get('displays_pds')->row_array();

        /*Si el mueble seleccionado esta en la tienda, entonces modificamos
        Sino se queda como estaba*/
        if (!empty($result)) {
            $elemento = array(
                'id_displays_pds' => $result['id_displays_pds'],
                'id_display'      => $postdata->get('id_display')
            );
            $CI->db->where('id_devices_pds',$xcrud)
                ->update('devices_pds', $elemento);

        }
        else {
            $postdata->set('id_displays_pds',$datosAntes['id_displays_pds']);
            $postdata->set('id_display',$datosAntes['id_display']);
            //$postdata->set("message","El mueble seleccionado no pertenece a la tienda");
          //  print_r($postdata);
            //$X=$xcrud->get_instance();

           // $CI->("El mueble seleccionado no pertenece a la tienda");
            echo "<text style='color: red; font-size: 18px '>ERROR - no se ha podido actualizar el dispositivo porque 
            el mueble seleccionado no pertenece a la tienda</text>";
            //$CI->set_echo_and_die();

        }
    }

