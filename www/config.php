<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Домен - адрес и Сервер БД и Пароль пользователя
require_once 'config_address.php';
//Имя сайта
define('SITENAME', 'toyshop.local');
//Путь к видам
define('VIEW', 'views/');
//Путь к создателям страниц
define('PAGE', 'views/pages/');
//Путь к шаблонам
define('TEMPLATE', 'views/pages/templates/');
//Путь к классам
define('CLASSES', 'views/pages/classes/');
//Путь к картинкам
define('PICT', 'views/pages/pictures/');
//Путь к загружаемым картинкам
define('LOAD', 'views/pages/pictures/originalimage/');
//Путь к стилям
define('STYL', 'views/pages/styles/');
//Путь к скриптам
define('JS', 'views/pages/js/');
//Путь к модели - обработчику базы данных
define('MODEL', 'model/db_rover.php');
//Путь к контроллеру
define('CONTROLLER', 'controller/controller.php');

//ХОСТ
define('HOST', 'localhost');
//БД
define('DB', 'toyshop');
//Пользователь БД по умолчанию
define('DB_USER', 'root');
//Префикс к паролям
define('PASS_PREFIX', 'multik');

//Таблицы базы данных - таблица товаров
define('TOYS', 'a_product');
//Таблицы базы данных - таблица мультфильмов
define('MULTS', 'a_catalog');
//Таблицы базы данных - таблица изображений
define('IMG', 'a_image');
//Таблицы базы данных - таблица больших изображений
define('LIMG', 'l_image');
//Таблицы базы данных - таблица контента
define('INFO', 'a_content');

//Название магазина
define('TITLE', 'Интернет магазин игрушек - героев мультфильмов');
//Длина "карусели"
define('N_MGR', 24);

//Имя администратора
define('ADMIN', 'Stoyan');
//Емеля администратора
define('ADMIN_EMAIL', 'stoyan.k-n@yandex.ru');


?>