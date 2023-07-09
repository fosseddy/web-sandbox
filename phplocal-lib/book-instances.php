<?php

namespace Book_Instances;

use Exception;
use Net, Books;

class Model
{
    const STATUS_TEXT = [
        "Available",
        "Maintenance",
        "Loaned",
        "Reserved"
    ];

    public $id;
    public $book_id;
    public $imprint;
    public $status;
    public $due_back;

    public $book;

    function url()
    {
        return "/book-instances/detail?id=$this->id";
    }

    function due_back_formatted()
    {
        return date_create($this->due_back)->format("D, d M Y");
    }

    function status_string()
    {
        return static::STATUS_TEXT[$this->status];
    }
}


function handle_index($ctx)
{
    $pdo = $ctx["pdo"];

    $s = $pdo->query("select BI.id, BI.imprint, BI.status, BI.due_back, " .
                        "B.title from book_instance as BI " .
                        "left join book as B on BI.book_id = B.id");

    $book_instances = [];

    foreach($s->fetchAll() as $it)
    {
        $bi = new Model();
        $bi->book = new Books\Model();

        $bi->id = $it["id"];
        $bi->imprint = $it["imprint"];
        $bi->status = $it["status"];
        $bi->due_back = $it["due_back"];
        $bi->book->title = $it["title"];

        $book_instances[] = $bi;
    }

    Net\render_view("book-instances/index", [
        "title" => "Book Instances List",
        "book_instances" => $book_instances
    ]);
}

function handle_detail($ctx)
{
    if (!isset($_GET["id"])) throw new Exception("404: instance not found");

    $pdo = $ctx["pdo"];
    $book_instance_id = $_GET["id"];

    $s = $pdo->prepare("select BI.id, BI.imprint, BI.status, BI.due_back, " .
                      "B.title, B.id as b_id from book_instance as BI " .
                      "left join book as B on BI.book_id = B.id " .
                      "where BI.id = ?");
    $s->execute([$book_instance_id]);
    $data = $s->fetch();

    if (!$data) throw new Exception("404: instance not found");

    $bi = new Model();
    $bi->book = new Books\Model();

    $bi->id = $data["id"];
    $bi->imprint = $data["imprint"];
    $bi->status = $data["status"];
    $bi->due_back = $data["due_back"];

    $bi->book->title = $data["title"];
    $bi->book->id = $data["b_id"];

    Net\render_view("book-instances/detail", [
        "title" => "Book:",
        "book_instance" => $bi
    ]);
}
