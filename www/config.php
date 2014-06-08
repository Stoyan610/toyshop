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
define('DB_USER', 'toyadmin');
//Пароль пользователя
define('DB_PASS', 'toyadmin');
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
//Таблицы базы данных - таблица клиентов
define('CLNTS', 'a_client');
//Таблицы базы данных - таблица заказов
define('ORDS', 'a_order');
//Таблицы базы данных - таблица корзины
define('BASKET', 'l_basket');
//Таблицы базы данных - таблица контента
define('INFO', 'a_content');
//Таблицы базы данных - таблица отзывов
define('REP', 'j_feedback');
//Таблицы базы данных - таблица аминистраторов
define('ADM', 's_admin_info');
//Таблицы базы данных - таблица категорий контента
define('CAT', 's_category');

//Название магазина
define('TITLE', 'Интернет магазин игрушек - героев мультфильмов');
//Длина "карусели"
define('N_MGR', 24);

//Стоимость доставки по Москве
define('MOSC', 300);
//Стоимость доставки по Московской области в пределах 15 км
define('MAREA', 500);
//Минимальные сроки доставки - дней
define('MINTIME', 1);

?>