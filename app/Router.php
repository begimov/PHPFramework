<?php

namespace App;

use App\Exceptions\RouteNotFoundException;
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
        if (!isset($this->routes[$this->path])) {
            throw new RouteNotFoundException("No route for '{$this->path}' path");
        }
        if (!in_array($_SERVER['REQUEST_METHOD'], $this->methods[$this->path])) {
            throw new MethodNotAllowedException;
        }
        return $this->routes[$this->path];
    }
}
