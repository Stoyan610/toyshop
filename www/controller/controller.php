<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение модели - обработчика базы данных
require_once MODEL;
//Подключение создателей видов страниц
require_once PAGE.'index.php';

?>