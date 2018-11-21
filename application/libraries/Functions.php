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
        if($postdata->get('status') =='En stock')
            $unidades=1;

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
    * funcion para que inserte en el historico_io cuando damos de alta un terminal en almacen
    * $xcrud guarda el ID del elemento
    $postdata tenemos los datos para actualizar el dispositivo
    */
    function insert_historicoIO($postdata,  $xcrud){
        $CI =& get_instance();
        $elemento = array(
            'id_devices_almacen'        => $xcrud,
            'id_device'                 => ($postdata->get('id_device') ? $postdata->get('id_device') : NULL),
            'id_alarm'                  => NULL,
            'id_client'                 => ($postdata->get('id_cliente') ? $postdata->get('id_client') : NULL),
            'fecha'                     => date("Y-m-d H:i:s"),
            'unidades'                  => 1, // En negativo porque luego la función lo multiplica por -1
            'id_incidencia'             => NULL,
            'procesado'                 => 1,
            'id_material_incidencia'    => ($postdata->get('id_material_incidencia') ? $postdata->get('id_material_incidencia'): NULL),
            'status'                    => ($postdata->get('status') ? $postdata->get('status') : NULL)
        );
        $CI->db->insert('historico_io', $elemento);
    }

    /*
    * funcion para que cuando se vaya a insertar el dispositivo automaticamente se busque el id_displays_pds segun el
    * mueble seleccionado
    * $xcrud guarda el ID del elemento a actualziar
    $postdata tenemos los datos para actualizar el dispositivo
    */
    function inventario_dispositivos_codigoMueble($postdata,  $xcrud){

        $CI =& get_instance();
        /*consultamos el estado anterior del dispositivo*/
        $result = $CI->db->select('id_displays_pds')
            ->where("id_display",$postdata->get('id_display'))
            ->where("id_pds",$postdata->get('id_pds'))
            ->get('displays_pds')->row_array();

        if (!empty($result)) {
            $elemento = array(
                'id_displays_pds' => ($result['id_displays_pds'])
            );
            $CI->db->where('id_devices_pds',$xcrud)
                    ->update('devices_pds', $elemento);

            /*Guardamos en el historico el alta del dispositivo*/
            /*Insertar en el historico de tienda el estado del dispositivo*/
            $elemento = array(
                'id_devices_pds' => $xcrud,
                'fecha' => date('Y-m-d H:i:s'),
                'status' => 'Alta',
                'motivo' => ALTA_MANUAL
            );
            $CI->db->insert('historico_devicesPDS',$elemento);
        }
        else {
            $CI->db->where('id_devices_pds',$xcrud)
                    ->delete('devices_pds');
            echo "<text style='color: red; font-size: 18px '>ERROR - no se ha podido agregar el dispositivo porque el 
            mueble seleccionado no pertenece a la tienda</text>";
        }
    }

    /*
   * funcion para que cuando se vaya a insertar el dispositivo automaticamente se busque el id_displays_pds segun el
   * mueble seleccionado
   * $xcrud guarda el ID del elemento a actualziar
   $postdata tenemos los datos para actualizar el dispositivo
   */
    function update_inventario_dispositivos($postdata,  $xcrud){

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
            ->where('status','Alta')
            ->get('displays_pds')->row_array();

        /*Si el mueble seleccionado esta en la tienda, entonces modificamos Sino se queda como estaba*/
        if (!empty($result)) {
            $elemento = array(
                'id_displays_pds' => $result['id_displays_pds'],
                'id_display'      => $postdata->get('id_display')
            );
            $CI->db->where('id_devices_pds',$xcrud)
                ->update('devices_pds', $elemento);

            if(!empty($datosAntes)) {
                /*preparamos para insertar en el historico el cambio de estado*/
                if ($datosAntes['status'] != $postdata->get('status')) {
                    $elemento = array(
                        'id_devices_pds'=> $xcrud,
                        'fecha'         => date("Y-m-d H:i:s"),
                        'status'        => ($postdata->get('status') ? $postdata->get('status') : NULL),
                        'motivo'        => CAMBIO_MANUAL
                    );

                    $CI->db->insert('historico_devicesPDS', $elemento);
                }
            }

        }
        else {
            $postdata->set('id_displays_pds',$datosAntes['id_displays_pds']);
            $postdata->set('id_display',$datosAntes['id_display']);
            $postdata->set('status',$datosAntes['status']);

            echo "<text style='color: red; font-size: 18px '>ERROR - no se ha podido actualizar el dispositivo porque 
            el mueble seleccionado no pertenece a la tienda</text>";

        }

    }

    /** funcion para que cuando se vaya a insertar el dispositivo en el almacen compruebe si ya existe un terminal con el mismo IMEI
    * en estado distinto a baja o RMA
    * $xcrud guarda el ID del elemento a actualziar
    $postdata tenemos los datos para actualizar el dispositivo*/
    function comprobarIMEI($postdata,  $xcrud){
        $CI =& get_instance();
        /*guardamos los datos anteriores del dispositivo en relacion al mueble y posicion del mismo*/
        $result = $CI->db->select('*')
            ->where("IMEI",$postdata->get('IMEI'))
            ->where("status!=",'Baja')
            ->get('devices_almacen')->result();

        if(!empty($result) && count($result)>0) {
            $postdata->set("error", "ERROR - ya existe un dispositivo con ese IMEI");
            echo "<text style='color: red; font-size: 18px '>ERROR - ya existe un dispositivo con ese IMEI</text>";
            return false;
        }
    }

    /*
     * crear la tienda en realdooh
     */
    function create_pds_realdooh($postdata,  $xcrud){

        $CI =& get_instance();
        if (!empty($postdata)){

            $result = $CI->db->select('titulo')
                ->where("id",$postdata->get('id_tipo'))
                ->get('pds_tipo')->row_array();
            $tipo=$result['titulo'];

            $result = $CI->db->select('titulo')
                ->where("id",$postdata->get('id_subtipo'))
                ->get('pds_subtipo')->row_array();
            $subtipo=$result['titulo'];

            $result = $CI->db->select('titulo')
                ->where("id",$postdata->get('id_tipologia'))
                ->get('pds_tipologia')->row_array();
            $tipologia=$result['titulo'];

            $result = $CI->db->select('titulo')
                ->where("id",$postdata->get('id_segmento'))
                ->get('pds_segmento')->row_array();
            $segmento=$result['titulo'];

            $result = $CI->db->select('province')
                ->where("id_province",$postdata->get('province'))
                ->get('province')->row_array();
            $province=$result['province'];

            $telefono=$postdata->get('phone');
            $mobile=$postdata->get('mobile');
            if(!empty($telefono)){
                if(!empty($mobile)){ $telefono=$telefono." / ".$mobile;
                }
            }else{
                if(!empty($mobile)){ $telefono=$mobile;
                }
            }


            $pds_realdooh=array(
                        "satCode"           =>  $postdata->get('codigoSAT'),
                        "name"              =>  $postdata->get('commercial'),
                        "locationSubtype"   =>array("name"=>$subtipo),
                        "locationSegment"   =>array("name"=>$segmento),
                        "locationTypology"  =>array("name"=>$tipologia),
                        "locationType"      =>array("name"=>$tipo),
                        "company"           =>array("id"=>2),
                        "code"              =>  $postdata->get("reference"),
                        "province"          =>  $province,
                        "city"          =>  $postdata->get('city'),
                        "address1"      =>  $postdata->get('address'),
                        "zipCode"       =>  $postdata->get('zip'),
                        "email"         =>  $postdata->get('email'),
                        "phoneNumber"   =>  $telefono,
                        "description"   =>  ""
                    );
        }
        //print_r($postdata);
        $json = json_encode($pds_realdooh);
        //print_r($pds_realdooh);
        //////////////////////////////////////////////////////////////////////////////////
        //                                                                              //
        //             Comunicación  con Realdooh VU: CREAR tienda                      //
        //                                                                              //
        //////////////////////////////////////////////////////////////////////////////////
        //                                                                              //
        //idOUParent es 2 en PRE pero en produccion será 1
        $resultado=alta_pds_realdooh(array(                                             //
            'user'=> 'altabox',
            'password' => 'realboxdemo'
        ), array(),$json);                                                //
        //
        //                                                                              //
        //////////////////////////////////////////////////////////////////////////////////

        //print_r($resultado);

    }

    /*
     * actualizar la tienda en realdooh
     */
    function update_pds_realdooh($postdata, $xcrud){

        $CI =& get_instance();

        if (!empty($postdata)){

            $result = $CI->db->select('titulo')
                ->where("id",$postdata->get('id_tipo'))
                ->get('pds_tipo')->row_array();
            $tipo=$result['titulo'];

            $result = $CI->db->select('titulo')
                ->where("id",$postdata->get('id_subtipo'))
                ->get('pds_subtipo')->row_array();
            $subtipo=$result['titulo'];

            $result = $CI->db->select('titulo')
                ->where("id",$postdata->get('id_tipologia'))
                ->get('pds_tipologia')->row_array();
            $tipologia=$result['titulo'];

            $result = $CI->db->select('titulo')
                ->where("id",$postdata->get('id_segmento'))
                ->get('pds_segmento')->row_array();
            $segmento=$result['titulo'];

            $result = $CI->db->select('province')
                ->where("id_province",$postdata->get('province'))
                ->get('province')->row_array();
            $province=$result['province'];

            $telefono=$postdata->get('phone');
            $mobile=$postdata->get('mobile');
            if(!empty($telefono)){
                if(!empty($mobile)){ $telefono=$telefono." / ".$mobile;
                }
            }else{
                if(!empty($mobile)){ $telefono=$mobile;
                }
            }

            $pds_realdooh=array(
                "satCode"           =>  $postdata->get('codigoSAT'),
                "name"              =>  $postdata->get('commercial'),
                "locationSubtype"   =>array("name"=>$subtipo),
                "locationSegment"   =>array("name"=>$segmento),
                "locationTypology"  =>array("name"=>$tipologia),
                "locationType"      =>array("name"=>$tipo),
                "company"           =>array("id"=>2),
                "code"              =>  $postdata->get('reference'),
                "province"          =>  $province,
                "city"          =>  $postdata->get('city'),
                "address1"       =>  $postdata->get('address'),
                "zipCode"       =>  $postdata->get('zip'),
                "email"         =>  $postdata->get('email'),
                "phoneNumber"   =>  $telefono,
                "description"   =>  ""
            );
        }
        $json = json_encode($pds_realdooh);

        //////////////////////////////////////////////////////////////////////////////////
        //                                                                              //
        //             Comunicación  con Realdooh VU: ACTUALIZAR tienda                 //
        //                                                                              //
        //////////////////////////////////////////////////////////////////////////////////
        //                                                                              //
        //idOUParent es 2 en PRE pero en produccion será 1
        $resultado=set_pds_realdooh(array(                                             //
            'user'=> 'altabox',
            'password' => 'realboxdemo'
        ), array("sfid"=>$postdata->get('reference')),$json);                                                //
        //
        //                                                                              //
        //////////////////////////////////////////////////////////////////////////////////

       // print_r($resultado);

    }

    /*
    * crear modelo de mueble en realdooh
    */
    function create_modeloMueble_realdooh($postdata,  $xcrud){

        $CI =& get_instance();

        if (!empty($postdata)){

          // echo site_url('application/uploads/').$postdata->get('picture_url'); echo "<br>";
            $imagen=$postdata->get('picture_url');
            $code="DR".$xcrud;
            $asset_realdooh=array(
                "drId"           =>  $xcrud,
                "code"           =>  $code,
                "internalCode"   =>  $code,
                "imageUrl"       =>  (!empty($imagen)) ? site_url('application/uploads/').$postdata->get('picture_url'):"",
                "layoutVisible"  =>  true,
                "demoReal"       =>  $postdata->get('positions')>0 ? true : false,
                "name"           =>  $postdata->get("display")
            );
        }
        //print_r($asset_realdooh);exit;
        $json = json_encode($asset_realdooh);
        //////////////////////////////////////////////////////////////////////////////////
        //                                                                              //
        //             Comunicación  con Realdooh VU: CREAR tienda                      //
        //                                                                              //
        //////////////////////////////////////////////////////////////////////////////////
        //                                                                              //
        //idOUParent es 2 en PRE pero en produccion será 1
        $resultado=alta_modeloMueble_realdooh(array(                                             //
            'user'=> 'altabox',
            'password' => 'realboxdemo'
        ), array(),$json);                                                //
        //
        //                                                                              //
        //////////////////////////////////////////////////////////////////////////////////

       // print_r($resultado);

    }

    /*
     * actualizar modelo de mueble en realdooh
     */
    function update_modeloMueble_realdooh($postdata, $xcrud){

        $CI =& get_instance();

        if (!empty($postdata)){
            /*guardamos los datos anteriores del dispositivo en relacion al mueble y posicion del mismo*/
            $result = $CI->db->select('id_display')
                ->where("id_display",$xcrud)
                ->get('display')->row_array();
            $datosAntes=$result;

            $imagen=$postdata->get('picture_url');
            $code="DR".$xcrud;
            $asset_realdooh=array(
                "drId"           =>  $xcrud,
                "code"           =>  $code,
                "internalCode"   =>  $code,
                "imageUrl"       =>  (!empty($imagen)) ? site_url('application/uploads/').$postdata->get('picture_url'):"",
                "layoutVisible"  =>  true,
                "demoReal"       =>  $postdata->get('positions')>0 ? true : false,
                "name"           =>  $postdata->get("display")
            );
        }
        $json = json_encode($asset_realdooh);
		//print_r($json);
        //////////////////////////////////////////////////////////////////////////////////
        //                                                                              //
        //             Comunicación  con Realdooh VU: ACTUALIZAR tienda                 //
        //                                                                              //
        //////////////////////////////////////////////////////////////////////////////////
        //                                                                              //
        //idOUParent es 2 en PRE pero en produccion será 1
        $resultado=set_modeloMueble_realdooh(array(                                             //
            'user'=> 'altabox',
            'password' => 'realboxdemo'
        ), "drId=".$datosAntes['id_display'],$json);                                                //
        //
        //                                                                              //
        //////////////////////////////////////////////////////////////////////////////////
//echo "RESPUESTA";
       // print_r($resultado);

    }

    /*
     * Devuelve un enlace al detalle de una incidencia
     */
    function enlace_idincidencia($value, $row){
        $CI =& get_instance();
        /*Obtenemos la tienda de la incidencia*/
        $result = $CI->db->select('id_pds')
            ->where("id_incidencia",$value)
            ->get('incidencias')->row_array();

        return '<a href="'.site_url("admin/operar_incidencia/".$result['id_pds']."/".$value).'" targe="_blank">'.$value.'</a>';

    }

    /*
    * funcion que nos devolvera el listado de incidencias de la posicion
    * $xcrud guarda el ID del elemento a actualziar
    $postdata tenemos los datos para actualizar el dispositivo
    */
    /*function incidencias_list_action($xcrud) {

        $CI =& get_instance();

        /*consultamos las incidencias
        $result = $CI->db->select('id_incidencia')
            //    ->join("pds","id_pds=".$postdata->get('id_pds'))
            ->where("id_devices_pds",$xcrud->get('primary'))
            ->get('incidencias')->result();
        //echo $CI->db->last_query();

        $lista="";
        if (!empty($result)) {
            $lista.= "<div class='form-group'>";
            $lista .= "<label class='control-label col-sm-3'>Incidencias</label>";
            $lista .= "<div class='col-sm-9'><input class='xcrud-input form-control' type='text' value=";
            foreach ($result as $r) {
                $lista .= $r->id_incidencia;
                if ($r !== end($result)) {
                    $lista .= ",";
                }
            }
            $lista .= " disabled ></div></div>";
        } else {
            echo "<text style='color: red; font-size: 18px '>El dispositivo no tiene incidencias</text>";
        }
        $xcrud->set('lista_incidencias',$lista);
        //echo $lista;
        return $xcrud;

    }*/
