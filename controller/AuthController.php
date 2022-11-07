<?php
require_once 'model/ApiModel.php';
require_once 'view/ApiView.php';
require_once 'helpers/AuthAPIHelper.php';

class AuthController extends ApiController
{
    private $helper;
    protected $view;
    function __construct()
    {
        $this->helper = new AuthAPIHelper();
        $this->view = new ApiView();
    }

    function getToken($params = null)
    {
        $basic = $this->helper->getAuthHeader();
        if (empty($basic)) {
            $this->view->response('No autorizado', 401);
            return;
        }
        $basic = explode(' ', $basic);
        if ($basic[0] != 'Basic') {
            $this->view->response('La autenticación debe ser Basic', 401);
            return;
        }
        $userpass = base64_decode($basic[1]);
        $userpass = explode(":", $userpass);
        $user = $userpass[0];
        $pass = $userpass[1];
        if ($user === 'admin' && $pass === 'admin') {
            $header = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );
            $payload = array(
                'id' => 1,
                'name' => 'admin',
                'exp' => time() + 3600
            );
            $header = base64url_encode(json_encode($header));
            $payload = base64url_encode(json_encode($payload));
            $signature = hash_hmac('SHA256', "$header.$payload", 'Clave1234', true);
            $token = "$header.$payload.$signature";
            $this->view->response($token);
        } else {
            $this->view->response('No autorizado', 401);
        }
    }
}
