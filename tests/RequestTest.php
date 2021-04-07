<?php

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Stream;

class RequestTest extends TestCase
{
    public function testEmpty(): void
    {
        $request = new Request();

        self::assertEquals([], $request->getHeaders());
        self::assertSame('', $request->getBody()->getContents());
    }

    public function testHeader()
    {
        $request = (new Request())
            // ->withBody((new Stream(''))->write(''))
            ->withHeader($key = "Head-1", $value = "Val-1");

        // $this->assertSame('', $request->getBody());
        $this->assertSame([$value], $request->getHeader($key));
    }
}
