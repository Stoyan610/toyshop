<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Адрес домена
$root = $_SERVER['SCRIPT_FILENAME'];
$sample = '~[^\\/]*[\\/][^\\/]*$~';
$pure_root = preg_replace($sample, '', $root);

//Для Денвера ничего не добавляется, а для MAMP добавляется 'htdoc/toyshop.local/' - ??? Надо проверить
//define('PATH', $pure_root.'htdoc/toyshop.local/');
define('PATH', $pure_root);

//URL-адрес админки - Для Денвера 'mainadmin.toyshop.local/' , а для MAMP 'http://localhost:8888/toyshop.local/mainadmin/'
define('ADMINURL', 'http://localhost:8888/toyshop.local/mainadmin/');
//define('ADMINURL', 'http://mainadmin.toyshop.local/');

//URL-адрес сайта - Для Денвера 'mainadmin.toyshop.local/' , а для MAMP 'http://localhost:8888/toyshop.local/www/'
define('SITEURL', 'http://localhost:8888/toyshop.local/www/');
//define('SITEURL', 'http://toyshop.local/');

//Для Денвера пароль '', а для MAMP пароль 'root'
define('DB_PASS', 'root');
//define('DB_PASS', '');

?>