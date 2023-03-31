<?php

require_once "database.php";
require_once "url.php";

$db = Database\connect();

$url = null;
$long_url = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $query = $_SERVER["QUERY_STRING"];
    if (!$query)
    {
        goto render_html;
    }

    [$key, $value] = explode("=", $query);

    $value = trim(htmlspecialchars($value));
    if ($key !== "id" || empty($value))
    {
        goto render_html;
    }

    try
    {
        $url = URL\find($value, $db);
        if (!$url)
        {
            goto render_html;
        }

        header("Location: " . $url->long_url);
        exit;
    }
    catch(Exception $e)
    {
        var_dump($e->getMessage());
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $long_url = trim(htmlspecialchars($_POST["long-url"]));
    $errors = URL\validate($long_url);
    if (!empty($errors))
    {
        goto render_html;
    }

    try
    {
        $url = URL\create($long_url, $db);
    }
    catch (Exception $e)
    {
        var_dump($e->getMessage());
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
    <title>ShURL</title>
</head>
<body>
    <h1>ShURL</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= $e ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/phpurl-short/">
        <label>
            URL <br>
            <input name="long-url" value="<?= $long_url ?>" style="width: 20rem;">
        </label>
        <button type="submit">submit</button>
    </form>

    <?php if ($url): ?>
        <br>
                   <!-- this must be in config or something -->
        <input value="<?= "websandbox.local/phpurl-short/?id=" . $url->short_url ?>" style="width: 30rem;">
    <?php endif; ?>
</body>
</html>
