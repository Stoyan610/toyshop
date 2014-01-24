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
<script type='text/javascript' src='<?=ADMINURL ?>js/jquery-1.10.2.min.js'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/jquery-ui-1.10.4.custom.min.js'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/pick.js'></script>
<script type='text/javascript' src='<?=ADMINURL ?>js/misc.js'></script>
<script type='text/javascript'>
	$(function(){
    $("#pick").datepicker();
  });
</script>
<style>
  body {background-color: #B3FFA5;}
</style>
</head>
<body>

  <ol>
    <li><a href="admin_controller.php?table=a_catalog">Категории</a></li>
    <li><a href="admin_controller.php?table=a_product">Товары</a></li>
    <li><a href="admin_controller.php?table=a_image">Изображения</a></li>
    <!-- ........................... -->
  </ol>
  