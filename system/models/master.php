<?php 
class Master_Model extends CS_Model{

	public function doLogin(){
		$account = $this -> tool_io -> post("account");
		$password = $this -> tool_io -> post("password");

		if($account == $this -> config_master -> developer["account"] &&
			$password == $this -> config_master -> developer["password"]){
			@session_start();
			$_SESSION[$_SERVER["HTTP_HOST"] . "_masterID"] = "master";
			@session_write_close();
			return true;
		}

		return false;
	}

	public function checkLogin(){
		@session_start();
		$value = (isset($_SESSION[$_SERVER["HTTP_HOST"] . "_masterID"]) 
			&& $_SESSION[$_SERVER["HTTP_HOST"] . "_masterID"] == "master");
		@session_write_close();
		return $value;
	}

	public function getInformation(){
		return (object)array("title" => "開發者管理系統");
	}

	public function getMenu(){
		return array(
			(object)array(
				"icon" => "fa fa-gears",
				"title" => "網站設定",
				"page" => "information"
			),

			(object)array(
				"icon" => "fa fa-user",
				"title" => "管理帳號",
				"page" => "admin"
			),
			
			(object)array(
				"icon" => "fa fa-th",
				"title" => "零件管理",
				"page" => "widget"
			),

			(object)array(
				"icon" => "fa fa-users",
				"title" => "會員帳號",
				"page" => "member"
			),

			(object)array(
				"icon" => "fa fa-file-o",
				"title" => "頁面管理",
				"page" => "page"
			),

			(object)array(
				"icon" => "fa fa-th-list",
				"title" => "選單管理",
				"page" => "menu"
			),

			(object)array(
				"icon" => "fa fa-envelope",
				"title" => "信箱設定",
				"page" => "mail_setting"
			),

			(object)array(
				"icon" => "fa fa-clone",
				"title" => "信件樣版",
				"page" => "mail_template"
			),

			(object)array(
				"icon" => "fa fa-clone",
				"title" => "權限管理",
				"page" => "authority"
			),

			

			
			
		);
	}

	public function getPageInfo(){
		$pageId = $this -> tool_io -> get("page_id");
		$funName = "load" . ucfirst($pageId) . "Page";
		if(method_exists($this, $funName)){
			$page_metas = $this -> $funName();
			
		}
		// 預設
		else{
			$page_metas = $this -> loadInformationPage();
		}

		return $page_metas;
	}

	private function loadInformationPage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_information",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

	private function loadWidgetPage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_widget",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

	

	private function loadPagePage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_page",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

	private function loadMenuPage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_menu",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

	private function loadMail_settingPage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_mailsetting",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

	private function loadMail_templatePage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_mailtemplate",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

	private function loadAdminPage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_admin",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

	private function loadMemberPage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_member",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

	private function loadAuthorityPage(){
		return (object)array(
			(object)array(
				"widgetID" => "master_authority",
				"hide" => false,
				"desktop" => 12,
				"pad" => 12,
				"phone" => 12
			),
			
		);
	}

}
?>