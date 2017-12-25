<?php 
class CS_Component extends Chihsin{

	/**
	 * 欄位名稱
	 */
	protected $title;

	/**
	 * 變數名稱
	 */
	protected $variable;

	/**
	 * 值
	 */
	protected $value;

	/**
	 * 屬性
	 */
	protected $properties = array();

	/**
	 * 提示文字
	 */
	protected $tip;

	/**
	 * 預設值
	 */
	protected $default;

	/**
	 * 是否為清單元件
	 */
	protected $list;

	/**
	 * 建構子
	 */
	public function __construct($title, $variable, $value, $default = "", $properties = array(), $tip = "", $list = "N") {
		$this -> title = $title;
		$this -> variable = $variable;
		$this -> value = $value;
		$this -> default = $default;
		$this -> properties = $properties;
		$this -> tip = $tip;
		$this -> list = $list;
	}

	public function getTitle(){
		return $this -> title;
	}

	public function setValue($value){
		$this -> value = $value;
	}

	public function getValue(){
		if(is_null($this -> value) || $this -> value == ""){
			return $this -> default;
		}
		else{
			return $this -> value;
		}
	}

	public function getVariable(){
		return $this -> variable;
	}

	/**
	 * 取得值的標籤，一般情況下標籤與值都相同，但有時選項為代號，要呈現的內容必需有意義時才需要此功能
	 * @return $label
	 * @example <option value="1">未處理</option>
	 * value=1, label=未處理
	 */
	public function getLabel(){
		echo "<label for=\"" . $this -> getVariable() . "\">";
		echo $this -> getTitle();
		if(isset($this -> properties -> required) && $this -> properties -> required == "Y"){
			echo "<required>(必填)</required>";
		}
		echo ":";
		echo "</label>";
	}

	public function getText(){
		return $this -> getValue();
	}

	public function setFromIO($method = "post", $xss = true){
		$this -> setValue($this -> tool_io -> $method($this -> getVariable(),$xss));
	}

	public function isList(){
		return ($this -> list == "Y");
	}

	public function isTab(){
		return false;
	}

	public function script(){

	}

	public function getProperties(){

		

		if(is_array($this -> properties)){
			$properties = $this -> properties;
		}
		else if(is_string($this -> properties)){

			$properties = array();

			$properties = explode(";", $this -> properties);
			foreach($properties as $property){
				if (preg_match("/data_source{(.*?)}/", $property, $element)) {
					$properties["data_source"] = $element[1];
				}
				if (preg_match("/allow{(.*?)}/", $property, $element)) {
					$properties["allow"] = $element[1];
				}
				if (preg_match("/required/", $property, $element)) {
					$properties["required"] = true;
				}
				if (preg_match("/count{(.*?)}/", $property, $element)) {
					$properties["count"] = $element[1];
				}
				if (preg_match("/type{(.*?)}/", $property, $element)) {
					$properties["type"] = $element[1];
				}
				if (preg_match("/step{(.*?)}/", $property, $element)) {
					$properties["step"] = $element[1];
				}
				if (preg_match("/include{(.*?)}/", $property, $element)) {
					$propertyName = "config_" . $element[1];
					$properties["fields"] = $this -> $propertyName -> fields;
				}
				if (preg_match("/nullitem{(.*?)}/", $property, $element)) {
					$properties["nullitem"] = $element[1];
				}

			}
			
		}

		return (object)$properties;
	}

	/**
	 * 選單專案
	 * @var $datasource 資料來源(資料集)
	 */
	public function getDatasource(){

		$properties = $this -> getProperties();

		// print_r($properties);

		$datas = array();
		
		if(isset($properties -> data_source)){
			$data_source = $properties -> data_source;

			$sources = explode(",",$data_source);

			foreach($sources as $source){
				$data = explode(":", $source);
				if(count($data) == 1){
					$datas[] = array($source,$source);
				}
				else{
					switch($data[0]){
						case "module_items": $datas = $this -> getModuleItems($data[1]); break;
						case "module_classes": break;
						case "module_recursive": $datas = $this -> getRecursiveClasses($data[1]); break;
						default: $datas[$data[0]] = $data;
					}
				}
			}
		};
		
		return $datas;
	}

	private function getModuleItems($code){
		$items = $this -> tool_database -> findAll($code,array(),array(),array(),array());
		$datas = array();
		foreach($items as $item){
			$datas[$item -> id] = array($item -> id, $item -> title);
		}

		return $datas;

	}

	private function getRecursiveClasses($code){
		$classes = $this -> tool_database -> findAll($code . "_class",array(),array(),array(),array("level ASC"));

		$tempClasses = array();

		foreach($classes as $class){
			if($class -> parentID != ""){
				$tempClasses[$class -> parentID]["sub_classes"][$class -> id] = array(
					"self" => $class,
					"path" => $tempClasses[$class -> parentID]["path"] . "/" . $class -> title
				);
			}
			else{
				$tempClasses[$class -> id]["self"] = $class;
				$tempClasses[$class -> id]["path"] = $class -> title;
			}
		}

		$datas = array();

		$this -> runRecursiveClasses($tempClasses, $datas);
		
		return $datas;
	}

	private function runRecursiveClasses($classes, &$datas){
		foreach($classes as $classID => $class){
			if(isset($class["sub_classes"]) && count($class["sub_classes"]) > 0){
				$datas[$classID] = array($classID, $class["path"]);
				$this -> runRecursiveClasses($class["sub_classes"], $datas);
			}
			else{
				$datas[$classID] = array($classID, $class["path"]);
			}
		}
	}
}

?>