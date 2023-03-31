<?php

namespace Database;

use PDO;

function connect()
{
    try
    {
        $db = new PDO("mysql:host=localhost;dbname=phpurl-short",
                      "user", "1234");
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }
    catch (Exception $e)
    {
        var_dump($e->getMessage());
        exit;
    }
}
