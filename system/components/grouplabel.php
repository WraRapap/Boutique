<?php 
class Grouplabel_Component extends CS_Component{

	public function getLabel(){
		// 不需要欄位標題
	}

	public function render($attrs = array()){

		if(!isset($attrs["class"])){
			$attrs["class"] = "";
		}
		$attrs["class"] .= " grouplabel";

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		$component = '<div ' . $attributes . '>' . $this -> title . '</div>';

		echo $component;
	}
}

?>