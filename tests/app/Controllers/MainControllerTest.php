<?php

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
// use Zend\Diactoros\Stream;

use Controllers\MainController;

class MainControllerTest extends TestCase
{
    public function testEmpty(): void
    {
        $request = new ServerRequest();
        $response = (new MainController())->indexAction($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertSame('Hello: Guest', $response->getBody()->getContents());
    }

    public function testWithName(): void
    {
        $request = (new ServerRequest())->withQueryParams(['name' => 'test']);
        $response = (new MainController())->indexAction($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertSame('Hello: test', $response->getBody()->getContents());
    }

    // public function testHeader()
    // {
    //     $request = (new ServerRequest())
    //         // ->withBody((new Stream(''))->write(''))
    //         ->withHeader($key = "Head-1", $value = "Val-1");

    //     // $this->assertSame('', $request->getBody());
    //     $this->assertSame([$value], $request->getHeader($key));
    // }
}
