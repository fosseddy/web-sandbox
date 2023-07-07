<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$title = "Book List";
$books = [];

try
{
    $data = $db->query("select B.id, B.title, A.first_name, A.family_name " .
                       "from book as B " .
                       "left join author as A on B.author_id = A.id " .
                       "order by B.title", PDO::FETCH_OBJ)
               ->fetchAll();

    foreach ($data as $d)
    {
        $b = new Models\Book();
        $a = new Models\Author();

        $b->id = $d->id;
        $b->title = $d->title;

        $a->first_name = $d->first_name;
        $a->family_name = $d->family_name;

        $b->author = $a;
        $books[] = $b;
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
