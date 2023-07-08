<?php

namespace Models;

class Book_Instance
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
        return "/book_instances/detail?id=$this->id";
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

class Genre
{
    public $id;
    public $name;

    function url()
    {
        return "/genres/detail?id=$this->id";
    }
}
