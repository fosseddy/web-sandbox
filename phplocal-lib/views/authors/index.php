<?php require_once Net\partial_view("layout/head") ?>

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

<?php require_once Net\partial_view("layout/footer") ?>
