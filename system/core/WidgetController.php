<?php 

class WidgetController extends CS_Controller{


	protected $widgetId = "";
	protected $widgetMeta = array();

	public static $widgetModels = array();
	
	public function __construct($widgetId, $widgetMeta){
		$this -> widgetId = $widgetId;
		$this -> widgetMeta = $widgetMeta;

		$this -> tool_go -> record();
	}

	public function __get($name) {
		
		$object = parent::__get($name);
		if(!is_null($object)){
			return $object;
		}

		$v = explode("_", $name);

		// 取得模組
		if ($v[0] == "widgetModel") {
			unset($v[0]);
			$modelName = implode("_", $v);
			if(!isset(self::$widgetModels[$modelName])){
				self::$widgetModels[$modelName] = $this -> loadWidgetModel($modelName);
			}

			return self::$widgetModels[$modelName];
		}

	}

	/**
	 * 載入Model
	 */
	private function loadWidgetModel($modelName){
		$widgetModule = $this -> widgetMeta -> model;
		$widgetModule = str_replace("-", "/", $widgetModule);

		$modelPath = $this -> config_env -> basePath  .  $this -> config_env -> widgetPath . "/" . $widgetModule . "/models" ;

		$modelPath .= "/" . $modelName . ".php";

		if(!file_exists($modelPath)){
			throw new Exception("Not Found Widget Model '{$modelName}' !");
		}

		require_once($modelPath);

		$className = ucfirst($modelName) . "_Model";
		return new $className($this -> widgetId, $this -> widgetMeta);
	}



	protected function loadView($viewName, $datas = array()){

		$view = new WidgetView($this -> widgetId, $this -> widgetMeta);
		
		$view -> load($viewName, $this -> layouts, $datas);
		$view -> replaceResource();
		$view -> replaceTagToData();
		$view -> render();

		$this -> tool_alert -> render();

	}
	
}

?>