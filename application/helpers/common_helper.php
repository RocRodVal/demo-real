<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 30/07/2015
 * Time: 14:05
 */

/*Funcion que devuelve los dias domingo que caen entre 2 fechas*/
function contar_domingos($fechaInicio,$fechaFin)
{
    $dias=array();
    $fecha1=date($fechaInicio);
    $fecha2=date($fechaFin);
    $fechaTime=strtotime("-1 day",strtotime($fecha1));//Les resto un dia para que el next sunday pueda evaluarlo en caso de que sea un domingo
    $fecha=date("Y-m-d",$fechaTime);
    $contador= 0;
    while($fecha <= $fecha2)
    {
        $proximo_domingo=strtotime("next Sunday",$fechaTime);
        $fechaDomingo=date("Y-m-d",$proximo_domingo);
        if($fechaDomingo <= $fechaFin)
        {
            $dias[$fechaDomingo]=$fechaDomingo;
            $contador++;

        }
        else
        {
            break;
        }
        $fechaTime=$proximo_domingo;
        $fecha=date("Y-m-d",$proximo_domingo);
    }


    return $contador;
    return $dias;
}//fin de domingos


/*Funcion que devuelve los dias que caen entre 2 fechas excepto domingos*/
function contar_dias_excepto($mes,$anio,$pasarDias=array('Sun'),$dia_actual = NULL)
{

    $contador = 0;
    $total_dias = 0;

    $fecha1 = strtotime(date($anio.'-'.$mes.'-01'));
    //$fecha2 = strtotime(date($anio.'-'.$mes.'-t'));
    $fecha2=strtotime(date($anio.'-'.$mes.'-'.(date("d",mktime(0,0,0,$mes+1,1,$anio)-1))));

    for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){
        if(!is_null($dia_actual) && ($anio==date("Y")) && strcmp(date('Y-m-d',$fecha1),date("Y-m-".$dia_actual)) ==0) break;
        foreach($pasarDias as $dia) {

            if ((strcmp(date('D', $fecha1), $dia) == 0)) {
               $contador++;
            }


        }
        $total_dias++;
    }

    return $total_dias - $contador;
}//fin de domingos



function nombre_mes($number)
{
    $nombre_meses = array(
        1=>"Enero", 2=>"Febrero", 3=>"Marzo", 4=>"Abril", 5=>"Mayo", 6=>"Junio",
        7=>"Julio",8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
    );


    return isset($nombre_meses[$number]) ? $nombre_meses[$number] : '';
}



/**
 * Función que busca en un array multidimensional, un valor de cierta clave
 */

function m_array_search($array,$key,$value){
    foreach($array as $elem){
        if($elem[$key] == $value) return TRUE;
    }
    return FALSE;
}

/**
 * Función que busca en un array de objetos multidimensional, un valor de cierta clave
 */

function m_object_search($array,$key,$value){
    foreach($array as $elem){
        if($elem->{$key} == $value) return TRUE;
    }
    return FALSE;
}

/**
 * Función que busca en un array de objetos multidimensional, un valor de cierta clave y lo devuelve
 */

function get_object_search($array,$key,$value){
    foreach($array as $elem){
        if($elem->{$key} == $value) return $elem;
    }
    return NULL;
}



/**
 *  Integración temporal con Realdooh.
 *  ***********************************************************
 */

/**
 * Obtiene la configuración del fichero config.php
 * @return mixed
 */
function get_realdooh_config() {
    $ci =& get_instance();
    $cfg = $ci->config->item('realdooh.api');

    return $cfg;
}

/**
 * Llamada a la API para alta de incidencia demoreal en las incidencias de Realdooh
 * Sólo si está ACTIVE=TRUE en la configuración
 * @param $params
 * @param $auth
 */
function alta_incidencia_realdooh($params, $auth) {
    $cfg = get_realdooh_config();
    //echo $cfg['createIncidenceUrl']."\n";
    if($cfg['active']) {
        $service = $cfg['createIncidenceUrl'];
        $response = rest_post($service, $params, $auth);

        return $response;
    }
}

/**
 * Llamada a la API para cambiar en realdooh el estado ed una ¡incidencia de Demoreal
 * SOLO SI ACTIVE=TRUE en la configuracion...
 * @param $params
 * @param $auth
 * @param string $postParams
 */
function set_estado_incidencia_realdooh($params, $auth, $postParams = '') {
    $cfg = get_realdooh_config();

    if($cfg['active']) {
        $service = $cfg['changeStatusUrl'];
        $response = rest_put($service, $params, $auth, $postParams);
        return $response;

    }
}

/**
 * Llamada a la API para cambiar en realdooh el estado ed una ¡incidencia de Demoreal
 * SOLO SI ACTIVE=TRUE en la configuracion...
 * @param $params
 * @param $auth
 * @param string $postParams
 */
function alta_pds_realdooh($auth, $params='',$postParams = '') {
    $cfg = get_realdooh_config();

    if($cfg['active']) {
        $service = $cfg['createPdsUrl'];
        $response = rest_post($service, $params, $auth,$postParams);
        return $response;

    }
}

/**
 * Llamada a la API para cambiar en realdooh los datos de la tienda en Realdooh
 * SOLO SI ACTIVE=TRUE en la configuracion...
 * @param $params
 * @param $auth
 * @param string $postParams
 */
function set_pds_realdooh($auth, $params='',$postParams = '') {
    $cfg = get_realdooh_config();

    if($cfg['active']) {
        $service = $cfg['updatePdsUrl'];
        $response = rest_put_json($service, $params, $auth,$postParams);
        return $response;

    }
}

/**
 * Llamada a la API para cambiar en realdooh el estado ed una ¡incidencia de Demoreal
 * SOLO SI ACTIVE=TRUE en la configuracion...
 * @param $params
 * @param $auth
 * @param string $postParams
 */
function set_assets_pds_realdooh($auth, $params='',$postParams = '') {
    $cfg = get_realdooh_config();

    if($cfg['active']) {
        $service = $cfg['addAssetsPdsUrl'];
        $response = rest_post($service, $params, $auth,$postParams);
        return $response;

    }
}

/**
 * Llamada a la API para borrar de manera logica una tienda en realdooh
 * SOLO SI ACTIVE=TRUE en la configuracion...
 * @param $params
 * @param $auth
 * @param string $postParams
 */
function delete_pds_realdooh($auth, $params='',$postParams = '') {
    $cfg = get_realdooh_config();

    if($cfg['active']) {
        $service = $cfg['updatePdsUrl'];
        $response = rest_delete($service, $params, $auth,$postParams);
        return $response;

    }
}
/**
 * Post básico a servicio REST mediante URL
 * @param $url
 * @param array $urlParams
 * @param array $auth
 * @return mixed
 */
function rest_post ($url, $urlParams = array(), $auth = array(), $queryParams= '') {
    // http://realdooh.pre.altabox.net:8080/rdorangeapi/api/v1/location/demoreal
    // Authorization: Basic aHR0cHdhdGNoOmY=    base64(User:Password)
    //$url = 'http://server.com/path';

    $url = replaceUrlParams($url, $urlParams);

    $headers = array("Content-type: application/json", );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $auth['user'] . ":" . $auth['password']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);
    curl_close ($ch);

    return $server_output;
}

/**
 * Put básico a servicio REST mediante URL
 * @param $url
 * @param array $urlParams
 * @param array $auth
 * @return mixed
 */
function rest_put ($url, $urlParams, $auth, $queryParams = '') {
    // http://realdooh.pre.altabox.net:8080/rdorangeapi/api/v1/demoreal/incident/{drId}
    $url = replaceUrlParams($url, $urlParams);
    $headers = array ("Content-type: application/json\r\n", );
    $url .= '?'.$queryParams;
  //  echo $queryParams;
//echo $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ch, CURLOPT_PUT, 1);
    //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $auth['user'] . ":" . $auth['password']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);
    $api_response_info = curl_getinfo($ch);
    print_r($api_response_info);
    curl_close ($ch);

    print_r($server_output);
exit;

    return $server_output;
}

/*
 * Metodo put pasandole datos en un JSON
 */
function rest_put_json ($url, $urlParams, $auth, $queryParams = '') {
    // http://realdooh.pre.altabox.net:8080/rdorangeapi/api/v1/location/demoreal/{sfid}
    $url = replaceUrlParams($url, $urlParams);
    $headers = array ("Content-type: application/json",'Content-Length: ' . strlen($queryParams), );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $auth['user'] . ":" . $auth['password']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);
    curl_close ($ch);

    return $server_output;
}

/**
 * DELETE básico a servicio REST mediante URL
 * @param $url
 * @param array $urlParams
 * @param array $auth
 * @return mixed
 */
function rest_delete ($url, $urlParams = array(), $auth = array(), $queryParams= '') {
    // http://realdooh.pre.altabox.net:8080/rdorangeapi/api/v1/location/demoreal
    // Authorization: Basic aHR0cHdhdGNoOmY=    base64(User:Password)
    //$url = 'http://server.com/path';

    $url = replaceUrlParams($url, $urlParams);

    $headers = array("Content-type: application/json", );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $auth['user'] . ":" . $auth['password']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);

    //$api_response_info = curl_getinfo($ch);
    //print_r($api_response_info); exit;
    curl_close ($ch);

    return $server_output;
}


function file_get_contents_curl($url) {
    if (strpos($url,'http://') !== FALSE) {
        $fc = curl_init();
        curl_setopt($fc, CURLOPT_URL,$url);
        curl_setopt($fc, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($fc, CURLOPT_HEADER,0);
        curl_setopt($fc, CURLOPT_VERBOSE,0);
        curl_setopt($fc, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($fc, CURLOPT_TIMEOUT,30);
        $res = curl_exec($fc);
        curl_close($fc);
    }
    else $res = file_get_contents($url);
    return $res;
}


function replaceUrlParams($url, $urlParams = array()){
    foreach($urlParams as $key=>$value) {
        $url  = str_replace('{'.$key.'}', $value, $url);
    }
    return $url;
}
function basicAuth ($auth = array()){
    $strAuth = $auth['user'] . ':' . $auth['password'];
    return 'Basic '. base64_encode($strAuth);
}
