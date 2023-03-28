<?php

namespace User;

require_once "session.php";

use Session;
use PDO;

class Model
{
    public $id;
    public $name;
    public $hash;
    public $salt;
}

function validate_credentials($name, $pass)
{
    $errors = [];

    if (strlen($name) < 3)
    {
        $errors[] = "name must be at least 3 characters long";
    }
    else if (strlen($name) > 12)
    {
        $errors[] = "name must be at most 12 characters long";
    }

    // at least 3 chars long; starts with alpha then word
    if (!preg_match("/^[a-zA-Z]{1}\w{2,}$/", $name))
    {
        $errors[] = "name must start with a letter and " .
                    "be at least 3 characters long";
    }

    if (strlen($pass) < 6)
    {
        $errors[] = "password must be at least 6 characters long";
    }

    return $errors;
}

function hash_password($pass)
{
    $salt = random_bytes(32);
    $hash = hash("sha256", $pass . $salt);
    return [$salt, $hash];
}

function compare_password($user, $pass)
{
    $hash = hash("sha256", $pass . $user->salt);
    return $hash === $user->hash;
}

function register($name, $pass, $db)
{
    $s = $db->prepare("SELECT * FROM user WHERE name = ?");
    $s->execute([$name]);

    if ($s->fetch())
    {
        return "user already exist";
    }

    [$salt, $hash] = hash_password($pass);

    $s = $db->prepare("INSERT INTO user (name, salt, hash) VALUES (?, ?, ?)");
    $s->execute([$name, $salt, $hash]);

    return "";
}

function login($name, $pass, $db)
{
    $s = $db->prepare("SELECT * FROM user WHERE name = ?");
    $s->setFetchMode(PDO::FETCH_INTO, new Model());
    $s->execute([$name]);

    $user = $s->fetch();
    if (!$user)
    {
        return "user does not exist";
    }

    if (!compare_password($user, $pass))
    {
        return "wrong password";
    }

    Session\create($user->id);

    return "";
}
