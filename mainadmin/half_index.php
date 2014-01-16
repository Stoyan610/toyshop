<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>Administrator page</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<style>
  body {background-color: #B3FFA5;}
</style>
</head>
<body>

  <ol>
    <li><a href="admin_controller.php?table=A_CATALOG">Категории</a></li>
    <li><a href="admin_controller.php?table=A_PRODUCT">Товары</a></li>
    <li><a href="admin_controller.php?table=A_IMAGE">Изображения</a></li>
    <!-- ........................... -->
  </ol>
  