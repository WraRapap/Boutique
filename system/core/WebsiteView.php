<?php 
class WebsiteView extends CS_View{

	


	public function load($viewName, $layouts, & $datas = array()){


		$viewTypes = array("php","html","htm");

		$basePath = $this -> config_env -> basePath  .  $this -> config_env -> websitePath;

		$viewPath = "";

		foreach($viewTypes as $type){
			if(file_exists($basePath . "/" . $viewName . "." . $type)){
				$viewPath = $basePath . "/" . $viewName . "." . $type;
				break;
			}
		}

		

		if(!file_exists($viewPath)){
			throw new Exception("Not Found View '{$viewName}' !");
		}

		$this -> datas = & $datas;
		$this -> viewName = $viewName;

		ob_start();
		include($viewPath);
		$this -> content = ob_get_contents();
		ob_end_clean();

		$basePath = $this -> config_env -> basePath  .  $this -> config_env -> websitePath;

		
		foreach($layouts as $layoutName => $layoutPath){

			$layoutPath = "";

			foreach($viewTypes as $type){
				if(file_exists($basePath . "/" . $layoutPath . "." . $type)){
					$layoutPath = $basePath . "/" . $layoutPath . "." . $type;
					break;
				}
			}
			
			if($layoutPath != ""){
				ob_start();
				include($layoutPath);
				$content = ob_get_contents();
				ob_end_clean();
				
				$this -> content = preg_replace("/<layout name=\"{$layoutName}\".*?\/>/", $content ,$this -> content);

			}
		}

		$this -> embedAngularJS($viewName);

		@session_start();
		
		$lang = isset($_SESSION["LANG"])?$_SESSION["LANG"]:"zh";
		@session_write_close();
		
		if($lang == "cn"){
			$this -> content = $this -> tool_translate -> covertTW2CN($this -> content);
		}
		if($lang == "zh"){
			$this -> content = $this -> tool_translate -> covertCN2TW($this -> content);
		}

		

		
	}

	private function embedAngularJS($viewName){


		$basePath = $this -> config_env -> websitePath;


		$this -> content = str_replace("<head>", "<head>\r\n<script src=\"" . $this -> config_env -> jsLibPath . "/angularjs/angular.min.js\"></script>", $this -> content);

		$this -> content = str_replace("<head>", "<head>\r\n<script src=\"" . $this -> config_env -> jsLibPath . "/jquery/jquery.2.1.1.min.js\"></script>", $this -> content);

		$this -> content = preg_replace("/<html(.*?)>/","<html$1 ng-app=\"PH\">", $this -> content);

		$this -> content = preg_replace("/<html(.*?)>/","<html$1 ng-controller=\"appController\">", $this -> content);

		$this -> content = str_replace("</body>", "</body>\r\n<script src=\"/index.php/website/script.js?t=" . uniqid(true) . "\"></script>", $this -> content);
	}

	public function replaceResource(){
		
		$lastDirName = (dirname($this -> viewName) == ".") ? "" : ("/" . dirname($this -> viewName));
		$viewPath = $this -> config_env -> websitePath . $lastDirName;
		$basePath = $this -> config_env -> baseUrl . "/index.php";

		$this -> content = preg_replace("/<(link|script|img)(.*?)(src|href)=\"([^\/\?].*?)\"/","<$1$2$3=\"{$viewPath}/$4\"",$this -> content);


		$this -> content = preg_replace("/<div(.*?)(data-slide-bg)=\"([^\/\?].*?)\"/","<div$1$2=\"{$viewPath}/$3\"",$this -> content);


		$this -> content = preg_replace("/<a(.*?)href=\"([^\/\?].*?)\"/","<a$1href=\"{$basePath}/$2\"",$this -> content);
		$this -> content = preg_replace("/href=\".*?(javascript|mailto|tel|callto):/","href=\"$1:",$this -> content);
	
		$this -> content = preg_replace("/url\('([^\/].*?)'\)/s", "url('{$viewPath}/$1')", $this -> content);

		$this -> content = str_replace("{$basePath}/{{", "{{", $this -> content);

		$this -> content = str_replace("{$viewPath}/http://", "http://", $this -> content);
		$this -> content = str_replace("{$viewPath}/https://", "https://", $this -> content);
		$this -> content = str_replace("http://{$_SERVER["HTTP_HOST"]}http://", "http://", $this -> content);
		$this -> content = str_replace("https://{$_SERVER["HTTP_HOST"]}https://", "https://", $this -> content);

		$this -> content = str_replace("{$basePath}/http://", "http://", $this -> content);
		$this -> content = str_replace("{$basePath}/https://", "https://", $this -> content);

	}

	
}

?>