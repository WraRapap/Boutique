<?php
/**
 * 訊息 模組
 */
class Alert_Tool extends CS_Tool {
	
	/**
	 * 設定訊息
	 * @param string $text 訊息內容(標題)
	 * @param string $status 訊息類型 (success,error,warning,info)
	 * @param string $description 描述
	 */
	public function set_alert($text, $status = "success" , $description = ""){
		@session_start();
		$_SESSION["alert_type"] = "Alert";
		$_SESSION["alert_text"] = $text;
		$_SESSION["alert_status"] = $status;
		$_SESSION["alert_description"] = $description;
		@session_write_close();
	}
	
	/**
	 * 確認訊息
	 * @param string $text 訊息內容(標題)
	 * @param string $status 訊息類型 (success,error,warning,info)
	 * @param string $description 描述
	 * @param string $confirm_function 按下確定後要執行的JS Function 名稱
	 * @param string $cancel_function 按下取消後要執行的JS Function 名稱
	 * @param string $confirm_text 確定鈕名稱
	 * @param string $cancel_text 取消鈕名稱
	 */
	public function set_confirm($text, $status="success", $description = "", $confirm_function = null, $cancel_function = null, $confirm_text="確定", $cancel_text="取消"){
		@session_start();
		$_SESSION["alert_type"] = "Confirm";
		$_SESSION["alert_text"] = $text;
		$_SESSION["alert_status"] = $status;
		$_SESSION["alert_description"] = $description;
		$_SESSION["alert_confirm_function"] = $confirm_function;
		$_SESSION["alert_cancel_function"] = $cancel_function;
		$_SESSION["alert_confirm_text"] = $confirm_text;
		$_SESSION["alert_cancel_text"] = $cancel_text;
		@session_write_close();
	}
	
	

	public function render() {
		@session_start();
		if (isset($_SESSION["alert_text"]) && trim($_SESSION["alert_text"]) != "") {
			$this -> show_alert();
		}
		@session_write_close();
	}

	private function show_alert() {
		
		$css = "<link rel='stylesheet' href='" . $this -> config_env -> jsLibPath . "/jquery/sweetalert/sweetalert.css' type='text/css' />";
		$jquery = "<script src='" . $this -> config_env -> jsLibPath . "/jquery/sweetalert/sweetalert-dev.js'></script>";
		
		@session_start();
		
		if($_SESSION["alert_type"] == "Alert"){
			
			$js = "<script> $(document).ready(function(){ swal('{$_SESSION["alert_text"]}','{$_SESSION["alert_description"]}','{$_SESSION["alert_status"]}'); }); </script>";
			unset($_SESSION["alert_type"]);
			unset($_SESSION["alert_text"]);
			unset($_SESSION["alert_status"]);
			unset($_SESSION["alert_description"]);
		}
		else if($_SESSION["alert_type"] == "Confirm"){
			
			$confirm_function = (trim($_SESSION["alert_confirm_function"]) == "") ? "" : (trim($_SESSION["alert_confirm_function"]) . "();");
			$cancel_function = (trim($_SESSION["alert_cancel_function"]) == "") ? "" : (trim($_SESSION["alert_cancel_function"]) . "();");
			$closeOnConfirm = ($confirm_function == "") ? "true" : "false";
			$closeOnCancel = ($cancel_function == "") ? "true" : "false";
			$js = "
				<script> 
				$(document).ready(function(){
					 swal({
					 	title: '{$_SESSION["alert_text"]}',
						text: '{$_SESSION["alert_description"]}',
						type: '{$_SESSION["alert_status"]}',
						showCancelButton: true,
						confirmButtonText: '{$_SESSION["alert_confirm_text"]}',
						cancelButtonText: '{$_SESSION["alert_cancel_text"]}',
						closeOnConfirm: {$closeOnConfirm},
						closeOnCancel: {$closeOnCancel}
					},
					function(isConfirm){
						if (isConfirm) {
							{$confirm_function}
							swal.close();
						}
						else {
							{$cancel_function}
							swal.close();
					  	}
					});
				});
				</script>";
				
				
			unset($_SESSION["alert_type"]);
			unset($_SESSION["alert_text"]);
			unset($_SESSION["alert_status"]);
			unset($_SESSION["alert_description"]);
			unset($_SESSION["alert_confirm_function"]);
			unset($_SESSION["alert_cancel_function"]);
			unset($_SESSION["alert_confirm_text"]);
			unset($_SESSION["alert_cancel_text"] );
		}
		
		@session_write_close();

		// For Widget
		echo $css . "\r\n" . $jquery . "\r\n" . $js . "\r\n";
	}

}
?>