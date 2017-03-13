<?php

use PHPUnit\Framework\TestCase;
use App\Container;

final class ContainerTest extends TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = new Container;
    }

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
        $this->container->offsetSet('test', function () {
            return 1;
        });
        $this->assertEquals(1, $this->container['test']);
        $this->assertEquals(1, $this->container->test);
        $this->container->offsetSet(null, function () {
            return 2;
        });

        $this->assertEquals(2, $this->container[0]);
    }

    public function testOffsetExists()
    {
        $this->container->offsetSet('test', 1);
        $this->assertTrue($this->container->offsetExists('test'));
        $this->assertFalse($this->container->offsetExists('nontest'));
    }

    public function testOffsetUnset()
    {
        $this->container->offsetSet('test', function () {
            return 1;
        });
        $this->assertEquals(1, $this->container->test);
        $this->container->offsetUnset('test');
        $this->assertNull($this->container->test);
    }

    public function testOffsetGet()
    {
        $this->container->offsetSet('test', function () {
            return 1;
        });
        $this->assertEquals(1, $this->container->offsetGet('test'));
        $this->assertNull($this->container->offsetGet('nontest'));
    }

    public function testHas()
    {
        $this->container->offsetSet('test', 1);
        $this->assertTrue($this->container->has('test'));
        $this->assertFalse($this->container->has('nontest'));
    }
}
