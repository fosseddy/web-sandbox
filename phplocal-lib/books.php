<?php

namespace Books;

use PDO, Exception;
use Net, Authors;

class Model
{
    public $id;
    public $title;
    public $author_id;
    public $summary;
    public $ISBN;

    public $author;

    function url()
    {
        return "/books/detail?id=$this->id";
    }
}

function handle_index($ctx)
{
    $pdo = $ctx["pdo"];

    $s = $pdo->query("select B.id, B.title, A.first_name, A.family_name " .
                     "from book as B " .
                     "left join author as A on B.author_id = A.id " .
                     "order by B.title");

    foreach($s->fetchAll() as $it)
    {
        $b = new Model();
        $b->author = new Authors\Model();

        $b->id = $it["id"];
        $b->title = $it["title"];
        $b->author->first_name = $it["first_name"];
        $b->author->family_name = $it["family_name"];

        $books[] = $b;
    }

    Net\render_view("books/index.php", [
        "title" => "Book List",
        "books" => $books
    ]);
}

function handle_detail($ctx)
{
    $pdo = $ctx["pdo"];
    $book_id = $_GET["id"];

    if (!$book_id) throw new Exception("404: book not found");

    $s = $pdo->prepare("select B.title, B.summary, B.ISBN, A.id as a_id, " .
                       "A.first_name, A.family_name " .
                       "from book as B " .
                       "left join author as A on B.author_id = A.id " .
                       "where B.id = ?");
    $s->execute([$book_id]);
    $data = $s->fetch();

    if (!$data) throw new Exception("404: book not found");

    $book = new Model();
    $book->author = new Authors\Model();

    $book->title = $data["title"];
    $book->summary = $data["summary"];
    $book->ISBN = $data["ISBN"];
    $book->author->id = $data["a_id"];
    $book->author->first_name = $data["first_name"];
    $book->author->family_name = $data["family_name"];

    $s = $pdo->prepare("select id, status, imprint, due_back " .
                       "from book_instance where book_id = ?");
    $s->execute([$book_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Book_Instances\Model");
    $book_instances = $s->fetchAll();

    $s = $pdo->prepare("select G.id, G.name from genre as G " .
                       "left join book_genres as BG on G.id = BG.genre_id " .
                       "where BG.book_id = ?");
    $s->execute([$book_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Genres\Model");
    $genres = $s->fetchAll();

    Net\render_view("books/detail.php", [
        "title" => "Book Detail",
        "book" => $book,
        "book_instances" => $book_instances,
        "genres" => $genres
    ]);
}
