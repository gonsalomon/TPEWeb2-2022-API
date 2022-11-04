<?php

abstract class ApiController
{
    protected $model;
    protected $view;

    private $data;

    function __construct()
    {
        $this->model = new ApiModel();
        $this->view = new ApiView();
        $this->data = file_get_contents('php://input');
    }

    function getData()
    {
        return json_decode($this->data);
    }
}
