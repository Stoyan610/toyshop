<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Домен - адрес и Сервер БД и Пароль пользователя
require_once 'config_address.php';
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
//Путь к картинкам
define('PICT', 'pictures/');

//БД
define('DB', 'toyshop');
//Пользователь БД
define('DB_USER', 'root');
//Префикс к паролям
define('PASS_PREFIX', 'multik');

//Таблицы базы данных - таблица товаров
define('TOYS', 'A_PRODUCT');
//Таблицы базы данных - таблица мультфильмов
define('MULTS', 'A_CATALOG');
//Таблицы базы данных - таблица контента
define('INFO', 'A_CONTENT');

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