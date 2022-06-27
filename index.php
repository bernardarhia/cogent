<?php

use Cogent\DB\Cogent;
use Cogent\Models\Model;

include_once __DIR__ . "/vendor/autoload.php";

include_once __DIR__ . "/models/User.php";

Cogent::connection("mysql/localhost/root/test1", function ($error) {
    if ($error) die("An error occurred");
});

$user = new Users(["first_name" => "Bernard", "last_name" => "Arhia"]);
// $r = $user->save(function ($err, $data) {
//     print_r([$err, $data]);
// });
$r = Users::find();
print_r($r);