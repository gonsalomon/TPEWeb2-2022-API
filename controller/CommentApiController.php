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
        //1- hay alguno de los campos pertenecientes al ordenamiento? (un campo por el que ordenar, y si el orden es ASC/DESC)
        if (isset($_GET['sortBy']) || isset($_GET['order'])) {
            //1.1- están todos?
            if (isset($_GET['sortBy']) && isset($_GET['order'])) {
                //1.2- son válidos los datos? tengo que checkear dato por dato
                if ($_GET['order'] == 'asc' || $_GET['order'] == 'ASC' || $_GET['order'] == 'desc' || $_GET['order'] == 'DESC') {
                    //order pasó
                    if (is_string($_GET['sortBy']) && in_array($_GET['sortBy'], $this->model->getTableFields())) {
                        //sortBy pasó: mando datos al modelo
                        $comments = $this->model->getAll($_GET['sortBy'], $_GET['order'], null, null, null, null, null);
                        if (isset($comments) && !empty($comments))
                            $this->view->response($comments);
                        else
                            $this->view->response('No se encontraron comentarios.', 404);
                    } else
                        $this->view->response('El campo por el que se quiere ordenar no está bien escrito.', 400);
                } else
                    $this->view->response('El orden informado debe ser "asc", "ASC", "desc" o "DESC".', 400);
            } else {
                $this->view->response('Debe enviar todos los datos requeridos (sortBy, order).', 400);
            }
        }
        //2- quiero paginar? (necesito saber cuántos elementos por página, la página indicada es para el funcionamiento interno)
        else if (isset($_GET['size'])) {
            //2.1- es un número?
            if (is_numeric($_GET['size'])) {
                $size = $_GET['size'];
                for ($i = 0; $i < count($this->model->getAll()) / $size; $i++) {
                    //al desconocer el funcionamiento del paginado de php, no me quedó otra opción que traer página por página
                    $pages[$i] = $this->model->getAll(null, null, $i, intval($size), null, null, null);
                }
                if (!empty($pages))
                    $this->view->response($pages);
                else
                    $this->view->response('No se encontraron comentarios.', 404);
            } else
                $this->view->response('Envíe un número de comentarios por página.', 400);
        }
        //3- quiero filtrar?
        else if (isset($_GET['filterBy']) || isset($_GET['value']) || isset($_GET['cond'])) {
            //3.1- están TODOS?
            if (isset($_GET['filterBy']) && isset($_GET['value']) && isset($_GET['cond'])) {
                //3.2- son válidos los datos? tengo que checkear dato por dato
                if (is_string($_GET['filterBy']) && in_array($_GET['filterBy'], $this->model->getTableFields())) {
                    //filterBy pasó
                    if (is_string($_GET['value']) || is_numeric($_GET['value'])) {
                        //value pasó
                        if ($_GET['cond'] == 'V' || $_GET['cond'] == 'v' || $_GET['cond'] == 'F' || $_GET['cond'] == 'f') {
                            //cond pasó: mando datos al modelo
                            $comments = $this->model->getAll(null, null, null, null, $_GET['filterBy'], $_GET['value'], $_GET['cond']);
                        } else {
                            //cond no pasó
                            $this->view->response('cond debe ser V / v (que incluya a value) o F / f (que no lo haga), intente nuevamente.', 400);
                        }
                    } else {
                        //value no pasó
                        $this->view->response('value debe ser un string o un número que se busque incluir o excluir del resultado, intente nuevamente.', 400);
                    }
                } else {
                    //filterBy no pasó
                    $this->view->response('filterBy debe ser un string de uno de los campos solicitables, intente nuevamente.', 400);
                }
                if (!empty($comments))
                    //hay resultados :)
                    $this->view->response($comments);
                else
                    //no los hay :(
                    $this->view->response('No se encontraron comentarios.', 404);
            } else
                //no están todos los datos
                $this->view->response('Debe enviar todos los datos requeridos (filterBy, value, cond).', 400);
        }
        //si no hay ningún campo informado, es un GET all común y corriente
        else {
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
        if (isset($params[':ID'])) {
            $id = $params[':ID'];
            $mueble = $this->model->getFromMueble($id);
            if ($mueble) {
                $this->view->response($mueble);
            } else {
                $this->view->response("El mueble con el id $id no tiene comentarios.", 404);
            }
        } else {
            $this->view->response("Debe informar de qué mueble desea obtener comentarios.", 400);
        }
    }

    function insertComment($params = null)
    {
        //comentar líneas 133, 144-146 para anular JWT y probar POST
        if ($this->helper->checkLoggedIn()) {
            $commentToAdd = $this->getData();
            //con esto reviso si el comentario está vacío o no me informan a qué mueble pertenece
            if (empty($commentToAdd->comment)) {
                $this->view->response('No se puede insertar un comentario vacío.', 400);
            } else if (!isset($commentToAdd->id_mueble) || !is_numeric($commentToAdd->id_mueble)) {
                $this->view->response('Necesita indicar a qué id_mueble pertenece este comentario, consulte /info para obtener las id asociadas a cada mueble', 400);
            } else {
                $success = $this->model->insert($commentToAdd->comment, $commentToAdd->id_mueble);
                $this->view->response($success, 201);
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
        //comentar líneas 163, 185-187 para anular JWT y probar PUT
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
