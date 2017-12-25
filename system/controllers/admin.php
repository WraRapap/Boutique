<?php
class Admin_Controller extends CS_Controller{

	public function main(){

		// 或許也可根據 parameters 的路徑來指定 action

		// 即可實踐出 由路徑當參數指定 news 或 about 這類的文章模組

		// 之後可做一支專門處理 UI 的 Model
		$this -> page();
	}

	public function login(){
		$this -> loadView("adminLTE/login", array(
			"information" 	=> $this -> model_admin -> getInformation(),
			"route" => "admin"
		));
	}

	public function doLogin(){
		if($this -> model_admin -> doLogin()){
			echo json_encode(array("result" => "yes"));
		}
		else{
			echo json_encode(array("result" => "no" ));
		}
	}

	public function page(){

		if(!$this -> model_admin -> checkLogin() || $this -> model_admin -> checkLogout()){
			$this -> tool_go -> page($this -> config_env -> baseUrl . "/index.php/admin/login.php");
		}
		/*
		@session_start();
		unset($_SESSION["url_history_type"]);
		unset($_SESSION["URL_HISTORY_TYPE"]);
		unset($_SESSION["admin"]);
		$_SESSION["URL_HISTORY_TYPE"] = "admin";
		@session_write_close();
		*/
		
		$this -> loadLayout("logo", "adminLTE/include/header_logo");
		$this -> loadLayout("footer", "adminLTE/include/footer");
		// $this -> loadLayout("userInfo", "adminLTE/include/sidebar_checkin");
		$this -> loadLayout("menu", "adminLTE/include/sidebar_menu");
		$this -> loadLayout("content", "adminLTE/include/content");
		$this -> loadLayout("contentTitle", "adminLTE/include/header");

		$this -> loadView("adminLTE/index", array(
			"information" 	=> $this -> model_admin -> getInformation(),
			"menu" 			=> $this -> model_admin -> getMenu(),
			"widgets" 		=> $this -> model_admin -> getPageInfo(),
			"pageTitle"		=> $this -> model_admin -> getPageTitle()
		));
	}	
	
}
?>