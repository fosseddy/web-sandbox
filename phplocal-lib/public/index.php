<?php

const BASE_DIR = __DIR__ . "/..";

function debug($val)
{
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
}

require_once BASE_DIR . "/../phpcore/net.php";
require_once Path\from_base("../phpcore/database.php");

require_once Path\from_base("home.php");
require_once Path\from_base("books.php");
require_once Path\from_base("authors.php");
require_once Path\from_base("genres.php");
require_once Path\from_base("book-instances.php");

$router = new Net\Router();
$router->ctx = [
    "db" => new Database\Connection("localhost", "local_lib", "art", "qweqwe123")
];

$router->get("/", "Home\handle_index");

$router->get("/books", "Books\handle_index");
$router->get("/books/detail", "Books\handle_detail", ["with_query_id"]);
$router->get("/books/create", "Books\handle_create");
$router->post("/books/create", "Books\handle_store");

$router->get("/authors", "Authors\handle_index");
$router->get("/authors/detail", "Authors\handle_detail", ["with_query_id"]);
$router->get("/authors/create", "Authors\handle_create");
$router->post("/authors/create", "Authors\handle_store");

$router->get("/genres", "Genres\handle_index");
$router->get("/genres/detail", "Genres\handle_detail", ["with_query_id"]);
$router->get("/genres/create", "Genres\handle_create");
$router->post("/genres/create", "Genres\handle_store");

$router->get("/book-instances", "Book_Instances\handle_index");
$router->get("/book-instances/detail", "Book_Instances\handle_detail", ["with_query_id"]);

$router->resolve();

function with_query_id()
{
    $id = $_GET["id"] ?? "";
    if (!$id) throw new Exception("400: id is required");
}
