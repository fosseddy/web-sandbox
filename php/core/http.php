<?php

namespace http;

use Exception;

class Not_Found extends Exception {

    public $status_code = 404;
    public $data;

    function __construct($msg = "", $data = null) {
        $this->data = $data;
        parent::__construct($msg);
    }
}
