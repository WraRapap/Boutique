<?php 

require_once(dirname(__FILE__) . "/class.JavaScriptPacker.php");

class Jspacker_Tool extends CS_Tool{
	public function encode($content){
		$packer = new JavaScriptPacker($content, 'Normal', true, false);
		//return $content;
		return $packer->pack();
	}
}
?>