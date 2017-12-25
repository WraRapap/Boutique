<?php 

class CS_Controller extends Chihsin{

	/**
	 * 記錄目前指定的 Model 及 Action
	 */
	protected $modelActions = array();

	/**
	 * 記錄目前指定的 Layout
	 */
	protected $layouts = array();

	/**
	 * 模組集
	 */
	public static $models = array();

	public function __construct(){
		
		$this -> tool_go -> record();
	}


	public function __get($name) {
		
		$object = parent::__get($name);
		if(!is_null($object)){
			return $object;
		}

		$v = explode("_", $name);

		// 取得模組
		if ($v[0] == "model") {
			unset($v[0]);
			$modelName = implode("_", $v);
			if(!isset(self::$models[$modelName])){
				self::$models[$modelName] = $this -> loadModel($modelName);
			}

			return self::$models[$modelName];
		}

	}


	/**
	 * 載入Model
	 */
	private function loadModel($modelName){
		$modelPath = $this -> config_env -> basePath  .  $this -> config_env -> modelPath;

		$modelPath .= "/" . $modelName . ".php";

		if(!file_exists($modelPath)){
			throw new Exception("Not Found Model '{$modelName}' !");
		}

		require_once($modelPath);

		$className = ucfirst($modelName) . "_Model";
		return new $className();
	}

	public function loadLayout($layoutName, $layoutPath){
		$this -> layouts[$layoutName] = $layoutPath;
	}

	protected function loadView($viewName, $datas = array()){
		
		$view = new CS_View();
		
		$view -> load($viewName, $this -> layouts, $datas);
		$view -> replaceResource();
		$view -> replaceTagToData();
		$view -> render();
	}

	
}

?>