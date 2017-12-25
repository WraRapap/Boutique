<?php
/**
 * 測試 模組
 */
class Test_Tool extends CS_Tool {
	
	public function show($variable){
		if(is_object($variable) || is_array($variable)){
			echo "<pre>\r\n";
			print_r($variable);
			echo "</pre>\r\n";	
		}
		else{
			echo $variable . "<br />\r\n";
		}
		
	}

	public function dump($variable){
		echo "<pre>\r\n";
		var_dump($variable);
		echo "</pre>\r\n";	
	}
}
?>