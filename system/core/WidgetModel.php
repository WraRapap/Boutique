<?php 
class WidgetModel extends CS_Model{
	protected $widgetId = "";
	protected $widgetMeta = array();

	public function __construct($widgetId, $widgetMeta){
		$this -> domainHandler();
		$this -> widgetId = $widgetId;
		$this -> widgetMeta = $widgetMeta;
	}

	protected function covertMeta2Component($metas){
		

		$components = array();

		foreach($metas as $variable => $meta){

			$meta = (object)$meta;

			$variable = $meta -> variable;

			$componentPath = $this -> config_env -> basePath . $this -> config_env -> componentPath . "/" . $meta -> type . ".php";

			if(!file_exists($componentPath)){
				throw new Exception("Not Found Component '{$meta -> type}'");
			}

			include_once($componentPath);
			$class_name = ucfirst($meta -> type) . "_Component";


			$components[$variable] = new $class_name(
				$meta -> name, 
				$variable, 
				"", 
				$meta -> default, 
				$meta -> properties, 
				$meta -> tip
			);

			$xss = isset($meta -> xss) ? ($meta -> xss=="Y") : true;
			$components[$variable] -> setFromIO("post", $xss);
		}

		return $components;
	}
}
?>