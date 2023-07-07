<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$title = "Book List";

try
{
    $books = $db->query("select id, title, author_id from book order by title",
                        PDO::FETCH_CLASS, "Models\Book")
                ->fetchAll();

    foreach($books as $i => $b)
    {
        $s = $db->prepare("select first_name, family_name " .
                          "from author where id = ?");
        $s->setFetchMode(PDO::FETCH_CLASS, "Models\Author");
        $s->execute([$b->author_id]);
        $b->author = $s->fetch();
        $books[$i] = $b;
    }
}
catch (Exception $e)
{
    error_log($e->getMessage());
    header("Location: /error.php");
    exit;
}

?>

<?php require_once "../partials/head.php" ?>

<h1><?= $title ?></h1>

<ul>
    <?php if (!$books): ?>
        <li>There are no books</li>
    <?php else: ?>
        <?php foreach ($books as $b): ?>
            <li><a href=<?= $b->url() ?>><?= $b->title ?></a> (<?= $b->author->name() ?>)</li>
        <?php endforeach ?>
    <?php endif ?>
</ul>

<?php require_once "../partials/footer.php" ?>
