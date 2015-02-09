<?php
/**
 * MY_Model
 *
 * @author Alma Fernández
 * @version 0.1
 *
 */

class MY_Model extends CI_Model {
    
}

/**
 * VO
 *
 * La clase base VO nos permite disponer de unos métodos básicos en todos
 * nuestros Value Objects
 *
 * @author Alma Fernández
 * @version 0.1
 *
 */

class VO extends MY_Model {
    function  __get($var) {
        return $this->$var;
    }

    function  __set($var,  $value) {
        $this->$var = $value;
    }

    function populate_VO($row) {
        $vars = get_object_vars($row);
        foreach ($vars as $var => $value) {
            $this->__set($var, $value);
        }
    }
}
/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
