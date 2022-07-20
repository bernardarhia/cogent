<?php
$str = "kofi and ama are going ";
$strlen = strlen($str);
$splitted = explode(" ", trim($str));
$lastWord = $splitted[count($splitted) - 1];
echo strlen($lastWord);
echo substr($str, 0, -strlen($lastWord) - 1);