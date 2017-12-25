<?php 
class Data_Model extends WidgetModel{

	public function getClasses(){

		$id = $this -> tool_io -> get("id");

		// 根分類
		if($id == ""){

			$wheres = $values = array();
			$wheres[] = "level=0";

			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			$classes = $this -> tool_database -> findAll(
				$this -> widgetMeta -> data_source . "_class", 
				array(), 
				$wheres,
				$values, 
				array("topTime DESC", "sortTime ASC", "sequence ASC", "createTime DESC"), 
				$this -> widgetMeta -> class_count
			);
		}
		// 子分類
		else{

			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;

			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			$cur_class = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source . "_class", 
				array("id, level"), 
				$wheres,
				$values
			);

			if($cur_class -> isExists()){
				// 超過指定的層數
				if($cur_class -> level == $this -> widgetMeta -> class_level - 1){
					return "items";
				}
			}

			$wheres = $values = array();
			$wheres[] = "parentID=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			if(count($classes = $this -> tool_database -> findAll(
					$this -> widgetMeta -> data_source . "_class", 
					array(), 
					$wheres, 
					$values,
					array("topTime DESC", "sortTime ASC", "sequence ASC", "createTime DESC"), 
					$this -> widgetMeta -> class_count
				)) == 0){

				// 沒有子分類，試找看看有沒有項目
				if(count($items = $this -> tool_database -> findAll(
					$this -> widgetMeta -> data_source, 
					array(), 
					$wheres, 
					$values
					)) > 0){

					return "items";
				}

			};

		}

		return $classes;
	}

	public function getNavigation($is_item = false){
		$id = $this -> tool_io -> get("id");

		$navigation = array();

		if($id != ""){

			// ID 為項目識別碼，取得其父分類
			if($is_item){

				$wheres = $values = array();
				$wheres[] = "id=?";
				$values[] = $id;
				@session_start();
				if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
					$wheres[] = "domainID=?";
					$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
				}
				@session_write_close();

				$item = $this -> tool_database -> find(
					$this -> widgetMeta -> data_source, 
					array(),
					$wheres,
					$values
				);



				$wheres = $values = array();
				$wheres[] = "id=?";
				$values[] = $item -> parentID;
				@session_start();
				if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
					$wheres[] = "domainID=?";
					$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
				}
				@session_write_close();
				
				$class = $this -> tool_database -> find(
					$this -> widgetMeta -> data_source . "_class", 
					array(),
					$wheres,
					$values
				);
			}
			else{



				$wheres = $values = array();
				$wheres[] = "id=?";
				$values[] = $id;
				@session_start();
				if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
					$wheres[] = "domainID=?";
					$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
				}
				@session_write_close();

				$class = $this -> tool_database -> find(
					$this -> widgetMeta -> data_source . "_class", 
					array(),
					$wheres,
					$values
				);
			}

			$navigation[] = $class;

			while($class -> isExists()){


				$wheres = $values = array();
				$wheres[] = "id=?";
				$values[] = $class -> parentID;
				@session_start();
				if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
					$wheres[] = "domainID=?";
					$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
				}
				@session_write_close();
				
				$class = $this -> tool_database -> find(
					$this -> widgetMeta -> data_source . "_class", 
					array(),
					$wheres,
					$values
				);

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


		$wheres = $values = array();
		$wheres[] = "id=?";
		$values[] = $id;
		@session_start();
		if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
			$wheres[] = "domainID=?";
			$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
		}
		@session_write_close();

		$class = $this -> tool_database -> find(
			$this -> widgetMeta -> data_source . "_class", 
			array(), 
			$wheres, 
			$values
		);
		
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
			$data = $this -> tool_database -> emptyRecord(
				$this -> widgetMeta -> data_source . "_class"
			);
		}
		// 修改
		else{

			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source . "_class", 
				array(), 
				$wheres, 
				$values
			);
		}

		$metas = (object)$this -> widgetMeta -> class_fields;

		$components = $this -> covertMeta2Component($metas);

		$jsonContent = array();

		$hasCreateTime = false;
		$hasUpdateTime = false;

		foreach($components as $variable => $component){

			$input_value = $component -> getValue();
			if(isset($input_value)){
				if($data -> isKeyExists($variable)){

					if($variable == "createTime"){
						$hasCreateTime = true;
					}
					if($variable == "updateTime"){
						$hasUpdateTime = true;
					}
					
					$data -> $variable = $input_value;
				}
				else{
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

				$wheres = $values = array();
				$wheres[] = "id=?";
				$values[] = $parentID;
				@session_start();
				if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
					$wheres[] = "domainID=?";
					$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
				}
				@session_write_close();
				
				$class = $this -> tool_database -> find(
					$this -> widgetMeta -> data_source . "_class", 
					array("id","level"), 
					$wheres,
					$values
				);

				if($class -> isExists()){
					$data -> parentID = $class -> id;
					$data -> level = ((int)($class -> level)) + 1;
				}
			}

			$data -> id = uniqid(true);
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$data -> domainID = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();
			if(!$hasCreateTime){
				$data -> createTime = date("Y-m-d H:i:s");
			}
			if(!$hasUpdateTime){
				$data -> updateTime = date("Y-m-d H:i:s");
			}
			$data -> insert();
			$this -> tool_alert -> set_alert("新增成功");
		}
		else{
			if(!$hasUpdateTime){
				$data -> updateTime = date("Y-m-d H:i:s");
			}
			$data -> update();
			$this -> tool_alert -> set_alert("修改成功");
		}

	}

	public function saveSortClass(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $key => $id){
			

			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();
			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source . "_class", 
				array(), 
				$wheres, 
				$values
			);

			if($data -> isExists()){
				$data -> sequence = $key;
				$data -> sortTime = date("Y-m-d H:i:s");
				$data -> update();
			}
		}

		$this -> tool_alert -> set_alert("排序成功");
	}

	public function deleteClass(){
		
		$id = $this -> tool_io -> post("id");
		

		$wheres = $values = array();
		$wheres[] = "id=?";
		$values[] = $id;
		@session_start();
		if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
			$wheres[] = "domainID=?";
			$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
		}
		@session_write_close();


		$data = $this -> tool_database -> find(
			$this -> widgetMeta -> data_source . "_class", 
			array(), 
			$wheres, 
			$values
		);

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


			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();
			
			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source . "_class", 
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

	public function batchPublishClass(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $id){

			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source . "_class", 
				array(), 
				$wheres, 
				$values
			);

			if($data -> isExists()){
				$data -> publish = "Y";
				$data -> update();
			}
		}

		$this -> tool_alert -> set_alert("上架成功");
	}

	public function batchUnPublishClass(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $id){

			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();
			
			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source . "_class", 
				array(), 
				$wheres, 
				$values
			);

			if($data -> isExists()){
				$data -> publish = "";
				$data -> update();
			}
		}

		$this -> tool_alert -> set_alert("下架成功");
	}


	public function getItems(){

		$id = $this -> tool_io -> get("id");

		// 根分類
		if($id == ""){

			$wheres = $values = array();
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();


			$items = $this -> tool_database -> findAll(
				$this -> widgetMeta -> data_source, 
				array(),
				$wheres,
				$values,
				array("topTime DESC", "sortTime ASC", "sequence ASC", "createTime DESC"), 
				$this -> widgetMeta -> item_count
			);
		}
		else{


			$wheres = $values = array();
			$wheres[] = "parentID=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			$items = $this -> tool_database -> findAll(
				$this -> widgetMeta -> data_source, 
				array(), 
				$wheres, 
				$values,
				array("topTime DESC", "sortTime ASC", "sequence ASC", "createTime DESC"), 
				$this -> widgetMeta -> item_count
			);
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

		$wheres = $values = array();
		$wheres[] = "id=?";
		$values[] = $id;
		@session_start();
		if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
			$wheres[] = "domainID=?";
			$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
		}
		@session_write_close();

		$item = $this -> tool_database -> find(
			$this -> widgetMeta -> data_source, 
			array(), 
			$wheres, 
			$values
		);
		
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

	public function getSearchItems(){
		$metas = array();
		if(isset($this -> widgetMeta -> search_fields)){
			$meta = json_decode($this -> widgetMeta -> search_fields);
			$metas = ($meta == "") ? array() : $meta;
		}
		
		$wheres = $values = array();
		foreach($metas as $meta){
			$v = $this -> tool_io -> post($meta -> variable);
			$v = isset($v) ? $v : "";

			if($v != ""){
				$wheres[] = $meta -> sql;
			
				if( preg_match_all("/(LIKE )?\?/i", $meta -> sql, $param) ){
					foreach($param[0] as $param){
						if($param == "LIKE ?"){
							$values[] = "%" . $v . "%";
						}
						else{
							$values[] = $v;
						}
					}
				}
			}
		}

		@session_start();
		if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
			$wheres[] = "domainID=?";
			$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
		}
		@session_write_close();

		$items = $this -> tool_database -> findAll(
			$this -> widgetMeta -> data_source, 
			array(),
			$wheres,
			$values,
			array("topTime DESC", "sortTime ASC", "sequence ASC", "createTime DESC"), 
			$this -> widgetMeta -> item_count
		);

		
		
		
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

	public function delete(){
		$id = $this -> tool_io -> get("id");


		$wheres = $values = array();
		$wheres[] = "id=?";
		$values[] = $id;
		@session_start();
		if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
			$wheres[] = "domainID=?";
			$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
		}
		@session_write_close();
		
		$data = $this -> tool_database -> find(
			$this -> widgetMeta -> data_source, 
			array(), 
			$wheres, 
			$values
		);
		
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
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

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

	public function batchPublish(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $id){
			
			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source, 
				array(), 
				$wheres, 
				$values
			);

			if($data -> isExists()){
				$data -> publish = "Y";
				$data -> update();
			}
		}

		$this -> tool_alert -> set_alert("上架成功");
	}

	public function batchUnPublish(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $id){
			
			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source, 
				array(), 
				$wheres, 
				$values
			);

			if($data -> isExists()){
				$data -> publish = "";
				$data -> update();
			}
		}

		$this -> tool_alert -> set_alert("下架成功");
	}

	public function saveSort(){
		$ids = $this -> tool_io -> post("ids");
		foreach($ids as $key => $id){

			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();
			
			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source, 
				array(), 
				$wheres, 
				$values
			);

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
			$data = $this -> tool_database -> emptyRecord(
				$this -> widgetMeta -> data_source
			);
		}
		// 修改
		else{

			$wheres = $values = array();
			$wheres[] = "id=?";
			$values[] = $id;
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$wheres[] = "domainID=?";
				$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();

			$data = $this -> tool_database -> find(
				$this -> widgetMeta -> data_source, 
				array(), 
				$wheres, 
				$values
			);
		}

		$metas = (object)$this -> widgetMeta -> item_fields;

		$components = $this -> covertMeta2Component($metas);

		$jsonContent = array();

		$hasCreateTime = false;
		$hasUpdateTime = false;

		foreach($components as $variable => $component){

			$input_value = $component -> getValue();


			if(isset($input_value)){
				// echo gettype($data -> $variable) . "===" . empty($data -> $variable) . "<br />";
				if($data -> isKeyExists($variable)){

					if($variable == "createTime"){
						$hasCreateTime = true;
					}
					if($variable == "updateTime"){
						$hasUpdateTime = true;
					}

					// echo $variable . "=" . $input_value . " - IN<br />";
					$data -> $variable = $input_value;

				}
				else{
					// echo $variable . "=" . $input_value . " - OUT<br />";
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

				$wheres = $values = array();
				$wheres[] = "id=?";
				$values[] = $parentID;
				@session_start();
				if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
					$wheres[] = "domainID=?";
					$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
				}
				@session_write_close();
				
				$class = $this -> tool_database -> find(
					$this -> widgetMeta -> data_source . "_class", 
					array("id"), 
					$wheres,
					$values
				);
				if($class -> isExists()){
					$data -> parentID = $class -> id;
				}
			}


			$data -> id = uniqid(true);
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$data -> domainID = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();
			if(!$hasCreateTime){
				$data -> createTime = date("Y-m-d H:i:s");
			}
			if(!$hasUpdateTime){
				$data -> updateTime = date("Y-m-d H:i:s");
			}
			$data -> insert();
			$this -> tool_alert -> set_alert("新增成功");
		}
		else{
			if(!$hasUpdateTime){
				$data -> updateTime = date("Y-m-d H:i:s");
			}
			$data -> update();
			$this -> tool_alert -> set_alert("修改成功");
		}
	}

}
?>