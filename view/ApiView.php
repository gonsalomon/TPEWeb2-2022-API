<?php

class ApiView
{
    function response($data, $status = 200)
    {
        header('Content-Type: application/json');
        header('HTTP/1.1' . $status . ' ' . $this->_requestStatus($status));
        echo json_encode($data);
    }

    private function _requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal Server Error'
        );
        return (isset($status[$code])) ? $status[$code] : $status[500];
    }
}
