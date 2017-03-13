<?php

use PHPUnit\Framework\TestCase;
use App\App;
use App\Container;

final class AppTest extends TestCase
{
    private $app;
    private $router;

    public function setUp()
    {
        $this->app = new App;
        $this->router = $this->app->getContainer()->router;
    }

    public function testGetContainer()
    {
        $this->assertInstanceOf(Container::class, $this->app->getContainer());
    }

    public function testGet()
    {
        $this->app->get('/test1', 'test1');
        $this->router->setPath('/test1');
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('test1', $this->router->getResponse());
    }

    public function testPost()
    {
        $this->app->post('/test1', 'test1');
        $this->router->setPath('/test1');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('test1', $this->router->getResponse());
    }

    public function testMap()
    {
        $this->app->map('/test1', 'test1', ['POST', 'PUT']);
        $this->router->setPath('/test1');
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertEquals('test1', $this->router->getResponse());
    }

    public function testRun()
    {
        $_SERVER['PATH_INFO'] = '';
        $this->router->addRoute('/', function () {
            return 1;
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->app->run();
        $this->assertInstanceOf(Closure::class, $this->router->getResponse());
    }
}
