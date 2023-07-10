<?php

namespace database;

use PDO;

function connect($host, $dbname, $user, $pass)
{
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;
}

class Connection
{
    public $pdo;
    public $stmt;

    function __construct($host, $dbname, $user, $pass)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function exec($q, $vals = [])
    {
        $this->stmt = $this->pdo->prepare($q);
        $this->stmt->execute($vals);
    }

    function query($q, $vals = [], $container = null)
    {
        if (gettype($vals) !== "array")
        {
            $container = $vals;
            $vals = [];
        }

        $this->exec($q, $vals);

        if ($container)
        {
            $mode = PDO::FETCH_INTO;
            if (gettype($container) === "string") $mode = PDO::FETCH_CLASS;
            $this->stmt->setFetchMode($mode, $container);
        }
    }

    function query_one($q, $vals = [], $container = null)
    {
        $this->query($q, $vals, $container);
        return $this->stmt->fetch();
    }

    function query_many($q, $vals = [], $container = null)
    {
        $this->query($q, $vals, $container);
        return $this->stmt->fetchAll();
    }
}
