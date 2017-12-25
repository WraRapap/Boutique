<?php 
class Hidden_Component extends CS_Component{

	public function getLabel(){
		// 不需要欄位標題
	}

	public function render($attrs = array()){

		$component = '<input type="hidden" name="' . $this -> getVariable() . '" value="' . $this -> getValue() . '"/>';

		echo $component;
	}
}

?>