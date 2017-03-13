<?php

use PHPUnit\Framework\TestCase;
use App\Router;
use App\Exceptions\MethodNotAllowedException;

final class RouterTest extends TestCase
{
    private $router;

    public function setUp()
    {
        $this->router = new Router;
    }

    public function testSetPathAndAddRoute()
    {
        $this->router = new Router;
        $this->router->setPath('/test');
        $this->router->addRoute('/test', function () {
            return 1;
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertInstanceOf(Closure::class, $this->router->getResponse());
    }

    public function testGetResponse()
    {
        $this->router->setPath('/test');
        $this->router->addRoute('/test', function () {
            return 1;
        });
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->expectException(MethodNotAllowedException::class);
        $this->router->getResponse();
    }
}
