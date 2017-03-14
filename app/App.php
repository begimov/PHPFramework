<?php

namespace App;

use App\Exceptions\RouteNotFoundException;
use App\Exceptions\MethodNotAllowedException;
use App\Response;

class App
{
    private $container;

    public function __construct()
    {
        $this->container = new Container([
          'router' => function () {
              return new Router;
          },
          'response' => function () {
              return new Response;
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
        $r->addRoute($uri, $cb, ['GET']);
    }

    public function post($uri, $cb)
    {
        $r = $this->container->router;
        $r->addRoute($uri, $cb, ['POST']);
    }

    public function map($uri, $cb, array $methods = ['GET'])
    {
        $r = $this->container->router;
        $r->addRoute($uri, $cb, $methods);
    }

    public function run()
    {
        $r = $this->container->router;
        $r->setPath(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');

        try {
            $res = $r->getResponse();
        } catch (RouteNotFoundException $e) {
            if ($this->container->has('routeNotFoundHandler')) {
                $res = $this->container->routeNotFoundHandler;
            } else {
                // TODO: msg to developer about configuring handler?
                return;
            }
        } catch (MethodNotAllowedException $e) {
            if ($this->container->has('methodNotAllowedHandler')) {
                $res = $this->container->methodNotAllowedHandler;
            } else {
                // TODO: msg to developer about configuring handler?
                return;
            }
        }
        return $this->respond($this->process($res));
    }

    private function process($func)
    {
        $response = $this->container->response;
        if (is_array($func)) {
            return !is_object($func[0])
                ? call_user_func([new $func[0], $func[1]], $response)
                : call_user_func($func, $response);
        }
        return $func($response);
    }

    private function respond($res)
    {
        if (!($res instanceof Response)) {
            echo $res;
            return;
        }
        echo $res->getBody();
    }
}
