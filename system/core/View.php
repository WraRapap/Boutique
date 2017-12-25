<?php 
class CS_View extends Chihsin{


	protected $content;
	protected $datas = array();
	protected $viewName = "";

	public function __get($name) {

		$object = parent::__get($name);
		if(!is_null($object)){
			return $object;
		}

		return (isset($this -> datas[$name])) ? $this -> datas[$name] : null;
	}

	public function load($viewName, $layouts, & $datas = array()){
		$viewPath = $this -> config_env -> basePath  .  $this -> config_env -> viewPath;

		$viewPath .= "/" . $viewName . ".php";

		if(!file_exists($viewPath)){
			throw new Exception("Not Found View '{$viewName}' !");
		}

		$this -> datas = & $datas;
		$this -> viewName = $viewName;

		ob_start();
		include($viewPath);
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

	

	public function replaceResource(){

		
		$lastDirName = (dirname($this -> viewName) == ".") ? "" : ("/" . dirname($this -> viewName));
		$viewPath = $this -> config_env -> viewPath . $lastDirName;
		$basePath = $this -> config_env -> baseUrl . "/index.php";

		$this -> content = preg_replace("/<(link|script|img)(.*?)(src|href)=\"([^\/\?].*?)\"/","<$1$2$3=\"{$viewPath}/$4\"",$this -> content);

		$this -> content = preg_replace("/<a(.*?)href=\"([^\/\?].*?)\"/","<a$1href=\"{$basePath}/$2\"",$this -> content);

		$this -> content = preg_replace("/href=\".*?(javascript|mailto|tel):/","href=\"$1:",$this -> content);
		
		$this -> content = preg_replace("/url\('([^\/].*?)'\)/s", "url('{$viewPath}/$1')", $this -> content);		
		$this -> content = str_replace("{$viewPath}/http://", "http://", $this -> content);
		$this -> content = str_replace("{$viewPath}/https://", "https://", $this -> content);
		$this -> content = str_replace("http://{$_SERVER["HTTP_HOST"]}http://", "http://", $this -> content);
		$this -> content = str_replace("https://{$_SERVER["HTTP_HOST"]}https://", "https://", $this -> content);

		$this -> content = str_replace("{$basePath}/http://", "http://", $this -> content);
		$this -> content = str_replace("{$basePath}/https://", "https://", $this -> content);

	}

	public function replaceTagToData(){
		if(preg_match_all("/<data name=\"(.*?)\".*?\/>/",$this -> content, $res)){
			
			$count = count($res[0]);
			for($i=0;$i<$count;$i++){
				$name = $res[1][$i];

				if(isset($this -> datas[$name])){
					$this -> content = preg_replace("/<data name=\"{$name}\".*?\/>/", $this -> datas[$name] ,$this -> content);
					
				}
				else{
					$this -> content = preg_replace("/<data name=\"{$name}\".*?\/>/", "" ,$this -> content);
				}
			}
			
		}
	}

	public function render(){
		echo $this -> content;
	}
}

?>