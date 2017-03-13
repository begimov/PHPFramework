<?php

namespace App;

class Router
{
    private $routes = array();

    private $path;

    public function setPath($path = '/')
    {
      $this->path = $path;
    }

    public function addRoute($uri, $cb)
    {
      $this->routes[$uri] = $cb;
    }

    public function getResponse()
    {
      return $this->routes[$this->path];
    }
}
