<?php
namespace Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class MainController extends Controller
{
    public function indexAction(ServerRequestInterface $request)
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse('Hello: ' . $name);
    }

    public function aboutAction()
    {
        $this->return('About', 200, false);
    }
}
