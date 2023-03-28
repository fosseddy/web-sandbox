<?php

require_once "database.php";
require_once "user.php";

if (isset($_COOKIE["session"]))
{
    header("Location: /phpauth/dashboard.php");
    exit;
}

$db = Database\connect();
$name = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $name = htmlspecialchars(trim($_REQUEST["name"]));
    $pass = htmlspecialchars(trim($_REQUEST["password"]));

    try
    {
        $errors = User\validate_credentials($name, $pass);
        if (!empty($errors))
        {
            goto render_html;
        }

        $err = User\register($name, $pass, $db);
        if ($err)
        {
            $errors[] = $err;
            goto render_html;
        }

        header("Location: /phpauth/");
        exit;
    }
    catch (Exception $e)
    {
        header("Location: /phpauth/error.php");
        exit;
    }
}

render_html:

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
</head>
<body>
    <h1>Register</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= $e ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/phpauth/register.php">
        <div>
            <label>
                Username <br>
                <input name="name" value="<?= $name ?>">
            </label>
        </div>

        <div>
            <label>
                Password <br>
                <input name="password" type="password">
            </label>
        </div>

        <button type="submit">submit</button>
    </form>
</body>
</html>
