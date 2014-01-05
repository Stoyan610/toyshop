<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
echo '№ 2 - Контроллер подключен<br />';

//Подключение модели - обработчика базы данных
require_once MODEL;
//Подключение создателей видов страниц
require_once VIEW.PAGE.'index.php';

?>