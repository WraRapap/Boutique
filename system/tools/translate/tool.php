<?php
/**
 * 訊息 模組
 */
use SteelyWing\Chinese\Chinese;

include_once(dirname(__FILE__) . "/Chinese.php");

class Translate_Tool extends CS_Tool {
	public function covertTW2CN($tw_content){
    	$chinese = new Chinese();
        return $chinese->to(Chinese::CHS, $tw_content);
	}

    public function covertCN2TW($cn_content){
    	$chinese = new Chinese();
        return $chinese->to(Chinese::CHT, $cn_content);
	}
}
?>