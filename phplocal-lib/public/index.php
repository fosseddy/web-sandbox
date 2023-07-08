<?php

function from_base(...$path)
{
    return join(DIRECTORY_SEPARATOR, [__DIR__, "..", ...$path]);
}

function debug($val)
{
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
}

require_once from_base("core/net.php");
require_once from_base("core/database.php");

require_once from_base("home.php");
require_once from_base("books.php");
require_once from_base("authors.php");
require_once from_base("genres.php");
require_once from_base("book-instances.php");

$router = new Net\Router();
$router->ctx = [
    "pdo" => Database\connect("localhost", "local_lib", "art", "qweqwe123")
];

$router->get("/", "Home\handle_index");

$router->get("/books", "Books\handle_index");
$router->get("/books/detail", "Books\handle_detail");

$router->get("/authors", "Authors\handle_index");
$router->get("/authors/detail", "Authors\handle_detail");

$router->get("/genres", "Genres\handle_index");
$router->get("/genres/detail", "Genres\handle_detail");
$router->get("/genres/create", "Genres\handle_create");
$router->post("/genres/create", "Genres\handle_store");

$router->get("/book-instances", "Book_Instances\handle_index");
$router->get("/book-instances/detail", "Book_Instances\handle_detail");

$router->resolve();
