<?php
class Tabend_Component extends CS_Component{
	
	public function getLabel(){
		// 不需要欄位標題
	}

	public function isTab(){
		return true;
	}
	
	
	private function newContent($content){
		@session_start();
		$_SESSION["tabContent"][] = $content;
		@session_write_close();
	}

	public function render($attributes = array()) {
		$content = ob_get_contents();
		ob_end_clean();

		$this -> newContent($content);

		@session_start();
		
		$render = "<div class=\"nav-tabs-custom\">
						<ul class=\"nav nav-tabs\">";
						foreach($_SESSION["tabName"] as $key => $tab){
							$active = ($key == 0) ? "active" : "";
		$render .=			"<li class=\"{$active}\"><a href=\"#tab_{$key}\" data-toggle=\"tab\">{$tab["name"]}</a></li>";
						}
        $render .="		</ul>
						<div class=\"tab-content\">";
						
						foreach($_SESSION["tabContent"] as $key => $content){
							$active = ($key == 0) ? "active" : "";
		$render .="			<div class=\"tab-pane {$active}\" id=\"tab_{$key}\">
							{$content}
							</div><!-- /.tab-pane -->";
						}
                            
		$render .="		</div><!-- /.tab-content -->
					</div><!-- nav-tabs-custom -->"	;

		
		unset($_SESSION["tabName"]);
		unset($_SESSION["tabContent"]);
	//	print_r($_SESSION["tab_component"]);
		@session_write_close();
		
		echo $render;
	}
	
}
?>