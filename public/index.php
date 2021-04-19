<?php
// set up composer autoloader
set_include_path(dirname(__DIR__));
require 'vendor/autoload.php';

// create a server request object
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

// create the router container and get the routing map
$routerContainer = new Aura\Router\RouterContainer();
$map = $routerContainer->getMap();

// add a route to the map, and a handler for it
require 'app/routes.php';

// get the route matcher from the container ...
$matcher = $routerContainer->getMatcher();

// .. and try to match the request to a route.
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
if (is_array($route->handler)) {
    $class = $route->handler[0];
    $method = $route->handler[1];
    try {
        $response = (new $class())->$method($request);
    } catch(\Controllers\RsponseReturnException $e) {
        $response = $e->response;
    }
} else {
    $callable = $route->handler;
    $response = $callable($request);
}

### Postprocessing ###
$response = $response->withHeader('PSR-7', 'MVC');
$response = $response->withHeader('Access-Control-Allow-Origin', '*');
// $response = $response->withStatus(209);

// emit the response
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
http_response_code($response->getStatusCode());
echo $response->getBody();