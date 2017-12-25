<?php 

require_once(dirname(__FILE__) . "/Sanitizer.php");

class Io_Tool extends CS_Tool{
	public function get($key, $escape_xss = true){

		$data = isset($_GET[$key])?$_GET[$key]:"";
		return ($escape_xss)?$this -> escape_xss($data):$data;
	}

	public function post($key, $escape_xss = true){

		$data = isset($_POST[$key])?$_POST[$key]:"";
		return ($escape_xss)?$this -> escape_xss($data):$data;
	}

	public function request($key, $escape_xss = true){
		$data = isset($_POST[$key])?$_POST[$key]:"";
		if($data == ""){
			$data = isset($_GET[$key])?$_GET[$key]:"";
		}
		return ($escape_xss)?$this -> escape_xss($data):$data;
	}

	public function dumpAll($escape_xss = true){
		$datas = array_merge($_POST, $_GET);
		if($escape_xss){
			foreach($datas as $key => $data){
				$datas[$key] = $this -> escape_xss($data);
			}
		}

		return $datas;
	}

	private function escape_xss($data){
		$san = new HTML_Sanitizer();
		
		if (isset($data)) {

			if (is_array($data)) {

				foreach ($data as $key => $val) {
					$data[$key] = $san -> sanitize($val);
				}
			} else {
				$data = $san -> sanitize($data);
			}

		}

		return $data;
	}
}
?>