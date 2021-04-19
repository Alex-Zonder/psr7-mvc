<?php
namespace Controllers;

use Exception;
class RsponseReturnException extends Exception
{
    public $response;
    public function __construct($response)
    {
        parent::__construct();
        $this->response = $response;
    }
}

abstract class Controller
{
    public function return($body, int $code = 200, bool $json = true)
    {
        $class = '\\Zend\\Diactoros\\Response\\' . ($json ? 'JsonResponse' : 'HtmlResponse');

        throw new RsponseReturnException(new $class($body, $code));
    }
}
