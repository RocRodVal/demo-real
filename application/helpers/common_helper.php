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