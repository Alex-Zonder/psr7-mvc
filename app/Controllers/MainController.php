<?php
namespace Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class MainController extends Controller
{
    public function indexAction(ServerRequestInterface $request)
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        // return new HtmlResponse('Hello: ' . $name);
        $this->return('Hello: ' . $name, 200, false);
    }
}
