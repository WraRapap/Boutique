<?php 
class Data_Model extends WidgetModel{

	

	public function getItem(){

		$wheres = $values = array();
		@session_start();
		if(isset($_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"])){
			$wheres[] = "domainID=?";
			$values[] = $_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"];
		}
		@session_write_close();
		$item = $this -> tool_database -> find(
			$this -> widgetMeta -> data_source,
			array(),
			$wheres,
			$values
		);
		
		if($item -> isExists()){
			$data = json_decode($item -> jsonContent);
			if($data != ""){
				foreach($data as $variable => $value){
					$item -> $variable = $value;
				}
				unset($item -> jsonContent);
			}
		}
		else{
			// print_r($item);
		}
		return $item;
	}



	public function save(){

		@session_start();
		if(!isset($_SESSION[$_SERVER["HTTP_HOST"] . "_adminID"]) || is_null($_SESSION[$_SERVER["HTTP_HOST"] . "_adminID"])){
			return;
		}
		
		$data_sources = array("sys_admin");

		$logins = $this -> tool_database -> findAll(
			"sys_member", 
			array("id","data_source", "authority"), 
			array("admin_login='Y'")
		);

		foreach($logins as $login){
			$data_sources[] = $login -> data_source;
			$authorities[] = $login -> authority;
		}


		
		$member = "";
		foreach($data_sources as $key => $data_source){
			$member = $this -> tool_database -> find(
				$data_source, 
				array(), 
				array("id=?"), 
				array($_SESSION[$_SERVER["HTTP_HOST"] . "_adminID"])
			);

			if($member -> isExists()){
				break;
			}
		}

		
		@session_write_close();

		if($member -> password != $this -> tool_io -> post("oldPassword")){
			$this -> tool_alert -> set_alert("舊密碼錯誤");
			return;
		}

		if($this -> tool_io -> post("confirm") != $this -> tool_io -> post("newPassword")){
			$this -> tool_alert -> set_alert("新密碼與確認密碼不相符");
			return;
		}

		$member -> password = $this -> tool_io -> post("newPassword");
		$member -> update();

		$this -> tool_alert -> set_alert("修改成功");


	}

	

}
?>