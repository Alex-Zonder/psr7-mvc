<?php
namespace Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Исключение для возврата $response из устаревшего $this->return
 */
class RsponseReturnException extends \Exception
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
    /**
     * Отправить запрос обработчику маршрута
     */
    public static function dispatch(ServerRequestInterface $request, $handler): ResponseInterface
    {
        if (is_array($handler)) {
            if (! class_exists($handler[0])) {
                return new JsonResponse("No controller: " . $handler[0]);
            }
            $controller = new $handler[0]();
            $action = $handler[1];
            if (! method_exists($controller, $action)) {
                return new JsonResponse("No action: " . $action);
            }
            try {
                return $controller->$action($request);
            } catch(RsponseReturnException $e) {
                return $e->response;
            }
        } else {
            $callable = $handler;
            return $callable($request);
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
