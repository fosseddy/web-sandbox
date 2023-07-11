<?php

declare(strict_types=1);

const BASE_DIR = __DIR__ . "/..";
const CORE_DIR = BASE_DIR . "/../core";

function debug($val)
{
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
}

require_once CORE_DIR . "/web.php";
require_once CORE_DIR . "/database.php";

class Url
{
    public $id;
    public $long_url;
    public $short_url;
}

$app = new web\App();
$app->ctx = [
    "db" => new database\Connection(
        "localhost",
        "phpurl_short",
        "user",
        "1234"
    )
];

$r = new web\Router();

$r->get("/", function(array $ctx) {
    $id = htmlspecialchars(trim($_GET["id"] ?? ""));

    if (!$id)
    {
        web\render_view("index");
        exit;
    }

    $url = $ctx["db"]->query_one(
        "select id, short_url, long_url from url where short_url = ?",
        [$id],
        "Url"
    );

    if (!$url)
    {
        web\render_view("index");
        exit;
    }

    web\redirect($url->long_url);
});

$r->post("/", function(array $ctx) {
    $long_url = htmlspecialchars(trim($_POST["long-url"] ?? ""));
    $errors = [];

    if (!$long_url) $errors[] = "url is required";
    if (!filter_var($long_url, FILTER_VALIDATE_URL)) $errors[] = "invalid url";

    if ($errors)
    {
        web\render_view("index", [
            "errors" => $errors,
            "long_url" => $long_url
        ]);
        exit;
    }

    $url = $ctx["db"]->query_one(
        "select id, short_url from url where long_url = ?",
        [$long_url],
        "Url"
    );

    if ($url)
    {
        web\render_view("index", ["url" => $url, "long_url" => $long_url]);
        exit;
    }

    $url = new Url();
    $url->short_url = bin2hex(random_bytes(8));
    $url->long_url = $long_url;

    $ctx["db"]->exec(
        "insert into url (long_url, short_url) values (?, ?)",
        [$url->long_url, $url->short_url]
    );

    $url->id = $ctx["db"]->pdo->lastInsertId();

    web\render_view("index", ["url" => $url, "long_url" => $long_url]);
});

$app->add_router($r);

try
{
    $app->handle_request();
}
catch (http\Not_Found $e)
{
    http_response_code(404);
    echo "Page Not Found";
}
catch (Exception $e)
{
    error_log($e->getMessage());

    http_response_code(500);
    echo "Something went wrong and it is not your fault";
}
