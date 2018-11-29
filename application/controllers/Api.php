<?php

class Api extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load_http_headers();

        $this->load->library(array('session'));
        $this->load->model('user_model');
        $this->load->library('auth');
    }


    public function index()
    {

    }
    private function check_origin()  {
        if(!isset($_SERVER['HTTP_ORIGIN'])) return '*';

        $allowed =  $this->config->item('allowed_origins');
        $origin = preg_replace("/http:\/\/|https:\/\//",'',$_SERVER['HTTP_ORIGIN']);
        if(in_array($origin, $allowed))
            return $_SERVER['HTTP_ORIGIN'];

        return NULL;
    }
    private function load_http_headers()
    {
        $origin = $this->check_origin();
        if($origin === NULL) {
            //header('Access-Control-Allow-Origin: *');
        }else{
            header('Access-Control-Allow-Origin: '.$origin);
        }
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Max-Age: 3600');
        header('Access-Control-Allow-Headers:  Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');

    }

    public function authenticate($sfid=NULL,$password=NULL)
    {
        // Petición JSON con propiedad { user { sfid: '', password:''} }
        $request = json_decode(trim(file_get_contents('php://input')), TRUE);
        $user = isset($request['user']) ? $request['user'] : NULL;

        // Intento de recuperación de campos por $_POST
        if(is_null($user)) {
            if(isset($request['sfid'])) {
                $user = array ('sfid' => $request['sfid'], 'password' => $request['password']);
            }
        }

        /** JSON $data */
        $data = array(
            'sfid' => strtolower($user['sfid']),
            'password' => sha1($user['password'])
        );

        // Intento de identificacion
        $response = $this->user_model->login_entorno($data);
        /*$isAuth = [
            1 => $this->auth->is_auth(1),
            9 => $this->auth->is_auth(9),
            10 => $this->auth->is_auth(10),
            11 => $this->auth->is_auth(11),
            12 => $this->auth->is_auth(12),
            13 => $this->auth->is_auth(13)
        ];*/
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
        echo json_encode(array('origin'=> $origin, 'response'=> $response));
        //echo json_encode(['response'=> $response,'request'=>$data['sfid'], 'session_name'=> session_id()]);

        exit;
    }


}
/* End of file api.php */
/* Location: ./application/controllers/api.php */