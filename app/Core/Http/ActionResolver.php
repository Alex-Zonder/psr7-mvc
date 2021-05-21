<?php

namespace Core\Http;

class ActionResolver
{
    public function resolve($handler)
    {
        // return \is_string($handler) ? new $handler : $handler;

        if (is_string($handler)) {
            return new $handler;
        }
        else if (is_callable($handler) && !is_array($handler)) {
            return $handler;
        }
        else if (is_array($handler)) {
            return function ($request) use ($handler) {
                $controller = new $handler[0]();
                $action = $handler[1];
                return $controller->$action($request);
            };
        }
    }
}