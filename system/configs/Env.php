<?php 
	/**
	 * 目前完整網址(含參數)
	 */
	function curPageURL() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
    function baseURL() {
        $baseUrl = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$baseUrl .= "s";
        }
        $baseUrl .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $baseUrl .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] ;
        } else {
            $baseUrl .= $_SERVER["SERVER_NAME"];
        }
        return $baseUrl;
    }
	$configs["controllerPath"] = "/system/controllers";
	$configs["viewPath"] = "/system/views";
	$configs["modelPath"] = "/system/models";
	$configs["toolPath"] = "/system/tools";
	$configs["logPath"] = "/system/logs";
	$configs["filePath"] = "/system/files";
	$configs["jsLibPath"] = "/system/jslibs";
	$configs["componentPath"] = "/system/components";
	$configs["widgetPath"] = "/system/widgets";
	$configs["websitePath"] = "/website";
	

	$configs["currentUrl"] = curPageURL();
	

?>