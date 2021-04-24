<?php
set_include_path(dirname(__DIR__));
require 'vendor/autoload.php';


### Initialization ###

// create the router container and get the routing map
$routerContainer = new Aura\Router\RouterContainer();
$map = $routerContainer->getMap();

// add a route to the map, and a handler for it
require 'app/routes.php';


### Running ###
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);
if (! $route) {
    echo "No route found for the request.";
    exit;
}

// add route attributes to the request
foreach ($route->attributes as $key => $val) {
    $request = $request->withAttribute($key, $val);
}

// dispatch the request to the route handler.
// (consider using https://github.com/auraphp/Aura.Dispatcher
// in place of the one callable below.)
$response = Controllers\Controller::dispatch($request, $route->handler);


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