<?php

namespace Core\Http\Router;

use Aura\Router\RouterContainer;
use Aura\Router\Exception\RouteNotFound;
use Psr\Http\Message\ServerRequestInterface;
use Core\Http\Router\Exception\RequestNotMatchedException;
use Core\Http\Router\Exception\RouteNotFoundException;

class AuraRouterAdapter implements Router
{
    private $aura;

    public function __construct(RouterContainer $aura)
    {
        $this->aura = $aura;
    }

    /**
     * @param ServerRequestInterface $request
     * @throws RequestNotMatchedException
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result
    {
        $matcher = $this->aura->getMatcher();
        if ($route = $matcher->match($request)) {
            return new Result($route->name, $route->handler, $route->attributes);
        }

        throw new RequestNotMatchedException($request);
    }

    /**
     * @param string $name
     * @param array $params = []
     * @throws RouteNotFoundException
     * @return string
     */
    public function generate(string $name, array $params = []): string
    {
        $generator = $this->aura->getGenerator();
        try {
            return $generator->generate($name, $params);
        } catch (RouteNotFound $e) {
            throw new RouteNotFoundException($name, $params);
        }
    }
}