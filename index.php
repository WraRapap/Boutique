<?php 

require_once( dirname(__FILE__) . "/system/core/Chihsin.php" );
require_once( dirname(__FILE__) . "/system/core/Controller.php" );
require_once( dirname(__FILE__) . "/system/core/Model.php" );
require_once( dirname(__FILE__) . "/system/core/View.php" );
require_once( dirname(__FILE__) . "/system/core/Tool.php" );
require_once( dirname(__FILE__) . "/system/core/WidgetController.php" );
require_once( dirname(__FILE__) . "/system/core/WidgetModel.php" );
require_once( dirname(__FILE__) . "/system/core/WidgetView.php" );
require_once( dirname(__FILE__) . "/system/core/WebsiteController.php" );
require_once( dirname(__FILE__) . "/system/core/WebsiteView.php" );
require_once( dirname(__FILE__) . "/system/core/Component.php" );

class EntryPoint{

	

	public function main(){


		// 載入所有設定值
		$this -> loadConfigs("system");
		$this -> loadConfigs("application");

		// 載入Controller
		$this -> loadController();

	}

	/**
	 * 載入所有設定檔
	 */
	private function loadConfigs($useDir = "system"){

		$configPath = dirname(__FILE__) . "/" . $useDir . "/configs";
		if(!file_exists($configPath)){
			return;
		}

		$files = scandir($configPath);

		$sysConfigs = array();

		foreach($files as $file){

			if(in_array($file, array(".",".."))){
				continue;
			}

			if(!file_exists($configPath . "/" . $file)){
				continue;
			}

			$configs = $_configs = array();
		
			include_once($configPath . "/" . $file);

			$_configs[basename(strtolower($file),".php")] = $configs;
			
			$sysConfigs = array_merge($sysConfigs, $_configs);
			unset($_configs);
		}


		$sysConfigs["env"]["basePath"] = dirname(__FILE__);
        $sysConfigs["env"]["baseUrl"] =baseURL();



		Chihsin::loadConfigs($sysConfigs);

	}


	/**
	 * 載入Controller
	 */
	private function loadController(){

		// 瀏覽器上呈現的網址
		$url = $_SERVER["REQUEST_URI"];

		if(trim($url) == ""){
			exit("URI ERROR");
		}

        // 取得 index 之後的 MVC路徑
        $mvc_path = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:"/";
        $controllerPath = Chihsin::$configs["env"]["basePath"] . Chihsin::$configs["env"]["controllerPath"];
        $websiteorignPath = Chihsin::$configs["env"]["basePath"] . Chihsin::$configs["env"]["websitePath"];
        $websitePath = $websiteorignPath . $mvc_path;

        if(in_array($mvc_path,Chihsin::$configs["filter"]["banEnterBehind"])>0){//过滤不用进来的页面
            return;
        }

        if(file_exists($websitePath) || file_exists($websiteorignPath."/view". $mvc_path) || in_array($mvc_path,Chihsin::$configs["filter"]["noNeedPage"])){//前端有页面存在的或者只有前端方法的都走这里


            if( isset($_GET["lanType"])){
                $lanType =$_GET["lanType"];
                if($lanType=="2"){
                    $lanType="cn";
                }
                else{
                    $lanType="zh";
                }
                @session_start();
                $_SESSION["LANG"]=$lanType;
                session_write_close();
            }

            $scriptName = basename($websitePath);

            // 首頁
            if(basename(Chihsin::$configs["env"]["websitePath"]) == $scriptName){
                $action = "index";
            }
            else{
                $fileNames = explode(".", $scriptName);
                $action = $fileNames[0];
            }

            if(file_exists($controllerPath . "/website.php")){
                require_once($controllerPath . "/website.php");
                $controllerObject = new Website_Controller();
            }
            else{
                $controllerObject = new WebsiteController();
            }

            if(method_exists($controllerObject, $action)){
                $controllerObject -> $action();
            }
            else{
                $controllerObject -> main($action);
            }

            return;
        }


		$paths = preg_split("/[\/\?\.#]/", $mvc_path);


		

		$count = count($paths);


		// 解析網址
		foreach($paths as $idx => $path){
			
			if(trim($path) == ""){
				continue;
			}

			$controllerPath .= "/" . $path;
			
			unset($paths[$idx]);


			// 存在檔案
			if(file_exists($controllerPath . ".php")){

				if ($idx < $count - 1) {
					$action = $paths[$idx + 1];
					unset($paths[$idx + 1]);
				}
				else{
					$action = "main";
				}

				// 載入 Controller
				
				
				require_once( $controllerPath . ".php" );

				$clsasName = basename($controllerPath);

				$controllerClass = ucfirst($clsasName) . "_Controller";

				$controllerObject = new $controllerClass();

				$parameters = implode("/", $paths);

				if(method_exists($controllerObject, $action)){
					$controllerObject -> $action();
				}
				else{
					exit("page not found!");
				}
				
				
				break;
			}

			// 目錄不存在(查無路徑是否可以轉向「預設」指定的Controller？)
			if(!file_exists($controllerPath)){
				exit("page not found!");
			}

		}
	}

}

date_default_timezone_set('Asia/Taipei');
// error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED); ini_set("display_errors", 1);
error_reporting(E_ALL); ini_set("display_errors", 1);
$entryPoint = new EntryPoint();
$entryPoint -> main();
unset($entryPoint);
?>