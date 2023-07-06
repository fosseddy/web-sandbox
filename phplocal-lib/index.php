<?php
require_once "./database.php";

$db = Database\connect();

try
{
    $author = $db->query("select count(*) as count from author")->fetch();
    $book = $db->query("select count(*) as count from book")->fetch();
    $genre = $db->query("select count(*) as count from genre")->fetch();

    $book_instance = $db->query("select count(*) as count from book_instance")
                        ->fetch();

    $book_instance_available = $db->query("select count(*) as count " .
                                          "from book_instance " .
                                          "where status = 0")
                                  ->fetch();
}
catch (Exception $e)
{
    error_log($e->getMessage());
    header("Location: /error.php");
    exit;
}

$title = "Local Library Home";
?>

<?php require_once "./partials/head.php" ?>

<h1><?= $title ?></h1>

<p>Welcome to <em>LocalLibrary</em>, a very basic website developed to practice PHP.</p>

<h2>Dynamic content</h2>

<p>The library has the following record counts:</p>

<ul>
    <li><strong>Books:</strong> <?= $book["count"] ?></li>
    <li><strong>Copies:</strong> <?= $book_instance["count"] ?></li>
    <li><strong>Copies available:</strong> <?= $book_instance_available["count"] ?></li>
    <li><strong>Authors:</strong> <?= $author["count"] ?></li>
    <li><strong>Genres:</strong> <?= $genre["count"] ?></li>
</ul>

<?php require_once "./partials/footer.php" ?>
