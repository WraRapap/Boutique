<?php
class Tabstart_Component extends CS_Component{
	
	
	public function getLabel(){
		// 不需要欄位標題
	}

	public function isTab(){
		return true;
	}

	public function render($attrs = array()) {
		$content = ob_get_contents();
		ob_end_clean();

		@session_start();
		$count = isset($_SESSION["tabName"]) ? count($_SESSION["tabName"]) : 0;
		@session_write_close();

		if($count == 0 && $content != ""){
			echo $content;
		}
		else{
			$this -> newContent($content);
		}
		$this -> newTab();

		ob_start();
	}

	private function newContent($content){
		@session_start();
		$_SESSION["tabContent"][] = $content;
		@session_write_close();
	}
	
	
	private function newTab(){
		@session_start();
		$_SESSION["tabName"][] = array(
			"name" => $this -> getTitle(),
			"variable" => $this -> getVariable()
		);
		@session_write_close();
	}
	
}
?>