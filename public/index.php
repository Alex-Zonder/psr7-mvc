<?php
set_include_path(dirname(__DIR__));
require 'vendor/autoload.php';

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\JsonResponse;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Zend\Diactoros\Response\HtmlResponse;


### Get Request ###
$request = ServerRequestFactory::fromGlobals();



### Action ###
$name = $request->getQueryParams()['name'] ?? 'Guest';
$body = 'Hello: ' . $name;
// echo $body;

$response = (new JsonResponse($body))
    ->withHeader('Hello', 'Test')
    ->withStatus(299);



### Send Response ###
if ($response) {
    header("HTTP/1.0 {$response->getStatusCode()} {$response->getReasonPhrase()}");
    foreach ($response->getHeaders() as $name => $values) {
        header($name . ':' . implode(', ', $values));
    }
    echo $response->getBody();
}
?>