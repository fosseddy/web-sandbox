<?php

declare(strict_types=1);

const BASE_DIR = __DIR__ . "/..";
const CORE_DIR = BASE_DIR . "/../core";

function debug($val)
{
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
}

require_once CORE_DIR . "/web.php";
require_once CORE_DIR . "/database.php";

require_once path\from_base("home.php");
require_once path\from_base("books.php");
require_once path\from_base("authors.php");
require_once path\from_base("genres.php");
require_once path\from_base("book-instances.php");

$app = new web\App();
$app->ctx = [
    "db" => new database\Connection("localhost", "local_lib", "art", "qweqwe123")
];

$router = new web\Router();

$router->get("/", "home\handle_index");

$router->get("/books", "books\handle_index");
$router->get("/books/detail", "books\handle_detail", ["with_query_id"]);
$router->get("/books/create", "books\handle_create");
$router->post("/books/create", "books\handle_store");

$router->get("/authors", "authors\handle_index");
$router->get("/authors/detail", "authors\handle_detail", ["with_query_id"]);
$router->get("/authors/create", "authors\handle_create");
$router->post("/authors/create", "authors\handle_store");

$router->get("/genres", "genres\handle_index");
$router->get("/genres/detail", "genres\handle_detail", ["with_query_id"]);
$router->get("/genres/create", "genres\handle_create");
$router->post("/genres/create", "genres\handle_store");

$router->get("/book-instances", "book_instances\handle_index");
$router->get("/book-instances/detail", "book_instances\handle_detail", ["with_query_id"]);

$app->add_router($router);

try
{
    $app->handle_request();
}
catch (http\Not_Found $e)
{
    http_response_code(404);
    web\render_view("error", ["title" => "Page Not Found"]);
}
catch (Exception $e)
{
    http_response_code(500);
    web\render_view("error", [
        "title" => "Something went wrong and it is not your fault"
    ]);
}

function with_query_id()
{
    $id = $_GET["id"] ?? "";
    if (!$id) throw new http\Not_Found();
}
