<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$author_id = $_GET["id"];

if (!$author_id)
{
    header("Location: /not_found.php");
    exit;
}

$title = "Author Detail";

try
{
    $s = $db->prepare("select first_name, family_name, date_of_birth, " .
                      "date_of_death from author where id = ?");
    $s->execute([$author_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Models\Author");
    $author = $s->fetch();

    if (!$author)
    {
        header("Location: /not_found.php");
        exit;
    }

    $s = $db->prepare("select id, title, summary from book " .
                      "where author_id = ?");
    $s->execute([$author_id]);
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

<h1>Author: <?= $author->name() ?></h1>
<p><?= $author->lifespan() ?></p>

<div>
    <h2>Books</h2>

    <dl>
        <?php if (!$books): ?>
            <p>This author has no books</p>
        <?php else: ?>
            <?php foreach ($books as $b): ?>
                <dt><a href=<?= $b->url() ?>><?= $b->title ?></a></dt>
                <dd><?= $b->summary ?></dd>
            <?php endforeach ?>
        <?php endif ?>
    </dl>
</div>
