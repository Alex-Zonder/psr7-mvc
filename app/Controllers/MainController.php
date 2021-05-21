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
        $body = "
            <hr><a href='/'>Главная</a>
            <br><a href='/about'>about</a>
            <br><a href='/test'>test</a>
            <br><a href='/blog/2'>blog</a>
            <br><a href='/admin'>admin</a>
            <br><a href='/pipe'>pipe</a>
            <br><a href='/easapr'>easapr</a>
        ";

        return new HtmlResponse('Hello: ' . $name . $body);
    }

    public function aboutAction()
    {
        $this->return('About: ' . $this->request['method'], 200, false);
    }
}
