<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);
//Подключение конфигурационного файла
require_once 'config_address.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>XXX</title>

<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<script type='text/javascript' src='<?=ADMINURL ?>js/jquery-1.10.2.min.js'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/entrance.js'></script>

</head>
<body>
  
  <h1 id='emp' onclick="entrance()">Форма - пуста,<br />Пустота - есть форма,<br />Пустота - пуста...</h1>
  
</body>

</body>
</html>