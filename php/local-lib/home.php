<?php

namespace Home;

use Net;

function handle_index($ctx)
{
    $db = $ctx["db"];

    $author = $db->query_one("select count(*) as count from author");
    $book = $db->query_one("select count(*) as count from book");
    $genre = $db->query_one("select count(*) as count from genre");

    $book_instance = $db->query_one("select count(*) as count " .
                                    "from book_instance");

    $book_instance_available = $db->query_one("select count(*) as count " .
                                              "from book_instance " .
                                              "where status = 0");

    Net\render_view("index", [
        "title" => "Local Library Home",
        "book" => $book,
        "book_instance" => $book_instance,
        "book_instance_available" => $book_instance_available,
        "author" => $author,
        "genre" => $genre,
    ]);
}
