<?php

use PHPUnit\Framework\TestCase;
use App\App;
use App\Container;
use App\Exceptions\RouteNotFoundException;
use App\Exceptions\MethodNotAllowedException;

final class AppTest extends TestCase
{
    private $app;
    private $router;

    public function setUp()
    {
        $this->app = new App;
        $this->router = $this->app->getContainer()->router;
    }

    public function dataProvider()
    {
        return [
            ['/test1', 'test1', 'test1'],
            ['/test1', 'test2', 'test2'],
            ['/test2', function () {
            }, function () {
            }],
        ];
    }

    public function testGetContainer()
    {
        $this->assertInstanceOf(Container::class, $this->app->getContainer());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGet($uri, $cb, $expected)
    {
        $this->app->get($uri, $cb);
        $this->router->setPath($uri);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals($expected, $this->router->getResponse());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPost($uri, $cb, $expected)
    {
        $this->app->post($uri, $cb);
        $this->router->setPath($uri);
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals($expected, $this->router->getResponse());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testMap($uri, $cb, $expected)
    {
        $this->app->map($uri, $cb, ['POST', 'PUT']);
        $this->router->setPath($uri);
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertEquals($expected, $this->router->getResponse());
    }

    public function testRun()
    {
        $_SERVER['PATH_INFO'] = '/';
        $this->router->addRoute('/', function () {
            return 1;
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals(1, $this->app->run());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = '/nontest';
        $this->assertNull($this->app->run());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['PATH_INFO'] = '/';
        $this->assertNull($this->app->run());
    }

    public function testRunRouteNotFoundException()
    {
        $c = $this->app->getContainer();
        $c['routeNotFoundHandler'] = function ($c) {
            return function () {
                return 'routeNotFoundHandler';
            };
        };
        $this->assertEquals('routeNotFoundHandler', $this->app->run());
    }

    public function testRunMethodNotAllowedException()
    {
        $c = $this->app->getContainer();
        $c['methodNotAllowedHandler'] = function ($c) {
            return function () {
                return 'methodNotAllowedHandler';
            };
        };
        $this->router->addRoute('/', 1);
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('methodNotAllowedHandler', $this->app->run());
    }
}
