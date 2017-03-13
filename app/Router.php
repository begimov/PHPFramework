<?php

namespace App;

use App\Exceptions\MethodNotAllowedException;

class Router
{
    private $routes = array();

    private $methods = array();

    private $path;

    public function setPath($path = '/')
    {
      $this->path = $path;
    }

    public function addRoute($uri, $cb, array $methods =['GET'])
    {
      $this->routes[$uri] = $cb;
      $this->methods[$uri] = $methods;
    }

    public function getResponse()
    {
      if (!in_array($_SERVER['REQUEST_METHOD'], $this->methods[$this->path])) {
        throw new MethodNotAllowedException("Method Not Allowed", 1);

      }
      return $this->routes[$this->path];
    }
}
