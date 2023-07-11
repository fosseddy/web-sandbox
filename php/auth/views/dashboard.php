<?php require_once web\partial_view("_head") ?>

<h1><?= $title ?></h1>

<p>This is your dashboard, <strong><?= $user->name ?></strong></p>

<form method="POST" action="/logout">
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit">logout</button>
</form>

<?php require_once web\partial_view("_footer") ?>
