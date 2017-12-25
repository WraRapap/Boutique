<?php
class Master_Controller extends CS_Controller{

	public function main(){

		// 或許也可根據 parameters 的路徑來指定 action

		// 即可實踐出 由路徑當參數指定 news 或 about 這類的文章模組

		// 之後可做一支專門處理 UI 的 Model
		$this -> page();

	}

	public function login(){
		$this -> loadView("adminLTE/login", array(
			"information" 	=> $this -> model_master -> getInformation(),
			"route" => "master"
		));
	}

	public function doLogin(){
		if($this -> model_master -> doLogin()){
			echo json_encode(array("result" => "yes"));
		}
		else{
			echo json_encode(array("result" => "no" ));
		}
	}

	public function page(){

		if(!$this -> model_master -> checkLogin()){
			$this -> tool_go -> page($this -> config_env -> baseUrl . "/index.php/master/login.php");
		}
		/*
		@session_start();
		unset($_SESSION["url_history_type"]);
		unset($_SESSION["URL_HISTORY_TYPE"]);
		unset($_SESSION["master"]);
		$_SESSION["URL_HISTORY_TYPE"] = "master";
		@session_write_close();
		*/
		
		$this -> loadLayout("logo", "adminLTE/include/header_logo");
		$this -> loadLayout("footer", "adminLTE/include/footer");
		$this -> loadLayout("menu", "adminLTE/include/sidebar_menu");
		$this -> loadLayout("content", "adminLTE/include/content");

		$this -> loadView("adminLTE/index", array(
			"information" 	=> $this -> model_master -> getInformation(),
			"menu" 			=> $this -> model_master -> getMenu(),
			"widgets" 		=> $this -> model_master -> getPageInfo(),
		));
	}	
	
}
?>