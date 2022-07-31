<?php

use Cogent\DataTypes\DataTypes;
use Cogent\Schemas\Schema;

$userSchema = new Schema([
    "id" => [
        "type" => DataTypes::Integer,
        "increment" => true,
        "primary" => true,
        "length" => 11
    ],
    "nickname" => DataTypes::String,
    "timestamps" => true,
    // "softDelete" => true
], [
    "engine" => "InnoDB",
    "charset" => "utf8"
]);

Cogent\DB\Cogent::model("Players", $userSchema);

/**
 * type string -
 * Increment (boolean) -
 * primary (boolean) -
 * default (any) -
 * ref (string) -
 * foreign key -
 * nullable (boolean)-
 * timestamps true -
 * unsigned -
 * unique -
 * softDelete true -
 */