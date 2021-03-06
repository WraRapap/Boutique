<?php 

require_once(dirname(__FILE__) . "/record.php");

class Database_Tool extends CS_Tool{

	private function getInstance(){

		if(strtolower($this -> config_db -> module == "mysql")){
			require_once(dirname(__FILE__) . "/modules/mysql.php");
			return CS_MySQL::get_database($this -> config_db);
		}

		if(strtolower($this -> config_db -> module == "mysqli")){
			require_once(dirname(__FILE__) . "/modules/mysqli.php");
			return CS_MySQLi::get_database($this -> config_db);
		}
	}
	
	public function findAll($tableName, $fields = array(), $where = array(), $values = array(), $sort = array(), $count = -1){
		$instance = $this -> getInstance();
		

		// 指定顯示欄位(不指定，預設全載入)
		$field_string = "";
		if($fields == array()){
			$field_string = "*";
		}
		else{
			$field_string = implode(", ", $fields);
		}

		// 不曉得為什麼…但可以防止 params 參數被父類的 __get 呼叫(吸走)!!!!
		$instance -> nani();

		// 條件式
		$where_string = "";

		if (count($where) > 0) {

			$where_string = "WHERE " . implode(" AND ", $where);
			foreach ($values as $value) {
				$instance -> embedData($value);
			}

		}


		// 排序條件
		$sort_string = "";
		if (count($sort) > 0) {
			$sort_string = "ORDER BY " . implode(", ", $sort);
		}
		
		$sql = "SELECT {$field_string} FROM <prefix>{$tableName} {$where_string} {$sort_string};";

		$instance -> embedCommand($sql);
		$res = $instance -> execute_query($count);

		foreach($res as $key => $r){
		 	$res[$key] = new CS_Record($tableName, $r);
		}

		return $res;
	}

    public function moreTableFindAll($tableName, $fields = array(), $where = array(), $values = array(), $sort = array(),$group=array(), $count = -1,$returnTotal=false){
        $instance = $this -> getInstance();


        // 指定顯示欄位(不指定，預設全載入)
        $field_string = "";
        if($fields == array()){
            $field_string = "*";
        }
        else{
            $field_string = implode(", ", $fields);
        }

        // 不曉得為什麼…但可以防止 params 參數被父類的 __get 呼叫(吸走)!!!!
        $instance -> nani();

        // 條件式
        $where_string = "";

        if (count($where) > 0) {

            $where_string = "WHERE " . implode(" AND ", $where);
            foreach ($values as $value) {
                $instance -> embedData($value);
            }

        }

        //分组
        $group_string = "";
        if (count($group) > 0) {
            $group_string = " Group BY " . implode(", ", $group);
        }

        // 排序條件
        $sort_string = "";
        if (count($sort) > 0) {
            $sort_string = " ORDER BY " . implode(", ", $sort);
        }

        $sql = "SELECT {$field_string} FROM {$tableName} {$where_string} {$group_string}{$sort_string};";
        $instance -> embedCommand($sql);
        return $instance -> execute_query($count,$returnTotal);
    }

	public function find($tableName, $fields = array(), $where = array(), $values = array(), $sort = array()){
		
		$instance = $this -> getInstance();

		$res = $this -> findAll($tableName, $fields, $where, $values, $sort, 1);

		if (count($res) > 0) {
			return $res[0];
		} else {
			return $this -> emptyRecord($tableName);
		}
	}

    public function moreTableFind($tableName, $fields = array(), $where = array(), $values = array(), $sort = array(),$group=array()){

        $instance = $this -> getInstance();

        $res = $this -> moreTableFindAll($tableName, $fields, $where, $values, $sort, $group,1);

        if (count($res) > 0) {
            return $res[0];
        } else {
            return $res;
        }
    }

	public function emptyRecord($tableName){
		$fields = $this -> getFields($tableName);
		
		return new CS_Record($tableName, $fields);
		// $instance = $this -> getInstance();
		//$this -> getFields();
	}



	public function insert($tableName, $data) {

		$instance = $this -> getInstance();


		$fields = array();
		$params = array();
		foreach ($data as $fieldName => $value) {
			$fields[] = $fieldName;
			$params[] = "?";
			$instance -> embedData($value);
		}

		$sql = "INSERT INTO <prefix>{$tableName} (" . implode(",", $fields) . ") VALUES (" . implode(",", $params) . ");";

		$instance -> embedCommand($sql);
		$instance -> execute();

		// $data["id"] = mysql_insert_id();
		
		return $data["id"];
	}


	/**
	 * 更新資料
	 */
	public function update($tableName, $data) {

		if (!isset($data["id"])) {
			throw new Exception("Id is undefined when Update!");
		}
		$instance = $this -> getInstance();

		$pairs = array();
		foreach ($data as $fieldName => $value) {
			$pairs[] = $fieldName . "=?";
			$instance -> embedData($value);
		}

		$instance -> embedData($data["id"]);

		$sql = "UPDATE <prefix>{$tableName} SET " . implode(",", $pairs) . " WHERE id=?;";

		$instance -> embedCommand($sql);
		$instance -> execute();

		return true;
	}

	/**
	 * 刪除資料
	 */
	public function delete($tableName, $data) {

		
		if (!isset($data["id"])) {
			throw new Exception("Id is undefined when Delete!");
		}

		$instance = $this -> getInstance();

		$instance -> embedData($data["id"]);

		$sql = "DELETE FROM <prefix>{$tableName} WHERE id=?;";

		$instance -> embedCommand($sql);
		$instance -> execute();

		return true;
	}

    public function transaction($actions = array()){
        $instance = $this -> getInstance();
        $excuteSql = array();
        foreach ($actions as $action){
            switch ($action[0]){
                case "1":
                    $excuteSql[]= $this->insertSql($instance,$action[1]->getTableName(),$action[1]->toArray());
                    break;
                case "2":
                    $excuteSql[]= $this->updateSql($instance,$action[1]->getTableName(),$action[1]->toArray());
                    break;
                case "3":
                    $excuteSql[]= $this->deleteSql($instance,$action[1]->getTableName(),$action[1]->toArray());
                    break;
                case "4":
                    $excuteSql[]= $this->customizeSql($instance,$action[1],$action[2]);//$action：1=>sql;2=>datas
                    break;
            }
        }
        return $instance->executeTransaction($excuteSql);
    }
    public function insertSql($instance,$tableName, $data) {
        $fields = array();
        $params = array();
        foreach ($data as $fieldName => $value) {
            $fields[] = $fieldName;
            $params[] = "?";
            $instance -> embedData($value);
        }

        $sql = "INSERT INTO <prefix>{$tableName} (" . implode(",", $fields) . ") VALUES (" . implode(",", $params) . ");";
        $instance -> embedCommand($sql);
       return  $instance -> getSqlAndClearParams($sql);
    }
    public function updateSql($instance,$tableName, $data) {

        if (!isset($data["id"])) {
            throw new Exception("Id is undefined when Update!");
        }

        $pairs = array();
        foreach ($data as $fieldName => $value) {
            $pairs[] = $fieldName . "=?";
            $instance -> embedData($value);
        }

        $instance -> embedData($data["id"]);

        $sql = "UPDATE <prefix>{$tableName} SET " . implode(",", $pairs) . " WHERE id=?;";
        $instance -> embedCommand($sql);
        return $instance -> getSqlAndClearParams($sql);
    }
    public function deleteSql($instance,$tableName, $data) {


        if (!isset($data["id"])) {
            throw new Exception("Id is undefined when Delete!");
        }

        $instance -> embedData($data["id"]);

        $sql = "DELETE FROM <prefix>{$tableName} WHERE id=?;";
        $instance -> embedCommand($sql);
        return $instance -> getSqlAndClearParams($sql);
    }
    public function customizeSql($instance,$sql, $datas) {
       foreach ($datas as $data){
           $instance -> embedData($data);
       }
        $instance -> embedCommand($sql);
        return $instance -> getSqlAndClearParams($sql);
    }

	/**
	 * 執行SQL語法
	 */
	public function execute($sql){

		$instance = $this -> getInstance();
		$instance -> embedCommand($sql);
		return $instance -> execute_query();;
	}

	/**
	 * 取得所有欄位名稱
	 * @return string[] $fields
	 */
	public function getFields($tableName) {
		$instance = $this -> getInstance();
		$sql = "DESCRIBE <prefix>{$tableName};";
		$instance -> embedCommand($sql);
		$res = $instance -> execute_query();
		$fields = array();
		foreach ($res as $field) {
			$fields[$field["Field"]] = "";
		}
		return $fields;
	}
}
?>