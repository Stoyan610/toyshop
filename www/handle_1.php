<?php

session_start();

if (isset($_POST['toyid'])) {
  $n = htmlspecialchars($_POST['toyid']);
  unset($_POST['toyid']);
  unset($_SESSION[$n.'toyid']);
  unset($_SESSION[$n.'toyname']);
  unset($_SESSION[$n.'toyprice']);
  unset($_SESSION[$n.'toyitems']);
}

?>