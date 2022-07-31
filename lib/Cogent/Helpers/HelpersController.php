<?php

namespace Cogent\Helpers;


trait HelpersController
{

    public function findAndReplace($key, $string, $replace)
    {
        if (strpos($key, $string) !== false) {
            return str_replace($key, $replace,  $string);
        }
        return $string;
    }
    static function turnString($from, $to)
    {
        return $to;
    }

    public static function formatArray($arr)
    {
        $returnable = [];
        foreach ($arr as $data_key => $data_value) {

            if ($data_key == "timestamps" && $arr['timestamps'] == true) {
                if ($data_value == true) {
                    $returnable['created_at'] = "datetime not null default current_timestamp";
                    $returnable['updated_at'] = "datetime not null default current_timestamp on update current_timestamp";
                }
            }
            if ($data_key == "softDelete" && $arr['softDelete'] == true) {
                $returnable['deleted_at'] = "datetime default null on update current_timestamp";
            }
            if (is_string($data_value)) {
                $data_value = strtolower($data_value);
                $data_value = $data_value == "string" ?  self::replaceString("string", "varchar") . "(100)" : self::replaceString("integer", "int");
                $returnable[$data_key] = $data_value;
            } else if (is_array($data_value)) {

                if (isset($data_value['type'])) {
                    $data = strtolower($data_value['type']) == "string" ?  self::replaceString($data_value['type'], "varchar") . "(100)" : self::replaceString($data_value['type'], "int");
                    $returnable[$data_key] = $data;
                }
                if (isset($data_value['identity'])) $returnable[$data_key] .= " identity(" . $data_value['identity'] . ")";
                if (isset($data_value['increment'])  && $data_value["increment"] == true) $returnable[$data_key] .= ' auto_increment';
                if (isset($data_value['primary'])  && $data_value["primary"] == true) $returnable[$data_key] .= ' primary key';
                if (isset($data_value['foreignKey'])  && $data_value["foreignKey"] == true) {
                    $returnable[$data_key] .= ' foreign key';
                }
                if (isset($data_value['ref'])) {
                    $splitted = explode(".", $data_value['ref']);
                    $parent = $splitted[0];
                    $child = $splitted[1];
                    $returnable[$data_key] .= " references " . strtolower($parent) . "(" . $child . ")";
                }
                if (isset($data_value["onDelete"])) {
                    $returnable[$data_key] .= " on delete " . $data_value['onDelete'];
                }
                if (isset($data_value["onUpdate"])) {
                    $returnable[$data_key] .= " on update " . $data_value['onUpdate'];
                }

                if (isset($data_value['length'])) {
                    $pos = strpos($returnable[$data_key], $data);
                    $returnable[$data_key] = substr_replace($returnable[$data_key], "(" . $data_value['length'] . ")", $pos + strlen($data), 0);
                }

                if (isset($data_value["unique"]) && $data_value["unique"] == true) $returnable[$data_key] .= " unique";
                if (isset($data_value["unsigned"]) && $data_value["unsigned"] == true) $returnable[$data_key] .= " unsigned";
                if (isset($data_value["nullable"]) && $data_value["nullable"] == true) $returnable[$data_key] .= " null";

                if ((in_array("default", array_keys($data_value)))) {
                    if (is_null($data_value["default"])) $returnable[$data_key] .= " default null";
                    if (is_string($data_value["default"])) $returnable[$data_key] .= " default " . "'" . $data_value['default'] . "'";
                    else $returnable[$data_key] .= " default " . $data_value["default"];
                }
            }
        }

        foreach ($returnable as $key => $value) {
            if (strpos($value, "null") == false) {
                $returnable[$key] .= " not null";
            }
        }
        return $returnable;
    }
    private static function replaceString($from, $to)
    {
        $data = null;
        $from = strtolower($from);
        if ($from == "string") $data = $to;
        else if (in_array(($from), ['integer', 'smallinteger', 'mediuminteger', 'biginteger', '', 'tinyinteger'])) {
            $data = str_replace("integer", $to, ($from));
        }
        return $data;
    }

    static function isString($data)
    {
        return is_string($data);
    }
    static function isArray($data)
    {
        return is_array($data);
    }
    static function isInt($data)
    {
        return is_int($data);
    }
    static function isFloat($data)
    {
        return is_float($data);
    }
    static function isBool($data)
    {
        return is_bool($data);
    }
    static function isNull($data)
    {
        return is_null($data);
    }
    static function isObject($data)
    {
        return is_object($data);
    }
    static function isResource($data)
    {
        return is_resource($data);
    }

    static function removeFromBack($string, $remove)
    {
        return substr($string, 0, -$remove);
    }

    static function lastWord($string)
    {
        $words = explode(" ", $string);
        return trim($words[count($words) - 1]);
    }
}