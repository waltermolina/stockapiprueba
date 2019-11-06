<?php
namespace App\Lib;

class Response
{
    public $result = null;
    public $response = false;
    public $message = 'Pasaron cosas!';
    public $href = null;
    public $filter = null;
    public $function = "hola";

    public function SetResponse($response, $m = '')
    {
        $this->response = $response;
        $this->message = $m;

        if (!$response && $m = '') {
            $this->response = 'Veníamos bien pero ¡pasaron cosas!';
        }

    }
}
