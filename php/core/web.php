<?php

// TODO(art): something for REST api?

namespace web;

require_once __DIR__ . "/path.php";
require_once __DIR__ . "/http.php";

use Exception;
use path, http;

class App
{
    public $routes = [];
    public $ctx = [];

    function add_router(Router $r, string $namespace = ""): void
    {
        $this->routes = [...$this->routes, ...$r->routes];
    }

    function handle_request(): void
    {
        var_dump(parse_url($_SERVER["REQUEST_URI"]));exit;
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

class Router
{
    public $namespace = "";
    public $routes = [];

    function __construct(string $ns = "")
    {
        $this->namespace = $ns;
    }

    function add(string $method, string $uri, callable $handler,
                 array/*callable*/ $middleware = []): void
    {
        $this->routes[$uri][$method] = [
            "handler" => $handler,
            "middleware" => $middleware
        ];
    }

    function get(string $uri, callable $handler,
                 array/*callable*/ $middleware = []): void
    {
        $this->add("GET", $uri, $handler, $middleware);
    }

    function post(string $uri, callable $handler,
                  array/*callable*/ $middleware = []): void
    {
        $this->add("POST", $uri, $handler, $middleware);
    }
}

function render_view($path, $vars = [])
{
    extract($vars);
    require_once path\from_base("views", "$path.php");
}

function partial_view($path)
{
    return path\from_base("views", "$path.php");
}

function redirect($url)
{
    header("Location: $url");
}
