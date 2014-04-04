<?php
session_start();

//Подключение модели - обработчика базы данных
define('ACCESS', TRUE);
require_once 'config.php';
/*
require_once MODEL;

$db = new DbRover(DB_USER, DB_PASS);
*/

//Формирование текста сообщения для отправки отзыва на емейл администратора
if (!isset($_POST['new_comment_x'])) exit();

$str_date = "Дата создания отзыва: ".htmlspecialchars($_POST['issuedate']);
unset($_POST['issuedate']);
$str_name = "Имя автора отзыва: ".htmlspecialchars($_POST['author']);
unset($_POST['author']);
$text = "Текст отзыва: ".htmlspecialchars($_POST['com_content']);
unset($_POST['com_content']);
unset($_POST['new_comment_x']);
unset($_POST['new_comment_y']);

// ? ? ? ? ? ? ? ? ? ? ? ? ? ? ?
//Отправка емейла администратору
$to_whom = ADMIN_EMAIL;
$from = SEND_EMAIL;
$subject = "Новый отзыв. ".$str_name;
$add_headers = "From: ".$from."\r\nReply_To: nowhere@nomail.non\r\nConent-type: text/plain; charset=utf-8\r\n";
$message = $str_name.'\n'.$str_date.'\n';
do {
  $str_rep = mb_substr($text, 0, 70, 'utf-8');
  $message .= $str_rep.'\n';
  $text = str_replace($str_rep, '', $text);
  $lng = mb_strlen($text, 'utf-8');
}
while ($lng != 0);

if (!mail($to_whom, $subject, $message, $add_headers))      exit ("не O.K.");
// ? ? ? ? ? ? ? ? ? ? ? ? ? ? ?


//Возвращение на страницу отзывов
header("Location: index.php?page=comments");

?>