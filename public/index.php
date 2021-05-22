<?php
set_include_path(dirname(__DIR__));
require 'vendor/autoload.php';


### Initialization ###
$routerContainer = new Aura\Router\RouterContainer();
$map = $routerContainer->getMap();

// add a route to the map, and a handler for it
require 'app/routes.php';

$router = new \Core\Http\Router\AuraRouterAdapter($routerContainer);
$resolver = new \Core\Http\ActionResolver;

### Running ###
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $key => $val) {
        $request = $request->withAttribute($key, $val);
    }
    $action = $resolver->resolve($result->getHandler());
    $response = $action($request);
} catch (\Core\Http\Router\Exception\RequestNotMatchedException $e) {
    $handler = new \Core\Http\Middleware\NotFoundHandler();
    $response = $handler($request);
} catch (\Controllers\RsponseReturnException $e) {
    $response = $e->response;
}


### Postprocessing ###
$response = $response->withHeader('PSR-7', 'MVC');
$response = $response->withHeader('Access-Control-Allow-Origin', '*');
// $response = $response->withStatus(209);


### Emit the response ###
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
http_response_code($response->getStatusCode());
echo $response->getBody();