<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 30/07/2015
 * Time: 11:31
 */

class Auth
{
    var $permitted_roles;
    var $sess_var_auth;


    function __construct($roles)
    {
        $this->ci =& get_instance();

        $this->set_roles($roles);
        $this->sess_var_auth = "logged_in";
        $this->sess_var_role = "type";
    }

    public function set_roles($arr)
    {
        if(is_array($arr))
        {
            $this->permitted_roles = $arr;
        }
        else
        {
            $this->permitted_roles = array();
        }
    }
    public function get_roles()
    {
        return $this->permitted_roles;
    }

    public function is_auth()
    {
        $sess_logged =  $this->ci->session->userdata($this->sess_var_auth);
        $sess_role = $this->ci->session->userdata($this->sess_var_role);
        $roles_permitidos = $this->permitted_roles;

        return ( $sess_logged && in_array($sess_role,$roles_permitidos));
    }


    public function get_type(){
        return $this->ci->session->userdata($this->sess_var_role);
    }



}