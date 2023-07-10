<?php

namespace Database;

use PDO;

function connect()
{
    try
    {
        $db = new PDO("mysql:host=localhost;dbname=phpauth", "user", "1234");
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }
    catch (Exception $e)
    {
        header("Location: /phpauth/error.php");
        exit;
    }
}
