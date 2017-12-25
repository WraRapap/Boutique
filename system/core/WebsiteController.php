<?php 

class WebsiteController extends CS_Controller{


	public function main($action){
		$this -> loadView($action);
	}

	protected function loadView($viewName, $datas = array()){

		$view = new WebsiteView();
		
		$view -> load($viewName, $this -> layouts, $datas);
		$view -> replaceResource();
		$view -> replaceTagToData();
		$view -> render();

		$this -> tool_alert -> render();

	}
	
}

?>