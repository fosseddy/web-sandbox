<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$title = "Book Instances List";

try
{
    $book_instances = $db->query("select * from book_instance",
                                 PDO::FETCH_CLASS, "Models\Book_Instance")
                         ->fetchAll();

    foreach($book_instances as $i => $b)
    {
        $s = $db->prepare("select * from book where id = ?");
        $s->setFetchMode(PDO::FETCH_CLASS, "Models\Book");
        $s->execute([$b->book_id]);
        $b->book = $s->fetch();
        $book_instances[$i] = $b;
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
