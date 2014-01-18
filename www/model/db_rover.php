<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

class DbRover {
  private $connection;
  
  public function __construct($user, $pass) {
		$this->connection = new mysqli(HOST, $user, $pass, DB) or die("№ 3' - Can't set connection to DB");
		$this->connection->query("SET NAMES 'utf8'");
    
	}

  //------------------------------  
  //Проверка числа на положительность и целочисленность
  private function isPosInt($n) {
		if ($n < 0) return FALSE;
		if (is_int($n)) return TRUE;
		if (is_string($n)) {
			if (preg_match("~^(0|(-?\s?[1-9]\d*))$~", $n)) return TRUE;
		}
		return FALSE;
	}
  
  //------------------------------
  //Подготовка названия полей для запроса
  private function GetFields($field_list) {
    $fields = '';
		$flag = FALSE;
    $field_num = count($field_list);
    for ($i=0; $i<$field_num; $i++) {
			if (($field_list[$i] != '*') && (!stripos($field_list[$i],"COUNT(`")) && (!stripos($field_list[$i],"SUM(`")) && (!stripos($field_list[$i],"AVG(`")) && (!stripos($field_list[$i],"MIN(`")) && (!stripos($field_list[$i],"MAX(`")))        $field_list[$i] = "`".$field_list[$i]."`";
			else {
				$fields = $field_list[$i];
				$flag = TRUE;
				break;
			}
		}
    if ($flag == FALSE) $fields = implode(",", $field_list);
    return $fields;
	}
  
  //------------------------------
  //Получение массива выборки из базы данных
	private function Choice($table, $field_list, $condition='', $sort='', $vozr=TRUE, $limit='') {
    $fields = $this->GetFields($field_list);
    //подготовка названия таблицы для запроса
		$table = "`".$table."`";
    //подготовка способа сортировки для запроса
		switch ($sort) {
			case "": {
				if (!$sort) $sort = "ORDER BY `ID`";
				break;
			}
			case "RAND()": {
				$sort = "ORDER BY ".$sort;
				break;
			}
			default: {
				$sort = "ORDER BY `".$sort."`";
				if (!$vozr)    $sort .= " DESC";
			}
		}
    //Подготовка количества записей в выборке для запроса
		if (($limit) && ($this->isPosInt($limit) ))     $limit = "LIMIT ".$limit;
		else $limit = '';
    //Подготовка запроса по условию выборки
		if ($condition)     $zapros = "SELECT ".$fields." FROM ".$table." WHERE ".$condition." ".$sort." ".$limit;
		else $zapros = "SELECT ".$fields." FROM ".$table." ".$sort." ".$limit;
    //Формирование массива выборки
		$result = $this->connection->query($zapros);
		if (!$result) return FALSE;
		$data_arr = array();
		$i = 0;
		while ($record = $result->fetch_assoc()) {
			$data_arr[$i] = $record;
			$i++;
		}
		$result->close();
		return $data_arr;
	}
  
  //------------------------------
  //Получение количества записей в базе
	public function СountData($table) {
		$result = $this->Choice($table, array("COUNT('ID')"));
		return $result[0]["COUNT('ID')"];
  }
  //Получение всех записей и их сортировка
	public function ReceiveAll($table, $sort='', $vozr=TRUE) {
    return $this->Choice($table, array("*"), '', $sort, $vozr);
  }

  //------------------------------
  //Получение информации в одном поле из записи - на выходе массив с ключами ID
	public function ReceiveField($table, $outfield) {
		$result = $this->Choice($table, array($outfield, 'ID'));
    $num = count($result);
		if ($num < 1) return FALSE;
		$output = array();
		for ($i = 0; $i < $num; $i++) $output[$result[$i]['ID']] = $result[$i][$outfield];
		return $output;
	}
  
  //------------------------------
  //Получение информации в одном поле из записи, если известно условие на значение другого поля в этой записи
	public function ReceiveFieldOnCondition($table, $outfield, $infield, $sign, $invalue, $limit='') {
		$cond = "`".$infield."`".$sign."'".addslashes($invalue)."'";
    $result = $this->Choice($table, array($outfield), $cond, '', TRUE, $limit);
    $num = count($result);
		if ($num < 1) return FALSE;
		$output = array();
		for ($i = 0; $i < $num; $i++) $output[$i] = $result[$i][$outfield];
		return $output;
	}
  //------------------------------
  //Получение случайной информации в одном поле из записи, если известно условие на значение другого поля в этой записи
	public function ReceiveRandomOnCondition($table, $outfield, $infield, $sign, $invalue, $limit) {
		$cond = "`".$infield."`".$sign."'".addslashes($invalue)."'";
    return $this->choice($table, array($outfield), $cond, "RAND()", "", $limit);
  }
  
  //------------------------------
  //Вставка новой записи в таблицу
  public function DataIn($table, $fields_values) {
		//подготовка названия таблицы для запроса
		$table = "`".$table."`";
    //Обработка массива ` и '-кавычками
    $new_f_v = array();
    foreach ($fields_values as $key => $value) {
      $key1 = "`".$key."`";
      if ($value === NULL)    $value1 = 'NULL';
      else $value1 = "'".$value."'";
      $new_f_v[$key1] = $value1;
    }
    //подготовка названия полей для запроса
		$fields = implode(", ", array_keys($new_f_v));
		//подготовка значений полей для запроса
		foreach ($new_f_v as $value) $value = addslashes($value);
		$values = implode(", ", $new_f_v);
    $zapros = "INSERT INTO ".$table." (".$fields.") VALUES (".$values.")";
    $this->connection->query($zapros);
	}
  
  //------------------------------
  //Получение всех полей записи по ID этой записи
	public function ReceiveAllOnId($table, $id) {
		if ((!is_int($id)) && (!((is_string($id)) && (preg_match("~^(0|(-?\s?[1-9]\d*))$~", $id)))))    return FALSE;
		$result = $this->Choice($table, array('ID'), "`ID`='".$id."'");
		if (count($result) === 0)     return FALSE;
    $arr = $this->Choice($table, array("*"), "`ID`='".$id."'");
		return $arr[0];
  }
  

  
  
  
  
  
  //удалить запись по ID этой записи
	public function DataOffOnId($table, $id) {
		if ((!is_int($id)) && (!((is_string($id)) && (preg_match("~^(0|(-?\s?[1-9]\d*))$~", $id)))))    return FALSE;
    $table = "`".$table."`";
		$zapros = "DELETE FROM ".$table." WHERE `ID`='".$id."'";
    $this->connection->query($zapros);
  }  
  
  
  
  
  
  
  
  
  public function __destruct() {
		if ($this->connection) $this->connection->close();
	}
}

?>