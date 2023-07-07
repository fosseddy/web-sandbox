<?php

require_once "../database.php";
require_once "../models.php";

$db = Database\connect();

$title = "Author List";

try
{
    $authors = $db->query("select * from author order by family_name",
                          PDO::FETCH_CLASS, "Models\Author")
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
    <?php if (!$authors): ?>
        <li>There are no authors</li>
    <?php else: ?>
        <?php foreach ($authors as $a): ?>
            <li><a href=<?= $a->url() ?>><?= $a->name() ?></a> (<?= $a->lifespan() ?>)</li>
        <?php endforeach ?>
    <?php endif ?>
</ul>

<?php require_once "../partials/footer.php" ?>
