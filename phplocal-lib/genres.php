<?php

namespace Genres;

use PDO, Exception;
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
    $pdo = $ctx["pdo"];

    $s = $pdo->query("select id, name from genre order by name",
                    PDO::FETCH_CLASS, "Genres\Model");

    Net\render_view("genres/index", [
        "title" => "Genre List",
        "genres" => $s->fetchAll()
    ]);
}

function handle_detail($ctx)
{
    if (!isset($_GET["id"])) throw new Exception("404: genre not found");

    $pdo = $ctx["pdo"];
    $genre_id = $_GET["id"];

    $s = $pdo->prepare("select name from genre where id = ?");
    $s->execute([$genre_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Genres\Model");
    $genre = $s->fetch();

    if (!$genre) throw new Exception("404: genre not found");

    $s = $pdo->prepare("select B.id, B.title, B.summary from book as B " .
                      "left join book_genres as BG on B.id = BG.book_id " .
                      "where BG.genre_id = ?");
    $s->execute([$genre_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Books\Model");
    $books = $s->fetchAll();

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
    $pdo = $ctx["pdo"];

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
        return;
    }

    $s = $pdo->prepare("select id from genre where name = ?");
    $s->execute([$genre->name]);
    $s->setFetchMode(PDO::FETCH_INTO, $genre);
    $s->fetch();

    if ($genre->id)
    {
        Net\redirect($genre->url());
        return;
    }

    $s = $pdo->prepare("insert into genre (name) values (?)");
    $s->execute([$genre->name]);

    $genre->id = $pdo->lastInsertId();
    Net\redirect($genre->url());
}
