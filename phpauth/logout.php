<?php

require_once "session.php";

Session\destroy();
header("Location: /phpauth/");

?>
