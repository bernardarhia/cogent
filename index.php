<?php

use Cogent\DB\Cogent;
use Cogent\Operators\Op;

include_once __DIR__ . "/vendor/autoload.php";

include_once __DIR__ . "/models/User.php";

Cogent::connection("mysql/localhost/root/test1", null, function ($error) {
    if ($error) die("An error occurred");
});


$r = Users::find()
    ->where(["id" => [OP::IN(1, 2, 3), OP::GT(Cogent::fn("COUNT", "id"))], "name" => "ben"])->groupBy("name")->get();