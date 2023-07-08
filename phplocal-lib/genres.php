<?php

namespace Genres;

use PDO, Exception;
use Net;

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

    Net\render_view("genres/index.php", [
        "title" => "Genre List",
        "genres" => $s->fetchAll()
    ]);
}

function handle_detail($ctx)
{
    $pdo = $ctx["pdo"];
    $genre_id = $_GET["id"];

    if (!$genre_id) throw new Exception("404: genre not found");

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

    Net\render_view("genres/detail.php", [
        "title" => "Genre Detail",
        "genre" => $genre,
        "books" => $books,
    ]);
}
