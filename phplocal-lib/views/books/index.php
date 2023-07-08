<?php require_once Net\partial_view("layout/head.php") ?>

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

<?php require_once Net\partial_view("layout/footer.php") ?>