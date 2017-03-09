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


    function __construct($roles = array())
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

    public function is_auth($roles = array())
    {
        if(!empty($roles))  {
            if(!is_array($roles)) { $roles = array ($roles);  }
            $this->set_roles($roles);
        }

        /*print_r($this->ci->session->all_userdata());*/

        $sess_logged =  $this->ci->session->userdata($this->sess_var_auth);
        $sess_role = $this->ci->session->userdata($this->sess_var_role);
        $roles_permitidos = $this->permitted_roles;


        return ( $sess_logged && in_array($sess_role,$roles_permitidos));
    }

    public function check_entorno($currentEntorno = ''){
        if($this->ci->session->userdata('entorno')){
            $entorno = $this->ci->session->userdata('entorno');
            if($entorno !== $currentEntorno) redirect($entorno. '/index','refresh');
        }
    }


    public function get_type(){
        return $this->ci->session->userdata($this->sess_var_role);
    }



}