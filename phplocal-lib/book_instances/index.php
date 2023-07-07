<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$title = "Book Instances List";
$book_instances = [];

try
{
    $data = $db->query("select BI.id, BI.imprint, BI.status, BI.due_back, " .
                       "B.title from book_instance as BI " .
                       "left join book as B on BI.book_id = B.id",
                       PDO::FETCH_OBJ)
               ->fetchAll();

    foreach($data as $d)
    {
        $bi = new Models\Book_Instance();
        $b = new Models\Book();

        $bi->id = $d->id;
        $bi->imprint = $d->imprint;
        $bi->status = $d->status;
        $bi->due_back = $d->due_back;

        $b->title = $d->title;

        $bi->book = $b;
        $book_instances[] = $bi;
    }
}
catch (Exception $e)
{
    error_log($e->getMessage());
    header("Location: /error.php");
    exit;
}

function status_to_string($s)
{
    $status_text = ["Available", "Maintenance", "Loaned", "Reserved"];
    return $status_text[$s];
}

function status_css_class($s)
{
    if ($s === 0)
    {
        return "text-success";
    }

    if ($s === 1)
    {
        return "text-danger";
    }

    return "text-warning";
}

?>

<?php require_once "../partials/head.php" ?>

<h1><?= $title ?></h1>

<ul>
    <?php if (!$book_instances): ?>
        <li>There are no book copies in this library</li>
    <?php else: ?>
        <?php foreach ($book_instances as $b): ?>
            <li>
                <a href=<?= $b->url() ?>><?= "{$b->book->title} : $b->imprint" ?></a> -
                <span class=<?= status_css_class($b->status) ?>><?= status_to_string($b->status) ?></span>
                <?php if ($b->status !== 0): ?>
                    <span>(Due: <?= $b->due_back_formatted() ?>)</span>
                <?php endif ?>
            </li>
        <?php endforeach ?>
    <?php endif ?>
</ul>

<?php require_once "../partials/footer.php" ?>
