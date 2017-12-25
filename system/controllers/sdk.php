<?php
class Sdk_Controller extends CS_Controller{

	public function __construct(){
		
	}

	public function main(){

	}

	public function login(){
		$code = $this -> getIO("code");
		$where = $this -> getIO("where");
		$value = $this -> getIO("value");
		$sort = $this -> getIO("sort");

		$databaseTableName = $this -> getDatabaseTableName($code);

		$where = ($where == "") ? array() : $where;
		$value = ($value == "") ? array() : $value;
		$sort = ($sort == "") ? array() : $sort;

		$item = $this -> tool_database -> find($databaseTableName, array(), $where, $value, $sort);

		if($item -> isExists()){
			@session_start();
			$_SESSION["USER_ID"] = $item -> id;
			$_SESSION["USER_NAME"] = $item -> title;
			@session_write_close();
			echo json_encode($item -> toArray());
		}
		else{
			echo json_encode($item -> toArray());
		}

	}

	public function checkLogin(){
		@session_start();
		if(isset($_SESSION["USER_ID"])){
			echo json_encode(array(
				"status" => "yes", 
				"userID" => $_SESSION["USER_ID"], 
				"userName" => $_SESSION["USER_NAME"])
			);
		}
		else{
			echo json_encode(array("status" => "no", "userID" => "none"));	
		}
		@session_write_close();
	}

	public function checkVerifyCode(){
		$verifycode = $this -> getIO("verifycode");
		@session_start();
		if(isset($_SESSION['seccode']) && $_SESSION['seccode'] == $verifycode){
			echo json_encode(array("status" => "yes"));
		}
		else{
			echo json_encode(array("status" => "no"));	
		}
		@session_write_close();
	}

	public function getItem(){

		$code = $this -> getIO("code");
		$where = $this -> getIO("where");
		$value = $this -> getIO("value");
		$sort = $this -> getIO("sort");


		$databaseTableName = $this -> getDatabaseTableName($code);
		
		$where = ($where == "") ? array() : $where;
		$value = ($value == "") ? array() : $value;
		$sort = ($sort == "") ? array() : $sort;

		$item = $this -> tool_database -> find($databaseTableName, array(), $where, $value, $sort);

		echo json_encode($item -> toArray());
	}

	public function getItems(){

		$code = $this -> getIO("code");
		$where = $this -> getIO("where");
		$value = $this -> getIO("value");
		$sort = $this -> getIO("sort");
		$count = $this -> getIO("count");
		if($count == 0 || $count == ""){
			$count = -1;
		}

		$databaseTableName = $this -> getDatabaseTableName($code);
		
		$where = ($where == "") ? array() : $where;
		$value = ($value == "") ? array() : $value;
		$sort = ($sort == "") ? array() : $sort;

		$items = $this -> tool_database -> findAll($databaseTableName, array(), $where, $value, $sort, $count);

		$results = array();
		foreach($items as $item){
			$results[] = $item -> toArray();
		}


		echo json_encode($results);
	}

	public function getClass(){

		$code = $this -> getIO("code");
		$where = $this -> getIO("where");
		$value = $this -> getIO("value");

		$databaseTableName = $this -> getDatabaseTableName($code);
		
		$where = ($where == "") ? array() : $where;
		$value = ($value == "") ? array() : $value;

		$item = $this -> tool_database -> find($databaseTableName . "_class", array(), $where, $value);

		echo json_encode($item -> toArray());
	}

	public function getClasses(){

		$code = $this -> getIO("code");
		$where = $this -> getIO("where");
		$value = $this -> getIO("value");
		$sort = $this -> getIO("sort");

		$databaseTableName = $this -> getDatabaseTableName($code);
		
		$where = ($where == "") ? array() : $where;
		$value = ($value == "") ? array() : $value;
		$sort = ($sort == "") ? array() : $sort;

		$classes = $this -> tool_database -> findAll($databaseTableName . "_class", array(), $where, $value, $sort);

		$results = array();
		foreach($classes as $class){
			$results[] = $class -> toArray();
		}


		echo json_encode($results);
	}

	public function saveForm(){
		$code = $this -> getIO("code");
		$datas = $this -> getIO("data");

		$databaseTableName = $this -> getDatabaseTableName($code);
		$fields = $this -> getItemFields($code);

		$item = $this -> tool_database -> emptyRecord($databaseTableName);

		// 欄位驗證
		$valids = array();
		foreach($datas as $key => $data){
			if(isset($fields[$key])){
				// 驗證是否格式相符
				$properties = $this -> covertProperties($fields[$key] -> properties);
				if(isset($properties -> required) && $data == ""){
					$valids[$key] = $fields[$key] -> name . " 為必填欄位";
				}
			}
		}
		if(count($valids) > 0){
			echo json_encode(array("status" => "no", "message" => $valids));
		}
		else{
			
			$item -> id = uniqid();
			foreach($datas as $key => $data){
				if(isset($fields[$key])){
					$item -> {$key} = $data;
				}
			}
			
			$item -> updateTime = date("Y-m-d H:i:s");
			$item -> createTime = date("Y-m-d H:i:s");
			$item -> insert();

			echo json_encode(array("status" => "yes"));
		}
	}

	public function modifyForm(){
		$id = $this -> getIO("id");
		$code = $this -> getIO("code");
		$datas = $this -> getIO("data");

		$databaseTableName = $this -> getDatabaseTableName($code);
		$item = $this -> tool_database -> find($databaseTableName, array(), array("id=?"), array($id));
		if(!$item -> isExists()){
			echo json_encode(array("status" => "no", "message" => "item not found!"));
		}
		else{
			foreach($datas as $key => $data){
				if($item -> isKeyExists($key)){
					$item -> {$key} = $data;
				}
			}

			$item -> updateTime = date("Y-m-d H:i:s");
			$item -> update();
			echo json_encode(array("status" => "yes"));	
		}
		
	}

	private function covertProperties($properties_string){

		if(is_string($properties_string)){

			$properties = array();

			$properties = explode(";", $properties_string);
			foreach($properties as $property){
				if (preg_match("/data_source{(.*?)}/", $property, $element)) {
					$properties["data_source"] = $element[1];
				}
				if (preg_match("/allow{(.*?)}/", $property, $element)) {
					$properties["allow"] = $element[1];
				}
				if (preg_match("/required/", $property, $element)) {
					$properties["required"] = true;
				}
				if (preg_match("/type{(.*?)}/", $property, $element)) {
					$properties["type"] = $element[1];
				}
				if (preg_match("/step{(.*?)}/", $property, $element)) {
					$properties["step"] = $element[1];
				}
				if (preg_match("/include{(.*?)}/", $property, $element)) {
					$propertyName = "config_" . $element[1];
					$properties["fields"] = $this -> $propertyName -> fields;
				}

			}
			
		}

		return (object)$properties;
	}

	private function getItemFields($code){
		if(trim($code) == ""){
			throw new Exception("Widget Code Can't Empty!");
		}

		$item = $this -> tool_database -> find("sys_widget", array("id","data_source"), array("code=?"), array($code));
		if(!$item -> isExists()){
			$item = $this -> tool_database -> find("sys_member", array(), array("code=?"), array($code));
			if(!$item -> isExists()){
				throw new Exception("Widget Code Not Found!");
			}
		}
		$data = json_decode($item -> jsonContent);
		$item_fields = json_decode($data -> item_fields);

		$fields = array();
		foreach($item_fields as $field){
			$fields[$field -> variable]	 = $field;
		}

		return $fields;
	}

	private function getDatabaseTableName($code){

		if(trim($code) == ""){
			throw new Exception("Widget Code Can't Empty!");
		}

		$item = $this -> tool_database -> find("sys_widget", array("id","data_source"), array("code=?"), array($code));
		if(!$item -> isExists()){
			$item = $this -> tool_database -> find("sys_member", array("id","data_source"), array("code=?"), array($code));
			if(!$item -> isExists()){
				throw new Exception("Widget Code Not Found!");
			}
		}
		return $item -> data_source;
	}

	private function getIO($key){
		$value = $this -> tool_io -> post($key, false);
		if(is_array($value) || json_decode($value) == ""){
			return $value;
		}
		else{
			return json_decode($value, true);
		}
	}
}
?>