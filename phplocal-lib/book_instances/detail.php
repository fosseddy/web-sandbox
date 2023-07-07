<?php

require_once "../database.php";
require_once "../models.php";
require_once "../debug.php";

$db = Database\connect();

$book_instance_id = $_GET["id"];

if (!$book_instance_id)
{
    header("Location: /not_found.php");
    exit;
}

$title = "Book:";

try
{
    $s = $db->prepare("select BI.id, BI.imprint, BI.status, BI.due_back, " .
                       "B.title, B.id as b_id from book_instance as BI " .
                       "left join book as B on BI.book_id = B.id " .
                       "where BI.id = ?");
    $s->execute([$book_instance_id]);
    $s->setFetchMode(PDO::FETCH_OBJ);
    $data = $s->fetch();

    if (!$data)
    {
        header("Location: /not_found.php");
        exit;
    }

    $bi = new Models\Book_Instance();
    $b = new Models\Book();

    $bi->id = $data->id;
    $bi->imprint = $data->imprint;
    $bi->status = $data->status;
    $bi->due_back = $data->due_back;
    $b->title = $data->title;
    $b->id = $data->b_id;
    $bi->book = $b;

    $book_instance = $bi;
}
catch (Exception $e)
{
    error_log($e->getMessage());
    header("Location: /error.php");
    exit;
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

function status_to_string($s)
{
    $status_text = ["Available", "Maintenance", "Loaned", "Reserved"];
    return $status_text[$s];
}

?>

<?php require_once "../partials/head.php" ?>

<h1>ID: <?= $book_instance->id ?></h1>
<p><strong>Title:</strong> <a href="<?= $book_instance->book->url() ?>"><?= $book_instance->book->title ?></a></p>
<p><strong>Imprint:</strong> <?= $book_instance->imprint ?></p>
<p><strong>Status:</strong>
    <span class="<?= status_css_class($book_instance->status) ?>"><?= status_to_string($book_instance->status) ?></span>
</p>

<?php if ($book_instance->status !== 0): ?>
    <p><strong>Due back:</strong> <?= $book_instance->due_back_formatted() ?></p>
<?php endif ?>
