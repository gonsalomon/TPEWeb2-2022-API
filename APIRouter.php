<?php
require_once 'libs/router.php';
require_once 'controller/CommentApiController.php';
require_once 'controller/AuthController.php';

$router = new Router();

$router->addRoute('comments', 'GET', 'CommentApiController', 'getComments');
$router->addRoute('comments/:ID', 'GET', 'CommentApiController', 'getComment');
$router->addRoute('comments', 'POST', 'CommentApiController', 'addComment');
$router->addRoute('comments/:ID', 'DELETE', 'CommentApiController', 'deleteComment');

$router->addRoute("auth/token", 'GET', 'AuthController', 'getToken');

$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
