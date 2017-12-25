<?php 
class Number_Component extends CS_Component{
	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		$properties = ($this -> getProperties());
		$step = (isset($properties -> step)) ? "step=\"{$properties -> step}\"" : "";
			


		$component = '<input type="number" name="' . $this -> getVariable() . '" value="' . $this -> getValue() . '" ' . $attributes . ' ' . $step . '/>';

		echo $component;
	}
}

?>