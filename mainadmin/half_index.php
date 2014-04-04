<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>Administrator page</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<link rel='stylesheet' type='text/css' href='styles/jquery-ui-1.10.4.custom.min.css' />
<!--
<link rel='stylesheet' type='text/css' href='styles/smoothness/jquery-ui-1.8.13.custom.css' media='screen' charset='utf-8'>
-->
<link rel='stylesheet' type='text/css' href='styles/elrte.min.css' media='screen' charset='utf-8'>
<script type='text/javascript' src='<?=ADMINURL ?>js/jquery-1.10.2.min.js'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/jquery-ui-1.10.4.custom.min.js'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/jquery-1.6.1.min.js' charset='utf-8'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/jquery-ui-1.8.13.custom.min.js' charset='utf-8'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/pick.js'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/misc.js'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/elrte.min.js' charset='utf-8'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/i18n/elrte.ru.js' charset='utf-8'></script>
<script type='text/javascript'>
	$(function(){
    $("#pick").datepicker();
  });
</script>
<script type='text/javascript' charset='utf-8'>
  $().ready(function() {
    var opts = {
      cssClass : 'el-rte',
      lang     : 'ru',
      height   : 250,
      toolbar  : 'complete',
      cssfiles : ['css/elrte-inner.css']
    }
    $('#editor').elrte(opts);
  });
</script>
<style>
  body {background-color: #B3FFA5;}
	.list {display: inline-block; padding: 0 45px 0 0;}
</style>
</head>
<body>

  <ul style="background-color: #8AE78A; padding: 10px 0 10px 30px; margin: 0; font-size: 125%;">
    <div class='list'><li><a href="admin_controller.php?table=a_catalog">Категории</a></li></div>
    <div class='list'><li><a href="admin_controller.php?table=a_product">Товары</a></li></div>
    <div class='list'><li><a href="admin_controller.php?table=a_image">Изображения</a></li></div>
    <div class='list'><li><a href="admin_controller.php?table=a_client">Клиенты</a></li></div>
    <div class='list'><li><a href="admin_controller.php?table=a_order">Заказы</a></li></div>
    <div class='list'><li><a href="admin_controller.php?table=a_content">Контент</a></li></div>
    <div class='list'><li><a href="admin_controller.php?table=j_feedback">Отзывы</a></li></div>
  </ul>
  