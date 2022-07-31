<?php

namespace Cogent\Interfaces;

interface SchemaInterface
{
    static function model(string $class, object $data, $options = null): object;
}