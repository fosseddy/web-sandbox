<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$title = "Genre List";

try
{
    $genres = $db->query("select * from genre order by name", PDO::FETCH_CLASS,
                         "Models\Genre")
                  ->fetchAll();
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
    <?php if (!$genres): ?>
        <li>There are no genres</li>
    <?php else: ?>
        <?php foreach ($genres as $g): ?>
            <li><a href=<?= $g->url() ?>><?= $g->name ?></li>
        <?php endforeach ?>
    <?php endif ?>
</ul>

<?php require_once "../partials/footer.php" ?>
