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

require_once from_base("net.php");
require_once from_base("database.php");

require_once from_base("models.php");
require_once from_base("books.php");

$router = new Net\Router();
$router->ctx = [
    "pdo" => Database\connect("localhost", "local_lib", "art", "qweqwe123")
];

$router->get("/", function($ctx) {
    $pdo = $ctx["pdo"];

    $author = $pdo->query("select count(*) as count from author")->fetch();
    $book = $pdo->query("select count(*) as count from book")->fetch();
    $genre = $pdo->query("select count(*) as count from genre")->fetch();

    $s = $pdo->query("select count(*) as count from book_instance");
    $book_instance = $s->fetch();

    $s = $pdo->query("select count(*) as count from book_instance " .
                     "where status = 0");
    $book_instance_available = $s->fetch();

    Net\render_view("index.php", [
        "title" => "Local Library Home",
        "book" => $book,
        "book_instance" => $book_instance,
        "book_instance_available" => $book_instance_available,
        "author" => $author,
        "genre" => $genre,
    ]);
});

$router->get("/books", "Books\handle_index");
$router->get("/books/detail", "Books\handle_detail");

$router->resolve();
