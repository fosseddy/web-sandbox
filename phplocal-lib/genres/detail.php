<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$genre_id = $_GET["id"];

if (!$genre_id)
{
    header("Location: /not_found.php");
    exit;
}

$title = "Genre Detail";

try
{
    $s = $db->prepare("select name from genre where id = ?");
    $s->execute([$genre_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Models\Genre");
    $genre = $s->fetch();

    if (!$genre)
    {
        header("Location: /not_found.php");
        exit;
    }

    $s = $db->prepare("select B.id, B.title, B.summary from book as B " .
                      "left join book_genres as BG on B.id = BG.book_id " .
                      "where BG.genre_id = ?");
    $s->execute([$genre_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Models\Book");
    $books = $s->fetchAll();

}
catch (Exception $e)
{
    error_log($e->getMessage());
    header("Location: /error.php");
    exit;
}

?>

<?php require_once "../partials/head.php" ?>

<h1>Genre: <?= $genre->name ?></h1>

<div>
    <h2>Books</h2>
    <dl>
        <?php if (!$books): ?>
            <p>This genre has no books</p>
        <?php else: ?>
            <?php foreach ($books as $b): ?>
                <dt><a href=<?= $b->url() ?>><?= $b->title ?></a></dt>
                <dd><?= $b->summary ?></dd>
            <?php endforeach ?>
        <?php endif ?>
    </dl>
</div>
