<?php
set_include_path(dirname(__DIR__));
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;


### Initialization ###
$request = ServerRequestFactory::fromGlobals();

### Processing ###


### Action ###
$path = $request->getUri()->getPath();
$action = null;

if ($path == '/') {
    $action = function (ServerRequestInterface $request) {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        return new HtmlResponse('Hello: ' . $name);
    };
}
else if ($path === '/about') {
    $action = function () {
        return new HtmlResponse('About');
    };
}
else if (preg_match('#^/about/(?P<id>\d+)$#i', $path, $matches)) {
    $request = $request->withAttribute('id', $matches['id']);
    $action = function ($request) {
        return new HtmlResponse($request->getAttribute('id'));
    };
}

if ($action) {
    $response = $action($request);
} else {
    $response = new HtmlResponse('Not found', 404);
}

### Postprocessing ###
$response = $response->withHeader('PSR-7', 'MVC');
$response = $response->withHeader('Access-Control-Allow-Origin', '*');
// $response = $response->withStatus(209);

### Send Response ###
if ($response) {
    header("HTTP/1.0 {$response->getStatusCode()} {$response->getReasonPhrase()}");
    foreach ($response->getHeaders() as $name => $values) {
        header($name . ':' . implode(', ', $values));
    }
    echo $response->getBody();
}
?>