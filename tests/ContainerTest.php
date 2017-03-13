<?php

use PHPUnit\Framework\TestCase;
use App\Container;

final class ContainerTest extends TestCase
{
    public function testInitialState()
    {
        $c = new Container([
            'initial' => function () {
                return 1;
            },
            'test' => function () {
              return 0;
            }
        ]);
        $this->assertEquals(1, $c['initial']);
        $this->assertEquals(0, $c->test);
    }

    public function testOffsetSet()
    {
        $c = new Container;
        $c->offsetSet('test', function () {
            return 1;
        });

        $this->assertEquals(1, $c['test']);
        $this->assertEquals(1, $c->test);

        $c->offsetSet(null, function () {
            return 2;
        });

        $this->assertEquals(2, $c[0]);
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
