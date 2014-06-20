<?php
session_start();

if (isset($_POST['toyid'])) {
  $toyid = htmlspecialchars($_POST['toyid']);
  unset($_POST['toyid']);
  $_SESSION[$toyid.'toyid'] = $toyid;
  $_SESSION[$toyid.'toyname'] = htmlspecialchars($_POST['toyname']);
  unset($_POST['toyname']);
  $_SESSION[$toyid.'toyprice'] = htmlspecialchars($_POST['price']);
  unset($_POST['toyprice']);
  $toyitems = htmlspecialchars($_POST['toyitems']);
  unset($_POST['toyitems']);
  $addedtoys = $toyitems - $_SESSION[$toyid.'toyitems'];
  $_SESSION[$toyid.'toyitems'] = $toyitems;
  $_SESSION['items'] += $addedtoys;
}

?>