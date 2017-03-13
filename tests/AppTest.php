<?php

use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
    public function testGetContainer()
    {
        $app = new App\App;
        $this->assertInstanceOf(
            App\Container::class,
            $app->getContainer()
        );
    }
}
