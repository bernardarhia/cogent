<?php

use Cogent\DB\Cogent;


include_once __DIR__ . "/vendor/autoload.php";

include_once __DIR__ . "/models/User.php";

Cogent::connection("mysql/localhost/root/test1", null, function ($error) {
    if ($error) die("An error occurred");
});


$r = Users::find([
    Cogent::fn("COUNT", "id") => "total_id",
    "SUM(id)" => "total_id_sum",
    "name"
], function ($r, $err) {
    // print_r($err);
});

print_r($r);