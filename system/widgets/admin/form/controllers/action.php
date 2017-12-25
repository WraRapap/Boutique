<?php
class Action_Widget extends WidgetController{

	public function main(){
		$this -> edit();
	}

	public function edit(){
		$this -> loadView("edit", array(
			"metas" => $this -> widgetModel_meta -> getItemMeta(),
			"data" => $this -> widgetModel_data -> getItem()
		));
	}

	public function save(){
		$this -> widgetModel_data -> save();
		$this -> tool_go -> back();
	}
}
?>