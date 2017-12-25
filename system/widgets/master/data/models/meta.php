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

}
?>