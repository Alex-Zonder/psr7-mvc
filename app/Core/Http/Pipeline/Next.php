<?php

namespace Core\Http\Pipeline;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Next
{
    private $default;
    private $queue;

    public function __construct(\SplQueue $queue, callable $default)
    {
        $this->queue = $queue;
        $this->default = $default;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request);
        }

        $current = $this->queue->dequeue();

        // PHP 7.0
        // return $current($request, function (ServerRequestInterface $request) {
        //     return $this($request);
        // });
        // PHP 7.4
        return $current($request, fn (ServerRequestInterface $request) => $this($request));
    }
}