<?php
use Psr\Http\Message\ServerRequestInterface;
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

# test middleware
$map->get('admin', '/admin', function ($request) {
    $profiler = new \Core\Http\Middleware\ProfilerMiddleware();
    $auth = new \Core\Http\Middleware\BasicAuthMiddleware(['admin' => 'pass1']);
    return $profiler($request, function (ServerRequestInterface $request) use ($auth) {
        return $auth($request, function (ServerRequestInterface $request) {
            return new HtmlResponse('test: '.$request->getAttribute(\Core\Http\Middleware\BasicAuthMiddleware::ATTRIBUTE), 403);
        });
    });
});

# test pipeline
$map->get('pipe', '/pipe', function ($request) {
    $pipeline = new \Core\Http\Pipeline\Pipeline();

    $pipeline->pipe(new \Core\Http\Middleware\ProfilerMiddleware());
    $pipeline->pipe(new \Core\Http\Middleware\BasicAuthMiddleware(['admin' => 'pass1']));
    $pipeline->pipe(function (ServerRequestInterface $request) {
        return new HtmlResponse('Pipe. User: '.$request->getAttribute(\Core\Http\Middleware\BasicAuthMiddleware::ATTRIBUTE));
    });

    return $pipeline($request, new \Core\Http\Middleware\NotFoundHandler());
});
