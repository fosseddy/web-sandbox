<?php

namespace Genres;

use Exception;
use Net, Genres;

class Model
{
    public $id;
    public $name;

    function url()
    {
        return "/genres/detail?id=$this->id";
    }
}

function handle_index($ctx)
{
    $db = $ctx["db"];

    $genres = $db->query_many("select id, name from genre order by name",
                              "Genres\Model");

    Net\render_view("genres/index", [
        "title" => "Genre List",
        "genres" => $genres
    ]);
}

function handle_detail($ctx)
{
    if (!isset($_GET["id"])) throw new Exception("404: genre not found");

    $db = $ctx["db"];
    $genre_id = $_GET["id"];

    $genre = $db->query_one("select name from genre where id = ?", [$genre_id],
                            "Genres\Model");

    if (!$genre) throw new Exception("404: genre not found");

    $books = $db->query_many("select B.id, B.title, B.summary " .
                             "from book as B left join book_genres as BG " .
                             "on B.id = BG.book_id where BG.genre_id = ?",
                             [$genre_id], "Books\Model");

    Net\render_view("genres/detail", [
        "title" => "Genre Detail",
        "genre" => $genre,
        "books" => $books,
    ]);
}

function handle_create($ctx)
{
    Net\render_view("genres/create", ["title" => "Create Genre"]);
}

function handle_store($ctx)
{
    $db = $ctx["db"];

    $errors = [];
    $genre = new Model();

    $genre->name = htmlspecialchars(trim($_POST["name"] ?? ""));
    $namelen = strlen($genre->name);

    if ($namelen < 3 || $namelen > 255) $errors[] = "Genre name must be at least 3 characters long, but less than 255 characters";

    if ($errors)
    {
        Net\render_view("genres/create", [
            "title" => "Create Genre",
            "genre" => $genre,
            "errors" => $errors
        ]);
        exit;
    }

    $db->query_one("select id from genre where name = ?", [$genre->name],
                   $genre);

    if ($genre->id)
    {
        Net\redirect($genre->url());
        exit;
    }

    $db->exec("insert into genre (name) values (?)", [$genre->name]);

    $genre->id = $db->pdo->lastInsertId();
    Net\redirect($genre->url());
}
