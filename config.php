<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
echo '№ 1 - Файл конфигурации подключен<br />';

//Домен - адрес
define('PATH', 'http://localhost:8888/toyshop.local/www/');
//Имя сайта
define('SITENAME', 'toyshop.local');
//Путь к модели - обработчику базы данных
define('MODEL', 'model/db_rover.php');
//Путь к контроллеру
define('CONTROLLER', 'controller/controller.php');
//Путь к видам
define('VIEW', 'views/');
//Путь к создателям страниц
define('PAGE', 'pages/');
//Путь к шаблонам
define('TEMPLATE', 'templates/');
//Путь к классам
define('CLASSES', 'classes/');

//Сервер БД
define('HOST', 'localhost:8889');
//БД
define('DB', 'toyshop');
//Пользователь БД
define('DB_USER', 'root');
//Пароль пользователя
define('DB_PASS', 'root');
//Префикс к паролям
define('PASS_PREFIX', 'multik');

//Таблицы базы данных - таблица товаров
define('TOYS', 'A_PRODUCT');
//Таблицы базы данных - таблица мультфильмов
define('MULTS', 'A_CATALOG');

//Название магазина
define('TITLE', 'Интернет магазин игрушек - героев мультфильмов');
//Длина "карусели"
define('N_MGR', 24);

//Имя администратора
define('ADMIN', 'Stoyan');
//Емеля администратора
define('ADMIN_EMAIL', 'stoyan.k-n@yandex.ru');

//Пустышка1 - картинка отсутствующей игрушки
define('EMPTY_TOY', 'emptytoy');
//Пустышка2 - картинка отсутствующего мультфильма
define('EMPTY_MULT', 'emptymult');


?>