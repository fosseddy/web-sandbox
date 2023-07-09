<?php

namespace Authors;

use PDO, Exception;
use Net;

class Model
{
    public $id;
    public $first_name;
    public $family_name;
    public $date_of_birth;
    public $date_of_death;

    public $books;

    function name()
    {
        return "$this->family_name, $this->first_name";
    }

    function url()
    {
        return "/authors/detail?id=$this->id";
    }

    function lifespan()
    {
        $fmt = "M d, Y";
        $birth = date_create($this->date_of_birth)->format($fmt);

        if (!$this->date_of_death)
        {
            return $birth;
        }

        $death = date_create($this->date_of_death)->format($fmt);
        return "$birth - $death";
    }
}

function handle_index($ctx)
{
    $pdo = $ctx["pdo"];

    $s = $pdo->query("select id, first_name, family_name, date_of_birth, " .
                     "date_of_death from author order by family_name",
                     PDO::FETCH_CLASS, "Authors\Model");

    Net\render_view("authors/index", [
        "title" => "Author List",
        "authors" => $s->fetchAll()
    ]);
}

function handle_detail($ctx)
{
    if (!isset($_GET["id"])) throw new Exception("404: author not found");

    $pdo = $ctx["pdo"];
    $author_id = $_GET["id"];

    $s = $pdo->prepare("select first_name, family_name, date_of_birth, " .
                       "date_of_death from author where id = ?");
    $s->execute([$author_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Authors\Model");
    $author = $s->fetch();

    if (!$author) throw new Exception("404: author not found");

    $s = $pdo->prepare("select id, title, summary from book " .
                       "where author_id = ?");
    $s->execute([$author_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Books\Model");

    $books = $s->fetchAll();

    Net\render_view("authors/detail", [
        "title" => "Author Detail",
        "author" => $author,
        "books" => $books
    ]);
}

function handle_create($ctx)
{
    Net\render_view("authors/create", ["title" => "Create Author"]);
}

function handle_store($ctx)
{
}
