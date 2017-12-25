<?php 
class CS_Model extends Chihsin{
	public function domainHandler(){
		@session_start();

		$_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"] = null;
		

		$information = $this -> tool_database -> find("sys_information");
		if($information -> domain == "Y"){
			$admin = $this -> tool_database -> find(
				"sys_admin", 
				array("id"), 
				array("domain=?"), 
				array($_SERVER["HTTP_HOST"])
			);

			if($admin -> isExists()){
				$_SESSION[$_SERVER["HTTP_HOST"] . "_domainID"] = $admin -> id;
			}
			unset($admin);
		}

		// print_r($_SESSION);
		@session_write_close();
	}
}

?>