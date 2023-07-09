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
    public $genres;

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

    $books = [];

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

    Net\render_view("books/index", [
        "title" => "Book List",
        "books" => $books
    ]);
}

function handle_detail($ctx)
{
    if (!isset($_GET["id"])) throw new Exception("404: book not found");

    $pdo = $ctx["pdo"];
    $book_id = $_GET["id"];

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

    Net\render_view("books/detail", [
        "title" => "Book Detail",
        "book" => $book,
        "book_instances" => $book_instances,
        "genres" => $genres
    ]);
}

function handle_create($ctx)
{
    $pdo = $ctx["pdo"];

    $s = $pdo->query("select id, first_name, family_name from author " .
                     "order by family_name",
                     PDO::FETCH_CLASS, "Authors\Model");
    $authors = $s->fetchAll();

    $s = $pdo->query("select id, name from genre", PDO::FETCH_CLASS,
                     "Genres\Model");
    $genres = $s->fetchAll();

    Net\render_view("books/create", [
        "title" => "Create Book",
        "authors" => $authors,
        "genres" => $genres
    ]);
}

function handle_store($ctx)
{
    $pdo = $ctx["pdo"];
    $b = new Model();
    $errors = [];

    // TODO(art): validation
    $b->title = htmlspecialchars(trim($_POST["title"] ?? ""));
    $b->author_id = htmlspecialchars(trim($_POST["author-id"] ?? ""));
    $b->summary = htmlspecialchars(trim($_POST["summary"] ?? ""));
    $b->ISBN = htmlspecialchars(trim($_POST["ISBN"] ?? ""));

    $b->genres = [];
    if (isset($_POST["genre"]))
    {
        foreach ($_POST["genre"] as $g)
        {
            $b->genres[] = htmlspecialchars(trim($g));
        }
    }

    if ($errors)
    {
        $s = $pdo->query("select id, first_name, family_name from author " .
                         "order by family_name",
                         PDO::FETCH_CLASS, "Authors\Model");
        $authors = $s->fetchAll();

        $s = $pdo->query("select id, name from genre", PDO::FETCH_CLASS,
                         "Genres\Model");
        $genres = $s->fetchAll();

        Net\render_view("books/create", [
            "title" => "Create Book",
            "book" => $b,
            "authors" => $authors,
            "genres" => $genres,
            "errors" => $errors
        ]);
        return;
    }

    $s = $pdo->prepare("select id from book where ISBN = ?");
    $s->execute([$b->ISBN]);

    if ($s->fetch())
    {
        $errors[] = "Book with this ISBN already exist";

        Net\render_view("books/create", [
            "title" => "Create Book",
            "book" => $b,
            "authors" => $authors,
            "genres" => $genres,
            "errors" => $errors
        ]);
        return;
    }

    $pdo->beginTransaction();

    try
    {
        $s = $pdo->prepare("insert into book (title, author_id, summary, ISBN) " .
                           "values (?, ?, ?, ?)");
        $s->execute([$b->title, $b->author_id, $b->summary, $b->ISBN]);

        $b->id = $pdo->lastInsertId();

        $sql = "insert into book_genres (book_id, genre_id) values ";
        $binds = [];
        foreach ($b->genres as $i => $g_id)
        {
            if ($i > 0) $sql .= ", ";
            $sql .= "(?, ?)";
            $binds[] = $b->id;
            $binds[] = $g_id;
        }

        $s = $pdo->prepare($sql);
        $s->execute($binds);

        $pdo->commit();
    }
    catch (Exception $e)
    {
        $pdo->rollBack();
        throw $e;
    }

    Net\redirect($b->url());
}
