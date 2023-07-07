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

    function url()
    {
        return "/authors/detail.php?id=$this->id";
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

class Book_Instance
{
    public $id;
    public $book_id;
    public $imprint;
    public $status;
    public $due_back;

    public $book;

    function url()
    {
        return "/book_instances/detail.php?id=$this->id";
    }

    function due_back_formatted()
    {
        return date_create($this->due_back)->format("D, d M Y");
    }
}

class Genre
{
    public $id;
    public $name;

    function url()
    {
        return "/genres/detail.php?id=$this->id";
    }
}
