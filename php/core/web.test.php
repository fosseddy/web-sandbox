<?php

$_SERVER = [
    "REQUEST_URI" => "/r2/",
    "REQUEST_METHOD" => "POST"
];

require_once "./web.php";

$app = new web\App();
$r = new web\Router("/namespace");

$r->get("/", function () {
    echo "This is Home Page\n";
});

$r->post("/", function () {
    echo "This is POST Method Handler\n";
});

$app->add_router($r);

$r2 = new web\Router("/namespace");

$r2->get("/r2", function () {
    echo "This Router 2 Home Page\n";
});

$r2->post("/r2", function () {
    echo "This is Router 2 POST Method Handler\n";
});

$app->add_router($r2);

$app->handle_request();
