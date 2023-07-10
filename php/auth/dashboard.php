<?php

require_once "database.php";
require_once "session.php";

$db = Database\connect();

try
{
    $user = Session\restore($db);
    if (!$user)
    {
        Session\destroy();
        header("Location: /phpauth/");
        exit;
    }
}
catch (Exception $e)
{
    header("Location: /phpauth/error.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>This is your dashboard, <strong><?= $user->name ?></strong></p>
    <a href="/phpauth/logout.php">logout</a>
</body>
</html>
