<?php require_once Net\partial_view("layout/head.php") ?>

<h1><?= $title ?></h1>

<ul>
    <?php if (!$genres): ?>
        <li>There are no genres</li>
    <?php else: ?>
        <?php foreach ($genres as $g): ?>
            <li><a href=<?= $g->url() ?>><?= $g->name ?></a></li>
        <?php endforeach ?>
    <?php endif ?>
</ul>

<?php require_once Net\partial_view("layout/footer.php") ?>
