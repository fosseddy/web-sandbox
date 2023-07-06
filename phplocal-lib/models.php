<?php

namespace Models;

class Book
{
    public $id;
    public $title;
    public $author_id;
    public $summary;
    public $ISBN;
    public $author;

    function url()
    {
        return "/books/detail.php?id=$this->id";
    }
}

class Author
{
    public $id;
    public $first_name;
    public $family_name;
    public $date_of_birth;
    public $date_of_death;

    function name()
    {
        return "$this->family_name, $this->first_name";
    }
}

