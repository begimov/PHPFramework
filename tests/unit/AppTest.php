<?php

use PHPUnit\Framework\TestCase;
use App\App;
use App\Container;
use App\Response;
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
            return 'Test';
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->expectOutputString('Test');
        $this->app->run();

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
        $this->expectOutputString('routeNotFoundHandler');
        $this->app->run();
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
        $this->expectOutputString('methodNotAllowedHandler');
        $this->app->run();
    }

    public function testProcess()
    {
        $stub = $this->createMock(App::class);
        $stub->method('getContainer')
             ->willReturn('test');
        $this->app->get('/', [$stub, 'getContainer']);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->expectOutputString('test');
        $this->app->run();
    }

    /**
     * @runInSeparateProcess
     */
    public function testRespond()
    {
        $this->app->get('/', function ($res) {
            return $res->setBody('');
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER["SERVER_PROTOCOL"] = 'HTTP/1.1';
        $this->app->run();
        $this->assertFalse(!!$this->getActualOutput());
    }

    /**
     * @runInSeparateProcess
     */
    public function testRespondWithHeaders()
    {
        $this->app->get('/', function ($res) {
            return $res->setBody('')->withHeader('Content-Type', 'text/plain');
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER["SERVER_PROTOCOL"] = 'HTTP/1.1';
        $this->app->run();
        $this->assertFalse(!!$this->getActualOutput());
    }
}
