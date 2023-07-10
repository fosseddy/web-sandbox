<?php

namespace URL;

use PDO;

class Model
{
    public $id;
    public $long_url;
    public $short_url;
    public $created_at;
}

function validate($url)
{
    $errors = [];

    if (empty($url))
    {
        $errors[] = "url is required";
    }

    if (!filter_var($url, FILTER_VALIDATE_URL))
    {
        $errors[] = "invalid url";
    }

    return $errors;
}

function create($long_url, $db)
{
    $s = $db->prepare("SELECT * FROM url WHERE long_url = ?");
    $s->setFetchMode(PDO::FETCH_INTO, new Model());
    $s->execute([$long_url]);

    $url = $s->fetch();
    if ($url)
    {
        return $url;
    }

    $short_url = bin2hex(random_bytes(8));
    $s = $db->prepare("INSERT INTO url (long_url, short_url) VALUES (?, ?)");
    $s->execute([$long_url, $short_url]);

    $s = $db->prepare("SELECT * FROM url WHERE id = ?");
    $s->setFetchMode(PDO::FETCH_INTO, new Model());
    $s->execute([$db->lastInsertId()]);

    return $s->fetch();
}

function find($short_url, $db)
{
    $s = $db->prepare("SELECT * FROM url WHERE short_url = ?");
    $s->setFetchMode(PDO::FETCH_INTO, new Model());
    $s->execute([$short_url]);

    return $s->fetch();
}
