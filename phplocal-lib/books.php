<?php

namespace Books;

use PDO, Exception;
use Net, Models;

class Model
{
    public $id;
    public $title;
    public $author_id;
    public $summary;
    public $ISBN;

    public $author;

    function __construct($data)
    {
        $this->id = $data["id"];
        $this->title = $data["title"];
        $this->author_id = $data["author_id"];
        $this->summary = $data["summary"];
        $this->ISBN = $data["ISBN"];
    }

    function url()
    {
        return "/books/detail?id=$this->id";
    }
}

function query_many($pdo, $with_author = false)
{
    if (!$with_author)
    {
        $s = $pdo->query("select * from book order by title",
                         PDO::FETCH_CLASS, "Books\Model");
        return $s->fetchAll();
    }

    $s = $pdo->query("select B.*, A.* from book as B " .
                     "left join author as A on B.author_id = A.id " .
                     "order by B.title");

    foreach ($s->fetchAll() as $it)
    {
        $b = new Model($it);
        $a = new Models\Author();

        $a->id = $it["author_id"];
        $a->first_name = $it["first_name"];
        $a->family_name = $it["family_name"];
        $a->date_of_birth = $it["date_of_birth"];
        $a->date_of_death = $it["date_of_death"];

        $b->author = $a;

        $books[] = $b;
    }

    return $books;
}

function query_one($pdo, $id, $with_author = false)
{
    if (!$with_author)
    {
        $s = $pdo->prepare("select * from book where id = ?");
        $s->execute([$id]);
        $s->setFetchMode(PDO::FETCH_CLASS, "Books\Model");

        return $s->fetch();
    }

    $s = $pdo->prepare("select B.*, A.* from book as B " .
                       "left join author as A on B.author_id = A.id " .
                       "where B.id = ?");
    $s->execute([$id]);
    $data = $s->fetch();

    if (!$data) return null;

    $b = new Model($data);
    $a = new Models\Author();

    $a->id = $data["author_id"];
    $a->first_name = $data["first_name"];
    $a->family_name = $data["family_name"];
    $a->date_of_birth = $data["date_of_birth"];
    $a->date_of_death = $data["date_of_death"];

    $b->author = $a;

    return $b;
}

function handle_index($ctx)
{
    Net\render_view("books/index.php", [
        "title" => "Book List",
        "books" => query_many($ctx["pdo"], true)
    ]);
}

function handle_detail($ctx)
{
    $pdo = $ctx["pdo"];
    $book_id = $_GET["id"];

    if (!$book_id) throw new Exception("404: book not found");

    $book = query_one($pdo, $book_id, true);

    if (!$book) throw new Exception("404: book not found");

    $s = $pdo->prepare("select id, status, imprint, due_back " .
                       "from book_instance where book_id = ?");
    $s->execute([$book_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Models\Book_Instance");

    $book_instances = $s->fetchAll();

    $s = $pdo->prepare("select G.id, G.name from genre as G " .
                       "left join book_genres as BG on G.id = BG.genre_id " .
                       "where BG.book_id = ?");
    $s->execute([$book_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Models\Genre");

    $genres = $s->fetchAll();

    Net\render_view("books/detail.php", [
        "title" => "Book Detail",
        "book" => $book,
        "book_instances" => $book_instances,
        "genres" => $genres
    ]);
}
