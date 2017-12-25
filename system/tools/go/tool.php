<?php 

class Go_Tool extends CS_Tool{


	private $max_length = 50;

	private $type = "";

	public function record(){
		$url = $this -> config_env -> currentUrl;
		$parts = explode("/", $url);

		// $this -> tool_test -> show($url);
		// $this -> tool_test -> show($parts);
		$this -> type = $parts[3];
		if($parts[3] == "index.php"){
			$this -> type = $parts[4];
		}


		@session_start();
		
		// $this -> type = isset($_SESSION["URL_HISTORY_TYPE"])?$_SESSION["URL_HISTORY_TYPE"]:"";
		


		// AJAX, don't record
		if($this -> type == ""){
			return;
		}

		if(isset($_SESSION) && !in_array($this -> type, array("widget"))){
			foreach($_SESSION as $key => $sessionValue){
				if(preg_match("/widget_.*?/i",$key)){
					unset($_SESSION[$key]);
				}
			}
		}

		/**
		 * 確認是否為零件使用的網址記錄
		 */
		parse_str(basename($url),$params);
		if(isset($params["widget"])){
			$this -> type .= "_" . $params["widget"];
		}
		

		if (isset($_SESSION[$this -> type]) && count($_SESSION[$this -> type]) > 0) {
        	$count = count($_SESSION[$this -> type]) - 1;
            if ($_SESSION[$this -> type][$count] != $url) {
            	$_SESSION[$this -> type][] = $url;
			} else {
            	return;
			}
		} else {
        	$_SESSION[$this -> type][] = $url;
		}



		$count = count($_SESSION[$this -> type]);
		$diff = $count - $this -> max_length;

		if ($diff > 0) {
			$i = 0;
            foreach ($_SESSION[$this -> type] as $key => $value) {
            	if ($i < $diff) {
					unset($_SESSION[$this -> type][$key]);
				}
                $i++;
			}
			$_SESSION[$this -> type] = array_values($_SESSION[$this -> type]);
		}
		// $this -> tool_test -> show($this -> type);
		//$this -> tool_test -> show($_SESSION[$this -> type]);
		// $this -> tool_test -> show($_SESSION);
		
		@session_write_close();

	}

	/**
	 * 回前n頁
	 * @param integer $step 回幾頁
	 */
	public function back($step = 1, $controllerName = "") {
		$type = ($controllerName != "") ? $controllerName : $this -> type;

    	@session_start();
        $count = count($_SESSION[$type]);
        $index = ($count - 1) - $step;
        @session_write_close();

        $url = $this -> indexToUrl($index, $controllerName);

        $this -> page($url, ($controllerName!=""));
	}

	/**
	 * 根據索引取得陣列中的網址
     * @param integer $index 索引
     * @return string $url 網址
     */
	private function indexToUrl($index, $controllerName = "") {
		$type = ($controllerName != "") ? $controllerName : $this -> type;
		
    	@session_start();
		$count = count($_SESSION[$type]);
        for ($i = $index + 1; $i < $count; $i++) {
        	unset($_SESSION[$type][$i]);
		}
				
		$url = ($index > -1) ? $_SESSION[$type][$index] : "";
        @session_write_close();
        return $url;
	}

    /**
     * 跳至指定網址
     * @param string $url 網址
     */
	public function page($url, $jsGo = false) {
    	if($url != ""){
    		if($jsGo){
    			echo "<script>location.href='{$url}';</script>";
    		}
    		else{
        		header("location: " . $url);
        	}
        	
		}
		else{
			if($jsGo){
    			echo "<script>location.href='index.php';</script>";
    		}
			else{
				header("location: index.php");
			}
		}
		exit ;
	}
}
?>