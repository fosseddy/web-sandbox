<?php

namespace Home;

use Net;

function handle_index($ctx)
{
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
}
