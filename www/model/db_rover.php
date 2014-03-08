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
      if (($field_list[$i] != '*') && (stripos($field_list[$i],"COUNT(`") === FALSE) && (stripos($field_list[$i],"SUM(`") === FALSE) && (stripos($field_list[$i],"AVG(`") === FALSE) && (stripos($field_list[$i],"MIN(`") === FALSE) && (stripos($field_list[$i],"MAX(`") === FALSE) && (stripos($field_list[$i],"DISTINCT `") === FALSE))        $field_list[$i] = "`".$field_list[$i]."`";
			else {
				$fields = $field_list[$i];
				$flag = TRUE;
				break;
			}
		}
    if ($flag === FALSE) $fields = implode(",", $field_list);
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
  
  //------------- СПРАВОЧНЫЕ -----------------
  //Получение количества записей в базе
	public function СountData($table) {
		$result = $this->Choice($table, array("COUNT(`ID`)"));
		return $result[0]["COUNT(`ID`)"];
  }
  //Получение количества записей в базе, если известно условие на значение поля
	public function СountDataOnCondition($table, $infield, $sign, $invalue) {
		$cond = "`".$infield."`".$sign."'".addslashes($invalue)."'";
    $result = $this->Choice($table, array("COUNT(`ID`)"), $cond);
    return $result[0]["COUNT(`ID`)"];
  }
  //Получение количества записей в базе, если известно условие на значение поля
	public function СountDataOnManyConditions($table, $condition) {
		$result = $this->Choice($table, array("COUNT(`ID`)"), $condition);
    return $result[0]["COUNT(`ID`)"];
  }
  //Получение количества уникальных записей (с неповторяющимся значением поля)
	public function СountUniqField($table, $field) {
		$result = $this->Choice($table, array("COUNT(DISTINCT `".$field."`)"));
    $count = $result[0]["COUNT(DISTINCT `".$field."`)"];
		return $count;
  }
  //Получение уникальных (неповторяющихся) значений поля
	public function GetUniqField($table, $field) {
		return $this->Choice($table, "DISTINCT `".$field."`");
  }
  //Получение ID последней вставленной записи
	public function IdOfLast($table) {
		$result = $this->choice($table, array("MAX(`ID`)"));
		return $result[0]["MAX(`ID`)"];
	}
  
  //------------- ВЫБОРКА ИНФОРМАЦИИ ПО НЕСКОЛЬКИМ ПОЛЯМ -----------------
  //Получение нескольких записей и их сортировка
	public function ReceiveFields($table, $field_list, $sort='', $vozr=TRUE) {
    return $this->Choice($table, $field_list, '', $sort, $vozr);
  }
  //Получение нескольких полей записи по ID этой записи
	public function ReceiveFieldsOnId($table, $field_list, $id) {
		if ((!is_int($id)) && (!((is_string($id)) && (preg_match("~^(0|(-?\s?[1-9]\d*))$~", $id)))))    return FALSE;
		$result = $this->Choice($table, array('ID'), "`ID`='".$id."'");
		if (count($result) === 0)     return FALSE;
    $arr = $this->Choice($table, $field_list, "`ID`='".$id."'");
		return $arr[0];
  }
  //Получение нескольких полей записи, если известно условие на значение другого поля в этой записи
	public function ReceiveFewFieldsOnCondition($table, $field_list, $infield, $sign, $invalue) {
		$cond = "`".$infield."`".$sign."'".addslashes($invalue)."'";
    $result = $this->Choice($table, $field_list, $cond);
		if (count($result) === 0)     return FALSE;
    return $result;
  }
  //Получение информации нескольких полей записи, если известно условие на значение другого поля в этой записи
	public function ReceiveFewFieldsOnFullCondition($table, $field_list, $infield, $sign, $invalue, $sort='', $vozr=TRUE, $limit='') {
		$cond = "`".$infield."`".$sign."'".addslashes($invalue)."'";
    $result = $this->Choice($table, $field_list, $cond, $sort, $vozr, $limit);
    if (count($result) === 0)     return FALSE;
    return $result;
	}
  
  //------------- ВЫБОРКА ИНФОРМАЦИИ ПО ОДНОМУ ПОЛЮ -----------------
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
  //Получение информации в одном поле из записи, если известно условие на значение другого поля в этой записи
	public function ReceiveFieldOnFullCondition($table, $outfield, $infield, $sign, $invalue, $sort='', $vozr=TRUE, $limit='') {
		$cond = "`".$infield."`".$sign."'".addslashes($invalue)."'";
    $result = $this->Choice($table, array($outfield), $cond, $sort, $vozr, $limit);
    $num = count($result);
		if ($num < 1) return FALSE;
		$output = array();
		for ($i = 0; $i < $num; $i++) $output[$i] = $result[$i][$outfield];
		return $output;
	}
  //Получение информации в одном поле из записи, если известны условия
	public function ReceiveFieldOnManyConditions($table, $outfield, $condition) {
		$result = $this->Choice($table, array($outfield), $condition);
    $num = count($result);
		if ($num < 1) return FALSE;
		$output = array();
		for ($i = 0; $i < $num; $i++) $output[$i] = $result[$i][$outfield];
		return $output;
	}
  //Получение информации в одном поле из записи по условию - на выходе массив с ключами ID
	public function ReceiveIDFieldsOnCondition($table, $outfield, $condition='') {
		$result = $this->Choice($table, array($outfield, 'ID'), $condition);
    $num = count($result);
		if ($num < 1) return FALSE;
		$output = array();
		for ($i = 0; $i < $num; $i++) $output[$result[$i]['ID']] = $result[$i][$outfield];
		return $output;
	}
  
  //Получение случайной информации в одном поле из записи, если известно условие на значение другого поля в этой записи
	public function ReceiveRandomOnCondition($table, $outfield, $infield, $sign, $invalue, $limit) {
		$cond = "`".$infield."`".$sign."'".addslashes($invalue)."'";
    $arr = $this->choice($table, array($outfield), $cond, "RAND()", "", $limit);
    $n = count($arr);
    $output = array();
    for ($i = 0; $i < $n; $i++)     $output[$i] = $arr[$i][$outfield];
    return $output;
  }
  
  
  //------------- ОБРАБОТКА ИНФОРМАЦИИ -----------------
  //Вставка новой записи в таблицу
  public function DataIn($table, $fields_values) {
		//Подготовка названия таблицы для запроса
		$table = "`".$table."`";
    //Обработка массива ` и '-кавычками
    $new_f_v = array();
    foreach ($fields_values as $key => $value) {
      $key1 = "`".$key."`";
      if ($value === NULL)    $value1 = 'NULL';
      else $value1 = "'".$value."'";
      $new_f_v[$key1] = $value1;
    }
    //Подготовка названия полей для запроса
		$fields = implode(", ", array_keys($new_f_v));
		//подготовка значений полей для запроса
		foreach ($new_f_v as $value) $value = addslashes($value);
		$values = implode(", ", $new_f_v);
    $zapros = "INSERT INTO ".$table." (".$fields.") VALUES (".$values.")";
    $this->connection->query($zapros);
	}
  
  //Удалить запись по ID этой записи
	public function DataOffOnId($table, $id) {
		if ((!is_int($id)) && (!((is_string($id)) && (preg_match("~^(0|(-?\s?[1-9]\d*))$~", $id)))))    return FALSE;
    $table = "`".$table."`";
		$zapros = "DELETE FROM ".$table." WHERE `ID`='".$id."'";
    $this->connection->query($zapros);
  }  
  //Удалить запись по условию
	public function DataOffOnCondition($table, $infield, $sign, $invalue) {
		if ($this -> СountDataOnCondition($table, $infield, $sign, $invalue) == 0)   return FALSE;
    $table = "`".$table."`";
    $zapros = "DELETE FROM ".$table." WHERE `".$infield."`".$sign."'".addslashes($invalue)."'";
    $this->connection->query($zapros);
  }  
  
  //Изменить запись по ID записи
  public function ChangeDataOnId($table, $input, $id) {
    if ((!is_int($id)) && (!((is_string($id)) && (preg_match("~^(0|(-?\s?[1-9]\d*))$~", $id)))))    return FALSE;
    $table = "`".$table."`";
    $some = array();
    foreach ($input as $key => $val) {
      if ($key != 'ID')       $some[] = "`".$key."`='".$val."'"; 
    }
    $what = implode(",", $some);
    $zapros = "UPDATE ".$table." SET ".$what." WHERE `ID` = ".$id;
    $this->connection->query($zapros);
  }
  //Изменить одно поле по ID записи
  public function ChangeFieldOnId($table, $field, $val, $id) {
    if ((!is_int($id)) && (!((is_string($id)) && (preg_match("~^(0|(-?\s?[1-9]\d*))$~", $id)))))    return FALSE;
    $table = "`".$table."`";
    $what = "`".$field."`='".$val."'"; 
    $zapros = "UPDATE ".$table." SET ".$what." WHERE `ID` = ".$id;
    $this->connection->query($zapros);
  }


  public function __destruct() {
		if ($this->connection) $this->connection->close();
	}
}

?>