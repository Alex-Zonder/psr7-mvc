<?php

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
// use Zend\Diactoros\Response\JsonResponse;

class ResponseTest extends TestCase
{
    public function testEmpty(): void
    {
        $response = new HtmlResponse($body = 'Body');

        self::assertEquals($body, $response->getBody()->getContents());
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('OK', $response->getReasonPhrase());
    }

    public function test404(): void
    {
        $response = new HtmlResponse($body = 'Not found', $status = 404);

        self::assertEquals($body, $response->getBody()->getContents());
        self::assertEquals($status, $response->getStatusCode());
        self::assertEquals('Not Found', $response->getReasonPhrase());
    }

    public function testHeaders(): void
    {
        $response = (new Response())
            ->withHeader($name = 'Head-1', $value = 'Val-1');

        self::assertEquals([$name => [$value]], $response->getHeaders());
        self::assertEquals($value, $response->getHeader($name)[0]);
    }
}