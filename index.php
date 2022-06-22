<?php

use Cogent\DB\Cogent;

include_once __DIR__ . "/vendor/autoload.php";

include_once __DIR__ . "/models/User.php";

Cogent::connection("mysql/localhost/root/test1", function ($error) {
    // print_r($error);
});

Users::insert([], function ($data, $err) {
    print_r($err);
});