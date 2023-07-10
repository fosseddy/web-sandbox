<?php

// TODO(art): http errors
// TODO(art): when route handler throws what should happen?

namespace Net;

require_once __DIR__ . "/path.php";

use Exception;
use Path;

class Router
{
    public $routes = [];
    public $ctx = [];

    function add($method, $uri, $handler, $middleware = [])
    {
        $this->routes[$uri][$method] = [
            "handler" => $handler,
            "middleware" => $middleware
        ];
    }

    function get($uri, $handler, $middleware = [])
    {
        $this->add("GET", $uri, $handler, $middleware);
    }

    function post($uri, $handler, $middleware = [])
    {
        $this->add("POST", $uri, $handler, $middleware);
    }

    function resolve()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"])["path"];
        $method = $_SERVER["REQUEST_METHOD"];

        $route = $this->routes[$uri][$method] ?? null;

        if (!$route) throw new Exception("route '$method $uri' does not exist");

        foreach ($route["middleware"] as $it)
        {
            $it($this->ctx);
        }

        $route["handler"]($this->ctx);
    }
}

function render_view($path, $vars = [])
{
    extract($vars);
    require_once Path\from_base("views", "$path.php");
}

function partial_view($path)
{
    return Path\from_base("views", "$path.php");
}

function redirect($url)
{
    header("Location: $url");
}
