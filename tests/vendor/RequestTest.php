<?php

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
// use Zend\Diactoros\ServerRequestFactory;
// use Zend\Diactoros\Request;
// use Zend\Diactoros\Stream;

class RequestTest extends TestCase
{
    public function testEmpty(): void
    {
        $request = new ServerRequest();

        self::assertEquals([], $request->getQueryParams());
        self::assertEquals([], $request->getHeaders());
        self::assertSame('', $request->getBody()->getContents());
    }

    public function testHeader()
    {
        $request = (new ServerRequest())
            // ->withBody((new Stream(''))->write(''))
            ->withHeader($key = "Head-1", $value = "Val-1");

        // $this->assertSame('', $request->getBody());
        $this->assertSame([$value], $request->getHeader($key));
    }
}
