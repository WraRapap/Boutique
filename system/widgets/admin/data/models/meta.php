<?php 
class Meta_Model extends WidgetModel{

	public function getClassMeta(){
		return $this -> widgetMeta -> class_fields;
	}

	public function getSpecialClassMeta(){

	}

	public function getItemMeta(){
		return $this -> widgetMeta -> item_fields;
	}

	public function getSearchMeta(){
		if(isset($this -> widgetMeta -> search_fields)){
			$meta = json_decode($this -> widgetMeta -> search_fields);

			return ($meta == "") ? array() : $meta;
		}
		else{
			return array();
		}
	}


}
?>