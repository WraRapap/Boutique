<?php
/**
 * 工具 模組
 */
class Utility_Tool extends CS_Tool {
	public function bytesToSize($bytes){
		$sizeUnit = array("Bytes", "KB", "MB", "GB", "TB");
		if($bytes == 0) return "0 Byte";
		$i = (int)floor(log($bytes) / log(1024));
		return round($bytes / pow(1024,$i),0) . " " . $sizeUnit[$i];
	}
}
?>