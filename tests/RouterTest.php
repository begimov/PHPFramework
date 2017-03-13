<?php

use PHPUnit\Framework\TestCase;
use App\Router;

final class RouterTest extends TestCase
{
    public function testRouter()
    {
        $r = new Router;
        $r->setPath('/test');

        $r->addRoute('/test', function () {
          return 1;
        });

        $this->assertEquals(function () {
          return 1;
        }, $r->getResponse());
    }

}
