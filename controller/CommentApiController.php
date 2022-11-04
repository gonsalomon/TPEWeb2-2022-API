<?php
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
        $comments = $this->model->getAll();
        if (!empty($comments))
            $this->view->response($comments);
        else
            $this->view->response('No se encontraron comentarios.', 404);
    }

    function getComment($params = null)
    {
        $id = $params['id'];
        $comment = $this->model->get($id);
        if ($comment)
            $this->view->response($comment);
        else
            $this->view->response("El comentario con el id $id no existe.", 404);
    }

    function insertComment($params = null)
    {
        $id = $params['id'];
        //con esto capto si no me informan el mueble
        if (!isset($id)) {
            $this->view->response('Debe informar a qué mueble quiere insertar el comentario.', 400);
            return;
        }
        $commentToAdd = $this->getData();
        //con esto si el comentario está vacío
        if (empty($commentToAdd->comment)) {
            $this->view->response('No se puede insertar un comentario vacío.', 400);
        } else {
            $id = $this->model->insert($commentToAdd->comment, false);
        }
    }

    function deleteComment($params = null)
    {
        $id = $params['id'];
        if ($id)
            $this->model->delete($id);
        else {
            $this->view->response('Se debe proporcionar un comentario a borrar', 401);
            return;
        }
    }
}
