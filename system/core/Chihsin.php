<?php 
class Chihsin {
	/**
	 * 工具集
	 */
	public static $tools = array();

	/**
	 * 系統設定值
	 */
	public static $configs = array();

	public static function loadConfigs(& $configs){
		self::$configs = & $configs;
	}

	/**
	 * Magic Method 可快速取得工具
	 *
	 * 範例：
	 * 	$this -> tool_database -> findAll();
	 */
	
	public function __get($name) {
// echo $name . "<br />";
		$v = explode("_", $name);

		// 取得工具
		if ($v[0] == "tool") {
			unset($v[0]);
			$toolName = implode("_", $v);
			if(!isset(self::$tools[$toolName])){
				self::$tools[$toolName] = $this -> loadTool($toolName);
			}

			return self::$tools[$toolName];
		}


		// 取得設定
		if ($v[0] == "config") {
			unset($v[0]);
			$configName = implode("_", $v);
			if(!isset(self::$configs[$configName])){
				return "";
			}

			return (object)self::$configs[$configName];
		}

		return null;

	}
	
	

	/**
	 * 載入工具
	 */
	
	private function loadTool($toolName){
		$toolPath = self::$configs["env"]["basePath"]  . self::$configs["env"]["toolPath"];

		$toolPath .= "/" . $toolName . "/tool.php";

		if(!file_exists($toolPath)){
			throw new Exception("Not Found Tool '{$toolName}' !");
		}

		require_once($toolPath);

		$className = ucfirst($toolName) . "_Tool";
		return new $className();
	}
	
}
?>