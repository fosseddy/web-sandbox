<?php require_once Net\partial_view("layout/head.php") ?>

<h1>Genre: <?= $genre->name ?></h1>

<div>
    <h2>Books</h2>
    <dl>
        <?php if (!$books): ?>
            <p>This genre has no books</p>
        <?php else: ?>
            <?php foreach ($books as $b): ?>
                <dt><a href=<?= $b->url() ?>><?= $b->title ?></a></dt>
                <dd><?= $b->summary ?></dd>
            <?php endforeach ?>
        <?php endif ?>
    </dl>
</div>

<?php require_once Net\partial_view("layout/footer.php") ?>
