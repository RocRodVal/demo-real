<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 05/10/2015
 * Time: 11:31
 */

/**
 * Class DataVar
 *  Clase genérica para gestionar un par Clave => Valor
 *  o permitiendo su modificación desde los métodos correspondientes;
 *
 */
class DataVar
{
    private $key;           // Clave
    private $value;         // Valor
    private $protected;     // Valor protegido



    /**
     * Constructor de la clase
     */
    function __construct($key=NULL,$value=NULL,$protected=FALSE)
    {
        if(!is_null($key) || !is_null($value))
        {
            $this->set($key,$value,$protected);
        }
        else
        {
            $this->initialize();
        }
    }

    /**
     * Método que inicializa la colección. También puede servir para resetear la colección.
     */
    private function  initialize()
    {
        $this->key = NULL;
        $this->value = NULL;
        $this->protected = NULL;
    }

    /**
     * Método que establece/añade un par clave=>valor
     * @param $key
     * @param $value
     * @param $protected
     */
    public function set($key,$value,$protected=FALSE)
    {
        $this->key = $key;
        $this->value = $value;
        $this->protected = $protected;
    }

    /**
     * Método que devuelve el valor del par
     * @return valor .
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Método que devuelve la key del par;
     * @return valor .
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Método que devuelve si un par está protegido;
     * @return TRUE => protegido | FALSE=> No protegido | NULL => No establecido.
     */
    public function isProtected()
    {
        return $this->protected;
    }


    /**
     * Establece el key del Par, si es escribible
     * @param $key
     */
    public function setKey($key)
    {
        if($this->isWritable())
        {
            $this->key = $key;
        }
    }

    /**
     * Establece el valor del Par, si es escribible
     * @param $key
     */
    public function setValue($value)
    {
        if($this->isWritable())
        {
            $this->value = $value;
        }
    }

    /**
     *
     */
    public function get()
    {
        $clave = $this->getKey();
        $valor = $this->getValue();

        return array("$clave" => "$valor");
    }
    /**
     * Función que comprueba si el par es reescribible, según el atributo PROTECTED y si ya se ha insertado algún valor.
     */
    public function isWritable()
    {
        $valor = $this->value;
        $protected = $this->protected;
        // Si no está protegida, o inicializada, o su valor aún es NULL, dejamos escribir...
        return ($protected === FALSE || is_null($protected) || is_null($valor));
    }

    /**
     * Resetea el par.
     */
    public function reset()
    {
        $this->initialize();
    }

}