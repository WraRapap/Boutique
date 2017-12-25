<?php 
class Data_Model extends WidgetModel{

	

	public function getItem(){
		$item = $this -> tool_database -> find($this -> widgetMeta -> data_source);
		
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
		$data = $this -> tool_database -> find($this -> widgetMeta -> data_source);
		
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
			$data -> insert();
			$this -> tool_alert -> set_alert("新增成功");
		}


	}

	

}
?>