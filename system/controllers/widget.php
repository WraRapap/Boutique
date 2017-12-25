<?php
class Widget_Controller extends CS_Controller{

	private $widgetId = "";

	public function main(){

		$widgetId = $this -> tool_io -> get("widget");

		$parse = explode("_", $widgetId);
		if($parse[0] == "master"){
			// Master的模組與ID相同
			$widgetMeta = (object)$this -> config_master -> {$widgetId};
	
		}
		else{
			// 管理者的模組資訊透過ID到資料庫去抓
			$widgetMeta = $this -> tool_database -> find("sys_widget", array(), array("id=?"),array($widgetId));
			if(!$widgetMeta -> isExists()){
				$widgetMeta = $this -> tool_database -> find("sys_member", array(), array("id=?"),array($widgetId));
			}
			$jsonContents = json_decode($widgetMeta -> jsonContent);
			if($jsonContents != ""){
				foreach($jsonContents as $variable => $value){
					if(in_array($variable, array("class_fields","special_class_fields","item_fields"))){
						$value = (json_decode($value));
					}
					$widgetMeta -> $variable = $value;
				}

				unset($widgetMeta -> jsonContent);
			}
		
		}


		$widgetModule = $widgetMeta -> model;

		$widgetModule = str_replace("-", "/", $widgetModule);
		$actionPath = $this -> config_env -> basePath  .  $this -> config_env -> widgetPath . "/" . $widgetModule . "/controllers/action.php";

		$action = $this -> tool_io -> get("action");
		if(!isset($action) || trim($action) == ""){
			$action = "main";
		}


		include_once($actionPath);
		$actionWidgetClass = new Action_Widget($widgetId, $widgetMeta);
		if(method_exists($actionWidgetClass, $action)){
			$actionWidgetClass -> $action();
		}
		else{
			throw new Exception("Call to undefined Widget Action function '{$action}' !");
		}

	}

}
	
?>