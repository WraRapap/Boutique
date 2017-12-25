<?php 
class WidgetView extends CS_View{

	protected $widgetId = "";
	protected $widgetMeta = array();

	public function __construct($widgetId, $widgetMeta){
		$this -> widgetId = $widgetId;
		$this -> widgetMeta = $widgetMeta;

		if(isset($this -> widgetMeta -> class_control)){
			$this -> widgetMeta -> class_control = explode(",",$this -> widgetMeta -> class_control);
		}

		if(isset($this -> widgetMeta -> special_class_control)){
			$this -> widgetMeta -> special_class_control = explode(",",$this -> widgetMeta -> special_class_control	);
		}

		if(isset($this -> widgetMeta -> item_control)){
			$this -> widgetMeta -> item_control = explode(",",$this -> widgetMeta -> item_control);
		}
	}


	public function load($viewName, $layouts, & $datas = array()){

		$widgetModule = $this -> widgetMeta -> model;
		$widgetModule = str_replace("-", "/", $widgetModule);

		
		$viewPath = $this -> config_env -> basePath  .  $this -> config_env -> widgetPath;

		$templatePath = $viewPath . "/" . $widgetModule . "/views/" . $viewName . ".php";
		if(!file_exists($templatePath)){
			throw new Exception("Not Found Widget View '{$viewName}' !");
		}

		$this -> datas = & $datas;
		$this -> viewName = $viewName;
		

		if(isset($this -> datas["metas"])){

			if(is_array($this -> data)){
				$rows = array();
				foreach($this -> data as $data){
					$rows[] = $this -> covertData2Component($this -> datas["metas"], $data);
				}
				$this -> datas["data"] = $rows;
			}
			else{

				$this -> datas["data"] = $this -> covertData2Component($this -> datas["metas"], $this -> datas["data"]);
			}

		}

		if(isset($this -> datas["searchMetas"])){
			$metas = $this -> datas["searchMetas"];
			$record = array();
			if(count($metas) == 0){
				$this -> datas["searchMetas"] = array();
			}
			else{
				foreach($metas as $meta){
					$v = $this -> tool_io -> post($meta -> variable);
					$record[$meta -> variable] = $v;
				}
				$this -> datas["searchMetas"] = $this -> covertData2Component($this -> datas["searchMetas"], $record);
			}
		}


		ob_start();
		include($templatePath);
		$this -> content = ob_get_contents();
		ob_end_clean();



		$viewPath = $this -> config_env -> basePath  .  $this -> config_env -> viewPath;

		foreach($layouts as $layoutName => $layoutPath){
			$layoutPath = $viewPath . "/" . $layoutPath . ".php";
			if(file_exists($layoutPath)){
				ob_start();
				include($layoutPath);
				$content = ob_get_contents();
				ob_end_clean();
				
				$this -> content = preg_replace("/<layout name=\"{$layoutName}\".*?\/>/", $content ,$this -> content);

			}
		}

	}

	


	/**
	 * 將 Meta 中的欄位型態轉換成元件
	 */
	public function covertData2Component($metas, $data = array()){

		if(is_array($data)){
			$data = (object)$data;
		}
		if(is_array($metas)){
			$metas = (object)$metas;
		}

/*
		echo "<pre>";
		print_r($metas);
		echo "</pre>";
		*/
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

			$value = (isset($data -> $variable)) ? $data -> $variable : "";

			$meta -> default = isset($meta -> default) ? $meta -> default : "";
			$meta -> tip = isset($meta -> tip) ? $meta -> tip : "";
			$meta -> list = isset($meta -> list) ? $meta -> list : "";

			
			$components[$variable] = new $class_name($meta -> name, $variable, $value, $meta -> default, $meta -> properties, $meta -> tip, $meta -> list);

			unset($data -> $variable);
		}

		foreach($data as $variable => $value){
			$components[$variable] = $value;
		}
		

		return (object)$components;
	}

}

?>