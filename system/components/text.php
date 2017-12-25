<?php 
class Text_Component extends CS_Component{
	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}


		$component = '<input type="text" name="' . $this -> getVariable() . '" value="' . $this -> getValue() . '" ' . $attributes . '/>';

		echo $component;
	}
}

?>