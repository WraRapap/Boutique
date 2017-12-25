<?php 
class Textarea_Component extends CS_Component{
	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}


		$component = '<textarea name="' . $this -> getVariable() . '" ' . $attributes . '>' . $this -> getValue() . '</textarea>';

		echo $component;
	}
}

?>