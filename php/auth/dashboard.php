<?php

namespace dashboard;

use web;

function router(): web\Router
{
    $r = new web\Router();
    $r->get("/dashboard", "dashboard\handle_index", ["auth\only_user"]);

    return $r;
}

function handle_index(array $ctx): void
{
    web\render_view("dashboard", [
        "title" => "Dashboard",
        "user" => $ctx["user"]
    ]);
}
