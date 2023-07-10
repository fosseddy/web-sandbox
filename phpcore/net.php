<?php

namespace Net;

use Exception;

// TODO(art): handle errors
// TODO(art): handle 404, 500, etc. events
// TODO(art): http errors
class Router
{
    public $routes = [];
    public $ctx = [];

    function add($method, $uri, $middlewares, $handler = null)
    {
        if (gettype($middlewares) === "string")
        {
            $handler = $middlewares;
            $middlewares = [];
        }

        $this->routes[$uri][$method] = [
            "handler" => $handler,
            "middlewares" => $middlewares,
        ];
    }

    function get($uri, $middlewares, $handler = null)
    {
        $this->add("GET", $uri, $middlewares, $handler);
    }

    function post($uri, $middlewares, $handler = null)
    {
        $this->add("POST", $uri, $middlewares, $handler);
    }

    function resolve()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"])["path"];
        $method = $_SERVER["REQUEST_METHOD"];

        if (!array_key_exists($uri, $this->routes)) throw new Exception("route '$uri' does not exist");

        $route = $this->routes[$uri];

        if (!array_key_exists($method, $route)) throw new Exception("route '$uri' does not support method '$method'");

        foreach ($route[$method]["middlewares"] as $it)
        {
            $it($this->ctx);
        }

        $route[$method]["handler"]($this->ctx);
    }
}

function render_view($path, $vars = [])
{
    extract($vars);
    require_once from_base("views", "$path.php");
}

function partial_view($path)
{
    return from_base("views", "$path.php");
}

function redirect($url)
{
    header("Location: $url");
}
