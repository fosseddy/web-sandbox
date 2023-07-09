<?php

namespace Net;

use Exception;

// TODO(art): handle errors
// TODO(art): handle 404, 500, etc. events
// TODO(art): middleware
class Router
{
    public $routes = [];
    public $ctx = [];

    function get($uri, $handler)
    {
        $this->routes[$uri]["GET"] = $handler;
    }

    function post($uri, $handler)
    {
        $this->routes[$uri]["POST"] = $handler;
    }

    function resolve()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"])["path"];
        $method = $_SERVER["REQUEST_METHOD"];

        if (!array_key_exists($uri, $this->routes))
        {
            throw new Exception("route '$uri' does not exist");
        }

        $route = $this->routes[$uri];

        if (!array_key_exists($method, $route))
        {
            throw new Exception("route '$uri' does not support method '$method'");
        }

        $handler = $route[$method];

        try
        {
            $handler($this->ctx);
        }
        catch (Exception $e)
        {
            // TODO(art): handle errors
            error_log($e);
            echo "<pre>$e</pre>";
        }
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
    header("Location: {$url}");
}
