<?php

// TODO(art): something for REST api?

namespace web;

require_once __DIR__ . "/path.php";
require_once __DIR__ . "/http.php";

use Exception;
use path, http;

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

        if (!$route) throw new http\Not_Found("route '$method $uri' does not exist");

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
