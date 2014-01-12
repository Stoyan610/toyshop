<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

$root = $_SERVER['DOCUMENT_ROOT'];
$sample = '~[^\\/]*$~';
$pure_root = preg_replace($sample, '', $root);

define('PATH', $pure_root);

define('HOST', 'localhost:8889');

define('DB_PASS', 'root');


?>