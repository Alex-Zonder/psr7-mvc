<?php
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Load Old conf file
 */
$routes = require 'app/routes.old.php';
foreach ($routes as $key => $route) {
    $map->get(
        $key,
        "/{$key}",
        [
            "\\Controllers\\{$route['controller']}Controller",
            "{$route['action']}Action"
        ]
    )->allows(['POST', 'PATCH', 'PUT', 'DELETE']);
}

/**
 * Add routes
 */
$map->get('index', '/', ['\Controllers\MainController', 'indexAction']);
$map->post('about', '/about', ['\Controllers\MainController', 'aboutAction'])->allows(['GET', 'PATCH', 'PUT']);

$map->get('test', '/test', function () { return new HtmlResponse('test', 403); });

$map->get('blog.read', '/blog/{id}', function ($request) {
    $id = (int) $request->getAttribute('id');
    $response = new Response();
    $response->getBody()->write("You asked for blog entry {$id}.");
    return $response;
});
