<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';
//Проверка - если зашёл без логина, то на выход
$goback = $_SERVER['HTTP_REFERER'];
if (empty($_SESSION['login'])) {
  header("Location: ".$goback);
  exit;
}
//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once PATH.'www/'.MODEL;
$db = new DbRover($_SESSION['login'], $_SESSION['password']);

//Выстраивание приоритетов по порядку
function PriorityOrder($arr) {
  $c = array();
	$N = count($arr);
	for ($j = 0; $j < ($N-1); $j++) {
		$min = $arr[$j]['Priority'];
		$k = $j;
		for ($i = ($j+1); $i < $N; $i++) {
			if ($min > $arr[$i]['Priority']) {
				$min = $arr[$i]['Priority'];
				$k = $i;
			}
		}
		if ($k != $j) {
			$c = $arr[$j];
			$arr[$j] = $arr[$k];
			$arr[$k] = $c;
		}
		$arr[$j]['Priority'] = $j;
	}
	$arr[$N-1]['Priority'] = $N-1;
	return $arr;
}
  
$img = array();
$input = array();
//Проверка POST-параметра 
if (isset($_POST['changing'])) {
  //Формирование массива параметров изображений для замены в l-image
  $maxnum = htmlspecialchars($_POST['count']);
  unset($_POST['changing']);
  unset($_POST['count']);
  for ($num = 0; $num < $maxnum; $num++) {
    $img[$num]['ID'] = htmlspecialchars($_POST['ID-'.$num]);
    unset($_POST['ID-'.$num]);
    $img[$num]['Image_ID'] = htmlspecialchars($_POST['Image_ID-'.$num]);
    unset($_POST['Image_ID-'.$num]);
    $img[$num]['Priority'] = htmlspecialchars($_POST['Priority-'.$num]);
    unset($_POST['Priority-'.$num]);
    $img[$num]['del'] = htmlspecialchars($_POST['del-'.$num]);
    unset($_POST['del-'.$num]);
  }
  for ($num = 0; $num < $maxnum; $num++) {
    if ($img[$num]['del'])    $img[$num]['Priority'] = 100;
  }
  $img = PriorityOrder($img);
  for ($num = 0; $num < $maxnum; $num++) {
    $input['Image_ID'] = $img[$num]['Image_ID'];
    $input['Priority'] = $img[$num]['Priority'];
    $db->ChangeDataOnId(LIMG, $input, $img[$num]['ID']);
    if ($img[$num]['del'])    $db->DataOffOnId(LIMG, $img[$num]['ID']);
  }
}
elseif (isset($_POST['adding'])) {
  //Добавление нового фото
  unset($_POST['adding']);
  $fields_values['Product_ID'] = htmlspecialchars($_POST['toy_id']);
  unset($_POST['toy_id']);
  $fields_values['Image_ID'] = htmlspecialchars($_POST['toyimg_id']);
  unset ($_POST['toyimg_id']);
  $fields_values['Priority'] = htmlspecialchars($_POST['Priority']);
  unset($_POST['Priority']);
  //Проверка на наличие этого фото
  $check = $db->ReceiveFieldOnManyConditions(LIMG, 'Priority', "`Product_ID`='".$fields_values['Product_ID']."' AND `Image_ID`='".$fields_values['Image_ID']."'");
  if (!$check)   $db->DataIn(LIMG, $fields_values);
  //Получение массива данной игрушки
  $field_list = array('ID', 'Image_ID', 'Priority');
  $img = $db->ReceiveFewFieldsOnCondition(LIMG, $field_list, 'Product_ID', '=', $fields_values['Product_ID']);
  $img = PriorityOrder($img);
  $maxnum = count($img);
  for ($num = 0; $num < $maxnum; $num++) {
    $input['Image_ID'] = $img[$num]['Image_ID'];
    $input['Priority'] = $img[$num]['Priority'];
    $db->ChangeDataOnId(LIMG, $input, $img[$num]['ID']);
  }
}

//Возвращение на страницу product.php
header("Location: ".ADMINURL."product.php");

?>