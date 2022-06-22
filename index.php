<?php
include_once __DIR__ . "/vendor/autoload.php";

include_once __DIR__ . "/models/User.php";

$person = User::find(null, "age grade");
print_r($person);