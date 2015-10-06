<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 05/10/2015
 * Time: 11:31
 */

/**
 * Class Data
 *  Clase genérica para gestionar la variable $data que se pasa a las vistas, añadiendo variables que son "globales"
 *  o permitiendo su modificación desde los métodos correspondientes;
 *
 */
require_once(APPPATH . "libraries/DataVar.php");

class Data
{
    private $content;        // Array variables que pasar a la vista

    /**
     * Constructor de la clase
     */
    function __construct()
    {
        $this->initialize();
    }

    /**
     * Método que inicializa la colección. También puede servir para resetear la colección.
     */
    private function  initialize()
    {
        $this->content = array();
        // DEFINICION/INICIALIZACION DE LAS VARIABLES COMUNES / GLOBALES
        $this->set("controlador",NULL,TRUE);
        $this->set("accion_home",NULL,TRUE);
        $this->set("entrada",NULL,TRUE);
    }

    /**
     * Método que establece/añade un par clave=>valor
     * @param $key
     * @param $value
     */
    public function set($key,$value,$constant = FALSE)
    {
        $datos = $this->getContent();
        $newData = new DataVar($key,$value,$constant);

        if(empty($datos))
        {
           $datos["$key"] = $newData;
        }
        else
        {
            foreach($datos as $id=>$valor)
            {
                if($valor->getKey() == $key) // Existe
                {
                    // Debemos comprobar si está protegida o no etc...
                    if($valor->isWritable() == TRUE)
                    {
                        $update = new DataVar($key,$value,$constant);
                        $datos["$key"] = $update;
                    }
                }
                else    // No existe
                {
                    $datos["$key"] = $newData;
                }
            }
        }

        $this->setContent($datos);
    }

    /**
     * Método que devuelve el valor de una clave consultada.
     * @param $key
     * @return null si no existe la clave consultada, o su valor si existe.
     */
    public function get($key)
    {
        if(array_key_exists($key,$this->content))
            return $this->content[$key]->getValue();
        else
            return NULL;
    }

    /**
     * Devuelve el array de objetos completo
     * @return array
     */
    public function getContent()
    {
        return $this->content;

    }

    /**
     * Sobreescribe el array de variables con el pasado como parám.
     * @param $arrayContent
     */
    public function setContent($arrayContent)
    {
        $this->content = $arrayContent;
    }


    /**
     * Elimina un elemento de la colección buscando por clave.
     * @return true si lo ha encontrado y borrado. Y FALSE si no lo ha encontrado.
     */
    public function delete($key)
    {
        $res = FALSE;
        if(array_key_exists($key,$this->content)){
            if(!$this->content[$key]["constant"])
            {
                $res = TRUE;
               unset($this->content[$key]);
            }
        }
        return $res;
    }

    /**
     * Resetea la colección.
     */
    public function reset()
    {
        $this->initialize();
    }


    /**
     * Añade un array de $data de variables para la vista
     */
    public function add($data)
    {
        foreach($data as $key=>$value)
        {
            $this->set($key,$value,FALSE);
        }
    }

    /**
     * Genera un array de estilo clave=>valor para pasar a la vista
     */
    public function getData()
    {
        $datos = $this->content;
        $data = array();
        foreach($datos as $key=>$value)
        {
            $data["$key"] = $value->getValue();
        }

        return $data;
    }

}