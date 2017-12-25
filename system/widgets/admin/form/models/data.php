<?php 
class Data_Model extends WidgetModel{

	

	public function getItem(){

		$wheres = $values = array();
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



	public function save(){

		// 新增

		$wheres = $values = array();
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
		
		// 修改
		if($data -> isExists()){
			$data -> update();
			$this -> tool_alert -> set_alert("修改成功");
		}
		
		else{
			$data -> id = uniqid(true);
			@session_start();
			if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
				$data -> domainID = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
			}
			@session_write_close();
			$data -> insert();
			$this -> tool_alert -> set_alert("新增成功");
		}

	}

	

}
?>