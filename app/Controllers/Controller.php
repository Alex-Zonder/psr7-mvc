<?php
namespace Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

use Exception;
class RsponseReturnException extends Exception
{
    public $response;
    public function __construct($response)
    {
        parent::__construct('Response');
        $this->response = $response;
    }
}

abstract class Controller
{
    public function return($body, int $code = 200, bool $json = true)
    {
        if ($json) {
            $response = new JsonResponse($body, $code);
        } else {
            $response = new HtmlResponse($body, $code);
        }

        throw new RsponseReturnException($response);
    }
}
