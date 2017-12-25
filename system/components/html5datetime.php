<?php 
class Html5datetime_Component extends CS_Component{
	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}


		$component = '<input type="datetime-local" name="' . $this -> getVariable() . '" value="' . $this -> getValue() . '" ' . $attributes . '/>';

		echo $component;
	}
}

?>