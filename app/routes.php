<?php
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;

$map->get('index', '/', ['\Controllers\MainController', 'indexAction']);
$map->get('about', '/about', ['\Controllers\MainController', 'aboutAction']);

$map->get('test', '/test', function ($request) { return new HtmlResponse('test', 403); });

$map->get('blog.read', '/blog/{id}', function ($request) {
    $id = (int) $request->getAttribute('id');
    $response = new Response();
    $response->getBody()->write("You asked for blog entry {$id}.");
    return $response;
});
