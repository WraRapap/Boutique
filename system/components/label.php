<?php 
class Label_Component extends CS_Component{
	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}


		$component = '<input type="hidden" name="' . $this -> getVariable() . '" value="' . $this -> getValue() . '" ' . $attributes . '/><div style="border-bottom:1px dotted gray;">' . $this -> getValue() . '</div>';

		echo $component;
	}
}

?>