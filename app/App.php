<?php

namespace App;

class App
{
    private $container;

    public function __construct()
    {
        $this->container = new Container([
          'router' => function () {
              return new Router;
          }
        ]);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function get($uri, $cb)
    {
        $r = $this->container->router;
        $r->addRoute($uri, $cb);
    }

    public function run()
    {
        $r = $this->container->router;
        $r->setPath($_SERVER['PATH_INFO'] ?: '/');

        $res = $r->getResponse();
        return $this->process($res);
    }

    private function process($func)
    {
        return $func();
    }
}
