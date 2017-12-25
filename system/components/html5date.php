<?php 
class Html5date_Component extends CS_Component{
	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}


		$component = '<input type="date" name="' . $this -> getVariable() . '" value="' . date("Y-m-d",strtotime($this -> getValue())) . '" ' . $attributes . '/>';

		echo $component;
	}

	public function getText(){
		return date("Y-m-d", strtotime($this -> getValue()));
	}
}

?>