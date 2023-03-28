<?php

namespace Session;

require_once "user.php";

use User;
use PDO;

const COOKIE_NAME = "session";

class Model
{
    public $id;
    public $user_id;
}

function create($user_id)
{
    $hash = hash_hmac("sha256", "$user_id", "secret_string");
    setcookie(COOKIE_NAME, "$user_id.$hash", [
        "expires" => time() + 60 * 60 * 24,
        "httponly" => true
    ]);
}

function destroy()
{
    setcookie(COOKIE_NAME, "", time() - 3600);
}

function restore($db)
{
    if (!isset($_COOKIE["session"]))
    {
        return null;
    }

    $sess = $_COOKIE["session"];

    [$user_id, $hash] = explode(".", $sess);
    $user_id = (int) $user_id;

    if ($hash !== hash_hmac("sha256", "$user_id", "secret_string"))
    {
        return null;
    }

    $s = $db->prepare("SELECT * FROM user WHERE id = ?");
    $s->setFetchMode(PDO::FETCH_INTO, new User\Model());
    $s->execute([$user_id]);

    return $s->fetch();
}
