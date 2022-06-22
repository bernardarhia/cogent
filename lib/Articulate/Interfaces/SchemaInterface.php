<?php

namespace Articulate\Interfaces;

interface SchemaInterface
{
    static function model(string $class, array $data): object;
}