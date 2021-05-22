<?php
namespace Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Исключение для возврата $response из устаревшего $this->return
 * @deprecated
 */
class RsponseReturnException extends \Exception
{
    public $response;
    public function __construct($response)
    {
        $this->response = $response;
        parent::__construct();
    }
}

abstract class Controller
{
    /**
     * @var array
     * @deprecated
     */
    public ?array $request = null;
    public function __construct()
    {
        // Deprecated !!!
        if (isset($_SERVER['REQUEST_URI'])) {
            $this->request = [
                'params' => $_REQUEST,
                'uri' => explode('?', trim($_SERVER['REQUEST_URI'], '?'))[0],
                'method' => $_SERVER['REQUEST_METHOD'],
                'body' => json_decode(file_get_contents('php://input'), true),
            ];
        }
    }

    /**
     * Возвращение ответа - УСТАРЕЛО!!!
     * Вместо этогу нужно применять:
     * return new HtmlResponse($body);
     * или return new JsonResponse($body);
     * @deprecated
     */
    public function return($body, int $code = 200, bool $json = true)
    {
        $class = '\\Zend\\Diactoros\\Response\\' . ($json ? 'JsonResponse' : 'HtmlResponse');

        throw new RsponseReturnException(new $class($body, $code));
    }
}
