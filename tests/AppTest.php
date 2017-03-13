<?php

use PHPUnit\Framework\TestCase;
use App\App;
use App\Container;

final class AppTest extends TestCase
{
    public function testGetContainer()
    {
        $app = new App;
        $this->assertInstanceOf(
            Container::class,
            $app->getContainer()
        );
    }

    public function testGet()
    {
        $app = new App;
        $app->get('/test1', 'test1');
        $r = $app->getContainer()->router;

        $r->setPath('/test1');

        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->assertEquals('test1', $r->getResponse());
    }

    public function testRun()
    {
        $app = new App;

        $_SERVER['PATH_INFO'] = '';
        $r = $app->getContainer()->router;
        $r->addRoute('/', function () {
            return 1;
        });

        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app->run();

        $this->assertInstanceOf(
            Closure::class,
            $r->getResponse()
        );
    }
}
