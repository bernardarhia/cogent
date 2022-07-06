<?php

use Cogent\DB\Cogent;

include_once __DIR__ . "/vendor/autoload.php";

include_once __DIR__ . "/models/User.php";

Cogent::connection("mysql/localhost/root/test1", null, function ($error) {
    if ($error) die("An error occurred");
});

$r = Users::find([
    "attributes" => [
        "id",
        "name",
        ["email", "another_email"],
        [Cogent::fn("COUNT", "id"), "total"]
    ],
    "conditions" => [
        "id" => [
            Cogent::fn("NOT IN", "1,2,3"),
        ],
        "name" => "John",
        "age" => "kwesi",
    ],
], function ($result, $err) {
    print_r([
        "err" => $err,
        "result" => $result
    ]);
});