<?php 
class Data_Model extends WidgetModel{

	public function getClasses(){

		$id = $this -> tool_io -> get("id");

		// 根分類
		if($id == ""){
			$classes = $this -> tool_database -> findAll($this -> widgetMeta -> data_source . "_class");
		}
		// 子分類
		else{


			if(count($classes = $this -> tool_database -> findAll($this -> widgetMeta -> data_source . "_class", array(), array("parentID=?"), array($id))) == 0){

				// 沒有子分類，試找看看有沒有項目
				if(count($items = $this -> tool_database -> findAll($this -> widgetMeta -> data_source, array(), array("parentID=?"), array($id))) > 0){

					return "items";
				}

			};

		}

		return $classes;
	}

	public function getNavigation(){
		$id = $this -> tool_io -> get("id");

		$navigation = array();

		if($id != ""){
			$class = $this -> tool_database -> find($this -> widgetMeta -> data_source . "_class", array(),array("id=?"),array($id));
			$navigation[] = $class;

			while($class -> isExists()){
				$class = $this -> tool_database -> find($this -> widgetMeta -> data_source . "_class", array(),array("id=?"),array($class -> parentID));
				if($class -> isExists()){
					$navigation[] = $class;
				}
			}

			$navigation = array_reverse($navigation);
		}



		return $navigation;

	}

	public function getClass(){
		$id = $this -> tool_io -> get("id");

		$class = $this -> tool_database -> find($this -> widgetMeta -> data_source . "_class", array(), array("id=?"), array($id));
		// print_r($widget);
		if($class -> isExists()){
			$data = json_decode($class -> jsonContent);
			if($data != ""){
				foreach($data as $variable => $value){
					$class -> $variable = $value;
				}
				unset($class -> jsonContent);
			}
		}
		else{
			// print_r($class);
		}
		return $class;
	}

	public function saveClass(){

		$id = $this -> tool_io -> request("id");
		
		// 新增
		if($id == ""){
			$data = $this -> tool_database -> emptyRecord($this -> widgetMeta -> data_source . "_class");
		}
		// 修改
		else{
			$data = $this -> tool_database -> find($this -> widgetMeta -> data_source . "_class", array(), array("id=?"), array($id));
		}

		$metas = (object)$this -> widgetMeta -> class_fields;

		$components = $this -> covertMeta2Component($metas);

		$jsonContent = array();

		foreach($components as $variable => $component){

			$input_value = $component -> getValue();
			
			if($data -> isKeyExists($variable)){
				$data -> $variable = $input_value;
			}
			else{
				if(isset($input_value)){
					$jsonContent[$variable] = $input_value;
				}
			}
		}

		if(count($jsonContent) > 0){
			$data -> jsonContent = json_encode($jsonContent);
		}

		if($id == ""){

			// 父類別
			$parentID = $this -> tool_io -> post("parentID");
			if($parentID != ""){
				$class = $this -> tool_database -> find($this -> widgetMeta -> data_source . "_class", array("id","level"), array("id=?"),array($parentID));
				if($class -> isExists()){
					$data -> parentID = $class -> id;
					$data -> level = ((int)($class -> level)) + 1;
				}
			}


			$data -> id = uniqid(true);
			$data -> insert();
			$this -> tool_alert -> set_alert("新增成功");
		}
		else{
			$data -> update();
			$this -> tool_alert -> set_alert("修改成功");
		}
	}

	public function deleteClass(){
		$id = $this -> tool_io -> get("id");
		$data = $this -> tool_database -> find($this -> widgetMeta -> data_source . "_class", array(), array("id=?"), array($id));
		if($data -> isExists()){
			$data -> delete();
			$this -> tool_alert -> set_alert("刪除成功");
		}
		else{
			$this -> tool_alert -> set_alert("找不到此記錄");
		}
	}

	public function batchDeleteClass(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $id){
			$data = $this -> tool_database -> find($this -> widgetMeta -> data_source . "_class", array(), array("id=?"), array($id));
			if($data -> isExists()){
				$data -> delete();
			}
		}

		$this -> tool_alert -> set_alert("刪除成功");

	}


	public function getItems(){

		$id = $this -> tool_io -> get("id");

		// 根分類
		if($id == ""){
			$items = $this -> tool_database -> findAll($this -> widgetMeta -> data_source,array(),array(),array(),array("sortTime ASC", "sequence ASC", "createTime DESC"));
		}
		else{
			$items = $this -> tool_database -> findAll($this -> widgetMeta -> data_source, array(), array("parentID=?"), array($id),array("sortTime ASC", "sequence ASC", "createTime DESC"));
		}
		
		
		foreach($items as $key => $item){
			$data = json_decode($item -> jsonContent);
			if($data == ""){
				continue;
			}
			foreach($data as $variable => $value){
				$items[$key] -> $variable = $value;
			}
			unset($items[$key] -> jsonContent);
		}

		return $items;
	}

	public function getItem(){
		$id = $this -> tool_io -> get("id");

		$item = $this -> tool_database -> find($this -> widgetMeta -> data_source, array(), array("id=?"), array($id));
		// print_r($widget);
		if($item -> isExists()){
			$data = json_decode($item -> jsonContent);
			if($data != ""){
				foreach($data as $variable => $value){
					$item -> $variable = $value;
				}
				unset($item -> jsonContent);
			}
		}
		else{
			// print_r($item);
		}
		return $item;
	}

	public function delete(){
		$id = $this -> tool_io -> get("id");
		$data = $this -> tool_database -> find($this -> widgetMeta -> data_source, array(), array("id=?"), array($id));
		if($data -> isExists()){
			$data -> delete();
			$this -> tool_alert -> set_alert("刪除成功");
		}
		else{
			$this -> tool_alert -> set_alert("找不到此記錄");
		}
	}

	public function batchDelete(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $id){
			
			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
		

			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source, 
				array(), 
				$wheres, 
				$values
			);

			if($data -> isExists()){
				$data -> delete();
			}
		}

		$this -> tool_alert -> set_alert("刪除成功");
	}

	public function saveSort(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $key => $id){
			$data = $this -> tool_database -> find($this -> widgetMeta -> data_source, array(), array("id=?"), array($id));
			if($data -> isExists()){
				$data -> sequence = $key;
				$data -> sortTime = date("Y-m-d H:i:s");
				$data -> update();
			}
		}

		$this -> tool_alert -> set_alert("排序成功");
	}


	public function save(){

		$id = $this -> tool_io -> request("id");
		
		// 新增
		if($id == ""){
			$data = $this -> tool_database -> emptyRecord($this -> widgetMeta -> data_source);
		}
		// 修改
		else{
			$data = $this -> tool_database -> find($this -> widgetMeta -> data_source, array(), array("id=?"), array($id));
		}

		$metas = (object)$this -> widgetMeta -> item_fields;

		$components = $this -> covertMeta2Component($metas);

		$jsonContent = array();

		foreach($components as $variable => $component){

			$input_value = $component -> getValue();
			
			if($data -> isKeyExists($variable)){
				$data -> $variable = $input_value;
			}
			else{
				if(isset($input_value)){	
					$jsonContent[$variable] = $input_value;
				}
			}
		}

		if($data -> isKeyExists("jsonContent")){
			$data -> jsonContent = json_encode($jsonContent);
		}

		if($id == ""){

			// 父類別
			$parentID = $this -> tool_io -> post("parentID");
			if($parentID != ""){

				$class = $this -> tool_database -> find($this -> widgetMeta -> data_source . "_class", array("id"), array("id=?"), array($parentID));

				if($class -> isExists()){
					$data -> parentID = $class -> id;
				}
			}


			$data -> id = uniqid(true);
			$data -> insert();
			$this -> tool_alert -> set_alert("新增成功");
		}
		else{
			$data -> update();
			$this -> tool_alert -> set_alert("修改成功");
		}


		// 零件模組才能動態處理資料庫
		if(in_array($this -> widgetMeta -> data_source,array("sys_widget","sys_member"))){

			// 有分類
			if(isset($components["class_level"]) && $components["class_level"] -> getValue() > 0){
				$this -> handelDB($components["data_source"] -> getValue() . "_class", $components["class_fields"] -> getValue());
			}
			// 有特殊分類
			if(isset($components["special_class_level"]) && $components["special_class_level"] -> getValue() > 0){
				$this -> handelDB($components["data_source"] -> getValue() . "_special_class", $components["special_class_fields"] -> getValue());
			}
			$this -> handelDB($components["data_source"] -> getValue(), $components["item_fields"] -> getValue());
		}

	}


	private function handelDB($tableName, $fieldMetas = array()){
		// 判斷資料表是否存在
		$checkTableExistsSql = "SHOW TABLES LIKE '<prefix>{$tableName}';";
		$res = $this -> tool_database -> execute($checkTableExistsSql);

		// 不存在則建立
		if(count($res) == 0){
			$createTableSql = "CREATE TABLE <prefix>{$tableName} (
									id varchar(40) PRIMARY KEY NOT NULL COMMENT '識別碼', 
									domainID varchar(40) NULL COMMENT '網域識別碼',
									langID varchar(40) NULL COMMENT '語系識別碼',
									parentID varchar(40) NULL COMMENT '父分類或父項目識別碼',
									level int(11) NULL COMMENT '分類層數',
									jsonContent TEXT NULL COMMENT '額外欄位資訊',
									createTime DATETIME NULL COMMENT '建立時間',
									updateTime DATETIME NULL COMMENT '更新時間',
									sortTime DATETIME NULL COMMENT '排序時間',
									sequence int(11) NULL COMMENT '排列順序',
									topTime DATETIME NULL COMMENT '置頂時間'
								) ENGINE=MyISAM; ";
			$this -> tool_database -> execute($createTableSql);
		}



		// 取得資料庫原本就有的欄位
		$dbFields = $this -> tool_database -> getFields($tableName);

		// 0:能刪； 1:不能刪

		// 每個欄位處理初始化
		$fields = array();
		foreach ($dbFields as $fieldName => $fieldValue) {
			$fields[$fieldName] = 0;
		}
		
		// 欄位固定班底，不能刪
		$fields["id"] = 1;
		$fields["domainID"] = 1;
		$fields["langID"] = 1;
		$fields["parentId"] = 1;
		$fields["level"] = 1;
		$fields["jsonContent"] = 1;
		$fields["createTime"] = 1;
		$fields["updateTime"] = 1;
		$fields["sortTime"] = 1;
		$fields["sequence"] = 1;
		$fields["topTime"] = 1;


		$settingFields = json_decode($fieldMetas);
		
		foreach($settingFields as $field){
			// 設定的欄位原本就在資料表
			if(isset($fields[$field -> variable])){
				$fields[$field -> variable] = 1;
			}
			// 設定的欄位原本不在資料表
			else{
				$datatype = "VARCHAR(100)";
				if(in_array($field -> type,array("jquerydate","html5date"))){
					$datatype = "DATE";
				}
				else if(in_array($field -> type,array("jquerydatetime","html5datetime"))){
					$datatype = "DATETIME";
				}
				else if(in_array($field -> type,array("number"))){
					$datatype = "INT(11)";
				}
				else if(in_array($field -> type,array("toggle"))){
					$datatype = "VARCHAR(1)";
				}
				else if(in_array($field -> type,array("grouplabel", "tabstart", "tabend"))){
					continue;
				}
				else{ // if(in_array($field -> type,array("textarea","html","file","image","multiselect"))){
					$datatype = "TEXT";
				}


				$alterFieldSql = "ALTER TABLE <prefix>{$tableName} ADD {$field -> variable} {$datatype}  COMMENT '{$field -> title}';";
				$this -> tool_database -> execute($alterFieldSql);
			}
			
		}
	}

}
?>