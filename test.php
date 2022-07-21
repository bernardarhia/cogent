<?php
function name(...$args, $callback)
{
    print_r(...$args);
    print_r($callback);
}
name("1", "2", "3", "4", "5", "function () {
}");