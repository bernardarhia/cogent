<?php

use Cogent\DB\Cogent;

include_once __DIR__ . "/vendor/autoload.php";

include_once __DIR__ . "/models/User.php";

Cogent::connection("mysql/localhost/root/test1", null, function ($error) {
    if ($error) die("An error occurred");
});

$users = new Users([
    "first_name" => "Bernard",
    "last_name" => "Arhia"
]);
$result = $users->save();