<?php
session_start();

if (isset($_POST['continue_x'])) {
  $_SESSION['thisorderid'] = htmlspecialchars($_SESSION['orderid']);
  unset($_SESSION['orderid']);
  unset($_POST['continue_x']);
  unset($_POST['continue_y']);
}

//Возвращение на страницу заказа
header("Location: index.php?page=order");

?>