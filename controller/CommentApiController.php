<?php
require_once 'controller/ApiController.php';
require_once 'model/ApiModel.php';
require_once 'view/ApiView.php';
require_once 'helpers/AuthAPIHelper.php';

class CommentApiController extends ApiController
{
    private $helper;
    function __construct()
    {
        parent::__construct();
        $this->helper = new AuthAPIHelper();
    }

    function getComments($params = null)
    {
        if (isset($_GET['sortBy'])) {
            if (isset($_GET['sortBy']) && isset($_GET['order'])) {
                $comments = $this->model->getAll($_GET['sortBy'], $_GET['order']);
                if (!empty($comments))
                    $this->view->response($comments);
                else
                    $this->view->response('No se encontraron comentarios.', 404);
            } else {
                $this->view->response('Envíe todos los parámetros requeridos.', 400);
            }
        } else {
            $comments = $this->model->getAll();
            if (!empty($comments))
                $this->view->response($comments);
            else
                $this->view->response('No se encontraron comentarios.', 404);
        }
    }

    function getComment($params = null)
    {
        $id = $params[':ID'];
        $comment = $this->model->get($id);
        if ($comment)
            $this->view->response($comment);
        else
            $this->view->response("El comentario con el id $id no existe.", 404);
    }

    function getMuebleComments($params = null)
    {
        $id = $params[':ID'];
        $mueble = $this->model->getFromMueble($id);
        if ($mueble) {
            $this->view->response($mueble);
        } else {
            $this->view->response("El mueble con el id $id no tiene comentarios.", 404);
        }
    }

    function insertComment($params = null)
    {
        if ($this->helper->checkLoggedIn()) {
            $commentToAdd = $this->getData();
            //con esto reviso si el comentario está vacío o no me informan a qué mueble pertenece
            if (empty($commentToAdd->comment)) {
                $this->view->response('No se puede insertar un comentario vacío.', 400);
            } else if (!isset($commentToAdd->id_mueble) || !is_numeric($commentToAdd->id_mueble)) {
                $this->view->response('Necesita indicar a qué id_mueble pertenece este comentario, consulte /info para obtener las id asociadas a cada mueble', 400);
            } else {
                $success = $this->model->insert($commentToAdd->comment, $commentToAdd->id_mueble);
                $this->view->response($success);
            }
        } else {
            $this->view->response('No autorizado. Solicite JWT mediante /auth.', 401);
        }
    }

    private function getInfo($showInfo)
    {
        $req = $this->model->getInfo();
        if (isset($showInfo)) {
            $this->view->response($req);
        } else {
            //editComment pide getInfo(false), con lo que showInfo está seteado
            return $req;
        }
    }

    function editComment($params = null)
    {
        if ($this->helper->checkLoggedIn()) {
            $id = $params[':ID'];
            if (isset($id) && is_numeric($id)) {
                $commentToAdd = $this->getData();
                if (empty($commentToAdd->comment))
                    $this->view->response('No se puede insertar un comentario vacío', 400);
                else {
                    //para checkear que un id_mueble sea válido, debo revisar si se encuentra entre los muebles existentes
                    $arr = $this->getInfo(false);
                    $contains = false;
                    foreach ($arr as $id_indiv) {
                        if ($id_indiv['id_mueble'] === $commentToAdd->id_mueble)
                            $contains = true;
                    }
                    if ($contains) {
                        //recién ahora puedo editar el comentario
                        $success = $this->model->edit($id, $commentToAdd->comment, $commentToAdd->id_mueble);
                        $this->view->response($success);
                    } else
                        $this->view->response('Debe indicar un id_mueble válido, vea cuáles en /info', 400);
                }
            }
        } else {
            $this->view->response('No autorizado. Solicite JWT mediante /auth.', 401);
        }
    }

    function deleteComment($params = null)
    {
        $id = $params[':ID'];
        if (!isset($id))
            $this->view->response('Se debe proporcionar un comentario a borrar', 401);
        else
            $this->view->response($this->model->delete($id));
    }
}
