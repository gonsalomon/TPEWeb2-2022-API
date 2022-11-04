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
            $this->view->response("La tarea con el id $id no existe.", 404);
    }

    function insertComment($params = null)
    {
        $comment = $this->getData();

        if (empty($comment->comment)) {
            $this->view->response('Debe completar todos los datos antes de insertar.', 400);
        } else {
            $id = $this->model->insert($comment->comment, false);
        }
    }
}
