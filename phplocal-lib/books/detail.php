<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$book_id = $_GET["id"];

if (!$book_id)
{
    header("Location: /not_found.php");
    exit;
}

$title = "Book Detail";

try
{
    $s = $db->prepare("select B.title, B.summary, B.ISBN, A.id as a_id, " .
                      "A.first_name, A.family_name from book as B " .
                      "left join author as A on B.author_id = A.id " .
                      "where B.id = ?");
    $s->execute([$book_id]);
    $s->setFetchMode(PDO::FETCH_OBJ);
    $data = $s->fetch();

    if (!$data)
    {
        header("Location: /not_found.php");
        exit;
    }

    $book = new Models\Book();
    $a = new Models\Author();

    $book->title = $data->title;
    $book->summary = $data->summary;
    $book->ISBN = $data->ISBN;
    $a->id = $data->a_id;
    $a->first_name = $data->first_name;
    $a->family_name = $data->family_name;

    $book->author = $a;

    $s = $db->prepare("select id, status, imprint, due_back " .
                      "from book_instance where book_id = ?");
    $s->execute([$book_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Models\Book_Instance");

    $book_instances = $s->fetchAll();

    $s = $db->prepare("select G.id, G.name from genre as G " .
                      "left join book_genres as BG on G.id = BG.genre_id " .
                      "where BG.book_id = ?");
    $s->execute([$book_id]);
    $s->setFetchMode(PDO::FETCH_CLASS, "Models\Genre");

    $genres = $s->fetchAll();
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

// TODO(art): should be partial
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

<h1>Title: <?= $book->title ?></h1>

<p><strong>Author:</strong> <a href="<?= $book->author->url() ?>"><?= $book->author->name() ?></a></p>
<p><strong>Summary:</strong> <?= $book->summary ?></p>
<p><strong>ISBN:</strong> <?= $book->ISBN ?></p>
<p><strong>Genre:</strong>
    <?php foreach ($genres as $i => $g): ?>
        <a href="<?= $g->url() ?>"><?= $g->name ?></a><?= $i < count($genres) - 1 ? "," : "" ?>
    <?php endforeach ?>
</p>

<div>
    <h2>Copies</h2>
    <?php if (!$book_instances): ?>
        <p>There are no copies of this book in the library</p>
    <?php else: ?>
        <?php foreach ($book_instances as $b): ?>
            <hr>
            <p class="<?= status_css_class($b->status) ?>"><?= status_to_string($b->status) ?></p>
            <p><strong>Imprint:</strong> <?= $b->imprint ?></p>
            <?php if ($b->status !== 0): ?>
                <p><strong>Due back:</strong> <?= $b->due_back_formatted() ?></p>
            <?php endif ?>
            <p><strong>Id:</strong> <a href="<?= $b->url() ?>"><?= $b->id ?></a></p>
        <?php endforeach ?>
    <?php endif ?>
</div>
