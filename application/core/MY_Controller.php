<?php

/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 16/02/2017
 * Time: 16:39
 */

class MY_Controller extends CI_Controller
{

    protected $ctrl = '';
    protected $environmentProfile = '';
    protected $homeAction = '';
    private $userType = 0;
    private $viewsFolder = '';



    function __construct()
    {
        parent::__construct();

        $this->load->library(array('email', 'encrypt', 'form_validation', 'session', 'pagination', 'data'));
        $this->load->helper(array('email', 'text', 'xcrud','url', 'form'));

        $this->load->config('files');
        $this->cfg = $this->config->config;
        $this->export = config_item("export");
        $this->ext = $this->export["default_ext"];
    }


    protected function setController($ctrl) {
        $this->ctrl = $ctrl;
        $this->data->set("controlador", $ctrl);
    }
    protected function setEnvironmentProfile ($envProfile ='') {
        $this->environmentProfile = $envProfile;
        $this->data->set("acceso", $envProfile);
    }
    protected function setHomeAction ($homeAction) {
        $this->data->set("accion_home", $homeAction);
        $this->data->set("entrada",($this->data->get("controlador") . '/' . $this->data->get("accion_home")));
    }


    protected function setViewsFolder($viewsFolder) {
        $this->viewsFolder = $viewsFolder;
    }
    protected function getViewsFolder(){
        return $this->viewsFolder;
    }
    protected function setUserType($userType = 0) {
        $this->userType = $userType;
    }
    protected function getUserType() {
        return $this->userType;
    }

    public function index()
    {
        $this->checkAuth();


    }


    public function checkAuth(){
        if(!$this->auth->is_auth()) redirect($this->ctrl.'/login');

         // Ya está logueado....
         $redirectTo = $this->data->get('entrada');

         if($this->data->get('acceso') != $this->environmentProfile) {
             $redirectTo = $this->environmentProfile;
         }

         redirect($redirectTo);
         //
    }

    public function login()
    {

        if($this->auth->is_auth()) redirect($this->data->get('entrada'));

        $config = array (
            [ 'field' => 'sfid-login', 'label'=> 'SFID', 'rules' => 'trim|required', 'errors'=> [ 'required'=> '%s es requerido'] ],
            [ 'field' => 'password', 'label'=> 'Password', 'rules' => 'trim|required',  'errors'=> [ 'required'=> '%s es requerido']  ]
        );

        $this->form_validation->set_rules($config);

        // Comprobación ´basica...
        if ($this->form_validation->run() == true)
        {
            $this->form_validation->set_rules('sfid-login','SFID','callback_do_login', [ 'do_login' => 'SFID o Password incorrectos']);

            // Comprobación de usuario
            if($this->form_validation->run() == true) redirect($this->data->get('entrada'));


            // Comprobación de usuario por entorno...
            $this->form_validation->set_rules('sfid-login','SFID','callback_do_env_login', [ 'do_login' => 'SFID o Password incorrectos']);
            $this->form_validation->run();

            // Redirigir si procede
            $entorno = $this->session->userdata('entorno');
            if($entorno) redirect($entorno,'refresh');


        }

        $data['title'] = 'Login';

        /// Añadir el array data a la clase Data y devolver la unión de ambos objetos en formato array..
        $this->data->add($data);
        $data = $this->data->getData();
        /////
        $this->load->view($this->getViewsFolder().'/header',$data);

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view($this->getViewsFolder().'/login',$data);
        }
        $this->load->view($this->getViewsFolder().'/footer');
    }



    public function do_login() {

        $type = $this->getUserType();



        $this->load->model('user_model');
        $data = array(
            'sfid' => strtolower($this->input->post('sfid-login')),
            'password' => sha1($this->input->post('password')),
        );

        // Logueado para el tipo ...
        if($this->user_model->login_tipo($data, $type)) return true;

        //var_dump($data);
        //exit;

        /*// Redirigir al entorno adecuado al usuario logueado (si procede)...
        $response = $this->user_model->login_entorno($data);
        if($response['entorno'] && $response['entorno'] !== $this->environmentProfile) redirect($response['entorno'], "refresh");*/

        // False
        return false;
    }

    public function do_env_login()
    {

        $this->load->model('user_model');
        $data = array(
            'sfid' => strtolower($this->input->post('sfid-login')),
            'password' => sha1($this->input->post('password')),
        );
        // Redirigir al entorno adecuado al usuario logueado (si procede)...
        $response = $this->user_model->login_entorno($data);
        if($response['entorno']) return TRUE;

        // False
        return FALSE;
    }



    /**
     * Función que guarda en sesión el valor de los filtros del POST, al venir de un form de filtrado
     * @param $array_filtros
     */
    public function set_filtros($array_filtros)
    {
        $array_valores = NULL;
        if(is_array($array_filtros))
        {
            $array_valores = array();
            foreach ($array_filtros as $filter=>$value)
            {
                $valor_filter = $value;
                if(empty($value)) $valor_filter = $this->input->post($filter);

                $this->session->set_userdata($filter, $valor_filter);
                $array_valores[$filter] = $valor_filter;
            }
        }
        return $array_valores;
    }

    /**
     * Método que borra de la sesión, X variables, pasado sus nombres en un array
     * Si el parámetro es un array (de variables), lo recorremos y eliminamos de la sesión cualquier valor que tenga
     * la variable de sesión de ese nombre
     */
    public function delete_filtros($array_filtros,$array_excepciones=array())
    {
        if(is_array($array_filtros)){
            foreach($array_filtros as $key =>$filtro){
                if(!in_array($key,$array_excepciones)) {
                    $this->session->unset_userdata($key);
                }
            }
        }
    }



    /**
     * Recibe el array de filtros (campos del buscador/filtrador) y buscará su valor en la sesión, y cargará otro array
     * con los pares VARIABLE=>VALOR SESION.
     * @param $array_filtros
     * @return array|null
     */
    public function get_filtros($array_filtros){
        $array_session = NULL;

        if(is_array($array_filtros)){
            $array_session = array();
            foreach($array_filtros as $filter=>$value){

                if(!empty($value)){
                    $sess_filter = $value;
                }else {
                    $sess_filter = $this->session->userdata($filter);
                }
                $array_session[$filter] = (!empty($sess_filter)) ? $sess_filter : NULL;

            }
        }
        return $array_session;
    }



    public function set_orden($formulario)
    {

        $array_orden = array();
        $campo_orden = $this->input->post($formulario . '_campo_orden');
        $orden_campo = $this->input->post($formulario . '_orden_campo');

        $this->session->set_userdata('campo_orden', $campo_orden);
        $this->session->set_userdata('orden_campo', $orden_campo);

        $array_orden[$campo_orden]= $orden_campo;
//print_r($array_orden);
        return $array_orden;

    }

    public function get_orden()
    {
        $sess_campo_orden = $this->session->userdata('campo_orden');
        $sess_orden_campo = $this->session->userdata('orden_campo');
        $array_orden = NULL;

        if(!empty($sess_campo_orden)){
            $array_orden = array();
            if(!empty($sess_orden_campo)){
                $array_orden[$sess_campo_orden] = $sess_orden_campo;
            }else{
                $array_orden[$sess_campo_orden] = "ASC";
            }
        }
        return $array_orden;
    }



    public function get_multifiltro_post($nombre_campo){

        $resultado = array();
        foreach($this->input->post($nombre_campo) as $valor_post){
            $resultado[] = $valor_post;
        }
        return $resultado;
    }



    public function strip_html_tags($text)
    {
        $text = preg_replace(
            array(
                // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
                // Add line breaks before and after blocks
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ),
            $text);
        return strip_tags($text);
    }


}