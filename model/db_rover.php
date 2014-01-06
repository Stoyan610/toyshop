<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

class DbRover {
  private $connection;
  
  public function __construct() {
		$this->connection = new mysqli(HOST, DB_USER, DB_PASS, DB) or die("№ 3' - Can't set connection to DB");
		$this->connection->query("SET NAMES 'utf8'");
    
	}

  //Эта функция готова...  
  //Проверка числа на положительность и целочисленность
  private function isPosInt($n) {
		if ($n < 0) return FALSE;
		if (is_int($n)) return TRUE;
		if (is_string($n)) {
			if (preg_match("~^(0|(-?\s?[1-9]\d*))$~", $n)) return TRUE;
		}
		return FALSE;
	}
  //Эта функция готова...
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
  //Эта функция готова...
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
				if (!$vozr)    $sort .= "DESC";
			}
		}
    //подготовка количества записей в выборке для запроса
		if (($limit) && ($this->isPosInt($limit) ))     $limit = "LIMIT ".$limit;
		else $limit = '';
    //подготовка запроса по условию выборки
		if ($condition)     $zapros = "SELECT ".$fields." FROM ".$table_name." WHERE ".$condition." ".$sort." ".$limit;
		else $zapros = "SELECT ".$fields." FROM ".$table_name." ".$sort." ".$limit;
    //формирование массива выборки
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
  //Эта функция готова...
  //Получение количества записей в базе
	public function СountData($table) {
		$result = $this->Choice($table, array("COUNT('ID')"));
		return $result[0]["COUNT('ID')"];
  }
  //Эта функция готова...
  //Получение информации в одном поле из записи
	public function ReceiveField($table, $outfield) {
		$result = $this->Choice($table, array($outfield));
    $num = count($result);
		if ($num < 1) return FALSE;
		$output = array();
		for ($i = 0; $i < $num; $i++) $output[$i] = $result[$i][$outfield];
		return $output;
	}
  //Эта функция готова...
  //Получение информации в одном поле из записи, если известно условие на значение другого поля в этой записи
	public function ReceiveFieldOnCondition($table, $outfield, $infield, $sign, $invalue, $limit='') {
		$result = $this->Choice($table, array($outfield), "`".$infield."`".$sign."'".addslashes($invalue)."'", '', TRUE, $limit);
    $num = count($result);
		if ($num < 1) return FALSE;
		$output = array();
		for ($i = 0; $i < $num; $i++) $output[$i] = $result[$i][$outfield];
		return $output;
	}
  //Эта функция готова...
  //получение случайной информации в одном поле из записи, если известно условие на значение другого поля в этой записи
	public function ReceiveRandomOnCondition($table, $outfield, $infield, $sign, $invalue, $limit) {
		return $this->choice($table_name, array($outfield), "`".$infield."`".$sign."'".addslashes($invalue)."'", "RAND()", "", $limit);
}

  
  
  
  public function __destruct() {
		if ($this->connection) $this->connection->close();
	}
}



echo '№ 3 - Модель подключена, и подключение к базе данных установлено<br />';




?>