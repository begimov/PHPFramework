<?php

use PHPUnit\Framework\TestCase;
use App\Container;

final class ContainerTest extends TestCase
{
    public function testOffsetSet()
    {
        $c = new Container;
        $c->offsetSet('test', function () {
            return 1;
        });

        $this->assertEquals(1, $c['test']);
        $this->assertEquals(1, $c->test);
    }

    public function testOffsetExists()
    {
        $c = new Container;
        $c->offsetSet('test', function () {
            return 1;
        });

        $this->assertEquals(true, $c->offsetExists('test'));
        $this->assertEquals(false, $c->offsetExists('nontest'));
    }

    public function testOffsetUnset()
    {
        $c = new Container;
        $c->offsetSet('test', function () {
            return 1;
        });

        $this->assertEquals(1, $c->test);
        $c->offsetUnset('test');
        $this->assertEquals(null, $c->test);
    }

    public function testOffsetGet()
    {
        $c = new Container;
        $c->offsetSet('test', function () {
            return 1;
        });

        $this->assertEquals(1, $c->offsetGet('test'));
        $this->assertEquals(null, $c->offsetGet('nontest'));
    }

    public function testHas()
    {
        $c = new Container;
        $c->offsetSet('test', function () {
            return 1;
        });

        $this->assertEquals(true, $c->has('test'));
        $this->assertEquals(false, $c->has('nontest'));
    }
}
