<?php require_once Net\partial_view("layout/head") ?>

<h1><?= $title ?></h1>

<form method="POST">
    <div class="form-group">
        <label>
            Genre:
            <input value="<?= isset($genre) ? $genre->name : "" ?>" name="name" placeholder="Fantasy, Poetry, etc.">
        </label>
    </div>
    <button class="btn btn-primary" type="submit">Submit</button>
</form>

<?php if(isset($errors)): ?>
    <ul>
        <?php foreach($errors as $e): ?>
            <li class="text-danger"><?= $e ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>

<?php require_once Net\partial_view("layout/footer") ?>
