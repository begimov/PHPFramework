<?php

use PHPUnit\Framework\TestCase;
use App\Router;
use App\Exceptions\RouteNotFoundException;
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
        $this->router->addRoute('/test', 1);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals(1, $this->router->getResponse());
    }

    public function testGetResponseMethodNotAllowedException()
    {
        $this->router->setPath('/test');
        $this->router->addRoute('/test', 1);
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->expectException(MethodNotAllowedException::class);
        $this->router->getResponse();
    }

    public function testGetResponseRouteNotFoundException()
    {
        $this->router->setPath('/nontest');
        $this->expectException(RouteNotFoundException::class);
        $this->router->getResponse();
    }
}
